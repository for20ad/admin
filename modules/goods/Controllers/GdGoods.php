<?php
#------------------------------------------------------------------
# GoodsBundle.php
# 묶음 전용 컨트롤러
# 김우진
# 2024-09-03 15:58:05
# @Desc :
#------------------------------------------------------------------

namespace Module\goods\Controllers;

use Config\Services;
use DOMDocument;
use Exception;

use Module\goods\Controllers\Goods;

class GdGoods extends Goods
{
    public function __construct()
    {
        parent::__construct();
    }

    public function lists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(82) === false)
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
            if ($this->menulib->isGrant(82) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();


        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\goods\Views\gdGoods\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }
    public function bestGoods()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(59) === false)
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
            if ($this->menulib->isGrant(59) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();

        $pageDatas['aColorConfig']                  = $this->sharedConfig::$goodsColor;
        $productLists                               = $this->bundleModel->getBestGoodsLists();
        $pageDatas['limitCnt']                      = _elm( _elm( $productLists, 0), 'A_LIMIT' );
        $pageDatas['productLists']                  = [];
        $productIdxs                                = [];
        if( empty( $productLists ) === false ){
            foreach( $productLists as $key => $list ){
                $goodsInfo                          = $this->goodsModel->getGoodsDataByIdx( _elm( $list, 'A_GOODS_IDX' ) );
                $productIdxs[]                      = _elm( $list, 'A_GOODS_IDX' );
                $pageDatas['productLists'][]        = $goodsInfo;
            }
        }

        $pageDatas['productIdxs']                   = $productIdxs;
        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\goods\Views\bundle\best\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }

    public function newArrival()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(60) === false)
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
            if ($this->menulib->isGrant(60) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();
        $pageDatas['aColorConfig']                  = $this->sharedConfig::$goodsColor;

        $productLists                               = $this->bundleModel->getNewGoodsLists();
        $pageDatas['limitCnt']                      = _elm( _elm( $productLists, 0), 'A_LIMIT' );
        $pageDatas['productLists']                  = [];
        $productIdxs                                = [];
        if( empty( $productLists ) === false ){
            foreach( $productLists as $key => $list ){
                $goodsInfo                          = $this->goodsModel->getGoodsDataByIdx( _elm( $list, 'A_GOODS_IDX' ) );
                $productIdxs[]                      = _elm( $list, 'A_GOODS_IDX' );
                $pageDatas['productLists'][]        = $goodsInfo;
            }
        }

        $pageDatas['productIdxs']                   = $productIdxs;
        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\goods\Views\bundle\newGoods\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }

    public function timeSale()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(61) === false)
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
            if ($this->menulib->isGrant(61) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();
        $pageDatas['aColorConfig']                  = $this->sharedConfig::$goodsColor;


        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\goods\Views\bundle\timeSale\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }

    public function timeSaleDetail( $timeIdx )
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(61) === false)
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
            if ($this->menulib->isGrant(61) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        if( empty($timeIdx) ){
            box_alert_back( '잘못된 접근입니다.' );
        }

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();
        $pageDatas['aColorConfig']                  = $this->sharedConfig::$goodsColor;
        $aData                                      = $this->bundleModel->timeSalseDataByIdx( $timeIdx );

        $productLists                               = $this->bundleModel->getTimeSaleGoodsLists( $timeIdx );

        $pageDatas['limitCnt']                      = _elm( $aData, 'A_LIMIT' );
        $aData['productLists']                      = [];

        $productIdxs                                = [];
        if( empty( $productLists ) === false ){
            foreach( $productLists as $key => $list ){
                $goodsInfo                          = $this->goodsModel->getGoodsDataByIdx( _elm( $list, 'AD_GOODS_IDX' ) );
                $productIdxs[]                      = _elm( $list, 'AD_GOODS_IDX' );
                $aData['productLists'][$key]        = $goodsInfo;
                $aData['productLists'][$key]['G_SALE_PRICE'] = _elm( $list, 'AD_SALE_PRICE' );
            }
        }

        $pageDatas['productIdxs']                   = $productIdxs;
        $pageDatas['aData']                         = $aData;
        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\goods\Views\bundle\timeSale\detail_list';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }

    public function weeklyGoods()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(61) === false)
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
            if ($this->menulib->isGrant(61) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();
        $pageDatas['aColorConfig']                  = $this->sharedConfig::$goodsColor;


        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\goods\Views\bundle\weekly\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }

    public function weeklyGoodsDetail( $weeklyIdx )
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(62) === false)
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
            if ($this->menulib->isGrant(62) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        if( empty($weeklyIdx) ){
            box_alert_back( '잘못된 접근입니다.' );
        }

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();
        $pageDatas['aColorConfig']                  = $this->sharedConfig::$goodsColor;
        $aData                                      = $this->bundleModel->weeklyGoodsDataByIdx( $weeklyIdx );

        $productLists                               = $this->bundleModel->getWeeklyGoodsDetailLists( $weeklyIdx );



        $pageDatas['limitCnt']                      = _elm( $aData, 'A_LIMIT' );
        $aData['productLists']                      = [];

        $productIdxs                                = [];
        if( empty( $productLists ) === false ){
            foreach( $productLists as $key => $list ){
                $goodsInfo                          = $this->goodsModel->getGoodsDataByIdx( _elm( $list, 'AD_GOODS_IDX' ) );
                $productIdxs[]                      = _elm( $list, 'AD_GOODS_IDX' );
                $aData['productLists'][]            = $goodsInfo;
            }
        }

        $pageDatas['productIdxs']                   = $productIdxs;
        $pageDatas['aData']                         = $aData;
        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\goods\Views\bundle\weekly\detail_list';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }

}