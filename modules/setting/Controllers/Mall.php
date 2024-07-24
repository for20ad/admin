<?php
#------------------------------------------------------------------
# Mall.php
# 상점설정 관련
# 김우진
# 2024-07-15 09:46:49
# @Desc :
#------------------------------------------------------------------
namespace Module\setting\Controllers;

use Module\setting\Config\Config as settingConfig;
use Module\setting\Models\PolicyModel;
use Config\Services;
use DOMDocument;

class Mall extends Setting
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

    public function mallPolicy()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(16) === false)
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
            if ($this->menulib->isGrant(16) === false)
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
        $policyModel                                = new PolicyModel();

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------
        $aConfig                                    = new settingConfig();

        #------------------------------------------------------------------
        # TODO: 데이터 로드
        #------------------------------------------------------------------
        $pageDatas['aData']                         = $policyModel->getMallPolicyDatas();
        $pageDatas['aConfig']                       = $aConfig->member;

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\setting\Views\mall\terms';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);
    }
}