<?php
#------------------------------------------------------------------
# ApiController.php
# api 최상위 컨트롤러
# 김우진
# 2024-05-13 17:06:19
# @Desc :
#------------------------------------------------------------------
namespace Module\core\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use App\Libraries\OwensCache;
use Module\setting\Libraries\Member;
use Module\core\Models\LogModel;
use Module\setting\Models\ExcelFormModel;

use Shared\Config as SharedConfig;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ApiController extends ResourceController
{
    protected $_encryption_key = 'ZIeAzykfMrqDKwtKTjk573t0ovtQS1Xn'; // 256bit
    use ResponseTrait;

    protected $helpers = ['owens', 'owens_url', 'owens_convert' , 'owens_validation'];
    public $owensCache = null;
    protected $_userInfo = [];
    protected $LogModel = null;
    public $session;
    public $uri;
    public $baseURL;
    protected $sharedConfig;

    public function __construct()
    {

        $this->response                             = Services::response();
        $this->request                              = Services::request();
        $this->session                              = Services::session();
        $this->uri                                  = Services::uri();
        $this->LogModel                             = new LogModel();
        $this->baseURL                              = base_url();
        $this->sharedConfig                         = new SharedConfig();

        helper($this->helpers);

        $this->owensCache                           = new OwensCache();
        #------------------------------------------------------------------
        # TODO: 요청 API 데이터 저장
        #------------------------------------------------------------------
        // $reciveParam                                = [];

        // $reciveParam['REQUEST_URI']                 = (string) current_url(true);
        // $reciveParam['METHOD']                      = $this->request->getMethod();
        // $_params                                    = json_encode( $this->request->getVar(), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) ;
        // $_params                                    = (array)json_decode( str_replace( '\n____', '', $_params ) );


        // $reciveParam['PARAMETERS']                  = json_encode( $_params, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );

        // $reciveParam['SENDER_IP']                   = $this->request->getIPAddress();
        // $reciveParam['AGENT']                       = (string) $this->request->getUserAgent();
        // $reciveParam['SEND_AT']                     = date( 'Y-m-d H:i:s' );

        // $this->LogModel->setRequestApiLog( $reciveParam );


        //$this->member = new Member();
    }

    protected function _returnArrayResponse($data, $statusCode = 200)
    {
        return $this->response->setBody($data)->setStatusCode($statusCode);
    }

    public function setUserInfo( $data )
    {
        $this->_userInfo = $data;
    }

    #------------------------------------------------------------------
    # TODO:  Override the respond method to use JSON_NUMERIC_CHECK option
    #   @param mixed  $data
    #  @param int    $status
    #  @param string $message
    #  @return \CodeIgniter\HTTP\Response
    #------------------------------------------------------------------
    public function respond($data = null, int $status = null, string $message = '')
    {
        // If the data is already an array, we can safely apply json_encode with JSON_NUMERIC_CHECK
        if (is_array($data) || is_object($data)) {
            $data                                   = json_decode(json_encode($data, JSON_NUMERIC_CHECK), true);
        }

        return parent::respond($data, $status, $message);
    }

    protected function _initApiResponse()
    {
        $response                                   = [];
        $response['status']                         = 400; // 500, 200, 201
        $response['error']                          = null;
        $response['messages']                       = null;
        //$response['time']     = time();
        //$response['data']    = [];

        return $response;
    }

    protected function _initResponse()
    {
        $response                                   = [];
        $response['status']                         = false;
        $response['messages']                       = [];
        $response['alert']                          = '';
        $response['redirect_url']                  = '';
        $response['replace_url']                   = '';
        $response['goback']                        = false;
        $response['reload']                        = false;
        $response['time']                           = time();

        return $response;
    }

    protected function _unset( $response = [] )
    {
        unset( $response['redirect_url'] );
        unset( $response['replace_url'] );

        return $response;
    }

    protected function _getSegment($offset = 0)
    {
        return _elm($this->uri->getSegments(), $offset);
    }

    protected function _upload_put($file, $_config)
    {

        $config                                     = [
            'upload_path' => WRITEPATH . 'uploads/' . _elm($_config, 'path'), // 파일이 업로드될 경로
            'allowed_types' => _elm($_config, 'mimes') ?? 'jpg|jpeg|png|gif|csv|xls|xlsx|zip|hwp|pdf|svg', // 허용된 파일 확장자
            'max_size' => _elm($_config, 'max_size') ?? '10240', // 업로드할 파일의 최대 크기 (KB)
        ];

        // 파일 크기 검사를 수행합니다.
        if ($file['size'] > ($config['max_size'] * 1024)) { // KB 단위로 비교
            $return['status']                       = false;
            $return['error']                        = '최대 사이즈 오류발생 ' . $config['max_size'] . ' KB';
            return $return;
        }

        // 파일이 업로드될 경로가 존재하지 않는 경우 폴더를 생성합니다.
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $return['org_name'] = $file['name'];
        $realFilename                               = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $uploadPath                                 = $config['upload_path'] . '/' . $realFilename;

        // 파일을 업로드합니다.
        if (is_uploaded_file($file['tmp_name']) || file_exists($file['tmp_name'])) {
            if (move_uploaded_file($file['tmp_name'], $uploadPath) || rename($file['tmp_name'], $uploadPath)) {
                $return['url']                      = base_url() . 'uploads/' . _elm($_config, 'path') . '/' . $realFilename;
                $return['uploaded_path']            = 'uploads/' . _elm($_config, 'path') . '/' . $realFilename;
                $return['status']                   = true;
                $return['type']                     = mime_content_type($uploadPath);
                $return['size']                     = filesize($uploadPath); // in bytes
                $return['ext']                      = pathinfo($uploadPath, PATHINFO_EXTENSION);
            } else {
                $return['status']                   = false;
                $return['error']                    = '파일 업로드 실패';
            }
        } else {
            $return['status']                       = false;
            $return['error']                        = '파일 업로드 중 오류 발생';
        }

        return $return;
    }



    protected function _upload( $file , $_config  )
    {
        $uploader                                   = Services::upload();

        $config                                     = [
            'upload_path'                           => WRITEPATH.'uploads/'._elm( $_config, 'path' ), // 파일이 업로드될 경로
            'allowed_types'                         => _elm( $_config, 'mimes' )??'jpg|jpeg|png|gif|csv|xls|xlsx|zip|hwp|pdf|svg', // 허용된 파일 확장자
            'max_size'                              => _elm( $_config, 'max_size' )??'10240', // 업로드할 파일의 최대 크기 (KB)
        ];

        // 파일 크기 검사를 수행합니다.
        if ($file->getSize() > ($config['max_size']*1024) )  { // KB 단위로 비교
            $return['status']                       = false;
            $return['error']                        = '최대 사이즈 오류발생 ' . $config['max_size'] . ' KB';
            return $return;
        }
        if (is_dir($config['upload_path']) === false)
        {
            mkdir($config['upload_path'], 0777, true);
        }

        // 파일이 업로드될 경로가 존재하지 않는 경우 폴더를 생성합니다.
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $return['org_name']                         = $file->getName();
        $realFilename                               = $file->getRandomName();

        // 파일을 업로드합니다.
        $file->move($config['upload_path'], $realFilename);

        $return['url']                              = base_url().'uploads/'._elm( $_config, 'path' ).'/'.$realFilename;
        $return['uploaded_path']                    = 'uploads/'._elm( $_config, 'path' ).'/'.$realFilename;
        $return['status']                           = 'true';

        $return['type']                             = mime_content_type( $return['uploaded_path'] );
        $return['size']                             = filesize( $return['uploaded_path'] ); // in bytes
        $return['ext']                              = pathinfo( $return['uploaded_path'] , PATHINFO_EXTENSION );


        return $return;
    }

    protected function _uploadAndResize( $file , $_config  )
    {
        $uploader                                   = Services::upload();

        // 기본 설정 및 사용자 정의 설정 병합
        $config                                     = [
            'upload_path'                           => WRITEPATH . 'uploads/' . _elm($_config, 'path'),
            'allowed_types'                         => _elm($_config, 'mimes') ?? 'jpg|jpeg|png|gif',
            'max_size'                              => _elm($_config, 'max_size') ?? '10240', // KB 단위
        ];

        // 파일 크기 검사를 수행
        if ($file->getSize() > ($config['max_size'] * 1024)) { // KB 단위로 비교
            return [
                'status'                            => false,
                'error'                             => '최대 사이즈 오류발생 ' . $config['max_size'] . ' KB'
            ];
        }

        // 업로드 경로가 존재하지 않으면 생성
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $originalName                               = $file->getName();
        $realFilename                               = $file->getRandomName();

        // 파일 업로드
        if (!$file->move($config['upload_path'], $realFilename)) {
            return [
                'status' => false,
                'error'  => '파일 업로드 실패'
            ];
        }

        $uploadedPath                               = $config['upload_path'] . '/' . $realFilename;
        $relativePath                               = 'uploads/' . _elm($_config, 'path') . '/' . $realFilename;

        // 리사이즈할 사이즈 목록
        $sizes = [
            [100, 100],
            [250, 250],
            [500, 500],
            [800, 800],
            [1024, 1024],
        ];

        $resizedImages                              = $this->_resizeImage($uploadedPath, $relativePath , $sizes, $config['upload_path'], $realFilename, $_config );

        // 업로드된 파일 및 리사이즈된 파일 정보 반환
        return [
            'status'                                => true,
            'org_name'                              => $originalName,
            'url'                                   => base_url($relativePath),
            'uploaded_path'                         => $relativePath,
            'type'                                  => mime_content_type($uploadedPath),
            'size'                                  => filesize($uploadedPath), // bytes 단위
            'ext'                                   => pathinfo($uploadedPath, PATHINFO_EXTENSION),
            'resized'                               => $resizedImages, // 리사이즈된 이미지 정보
        ];
    }

    // 이미지 리사이즈 함수
    protected function _resizeImage($sourcePath, $relativePath, $sizes, $destinationPath, $originalFilename, $_config )
    {

        $resizedImages                              = [];

        foreach ($sizes as $size) {
            $width = $size[0];
            $height = $size[1];
            $resizedFilename = pathinfo($originalFilename, PATHINFO_FILENAME) . "_{$width}x{$height}." . pathinfo($originalFilename, PATHINFO_EXTENSION);
            $resizedPath = $destinationPath . '/' . $resizedFilename;
            $realResizedPath = str_replace('/home/admin/writable/','',$destinationPath) . '/' . $resizedFilename;

            // 리사이즈 작업 수행
            if ($this->_performResize($sourcePath, $resizedPath, $width, $height)) {
                $resizedImages[] = [
                    'name'  => $resizedFilename,
                    'path' => $relativePath,
                    'size' => "{$width}"
                ];
            }
        }

        // foreach ($sizes as $size) {
        //     list($targetWidth, $targetHeight) = $size;
        //     $resizedFilename = "{$targetWidth}x{$targetHeight}_{$originalFilename}";
        //     $resizedPath = $destinationPath . '/' . $resizedFilename;

        //     // 리사이즈 작업 수행

        //     if ($this->_performResize($sourcePath, $resizedPath, $targetWidth, $targetHeight)) {
        //         $resizedImages[] = [
        //             'name'  => $resizedFilename,
        //             'path' => $relativePath,
        //             'size' => "{$targetWidth}"
        //         ];
        //     }
        //     // $resized = $this->_createResizedImage($sourcePath, $targetWidth, $targetHeight, $resizedPath);

        //     // if ($resized) {
        //     //     $relativePath = 'uploads/' . _elm($_config, 'path').'/'. $resizedFilename;

        //     //     $resizedImages[] = [
        //     //         'name'  => $resizedFilename,
        //     //         'path'  => $relativePath,  // '/uploads/'로 시작하는 경로 반환
        //     //         'size'  => "{$targetWidth}",
        //     //     ];
        //     // }
        // }

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

        // 원본 이미지 크기 가져오기
        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);

        // 원본 비율 유지하여 새 크기 계산
        $aspectRatio = $sourceWidth / $sourceHeight;

        if ($width / $height > $aspectRatio) {
            $newWidth = $height * $aspectRatio;
            $newHeight = $height;
        } else {
            $newWidth = $width;
            $newHeight = $width / $aspectRatio;
        }

        // 새 캔버스 생성
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

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
            $newWidth, $newHeight,
            $sourceWidth,
            $sourceHeight
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


    // protected function _performResize($sourcePath, $destPath, $width, $height)
    // {
    //     $extension = strtolower(pathinfo($destPath, PATHINFO_EXTENSION));
    //     $sourceImage = null;

    //     // 원본 이미지 로드
    //     if ($extension === 'jpg' || $extension === 'jpeg') {
    //         $sourceImage = imagecreatefromjpeg($sourcePath);
    //     } elseif ($extension === 'png') {
    //         $sourceImage = imagecreatefrompng($sourcePath);
    //     } elseif ($extension === 'gif') {
    //         $sourceImage = imagecreatefromgif($sourcePath);
    //     }

    //     if ($sourceImage === false) {
    //         return false;
    //     }

    //     // 새 캔버스 생성
    //     $resizedImage = imagecreatetruecolor($width, $height);

    //     // 투명 배경 처리 (PNG, GIF)
    //     if ($extension === 'png' || $extension === 'gif') {
    //         imagealphablending($resizedImage, false);
    //         imagesavealpha($resizedImage, true);
    //         $transparentColor = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
    //         imagefill($resizedImage, 0, 0, $transparentColor);
    //     } else {
    //         // 비투명 이미지의 경우 흰색 배경 채우기
    //         $white = imagecolorallocate($resizedImage, 255, 255, 255);
    //         imagefill($resizedImage, 0, 0, $white);
    //     }

    //     // 이미지 복사 및 리사이징
    //     $result = imagecopyresampled(
    //         $resizedImage,
    //         $sourceImage,
    //         0, 0, 0, 0,
    //         $width, $height,
    //         imagesx($sourceImage),
    //         imagesy($sourceImage)
    //     );

    //     if (!$result) {
    //         imagedestroy($sourceImage);
    //         imagedestroy($resizedImage);
    //         return false;
    //     }

    //     // 리사이즈된 이미지 저장
    //     $saveResult = false;
    //     if ($extension === 'jpg' || $extension === 'jpeg') {
    //         $saveResult = imagejpeg($resizedImage, $destPath, 90); // 품질 90
    //     } elseif ($extension === 'png') {
    //         $saveResult = imagepng($resizedImage, $destPath, 6); // 압축 레벨 6
    //     } elseif ($extension === 'gif') {
    //         $saveResult = imagegif($resizedImage, $destPath);
    //     }

    //     imagedestroy($sourceImage);
    //     imagedestroy($resizedImage);

    //     return $saveResult;
    // }

    // 실제 리사이즈 작업을 수행하는 함수 (GD 라이브러리 사용)
    protected function _createResizedImage($sourcePath, $targetWidth, $targetHeight, $destinationPath)
    {
        list($originalWidth, $originalHeight, $imageType) = getimagesize($sourcePath);

        // 원본 이미지에 대한 리소스 생성
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage                        = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage                        = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $sourceImage                        = imagecreatefromgif($sourcePath);
                break;
            default:
                return false; // 지원되지 않는 파일 형식
        }

        // 새로운 크기의 이미지 리소스 생성
        $resizedImage                               = imagecreatetruecolor($targetWidth, $targetHeight);

        // 원본 이미지의 내용을 리사이즈된 이미지에 복사
        imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $originalWidth, $originalHeight);

        // 리사이즈된 이미지를 저장
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($resizedImage, $destinationPath);
                break;
            case IMAGETYPE_PNG:
                imagepng($resizedImage, $destinationPath);
                break;
            case IMAGETYPE_GIF:
                imagegif($resizedImage, $destinationPath);
                break;
        }

        // 메모리에서 이미지 리소스 제거
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        return true;
    }

    protected function _upload_base64( $file_data , $org_name , $_config  )
    {
        $uploader                                   = Services::upload();

        $config                                     = [
            'upload_path'                           => WRITEPATH.'uploads/'._elm( $_config, 'path' ), // 파일이 업로드될 경로
            'allowed_types'                         => _elm( $_config, 'mimes' )??'jpg|jpeg|png|gif|csv|xls|xlsx|zip|hwp|pdf|svg', // 허용된 파일 확장자
            'max_size'                              => _elm( $_config, 'max_size' )??'10240', // 업로드할 파일의 최대 크기 (KB)
        ];

        if (is_dir($config['upload_path']) === false)
        {
            mkdir($config['upload_path'], 0777, true);
        }

        // 파일이 업로드될 경로가 존재하지 않는 경우 폴더를 생성합니다.
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $return['org_name']                         = $org_name;


        $fileName                                   = uniqid() . '.jpg'; // 파일 이름 생성
        $filepath                                   = $config['upload_path'].'/'.$fileName;

        $bStatus                                    = file_put_contents($filepath, $file_data);
        if( $bStatus ){
            $return['url']                          = base_url().'uploads/'._elm( $_config, 'path' ).'/'.$fileName;
            $return['uploaded_path']                = 'uploads/'._elm( $_config, 'path' ).'/'.$fileName;
            $return['status']                       = true;

            $return['type']                         = mime_content_type($filepath);
            $return['size']                         = filesize($filepath); // in bytes
            $return['ext']                          = pathinfo($filepath, PATHINFO_EXTENSION);

        }else{
            $return['status']                       = false;
        }


        return $return;
    }

    protected function _upload_tmp_base64( $file_data , $org_name , $_config  )
    {
        $uploader                                   = Services::upload();

        $config                                     = [
            'upload_path'                           => WRITEPATH.'uploads/temp/'._elm( $_config, 'path' ), // 파일이 업로드될 경로
            'allowed_types'                         => _elm( $_config, 'mimes' )??'jpg|jpeg|png|gif|csv|xls|xlsx|zip|hwp|pdf|svg', // 허용된 파일 확장자
            'max_size'                              => _elm( $_config, 'max_size' )??'10240', // 업로드할 파일의 최대 크기 (KB)
        ];

        if (is_dir($config['upload_path']) === false)
        {
            mkdir($config['upload_path'], 0777, true);
        }

        // 파일이 업로드될 경로가 존재하지 않는 경우 폴더를 생성합니다.
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $return['org_name']                         = $org_name;


        $fileName                                   = uniqid() . '.jpg'; // 파일 이름 생성
        $filepath                                   = $config['upload_path'].'/'.$fileName;

        $bStatus                                    = file_put_contents($filepath, $file_data);
        if( $bStatus ){
            $return['url']                          = base_url().'uploads/temp/'._elm( $_config, 'path' ).'/'.$fileName;
            $return['uploaded_path']                = 'uploads/'._elm( $_config, 'path' ).'/'.$fileName;
            $return['status']                       = true;

            $return['type']                         = mime_content_type($filepath);
            $return['size']                         = filesize($filepath); // in bytes
            $return['ext']                          = pathinfo($filepath, PATHINFO_EXTENSION);

        }else{
            $return['status']                       = false;
        }


        return $return;
    }


    protected function _aesEncrypt($string = '')
    {
        if (empty($string) === true)
        {
            return $string;
        }

        $key = $this->_encryption_key;

        if (empty($key) === true)
        {
            return $string;
        }

        return '[' . _encrypt($string, $key, '', 'AES-256-ECB');
    }

    // decrypt
    protected function _aesDecrypt($string = '')
    {
        if (empty($string) === true)
        {
            return $string;
        }

        $key = $this->_encryption_key;

        if (empty($key) === true)
        {
            return $string;
        }

        return _decrypt(substr($string, 1), $key, '', 'AES-256-ECB');
    }

    /**
     * 페이징 처리
     *
     * @param array $param
     * @return string
     */

    protected function _pagination($param = [])
    {
        $pager                                      = \Config\Services::pager();

        $currentPage                                = $param['cur_page'] ?? 1;
        $perPage                                    = $param['per_page'] ?? 10;
        $totalRows                                  = $param['total_rows'] ?? 0;
        $baseUrl                                    = $param['base_url'] ?? '';

        $totalPages                                 = ceil($totalRows / $perPage);

        // 페이지 그룹당 표시할 최대 페이지 수
        $pageGroupSize                              = 10;
        $currentGroupStart                          = (ceil($currentPage / $pageGroupSize) - 1) * $pageGroupSize + 1;
        $currentGroupEnd                            = min($currentGroupStart + $pageGroupSize - 1, $totalPages);

        $html                                       = '<ul class="pagination align-items-center body2-c">';

        // 이전 그룹 버튼
        if ($currentGroupStart > 1) {
            $prevGroup                              = _elm( $param, 'ajax' ) === false ? $baseUrl . '?page=' . ($currentGroupStart - 1) : 'javascript:void(0);';
            $html                                  .= '<li class="page-item">
                                                        <a href="' . $prevGroup . '" data-page="'.($currentGroupStart - 1).'" class="page-link">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M10 4L6 8L10 12" stroke="#ADB5BD" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </a>
                                                    </li>';
        }

        // 페이지 번호
        for ($i = $currentGroupStart; $i <= $currentGroupEnd; $i++) {
            if ($i == $currentPage) {
                $html                              .= '<li class="page-item active"><a href="javascript:void(0);" class="">' . $i . '</a></li>';
            } else {
                $nums                               = _elm( $param, 'ajax' ) === false ? $baseUrl . '?page=' . ($i) : 'javascript:void(0);';
                $html                              .= '<li class="page-item"><a href="' . $nums.'" data-page="'.$i.'"class="page-link">' . $i . '</a></li>';
            }
        }

        // 다음 그룹 버튼
        if ($currentGroupEnd < $totalPages) {
            $nextGroup                              = _elm( $param, 'ajax' ) === false ? $baseUrl . '?page=' . ($currentGroupEnd + 1) : 'javascript:void(0);';
            $html                                  .= '<li class="page-item">
                                                        <a href="' . $nextGroup .'" data-page="'.( $currentGroupEnd + 1 ).'" class="page-link">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 4L10 8L6 12" stroke="#616876" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </a>
                                                    </li>';
        }

        $html                                      .= '</ul>';

        // 총 페이지 및 이동 드롭다운
        $html                                      .= '<div class="pagination-goto" style="gap: 8px">';
        $html                                      .= '<p>페이지</p>';
        $html                                      .= '<select class="form-select">';

        for ($i = 1; $i <= $totalPages; $i++) {
            $selected                               = ($i == $currentPage) ? ' selected' : '';
            $html                                  .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
        }

        $html                                      .= '</select>';
        $html                                      .= '<p>총 ' . $totalPages . '</p>';
        $html                                      .= '</div>';

        return $html;
    }
    protected function exportExcel($data, $form_idx, $down_file_name) {
        $spreadsheet                                = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet                                      = $spreadsheet->getActiveSheet();
        $excelFormModel                             = new ExcelFormModel();
        $formParam                                  = ['F_IDX' => $form_idx];
        #------------------------------------------------------------------
        # TODO: 폼데이터 받아오기
        #------------------------------------------------------------------
        $formData                                   = $excelFormModel->getFormsDataByIdx($formParam);
        #------------------------------------------------------------------
        # TODO: 테이블의 필드 데이터 모두 뽑아옴
        #------------------------------------------------------------------
        $keys                                       = _elm( $formData, 'F_MENU' );
        $_fields                                    = _elm($this->sharedConfig::$excelField,$keys );

        #------------------------------------------------------------------
        # TODO: 필드 구분자로 분리
        #------------------------------------------------------------------
        $fields                                     = explode('|', _elm($formData, 'F_FIELDS'));

        $columns = [];

        #------------------------------------------------------------------
        # TODO: 모든 필드에서 구분자로 분리된 필드들만 뽑아서 재정렬
        #------------------------------------------------------------------
        foreach ($fields as $field) {
            foreach ($_fields as $_key => $_field) {
                if ($_key == $field) {
                    $columns[]                      = $_field;
                    break;
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 다시 엑셀의 해더 정렬
        #------------------------------------------------------------------
        $colIndex                                   = 'A';
        foreach ($columns as $column) {
            $sheet->setCellValue($colIndex . '1', $column);
            $colIndex++;
        }
        #------------------------------------------------------------------
        # TODO: 구분자로 분리된걸 재정렬 했으니 해당 필드만 데이터로 만들어준다.
        #------------------------------------------------------------------
        $row = 2;
        foreach ($data as $item) {
            $colIndex                               = 'A';
            foreach ($fields as $field) {
                if( is_numeric( _elm($item, $field) ) ){
                    $sheet->setCellValue($colIndex . $row, number_format( _elm( $item, $field, '0', true) ) );
                    // 셀 스타일 설정
                    $sheet->getStyle($colIndex . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }else{
                    $sheet->setCellValue($colIndex . $row, _elm($item, $field, '-', true));
                    // 셀 스타일 설정
                    $sheet->getStyle($colIndex . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                $colIndex++;
            }
            $row++;
        }
        // 컬럼 너비 자동 조정 적용
        foreach (range(1, count($data[0])) as $colIndex) {
            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex))->setAutoSize(true);
        }


        #------------------------------------------------------------------
        # TODO: 엑셀 다운로드 해더
        #------------------------------------------------------------------        //  // HTTP 응답으로 엑셀 파일 다운로드
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$down_file_name.'.xlsx"');
        header('Cache-Control: max-age=0');
        header('Expires: 0');
        header('Pragma: public');

        $writer                                     = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean(); // Output 버퍼 비우기
        $writer->save('php://output');
        exit;
    }


}
