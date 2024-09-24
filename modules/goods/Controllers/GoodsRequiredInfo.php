<?php
#------------------------------------------------------------------
# GoodsRequiredInfo.php
# 상품필수정보관리
# 김우진
# 2024-08-12 15:34:54
# @Desc :
#------------------------------------------------------------------
namespace Module\goods\Controllers;

use Config\Services;
use DOMDocument;
use Exception;

use Module\goods\Controllers\Goods;
use Module\setting\Models\MembershipModel;
class goodsRequiredInfo extends Goods
{
    protected $membershipModel;
    public function __construct()
    {
        parent::__construct();
        $this->membershipModel                      = new MembershipModel();
    }

    public function lists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(55) === false)
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
            if ($this->menulib->isGrant(55) === false)
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
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $pageDatas['aDatas']                        = $this->iconsModel->getIconsLists();

        $pageDatas['getData']                       = $requests;
        $pageDatas['owensView']                     = $this->owensView;

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------
        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\goods\Views\requiredInfo\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }
}