<?php
namespace Module\login\Controllers;
use Module\core\Controllers\CoreController;
use Module\login\Models\LoginModel;
use Config\Services;
use DOMDocument;


use Exception;

class Login extends CoreController
{

    public function __construct()
    {

        parent::__construct();
    }

    public function index()
    {

        $pageDatas                                  = [];

        // ---------------------------------------------------------------------
        // 메인뷰 처리
        // ---------------------------------------------------------------------
        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\login\Views\login';
        $pageParam['pageLayout']                    = 'login';
        $pageParam['pageDatas']                     = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }

    public function logOut()
    {
        // ---------------------------------------------------------------------
        // 세션들 삭제
        // ---------------------------------------------------------------------
        $this->session->destroy();

        // ---------------------------------------------------------------------
        // 로그아웃 후 이동
        // ---------------------------------------------------------------------
        return $this->response->redirect('/login');
    }
}
