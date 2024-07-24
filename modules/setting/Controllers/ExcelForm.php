<?php
#------------------------------------------------------------------
# ExcelForm.php
# 엑셀 폼 관리
# 김우진
# 2024-07-16 14:00:59
# @Desc :
#------------------------------------------------------------------
namespace Module\setting\Controllers;

use Module\setting\Config\Config as settingConfig;
use Module\setting\Models\ExcelFormModel;
use Module\setting\Models\MenuModel;
use Config\Services;
use DOMDocument;

class ExcelForm extends Setting
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\core\Views\errors\error_404';
        $pageParam['pageLayout']                    = 'blank';

        $this->owensView->loadLayoutView($pageParam);
    }

    public function formLists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(40) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        #------------------------------------------------------------------
        # TODO: 최고관리자 권한체크
        #------------------------------------------------------------------
        // 권한 체크
        if ($this->memberlib->isSuperAdmin() === false)
        {
            if ($this->menulib->isGrant(4) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();
        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $excelFormModel                             = new ExcelFormModel();

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------
        $aConfig                                    = new settingConfig();

        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $pageDatas['aDatas']                        = $excelFormModel->getExcelFormLists();
        $pageDatas['aConfig']                       = $aConfig->menu;
        $pageDatas['getData']                       = $requests;

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\setting\Views\download\form_lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);
    }


}