<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Module\dahae\Models\DahaeModel;
use Config\Services;
class ContentReplace extends BaseCommand
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
    protected $name = 'update:goods-content';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = '';

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
        $idx = isset($params[0]) && is_numeric($params[0]) ? (int)$params[0] : null;
         // 모델 호출
        $dModel = new DahaeModel();
        $sParam = [];
        $sParam[ 'G_IDX' ] = $idx;
        // 데이터 가져오기
        $aData = $dModel->getRealGoodsLists($sParam);

        if (empty($aData['lists'])) {
            CLI::error("상품 데이터가 없습니다.");
            return;
        }
        $matched = [
            '/<style\b[^>]*>(.*?)<\/style>/is',  // <style> 태그 제거
            '/<head\b[^>]*>(.*?)<\/head>/is',    // <head> 태그 제거
            '/<\/head>/is',                      // </head> 태그 제거
            '/<body\b[^>]*>|<\/body>/is',        // <body> 태그 제거
            '/<span\b[^>]*>|<\/span>/is',        // <span> 태그 제거
            '/<div\b[^>]*>|<\/div>/is',          // <div> 태그 제거
            '/<center\b[^>]*>|<\/center>/is',          // <center> 태그 제거
            '/\\\\"/',
        ];
        $this->db->transBegin();
        foreach ($aData['lists'] as $data) {
            $modelParam = [];
            $modelParam['G_IDX'] = _elm( $data, 'G_IDX' );
            CLI::write("==================================================", 'red');
            CLI::write("상품 처리 중: G_IDX " . $data['G_IDX'], 'cyan');
            //CLI::write("CONTENT_PC : " . $data['G_CONTENT_PC'], 'red');
            CLI::write("**************************************************", 'green');
            $cleanedHtmlPc = preg_replace($matched, '', $data['G_CONTENT_PC']);
            //CLI::write("CONTENT_PC replace : " . $cleanedHtmlPc, 'cyan');

            $modelParam['G_CONTENT_PC'] = $cleanedHtmlPc;
            if( empty( $data['G_CONTENT_MOBILE'] ) === false ){
                $cleanedHtmlMo = preg_replace($matched, '', $data['G_CONTENT_MOBILE']);
                //CLI::write("CONTENT_PC replace : " . $cleanedHtmlMo, 'cyan');

                $modelParam['G_CONTENT_MOBILE'] = $cleanedHtmlMo;
            }

            $aStatus = $dModel->updateRealGoodsContents( $modelParam );
            if (!$this->db->transStatus() || !$aStatus) {
                $this->db->transRollback();
                CLI::error("데이터 처리 실패: G_IDX " . $data['G_IDX']);
            }
            $this->db->transCommit();

            CLI::write("상품 처리 완료: G_IDX " . $data['G_IDX'], 'green');
            CLI::write("==================================================", 'red');


        }


    }
}
