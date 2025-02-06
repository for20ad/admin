<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Module\dahae\Models\DahaeModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use Config\Services;

class SetGoodsImages extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'custom';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'set:goods-images';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Set goods images for specific pages';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'command:name [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */

    protected $db;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    public function run(array $params)
    {
        helper('owens');
        // 페이지 설정
        $page = isset($params[0]) && is_numeric($params[0]) ? (int)$params[0] : 1;
        $perPage = 200;

        CLI::write("처리 중인 페이지: {$page}, 한 페이지당 데이터: {$perPage}", 'yellow');

        // 모델 호출
        $dModel = new DahaeModel(); // 실제 데이터 모델 이름으로 교체
        $modelParam = [
            'limit' => $perPage,
            'start' => ($page - 1) * $perPage,
        ];
        // 두 번째 파라미터 처리 (G_IDX 추가)
        if (isset($params[1]) && is_numeric($params[1])) {
            $modelParam['G_IDX'] = (int)$params[1];
            CLI::write("G_IDX 필터링 적용: {$modelParam['G_IDX']}", 'yellow');
        }
        // 데이터 가져오기
        $aData = $dModel->getGoodsLists($modelParam);

        if (empty($aData['lists'])) {
            CLI::error("상품 데이터가 없습니다.");
            return;
        }

        CLI::write("상품 총: " . $aData['total_count'], 'green');
        CLI::write("페이지 수: " . ceil($aData['total_count'] / $perPage), 'green');

        CLI::write("남은 패에지 수 :: ".ceil(  $aData['total_count'] / $perPage - $page ), 'green' );

        foreach ($aData['lists'] as $data) {
            if( $data['G_IDX'] != '899'){
                CLI::write("==================================================", 'red');
                CLI::write("상품 처리 중: G_IDX " . $data['G_IDX'], 'cyan');
                CLI::write("**************************************************", 'red');
                // 이미지 세팅
                $esGoodsInfo = $dModel->getEsGoodsByIdx($data['G_GODO_GOODSNO']);
                $imageDatas = $dModel->getGodoImages($data['G_GODO_GOODSNO']);
                $config = [
                    'path' => 'goodsTest/product/' . $data['G_IDX'],
                    'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
                ];

                $this->db->transBegin();

                if (!empty($imageDatas)) {
                    foreach ($imageDatas as $iKey => $image) {
                        $filePath = WRITEPATH . 'uploads/godo_data/goods/' . $esGoodsInfo['imagePath'] . $image['imageName'];

                        if (file_exists($filePath)) {
                            $fileReturn = $this->_uploadAndResize($filePath, $config);

                            if (!$fileReturn['status']) {
                                $this->db->transRollback();
                                CLI::error("이미지 업로드 실패: " . $fileReturn['error']);
                                continue;
                            }

                            // 원본 이미지 저장
                            $imgParam = [
                                'I_SORT' => $iKey + 1,
                                'I_GOODS_IDX' => $data['G_IDX'],
                                'I_IMG_NAME' => $fileReturn['org_name'],
                                'I_IMG_PATH' => $fileReturn['uploaded_path'],
                                'I_IMG_SIZE' => $fileReturn['size'],
                                'I_IMG_EXT' => $fileReturn['ext'],
                                'I_IMG_MIME_TYPE' => $fileReturn['type'],
                                'I_IS_ORIGIN' => 'Y',
                                'I_CREATE_AT' => date('Y-m-d H:i:s'),
                                'I_CREATE_IP' => $ipAddress = PHP_SAPI === 'cli' ? '127.0.0.1' : $this->request->getIPAddress(),
                                'I_CREATE_MB_IDX' => '1',
                            ];

                            $imgIdx = $dModel->insertGoodsImages($imgParam);
                            if (!$this->db->transStatus() || !$imgIdx) {
                                $this->db->transRollback();
                                CLI::error("이미지 저장 실패: G_IDX " . $data['G_IDX']);
                                continue;
                            }

                            // 리사이즈 이미지 저장
                            foreach ($fileReturn['resized'] ?? [] as $resized) {
                                $resizeParam = [
                                    'I_SORT' => $iKey + 1,
                                    'I_GOODS_IDX' => $data['G_IDX'],
                                    'I_IMG_NAME' => $fileReturn['org_name'],
                                    'I_IMG_PATH' => $resized['path'],
                                    'I_IMG_VIEW_SIZE' => $resized['size'],
                                    'I_IMG_SIZE' => filesize($resized['path']),
                                    'I_IMG_EXT' => $fileReturn['ext'],
                                    'I_IMG_MIME_TYPE' => $fileReturn['type'],
                                    'I_IS_ORIGIN' => 'N',
                                    'I_CREATE_AT' => date('Y-m-d H:i:s'),
                                    'I_CREATE_IP' => $ipAddress = PHP_SAPI === 'cli' ? '127.0.0.1' : $this->request->getIPAddress(),
                                    'I_CREATE_MB_IDX' => '1',
                                ];

                                $resizeImgIdx = $dModel->insertGoodsImages($resizeParam);
                                if (!$this->db->transStatus() || !$resizeImgIdx) {
                                    $this->db->transRollback();
                                    CLI::error("리사이즈 이미지 저장 실패: G_IDX " . $data['G_IDX']);
                                }
                            }
                        }
                    }
                }
                CLI::write("**************************************************", 'red');
                $this->db->transCommit();
                CLI::write("상품 처리 완료: G_IDX " . $data['G_IDX'], 'green');
                CLI::write("==================================================", 'red');
            }
        }

        $totalPages = ceil($aData['total_count'] / $perPage);
        CLI::write("총 페이지 수: {$totalPages}", 'yellow');
        CLI::write("남은 패에지 수 :: ".ceil(  $aData['total_count'] / $perPage - $page ), 'green' );

        if ($page < $totalPages) {
            $nextPage = $page + 1;
            CLI::write("다음 페이지를 실행하려면 명령어를 실행하세요: php spark set:goods-images {$nextPage}", 'green');
        }
    }

    protected function _uploadAndResize($filePath, $_config)
    {
        $uploader = Services::upload();

        // 기본 설정 및 사용자 정의 설정 병합
        $config = [
            'upload_path' =>  WRITEPATH . 'uploads/' . _elm($_config, 'path'),
            'allowed_types' => _elm($_config, 'mimes') ?? 'jpg|jpeg|png|gif',
            'max_size' => _elm($_config, 'max_size') ?? '10240', // KB 단위
        ];

        $file = new UploadedFile(
            $filePath,
            basename($filePath),
            mime_content_type($filePath) ?: 'application/octet-stream',
            filesize($filePath),
            UPLOAD_ERR_OK
        );

        // 파일 크기 검사
        if ($file->getSize() > ($config['max_size'] * 1024)) { // KB 단위로 비교
            return [
                'status' => false,
                'error' => '최대 사이즈 오류발생 ' . $config['max_size'] . ' KB'
            ];
        }


        // 업로드 경로 생성
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        // 로컬 파일을 UploadedFile 객체로 변환
        $filePath = realpath($file);
        if ($filePath === false) {
            return [
                'status' => false,
                'error'  => '유효하지 않은 파일 경로입니다.'
            ];
        }



        $originalName = $file->getName();
        $realFilename = $file->getRandomName();

        // 파일 업로드

        if (!is_dir($config['upload_path'])) {
            if (!mkdir($config['upload_path'], 0755, true)) {
                return [
                    'status' => false,
                    'error'  => "업로드 경로 생성 실패: " . $config['upload_path']
                ];
            }
        }

        if (!is_writable($config['upload_path'])) {
            return [
                'status' => false,
                'error'  => "업로드 경로에 쓰기 권한이 없습니다: " . $config['upload_path']
            ];
        }

        if (!copy($filePath, $config['upload_path'] . '/' . $realFilename)) {
            return [
                'status' => false,
                'error'  => '파일 복사 실패'
            ];
        }

        $uploadedPath = $config['upload_path'] . '/' . $realFilename;
        $relativePath = 'uploads/' . _elm($_config, 'path') . '/' . $realFilename;

        // 리사이즈할 사이즈 목록
        $sizes = [
            [100, 100],
            [250, 250],
            [500, 500],
            [800, 800],
            [1024, 1024],
        ];

        $resizedImages = $this->_resizeImage($uploadedPath, $relativePath, $sizes, $config['upload_path'], $realFilename, $_config);

        // 업로드된 파일 및 리사이즈된 파일 정보 반환
        return [
            'status' => true,
            'org_name' => $originalName,
            'url' => base_url($relativePath),
            'uploaded_path' => $relativePath,
            'type' => mime_content_type($uploadedPath),
            'size' => filesize($uploadedPath), // bytes 단위
            'ext' => pathinfo($uploadedPath, PATHINFO_EXTENSION),
            'resized' => $resizedImages, // 리사이즈된 이미지 정보
        ];
    }


    protected function _resizeImage($uploadedPath, $relativePath, $sizes, $uploadPath, $realFilename, $_config)
    {
        $resizedImages = [];
        foreach ($sizes as $size) {
            $width = $size[0];
            $height = $size[1];
            $resizedFilename = pathinfo($realFilename, PATHINFO_FILENAME) . "_{$width}x{$height}." . pathinfo($realFilename, PATHINFO_EXTENSION);
            $resizedPath = $uploadPath . '/' . $resizedFilename;
            $realResizedPath = str_replace('/home/admin/writable/','',$uploadPath) . '/' . $resizedFilename;

            // 리사이즈 작업 수행
            if ($this->_performResize($uploadedPath, $resizedPath, $width, $height)) {
                $resizedImages[] = [
                    'path' => $realResizedPath,
                    'size' => "{$width}"
                ];
            }
        }

        return $resizedImages;
    }



    protected function _performResize($sourcePath, $destPath, $width, $height)
    {
        $extension = strtolower(pathinfo($destPath, PATHINFO_EXTENSION));
        $sourceImage = null;

        // 원본 이미지 로드
        if ($extension === 'jpg' || $extension === 'jpeg') {
            $sourceImage = imagecreatefromjpeg($sourcePath);
        } elseif ($extension === 'png') {
            $sourceImage = imagecreatefrompng($sourcePath);
        } elseif ($extension === 'gif') {
            $sourceImage = imagecreatefromgif($sourcePath);
        }

        if ($sourceImage === false) {
            return false;
        }

        // 새 캔버스 생성
        $resizedImage = imagecreatetruecolor($width, $height);

        // 투명 배경 처리 (PNG, GIF)
        if ($extension === 'png' || $extension === 'gif') {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparentColor = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefill($resizedImage, 0, 0, $transparentColor);
        } else {
            // 비투명 이미지의 경우 흰색 배경 채우기
            $white = imagecolorallocate($resizedImage, 255, 255, 255);
            imagefill($resizedImage, 0, 0, $white);
        }

        // 이미지 복사 및 리사이징
        $result = imagecopyresampled(
            $resizedImage,
            $sourceImage,
            0, 0, 0, 0,
            $width, $height,
            imagesx($sourceImage),
            imagesy($sourceImage)
        );

        if (!$result) {
            imagedestroy($sourceImage);
            imagedestroy($resizedImage);
            return false;
        }

        // 리사이즈된 이미지 저장
        $saveResult = false;
        if ($extension === 'jpg' || $extension === 'jpeg') {
            $saveResult = imagejpeg($resizedImage, $destPath, 90); // 품질 90
        } elseif ($extension === 'png') {
            $saveResult = imagepng($resizedImage, $destPath, 6); // 압축 레벨 6
        } elseif ($extension === 'gif') {
            $saveResult = imagegif($resizedImage, $destPath);
        }

        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        return $saveResult;
    }

}
