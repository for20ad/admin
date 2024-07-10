<?php

#------------------------------------------------------------------
# Member.php
# 관리자 회원 컨트롤러
# 김우진
# 2024-06-27 11:27:30
# @Desc :
#------------------------------------------------------------------
namespace Module\setting\Controllers;

use Module\setting\Controllers\Setting;
use Module\setting\Config\Config as settingConfig;
use Module\setting\Models\MemberModel;
use Module\setting\Models\MenuModel;



use Exception;

class Member extends Setting
{

    public function __construct()
    {

        parent::__construct();

    }

    public function index()
    {
        $pageParam               = [];
        $pageParam['file']       = '\Module\core\Views\errors\error_404';
        $pageParam['pageLayout'] = 'blank';

        $this->owensView->loadLayoutView($pageParam);
    }

    public function lists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(20) === false)
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
            if ($this->menulib->isGrant(20) === false)
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
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $memberModel                                = new MemberModel();

        #------------------------------------------------------------------
        # TODO: parameter 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['startDate']                    = _elm( $requests, 'start_date' );
        $modelParam['endDate']                      = _elm( $requests, 'end_date' );

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------
        $aConfig                                    = new settingConfig();

        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $pageDatas['member_group']                  = $memberModel->getMemberGroup();
        $pageDatas['aConfig']                       = $aConfig->member;
        $pageDatas['getData']                       = $requests;

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\setting\Views\member\admin_lists';
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
            if ($this->menulib->isGrant(21) === false)
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
            if ($this->menulib->isGrant(21) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }
        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------
        $pageDatas                                  = [];
        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $memberModel                                = new MemberModel();

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------
        $aConfig                                    = new settingConfig();

        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------

        $pageDatas['member_group']                  = $memberModel->getMemberGroup();
        $pageDatas['aConfig']                       = $aConfig->member;
        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\setting\Views\member\register';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);
    }

    public function detail( $memIdx = 0 )
    {
        if( empty( $memIdx ) === true ){
            $pageParam                              = [];
            $pageParam['file']                      = '\Module\core\Views\errors\error_404';
            $pageParam['pageLayout']                = 'blank';

            $this->owensView->loadLayoutView($pageParam);
            exit;
        }
        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------
        $pageDatas                                  = [];
        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $memberModel                                = new MemberModel();

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------
        $aConfig                                    = new settingConfig();

        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------

        $pageDatas['member_group']                  = $memberModel->getMemberGroup();
        $pageDatas['aConfig']                       = $aConfig->member;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];

        $modelParam['MB_IDX']                       = $memIdx;
        $pageDatas['aData']                         = $memberModel->getAdminMemberData( $modelParam );

        if( empty( _elm( $pageDatas, 'aData') ) === true ){
            $pageParam                              = [];
            $pageParam['file']                      = '\Module\core\Views\errors\error_404';
            $pageParam['pageLayout']                = 'blank';

            $this->owensView->loadLayoutView($pageParam);
            exit;
        }
        $pageDatas['aData']['MB_MOBILE_NUM_DEC']    = _add_dash_tel_num( $this->_aesDecrypt( _elm(  _elm($pageDatas, 'aData' ), 'MB_MOBILE_NUM' ) ) );
        $pageDatas['aData']['MB_EMAIL_DEC']         = $this->_aesDecrypt( _elm(  _elm($pageDatas, 'aData' ), 'MB_EMAIL' ) );

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\setting\Views\member\detail';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }

}
