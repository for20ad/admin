<?php
#------------------------------------------------------------------
# Lists.php
# 주문 리스트
# 김우진
# 2024-11-26 09:48:34
# @Desc :
#------------------------------------------------------------------
namespace Module\order\Controllers;
use Module\order\Controllers\Order;

class Lists extends Order
{
    public function __construct()
    {
        parent::__construct();
    }

    function orderLists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(77) === false)
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
            if ($this->menulib->isGrant(77) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------

        $pageDatas                                  = [];

        $requests                                   = $this->request->getGet();
        $pageDatas['aOrderConf']                    = $this->sharedConfig::$orderStatus;
        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];

        $pageParam['file']                          = '\Module\order\Views\order\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }

    function orderPayStatusLists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(78) === false)
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
            if ($this->menulib->isGrant(78) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();
        $pageDatas['aOrderConf']                    = $this->sharedConfig::$orderStatus;
        $pageDatas['aPayMethodConf']                = $this->sharedConfig::$paymentMethods;

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\order\Views\payStatus\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }


    function orderPrdProcessLists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(79) === false)
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
            if ($this->menulib->isGrant(79) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();
        $pageDatas['aOrderConf']                    = $this->sharedConfig::$orderStatus;
        $pageDatas['aPayMethodConf']                = $this->sharedConfig::$paymentMethods;

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\order\Views\payStatusOver\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }
    public function orderExchangeProcessLists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(81) === false)
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
            if ($this->menulib->isGrant(81) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();
        $pageDatas['aOrderConf']                    = $this->sharedConfig::$orderStatus;
        $pageDatas['aPayMethodConf']                = $this->sharedConfig::$paymentMethods;

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\order\Views\exchanges\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }

    public function orderReturnProcessLists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(80) === false)
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
            if ($this->menulib->isGrant(80) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();
        $pageDatas['aOrderConf']                    = $this->sharedConfig::$orderStatus;
        $pageDatas['aPayMethodConf']                = $this->sharedConfig::$paymentMethods;

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\order\Views\returns\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }
}