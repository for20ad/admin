<?php
#------------------------------------------------------------------
# Menu.php
# 관리자 메뉴관리 컨트롤러
# 김우진
# 2024-06-27 11:28:15
# @Desc :
#------------------------------------------------------------------

namespace Module\setting\Controllers;

use Module\setting\Controllers\Setting;
use Module\setting\Models\MemberModel;
use Module\setting\Models\MenuModel;


class Menu extends Setting
{

    public function __construct()
    {
        parent::__construct();

        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(2) === false)
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
            if ($this->menulib->isGrant(2) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }
    }
    public function index()
    {
        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------
        $pageDatas                                 = [];
        $menu                                      = [];
        $pageDatas['menu_tree_lists']              = [];

        #------------------------------------------------------------------
        # TODO: request 데이터 세팅
        #------------------------------------------------------------------
        $requests                                  = $this->request->getPost();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $menuModel                                 = new MenuModel();
        $memberModel                               = new MemberModel();

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------


        $pageDatas['config']                       = $this->mConfig->menu;

        $menu_lists                                = $menuModel->getMenuLists();

        if( empty( $menu_lists ) === false  ){
            #------------------------------------------------------------------
            # TODO: 트리형식으로 리스트 변경
            #------------------------------------------------------------------
            $pageDatas['menu_tree_lists']          = _build_tree($menu_lists);

            foreach (_elm($pageDatas, 'menu_tree_lists', []) as $kIDX => $vMENU)
            {
                $menu[_elm($vMENU, 'MENU_IDX')]    = _elm($vMENU, 'MENU_NAME');

                if (empty($vMENU['CHILD']) === false)
                {
                    foreach (_elm($vMENU, 'CHILD', []) as $kIDX_CHILD => $vMENU_CHILD)
                    {
                        $menu[_elm($vMENU_CHILD, 'MENU_IDX')] = '   >>>' ._elm($vMENU_CHILD, 'MENU_NAME');
                    }
                }
            }
        }
        #------------------------------------------------------------------
        # TODO: 메뉴 트리 적용
        #------------------------------------------------------------------
        $pageDatas['menu']                         = $menu;

        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $pageDatas['member_group']                 = $memberModel->getMemberGroup();

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                 = [];
        $pageParam['file']                         = '\Module\setting\Views\menu\menu';
        $pageParam['pageLayout']                   = '';
        $pageParam['pageDatas']                    = $pageDatas;

        //$pageParam['owensView']                    = $this->owensView;



        $this->owensView->loadLayoutView($pageParam);
    }
}