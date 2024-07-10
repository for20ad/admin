<?php
#------------------------------------------------------------------
# Membership.php
# 회원관리
# 김우진
# 2024-07-09 13:18:01
# @Desc :
#------------------------------------------------------------------

namespace Module\setting\Controllers;

use Module\setting\Controllers\Setting;
use Module\setting\Config\Config as settingConfig;
use Module\setting\Models\MembershipModel;

class Membership extends Setting
{
    public function __construct()
    {

        parent::__construct();
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(27) === false)
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
            if ($this->menulib->isGrant(27) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }
    }

    public function index()
    {
        $pageParam               = [];
        $pageParam['file']       = '\Module\core\Views\errors\error_404';
        $pageParam['pageLayout'] = 'blank';

        $this->owensView->loadLayoutView($pageParam);
    }

    public function grade()
    {
        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------
        $pageDatas                                 = [];
        $requests                                  = $this->request->getGet();
        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $membershipModel                           = new MembershipModel();

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------
        $aConfig                                   = new settingConfig();
        $pageDatas['aConfig']                      = $aConfig->policy['membership'];

        #------------------------------------------------------------------
        # TODO: 정책관리 데이터 로드
        #------------------------------------------------------------------
        $pageDatas['gradeDatas']              = $membershipModel->getMembershipGrade();

         #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                 = [];
        $pageParam['file']                         = '\Module\setting\Views\membership\grade';
        $pageParam['pageLayout']                   = '';
        $pageParam['pageDatas']                    = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);

    }


}