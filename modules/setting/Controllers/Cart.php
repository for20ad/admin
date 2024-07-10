<?php
#------------------------------------------------------------------
# Cart.php
# 장바구니 설정
# 김우진
# 2024-07-09 09:25:14
# @Desc :
#------------------------------------------------------------------

namespace Module\setting\Controllers;

use Module\setting\Controllers\Setting;
use Module\setting\Config\Config as settingConfig;
use Module\setting\Models\CartModel;

class Cart extends Setting
{
    public function __construct()
    {

        parent::__construct();
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(24) === false)
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
            if ($this->menulib->isGrant(24) === false)
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

    public function defaultSetting()
    {
        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------
        $pageDatas                                 = [];
        $requests                                  = $this->request->getGet();
        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $cartModel                                 = new CartModel();

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------
        $aConfig                                   = new settingConfig();
        $pageDatas['aConfig']                      = $aConfig->policy['cart'];

        #------------------------------------------------------------------
        # TODO: 정책관리 데이터 로드
        #------------------------------------------------------------------
        $pageDatas['cartSettingData']              = $cartModel->getCartSetting();

         #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                 = [];
        $pageParam['file']                         = '\Module\setting\Views\cart\cartSetting';
        $pageParam['pageLayout']                   = '';
        $pageParam['pageDatas']                    = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }


}