<?php
#------------------------------------------------------------------
# Delivery.php
#
# 김우진
# 2024-07-11 15:25:34
# @Desc :
#------------------------------------------------------------------

namespace Module\setting\Controllers;
use Module\setting\Config\Config as settingConfig;
use Module\setting\Models\DeliveryModel;
use Config\Services;
use DOMDocument;
class Delivery extends Setting
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

    public function deliveryComp()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(38) === false)
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
            if ($this->menulib->isGrant(38) === false)
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
        $deliveryModel                              = new DeliveryModel();

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------
        $aConfig                                    = new settingConfig();

        #------------------------------------------------------------------
        # TODO: 데이터 로드
        #------------------------------------------------------------------
        $pageDatas['gData']                         = $deliveryModel->getDeliveryCompany();
        $pageDatas['aConfig']                       = $aConfig->member;

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\setting\Views\delivery\deliveryComp';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);
    }


}