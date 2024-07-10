<?php
#------------------------------------------------------------------
# Policy.php
# 정책관련
# 김우진
# 2024-07-08 14:27:10
# @Desc :
#------------------------------------------------------------------
namespace Module\setting\Controllers;

use Module\setting\Controllers\Setting;
use Module\setting\Config\Config as settingConfig;
use Module\setting\Models\PolicyModel;

class Policy extends Setting
{
    public function __construct()
    {

        parent::__construct();
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(14) === false)
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
            if ($this->menulib->isGrant(14) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }
    }

    public function index()
    {
        $pageParam               = [];
        $pageParam['file']       = '\Module\core\Views\errors\error_404';
        $pageParam['pageLayout'] = 'blank';

        $this->owensView->loadLayoutView($pageParam);
    }

    public function memberData()
    {
        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------
        $pageDatas                                 = [];
        $requests                                  = $this->request->getGet();
        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $policyModel                               = new PolicyModel();

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------
        $aConfig                                   = new settingConfig();
        $pageDatas['aConfig']                      = $aConfig->policy['member'];

        #------------------------------------------------------------------
        # TODO: 정책관리 데이터 로드
        #------------------------------------------------------------------
        $pageDatas['policyData']                   = $policyModel->getPolicy();

         #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                 = [];
        $pageParam['file']                         = '\Module\setting\Views\policy\member';
        $pageParam['pageLayout']                   = '';
        $pageParam['pageDatas']                    = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }

    public function policyPurchase()
    {
        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------
        $pageDatas                                 = [];
        $requests                                  = $this->request->getGet();
        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $policyModel                               = new PolicyModel();

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------
        $aConfig                                   = new settingConfig();
        $pageDatas['aConfig']                      = $aConfig->policy['purchase'];

        #------------------------------------------------------------------
        # TODO: 정책관리 데이터 로드
        #------------------------------------------------------------------
        $pageDatas['policyData']                   = $policyModel->getPolicy();

         #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                 = [];
        $pageParam['file']                         = '\Module\setting\Views\policy\purchase';
        $pageParam['pageLayout']                   = '';
        $pageParam['pageDatas']                    = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }
}