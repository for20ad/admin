<?php
#------------------------------------------------------------------
# Code.php
# 코드관리
# 김우진
# 2024-07-23 09:31:36
# @Desc :
#------------------------------------------------------------------
namespace Module\setting\Controllers;

use Module\setting\Controllers\Setting;
use Module\setting\Config\Config as settingConfig;
use Module\setting\Models\CodeModel;

class Code extends Setting
{
    protected $codeModel;
    public function __construct()
    {

        parent::__construct();
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(45) === false)
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
            if ($this->menulib->isGrant(45) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }
        $this->codeModel                            = new CodeModel();
    }

    public function index()
    {

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\core\Views\errors\error_404';
        $pageParam['pageLayout']                    = 'blank';

        $this->owensView->loadLayoutView($pageParam);
    }

    public function getCodeLists()
    {
        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------
        $pageDatas                                  = [];
        $code                                       = [];
        $pageDatas['code_tree_lists']               = [];

        #------------------------------------------------------------------
        # TODO: request 데이터 세팅
        #------------------------------------------------------------------
        $requests                                   = $this->request->getGet();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------


        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------


        $pageDatas['config']                        = $this->mConfig->menu;

        $code_lists                                 = $this->codeModel->getCodeLists();

        #------------------------------------------------------------------
        # TODO: 메뉴 트리 적용
        #------------------------------------------------------------------
        if( empty( $code_lists ) === false  ){
            #------------------------------------------------------------------
            # TODO: 트리형식으로 리스트 변경
            #------------------------------------------------------------------

            $pageDatas['code_tree_lists']           = _build_tree( $code_lists, _elm($code_lists, 'C_PARENT_IDX', 0 , true), 'C_IDX', 'C_PARENT_IDX'  );

            foreach (_elm($pageDatas, 'code_tree_lists', []) as $kIDX => $vCODE)
            {
                $code[_elm($vCODE, 'C_IDX')]     = _elm($vCODE, 'C_NAME');

                if (empty($vCODE['CHILD']) === false)
                {
                    foreach (_elm($vCODE, 'CHILD', []) as $kIDX_CHILD => $vCODE_CHILD)
                    {
                        $code[_elm($vCODE_CHILD, 'C_IDX')] = '   >>>' ._elm($vCODE_CHILD, 'C_NAME');
                    }
                }
            }
        }

        $pageDatas['code']                          = $code;

        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------


        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\setting\Views\code\code';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;

        //$pageParam['owensView']                    = $this->owensView;



        $this->owensView->loadLayoutView($pageParam);

    }


}