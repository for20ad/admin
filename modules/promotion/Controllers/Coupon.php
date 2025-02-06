<?php

namespace Module\promotion\Controllers;

use Module\goods\Models\GoodsModel;
use Module\goods\Models\CategoryModel;
use Module\goods\Models\BrandModel;


use Config\Services;
use DOMDocument;
use Exception;

use Module\promotion\Controllers\Promotion;

class Coupon extends Promotion
{
    protected $goodsModel, $categoryModel, $brandModel;
    public function __construct()
    {
        parent::__construct();
        $this->goodsModel                           = new GoodsModel();
        $this->categoryModel                        = new CategoryModel();
        $this->brandModel                           = new BrandModel();
    }

    public function lists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(70) === false)
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
            if ($this->menulib->isGrant(70) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();

        $memberGroup                                = $this->memberModel->getMembershipGrade();

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\promotion\Views\coupon\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }

    public function register()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(71) === false)
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
            if ($this->menulib->isGrant(71) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();

        $_aMemberGrade                              = $this->memberModel->getMembershipGrade();
        $aMemberGrade                               = [];

        if( empty($_aMemberGrade) === false ){
            foreach( $_aMemberGrade as $val){
                if (isset($val['G_IDX'])) {
                    $aMemberGrade[_elm($val, 'G_IDX')] = _elm($val, 'G_NAME');
                }
            }
        }
        $pageDatas['aMemberGrade']                  = $aMemberGrade;
        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\promotion\Views\coupon\register';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);
    }

    public function detail( $cpn_idx )
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(71) === false)
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
            if ($this->menulib->isGrant(71) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        if( empty($cpn_idx) ){
            box_alert_back( '잘못된 접근입니다.' );
        }

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();

        $_aMemberGrade                              = $this->memberModel->getMembershipGrade();
        $aMemberGrade                               = [];

        if( empty($_aMemberGrade) === false ){
            foreach( $_aMemberGrade as $val){
                if (isset($val['G_IDX'])) {
                    $aMemberGrade[_elm($val, 'G_IDX')] = _elm($val, 'G_NAME');
                }
            }
        }
        $couponInfo                                 = $this->couponModel->getCouponInfo( $cpn_idx );

        if( empty( $couponInfo ) ){
            box_alert_back( '잘못된 접근입니다.' );
        }

        #------------------------------------------------------------------
        # TODO: 제외상품 리스트
        #------------------------------------------------------------------
        $couponInfo['exceptProductLists']           = [];
        if( empty( _elm( $couponInfo, 'C_EXCEPT_PRODUCT_IDXS' ) ) === false ){
            $goodsIdxs                              = explode( ',',_elm( $couponInfo, 'C_EXCEPT_PRODUCT_IDXS' ) );
            foreach( $goodsIdxs as $gIdx ){
                $goodsInfo                          = $this->goodsModel->getGoodsDataByIdx( $gIdx );
                if(  empty( $goodsInfo ) == false){
                    $couponInfo['exceptProductLists'][] = $goodsInfo;
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: scope에 따른 선택 브랜드, 카테고리, 상품 데이터 로드
        #------------------------------------------------------------------
        $couponInfo['pickItemLists']                = [];

        switch( _elm( $couponInfo, 'C_SCOPE_GBN' ) )
        {
            case 'category':
                if( empty( _elm( $couponInfo, 'C_PICK_ITEMS' ) ) === false ){
                    $pick_idxs                      = explode( ',', _elm( $couponInfo, 'C_PICK_ITEMS' ) );
                    foreach( $pick_idxs as $cateIdx ){
                        $cateInfo                   = $this->categoryModel->getCategoryDataByIdx( $cateIdx );
                        if( empty( $cateInfo ) === false ){
                            $couponInfo['pickItemLists'][] = $cateInfo;
                        }
                    }
                }
                break;

            case 'brand':
                if( empty( _elm( $couponInfo, 'C_PICK_ITEMS' ) ) === false ){
                    $pick_idxs                      = explode( ',', _elm( $couponInfo, 'C_PICK_ITEMS' ) );
                    foreach( $pick_idxs as $brandIdx ){
                        $brandInfo                  = $this->brandModel->getBrandDataByIdx( $brandIdx );
                        if( empty( $brandInfo ) === false ){
                            $couponInfo['pickItemLists'][] = $brandInfo;
                        }
                    }
                }
                break;

            case 'product':
                if( empty( _elm( $couponInfo, 'C_PICK_ITEMS' ) ) === false ){
                    $pick_idxs                      = explode( ',', _elm( $couponInfo, 'C_PICK_ITEMS' ) );
                    foreach( $pick_idxs as $goodsIdx ){
                        $goodsInfo                  =  $this->goodsModel->getGoodsDataByIdx( $goodsIdx );
                        if( empty( $goodsInfo ) === false ){
                            $couponInfo['pickItemLists'][] = $goodsInfo;
                        }
                    }
                }
                break;
            default:
                $couponInfo['pickItemLists'] = [] ;
        }



        $pageDatas['aData']                         = $couponInfo;

        $pageDatas['aMemberGrade']                  = $aMemberGrade;
        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\promotion\Views\coupon\detail';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);
    }


}