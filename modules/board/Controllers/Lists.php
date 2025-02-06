<?php
namespace Module\board\Controllers;

use Module\board\Controllers\Boards;

class Lists extends Boards
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(74) === false)
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
            if ($this->menulib->isGrant(74) === false)
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
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\board\Views\board\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }

    public function noticeLists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(75) === false)
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
            if ($this->menulib->isGrant(75) === false)
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
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\board\Views\notice\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }

    public function faqLists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(76) === false)
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
            if ($this->menulib->isGrant(76) === false)
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
        # TODO: 카테고리
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['C_PARENT_IDX']                 = '129';
        $_cateLists                                 = $this->boardModel->getFaqCode( $modelParam );
        $cateLists                                  = [];
        if( empty( $_cateLists ) === false ){
            foreach( $_cateLists as $cKey => $lists ){
                $cateLists[_elm( $lists, 'C_CODE' )]= _elm( $lists, 'C_NAME' );
            }
        }
        $pageDatas['aCate']                         = $cateLists;
        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\board\Views\faq\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;



        $this->owensView->loadLayoutView($pageParam);
    }


    public function postsLists($type)
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(74) === false)
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
            if ($this->menulib->isGrant(74) === false)
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
        # TODO: 게시판 정보 가져오기
        #------------------------------------------------------------------
        $boardInfo                                  = $this->boardModel->getBoardDataById( $type );
        if( empty( $boardInfo ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '게시판이 존재하지 않습니다.';

            return $this->respond( $response );
        }

        $pageDatas['boardInfo']                     = $boardInfo;
        $pageDatas['getParam']                      = $requests;


        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\board\Views\posts\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;



        $this->owensView->loadLayoutView($pageParam);
    }
}