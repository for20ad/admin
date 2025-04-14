<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Module\dahae\Models\DahaeModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use Config\Services;

class SetGoodsOptionsText extends BaseCommand
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
    protected $name = 'set:goods-option-text';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Set goods option title setting file';

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
        $perPage = 1000;

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
        $aData = $dModel->getRealGoodsListsA($modelParam);

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

                $optionInfo = $dModel->getGoodsOptionGroup($data['G_IDX']);

                //$this->db->transBegin();
                if( empty( $optionInfo ) === true ){
                    CLI::write("**************************************************", 'red');
                    CLI::write("상품 옵션 EMPTY: G_IDX " . $data['G_IDX'], 'green');
                    CLI::write("==================================================", 'red');
                }else{
                    $formattedData = [];
                    $optionKeys = [];

                    foreach ($optionInfo as $option) {
                        $key = $option['O_KEYS'];
                        $value = $option['O_VALUES'];

                        // O_KEYS를 배열에 저장 (중복 제거)
                        if (!in_array($key, $optionKeys)) {
                            $optionKeys[] = $key;
                        }

                        // JSON 형식의 데이터 생성
                        $formattedData[$key][] = ['value' => $value];
                    }

                    // O_KEYS를 파이프(|) 구분자로 연결
                    $optionKeysString = implode('|', $optionKeys);

                    // 결과 출력

                    $modelParam  = [];
                    $modelParam['G_IDX']                =  $data['G_IDX'];
                    $modelParam['G_OPTION_COMBINATION_FLAG'] = 'N';
                    $modelParam['G_OPTION_NAMES'] = $optionKeysString;
                    $modelParam['G_OPTION_INFO'] = json_encode($formattedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    $aStatus                     = $dModel->updateGoodsCombinationsInfo( $modelParam );
                    CLI::write("**************************************************", 'red');
                    $this->db->transCommit();
                    CLI::write("상품 처리 완료: G_IDX " . $data['G_IDX'], 'green');
                    CLI::write("==================================================", 'red');
                }




            }
        }

        $totalPages = ceil($aData['total_count'] / $perPage);
        CLI::write("총 페이지 수: {$totalPages}", 'yellow');
        CLI::write("남은 패에지 수 :: ".ceil(  $aData['total_count'] / $perPage - $page ), 'green' );

        if ($page < $totalPages) {
            $nextPage = $page + 1;
            CLI::write("다음 페이지를 실행하려면 명령어를 실행하세요: php spark set:goods-option-text {$nextPage}", 'green');
        }
    }


}
