<?php
#------------------------------------------------------------------
# GoodsDetail.php
# 상품수정 컨트롤러
# 김우진
# 2024-08-09 14:02:13
# @Desc :
#------------------------------------------------------------------
namespace Module\goods\Controllers;

use Config\Services;
use DOMDocument;
use Exception;

use Module\goods\Controllers\Goods;
use Module\setting\Models\MembershipModel;


class GoodsDetail extends Goods
{
    protected $membershipModel;
    public function __construct()
    {
        parent::__construct();
        $this->membershipModel                      = new MembershipModel();
    }

    public function index( $goodsIdx )
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(54) === false)
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
            if ($this->menulib->isGrant(54) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        if( empty( $goodsIdx ) === true ){
            box_alert_target_close( '잘못된 접근입니다.' );
        }
        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();

        #------------------------------------------------------------------
        # TODO: 데이터 로드
        #------------------------------------------------------------------
        $aDatas                                     = $this->goodsModel->getGoodsDataByIdx( $goodsIdx );

        if( empty( $aDatas ) ){
            box_alert_target_close( '잘못된 접근입니다.' );
        }
        #------------------------------------------------------------------
        # TODO: 이미지 데이터 로드
        #------------------------------------------------------------------
        $aDatas['IMGS_INFO']                        = $this->goodsModel->getGoodsInImagesToOrigin( $goodsIdx );

        #------------------------------------------------------------------
        # TODO: 정보제공고시 로드
        #------------------------------------------------------------------
        $aDatas['REQ_INFO']                         = $this->goodsModel->getReqInfos( $goodsIdx );
        #------------------------------------------------------------------
        # TODO: 연관상품 데이터 로드
        #------------------------------------------------------------------
        $aDatas['RELATION_INFO']                    = [];
        if( _elm( $aDatas, 'G_RELATION_GOODS_FLAG' ) == 'Y' ){
            $relation_info                          = json_decode( _elm( $aDatas, 'G_RELATION_GOODS' ), true);
            if( empty( $relation_info ) === false ){
                foreach( $relation_info as $rKey => $rInfo ){
                    $aDatas['RELATION_INFO'][_elm( $rInfo, 'g_idx' )]['g_add_gbn'] = _elm( $rInfo, 'g_add_gbn' );
                }
            }
            foreach( $relation_info as $rKey => $rInfo ){
                $aDatas['RELATION_LISTS'][]         = $this->goodsModel->getGoodsDataByIdx( _elm( $rInfo, 'g_idx' ) );
            }
        }
        #------------------------------------------------------------------
        # TODO: 추가상품 데이터 로드
        #------------------------------------------------------------------
        $aDatas['ADD_GOODS_INFO']                   = [];
        if( _elm( $aDatas, 'G_ADD_GOODS_FLAG' ) == 'Y' ){
            $aDatas['ADD_GOODS_INFO']               = json_decode( _elm( $aDatas, 'G_ADD_GOODS' ), true);
            if( empty( $aDatas['ADD_GOODS_INFO'] ) === false ){
                foreach( $aDatas['ADD_GOODS_INFO'] as $aInfo ){
                    $aDatas['ADD_GOODS_LISTS'][]        = $this->goodsModel->getGoodsDataByIdx( $aInfo );
                }
            }

        }

        #------------------------------------------------------------------
        # TODO: 그룹상품 데이터 로드
        #------------------------------------------------------------------
        if( empty( _elm( $aDatas, 'G_GROUP' ) ) === false ){
            $aDatas['G_GROUP_INFO']                 = json_decode( _elm( $aDatas, 'G_GROUP' ), true);
            if( empty( $aDatas['G_GROUP_INFO'] ) === false ){
                foreach( $aDatas['G_GROUP_INFO'] as $gKey => $group ){
                    $aDatas['G_GROUP_LISTS'][]          = $this->goodsModel->getGoodsDataByIdx( _elm($group, 'g_idx') );
                }
            }

        }

        #------------------------------------------------------------------
        # TODO: 할인성정 데이터 로드
        #------------------------------------------------------------------
        if( _elm( $aDatas, 'G_SELL_POINT_FLAG' ) == 'Y' ){
            $aDatas['SEL_POINT_GROUP']              = $this->goodsModel->getGoodsDCGroup( $goodsIdx );
        }

        #------------------------------------------------------------------
        # TODO: 옵션 데이터 로드
        #------------------------------------------------------------------
        $aDatas['GOODS_OPTION_LISTS']               = [];
        if( _elm( $aDatas, 'G_OPTION_USE_FLAG' ) == 'Y' ){
            $aDatas['GOODS_OPTION_LISTS']           = $this->goodsModel->getGoodsOptions( $goodsIdx );
        }

        #------------------------------------------------------------------
        # TODO: 카테고리 데이터 로드
        #------------------------------------------------------------------
        $aDatas['GOODS_CATEGOTY_LISTS']             = [];
        if( empty( _elm( $aDatas, 'G_CATEGORYS' ) ) === false ){
            $cates                                   = json_decode( _elm( $aDatas, 'G_CATEGORYS' ) ,true );
            if( empty( $cates ) === false ){
                foreach( $cates as $cKey => $cate ){
                    $aDatas['GOODS_CATEGOTY_LISTS'][$cKey]['C_IDX'] = _elm( $cate, 'C_IDX' );
                    $aDatas['GOODS_CATEGOTY_LISTS'][$cKey]['C_FULL_NAME'] = _elm( $this->categoryModel->getCateTopNameJoin(_elm( $cate, 'C_IDX' )), 'FullCategoryName' ) ;
                    $aDatas['GOODS_CATEGOTY_LISTS'][$cKey]['IS_MAIN'] = _elm( $aDatas, 'G_CATEGORY_MAIN_IDX' ) == _elm( $cate, 'C_IDX' ) ? 'Y': 'N';
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 야이콘 데이터 로드
        #------------------------------------------------------------------
        $aDatas['ICONS_LISTS']                      = $this->iconsModel->getGoodsInIcons( $goodsIdx );

        $_iconData                                  = $this->iconsModel->getIconsLists();

        $iconData                                   = [];

        if( !empty( _elm( $_iconData, 'lists') ) ){
            foreach( _elm( $_iconData, 'lists') as $key => $icon ){
                if( _elm( $icon, 'I_GBN' ) == 'L'  ){
                    $iconData['L'][]                = $icon;
                }else{
                    $iconData['P'][]                = $icon;
                }
            }
        }
        $pageDatas['iconsData']                      = $iconData;

        $pageDatas['aDatas']                        = $aDatas;
        $pageDatas['getData']                       = $requests;
        $pageDatas['owensView']                     = $this->owensView;
        $_aMemberGrade                              = $this->membershipModel->getMembershipGrade();
        $aMemberGrade                               = [];

        if( empty($_aMemberGrade) === false ){
            foreach( $_aMemberGrade as $val){
                if (isset($val['G_IDX'])) {
                    $aMemberGrade[_elm($val, 'G_IDX')] = _elm($val, 'G_NAME');
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------
        $pageDatas['aMemberGrade']                  = $aMemberGrade;

        $pageDatas['aColorConfig']                  = $this->sharedConfig::$goodsColor;
        $pageDatas['aDefaultTxtConfig']             = $this->sharedConfig::$goodsDefaultInfoText;
        $pageDatas['aGoodsCondition']               = $this->sharedConfig::$goodsCondition;
        $pageDatas['aGoodsProductType']             = $this->sharedConfig::$goodsProductType;
        $pageDatas['aGoodsSellType']                = $this->sharedConfig::$goodsSellType;

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\goods\Views\goods\detail';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }
    public function index2( $goodsIdx )
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(54) === false)
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
            if ($this->menulib->isGrant(54) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        if( empty( $goodsIdx ) === true ){
            box_alert_target_close( '잘못된 접근입니다.' );
        }
        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();

        #------------------------------------------------------------------
        # TODO: 데이터 로드
        #------------------------------------------------------------------
        $aDatas                                     = $this->goodsModel->getGoodsDataByIdx( $goodsIdx );

        if( empty( $aDatas ) ){
            box_alert_target_close( '잘못된 접근입니다.' );
        }
        #------------------------------------------------------------------
        # TODO: 이미지 데이터 로드
        #------------------------------------------------------------------
        $aDatas['IMGS_INFO']                        = $this->goodsModel->getGoodsInImagesToOrigin( $goodsIdx );

        #------------------------------------------------------------------
        # TODO: 정보제공고시 로드
        #------------------------------------------------------------------
        $aDatas['REQ_INFO']                         = $this->goodsModel->getReqInfos( $goodsIdx );
        #------------------------------------------------------------------
        # TODO: 연관상품 데이터 로드
        #------------------------------------------------------------------
        $aDatas['RELATION_INFO']                    = [];
        if( _elm( $aDatas, 'G_RELATION_GOODS_FLAG' ) == 'Y' ){
            $relation_info                          = json_decode( _elm( $aDatas, 'G_RELATION_GOODS' ), true);
            if( empty( $relation_info ) === false ){
                foreach( $relation_info as $rKey => $rInfo ){
                    $aDatas['RELATION_INFO'][_elm( $rInfo, 'g_idx' )]['g_add_gbn'] = _elm( $rInfo, 'g_add_gbn' );
                }
            }
            foreach( $relation_info as $rKey => $rInfo ){
                $aDatas['RELATION_LISTS'][]         = $this->goodsModel->getGoodsDataByIdx( _elm( $rInfo, 'g_idx' ) );
            }
        }
        #------------------------------------------------------------------
        # TODO: 추가상품 데이터 로드
        #------------------------------------------------------------------
        $aDatas['ADD_GOODS_INFO']                   = [];
        if( _elm( $aDatas, 'G_ADD_GOODS_FLAG' ) == 'Y' ){
            $aDatas['ADD_GOODS_INFO']               = json_decode( _elm( $aDatas, 'G_ADD_GOODS' ), true);
            if( empty( $aDatas['ADD_GOODS_INFO'] ) === false ){
                foreach( $aDatas['ADD_GOODS_INFO'] as $aInfo ){
                    $aDatas['ADD_GOODS_LISTS'][]        = $this->goodsModel->getGoodsDataByIdx( $aInfo );
                }
            }

        }

        #------------------------------------------------------------------
        # TODO: 그룹상품 데이터 로드
        #------------------------------------------------------------------
        if( empty( _elm( $aDatas, 'G_GROUP' ) ) === false ){
            $aDatas['G_GROUP_INFO']                 = json_decode( _elm( $aDatas, 'G_GROUP' ), true);
            if( empty( $aDatas['G_GROUP_INFO'] ) === false ){
                foreach( $aDatas['G_GROUP_INFO'] as $gKey => $group ){
                    $aDatas['G_GROUP_LISTS'][]          = $this->goodsModel->getGoodsDataByIdx( _elm($group, 'g_idx') );
                }
            }

        }

        #------------------------------------------------------------------
        # TODO: 할인성정 데이터 로드
        #------------------------------------------------------------------
        if( _elm( $aDatas, 'G_SELL_POINT_FLAG' ) == 'Y' ){
            $aDatas['SEL_POINT_GROUP']              = $this->goodsModel->getGoodsDCGroup( $goodsIdx );
        }

        #------------------------------------------------------------------
        # TODO: 옵션 데이터 로드
        #------------------------------------------------------------------
        $aDatas['GOODS_OPTION_LISTS']               = [];
        if( _elm( $aDatas, 'G_OPTION_USE_FLAG' ) == 'Y' ){
            $aDatas['GOODS_OPTION_LISTS']           = $this->goodsModel->getGoodsOptions( $goodsIdx );
        }

        #------------------------------------------------------------------
        # TODO: 카테고리 데이터 로드
        #------------------------------------------------------------------
        $aDatas['GOODS_CATEGOTY_LISTS']             = [];
        if( empty( _elm( $aDatas, 'G_CATEGORYS' ) ) === false ){
            $cates                                   = json_decode( _elm( $aDatas, 'G_CATEGORYS' ) ,true );
            if( empty( $cates ) === false ){
                foreach( $cates as $cKey => $cate ){
                    $aDatas['GOODS_CATEGOTY_LISTS'][$cKey]['C_IDX'] = _elm( $cate, 'C_IDX' );
                    $aDatas['GOODS_CATEGOTY_LISTS'][$cKey]['C_FULL_NAME'] = _elm( $this->categoryModel->getCateTopNameJoin(_elm( $cate, 'C_IDX' )), 'FullCategoryName' ) ;
                    $aDatas['GOODS_CATEGOTY_LISTS'][$cKey]['IS_MAIN'] = _elm( $aDatas, 'G_CATEGORY_MAIN_IDX' ) == _elm( $cate, 'C_IDX' ) ? 'Y': 'N';
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 야이콘 데이터 로드
        #------------------------------------------------------------------
        $aDatas['ICONS_LISTS']                      = $this->iconsModel->getGoodsInIcons( $goodsIdx );

        $_iconData                                  = $this->iconsModel->getIconsLists();

        $iconData                                   = [];

        if( !empty( _elm( $_iconData, 'lists') ) ){
            foreach( _elm( $_iconData, 'lists') as $key => $icon ){
                if( _elm( $icon, 'I_GBN' ) == 'L'  ){
                    $iconData['L'][]                = $icon;
                }else{
                    $iconData['P'][]                = $icon;
                }
            }
        }
        $pageDatas['iconsData']                      = $iconData;

        $pageDatas['aDatas']                        = $aDatas;
        $pageDatas['getData']                       = $requests;
        $pageDatas['owensView']                     = $this->owensView;
        $_aMemberGrade                              = $this->membershipModel->getMembershipGrade();
        $aMemberGrade                               = [];

        if( empty($_aMemberGrade) === false ){
            foreach( $_aMemberGrade as $val){
                if (isset($val['G_IDX'])) {
                    $aMemberGrade[_elm($val, 'G_IDX')] = _elm($val, 'G_NAME');
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------
        $pageDatas['aMemberGrade']                  = $aMemberGrade;

        $pageDatas['aColorConfig']                  = $this->sharedConfig::$goodsColor;
        $pageDatas['aDefaultTxtConfig']             = $this->sharedConfig::$goodsDefaultInfoText;
        $pageDatas['aGoodsCondition']               = $this->sharedConfig::$goodsCondition;
        $pageDatas['aGoodsProductType']             = $this->sharedConfig::$goodsProductType;
        $pageDatas['aGoodsSellType']                = $this->sharedConfig::$goodsSellType;

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\goods\Views\goods\detail2';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }
}