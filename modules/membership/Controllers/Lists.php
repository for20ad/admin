<?php
#------------------------------------------------------------------
# Lists.php
# 회원 리스트
# 김우진
# 2024-07-15 17:32:24
# @Desc :
#------------------------------------------------------------------
namespace Module\membership\Controllers;

use Module\membership\Controllers\Membership;
use Module\setting\Models\ExcelFormModel;

class Lists extends Membership
{
    protected $excelFormModel;
    public function __construct()
    {
        parent::__construct();
        $this->excelFormModel                       = new ExcelFormModel();
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
            if ($this->menulib->isGrant(3) === false)
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
            if ($this->menulib->isGrant(3) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }
        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();

        $_member_grade                              = $this->membershipModel->getMembershipGrade();

        $member_grade                               = [];
        if( empty( $_member_grade ) === false ){
            foreach ($_member_grade as $item) {
                $member_grade[$item['G_IDX']]       = $item['G_NAME'];
            }
        }


        $pageDatas['aConfig']                       = $this->aConfig->member;
        $pageDatas['getData']                       = $requests;
        $pageDatas['member_grade']                  = $member_grade;
        $pageDatas['wait_members']                  = $this->membershipModel->getWaitMembershipMembers();

        #------------------------------------------------------------------
        # TODO: 폼 데이터 로드
        #------------------------------------------------------------------
        $formParam                                  = [];
        $formParam['F_MENU']                        = 'membership';
        $pageDatas['form_lists']                    = $this->excelFormModel->getFormsDataByMenu( $formParam );
        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\membership\Views\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);
    }
}