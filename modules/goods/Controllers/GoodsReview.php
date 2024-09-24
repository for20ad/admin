<?php
#------------------------------------------------------------------
# GoodsReview.php
# 상품리뷰
# 김우진
# 2024-09-19 14:43:28
# @Desc :
#------------------------------------------------------------------

namespace Module\goods\Controllers;

use Config\Services;
use DOMDocument;
use Exception;

use Module\goods\Controllers\Goods;

class GoodsReview extends Goods
{
    public function __construct()
    {
        parent::__construct();
    }

    function lists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(66) === false)
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
            if ($this->menulib->isGrant(66) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();
        $moduleParam                                = [];
        $reviewLists                                = [];

        $modleParam                                 = [];
        $modelParam['order']                        = ' C_SORT ASC';

        $aLISTS_RESULT                              = $this->brandModel->getBrandLists( $modelParam );

        $brand_lists                                = _elm( $aLISTS_RESULT, 'lists' );
        $selectOptions = '<select name="s_brand" class="form-select" style="max-width:150px;">'; // select 시작 태그

        if (!empty($brand_lists)) {
            #------------------------------------------------------------------
            # TODO: 트리형식으로 리스트 변경
            #------------------------------------------------------------------

            $view_datas['brand_tree_lists'] = _build_tree($brand_lists, _elm($brand_lists, 'C_PARENT_IDX', 0, true), 'C_IDX', 'C_PARENT_IDX');

            foreach (_elm($view_datas, 'brand_tree_lists', []) as $vBRAND) {
                // CHILD가 있는지 확인
                $childList = _elm($vBRAND, 'CHILD', []);

                if (!empty($childList)) {
                    // CHILD가 있는 경우 <optgroup>으로 묶어서 저장
                    $selectOptions .= '<optgroup label="' . _elm($vBRAND, 'C_BRAND_NAME') . '">';

                    foreach ($childList as $vBRAND_CHILD) {
                        $selectOptions .= '<option value="' . _elm($vBRAND_CHILD, 'C_IDX') . '">   >>>' . _elm($vBRAND_CHILD, 'C_BRAND_NAME') . '</option>';
                    }

                    $selectOptions .= '</optgroup>'; // optgroup 닫기
                } else {
                    // CHILD가 없는 경우 단일 option 저장
                    $selectOptions .= '<option value="' . _elm($vBRAND, 'C_IDX') . '">' . _elm($vBRAND, 'C_BRAND_NAME') . '</option>';
                }
            }
        }

        $selectOptions .= '</select>'; // select 닫기 태그

        $pageDatas['brandOption']                   = $selectOptions;
        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\goods\Views\review\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }

    public function defaultGoods()
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
        $productLists                               = $this->bundleModel->getDefaultGoodsLists();
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
        $pageParam['file']                          = '\Module\goods\Views\bundle\default\lists';
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