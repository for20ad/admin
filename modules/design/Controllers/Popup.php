<?php
#------------------------------------------------------------------
# Popup.php
# 팝업관리
# 김우진
# 2024-08-06 09:56:29
# @Desc :
#------------------------------------------------------------------
namespace Module\design\Controllers;

use Config\Services;
use DOMDocument;
use Exception;
use Module\design\Controllers\Design;

class Popup extends Design
{
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
            if ($this->menulib->isGrant(53) === false)
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
            if ($this->menulib->isGrant(53) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();
        $popupConfig                                = $this->sharedConfig::$popup;
        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['order']                        = 'C_SORT ASC';
        $pageDatas['aDatas']                        = $this->logoModel->getLogoData( $modelParam );
        $pageDatas['aConfig']                       = $popupConfig;

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\design\Views\popup\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);

    }
}