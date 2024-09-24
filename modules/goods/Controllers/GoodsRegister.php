<?php
#------------------------------------------------------------------
# GoodsRegister.php
# 상품등록 컨트롤러
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


class GoodsRegister extends Goods
{
    protected $membershipModel;
    public function __construct()
    {
        parent::__construct();
        $this->membershipModel                      = new MembershipModel();
    }

    public function index()
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
        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $pageDatas['aDatas']                        = $this->iconsModel->getIconsLists();

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
        $pageParam['file']                          = '\Module\goods\Views\goods\register';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }
}