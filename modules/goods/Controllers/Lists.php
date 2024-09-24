<?php
#------------------------------------------------------------------
# Icons.php
# 상품 아이콘 관리
# 김우진
# 2024-07-24 11:11:43
# @Desc :
#------------------------------------------------------------------
namespace Module\goods\Controllers;

use Config\Services;
use DOMDocument;
use Exception;

use Module\goods\Controllers\Goods;


class Lists extends Goods
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

    public function lists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(48) === false)
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
            if ($this->menulib->isGrant(48) === false)
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

        $_cateTopList                               = $this->categoryModel->getTopLists();
        $cateTopList                                = [];
        if( !empty( $_cateTopList ) ){
            foreach( $_cateTopList as $key => $cate ){
                $cateTopList[_elm( $cate, 'C_IDX' )]= _elm( $cate, 'C_CATE_NAME' );
            }
        }
        $pageDatas['cateTopList']                   = $cateTopList;
        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\goods\Views\goods\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);
    }


}