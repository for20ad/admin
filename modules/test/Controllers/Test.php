<?php
namespace Module\test\Controllers;
use Module\core\Controllers\CoreController;
use Module\test\Models\TestModel;
use Config\Services;
use DOMDocument;


use Exception;

class Test extends CoreController
{

    public function __construct()
    {

        parent::__construct();
    }

    public function index()
    {
        $url = "https://sansuyuram.com/board/view.php?&bdId=TNBreview&sno=697";

        $aResult = $this->makeShortUrl($url);
        echo $aResult;
    }

    public function pageOne()
    {
        $pageDatas = [];

        // ---------------------------------------------------------------------
        // 메인뷰 처리
        // ---------------------------------------------------------------------
        $pageParam               = [];
        $pageParam['file']       = '\Module\test\Views\one';
        $pageParam['pageLayout'] = 'none';
        $pageParam['pageDatas']  = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }

    public function pageTwo()
    {
        $pageDatas = [];

        // ---------------------------------------------------------------------
        // 메인뷰 처리
        // ---------------------------------------------------------------------
        $pageParam               = [];
        $pageParam['file']       = '\Module\test\Views\two';
        $pageParam['pageLayout'] = 'none';
        $pageParam['pageDatas']  = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }

    public function pageThree()
    {
        $pageDatas = [];

        // ---------------------------------------------------------------------
        // 메인뷰 처리
        // ---------------------------------------------------------------------
        $pageParam               = [];
        $pageParam['file']       = '\Module\test\Views\three';
        $pageParam['pageLayout'] = 'none';
        $pageParam['pageDatas']  = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }

    public function pagefour()
    {
        $pageDatas = [];
        $this->response->setHeader('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';");
        // ---------------------------------------------------------------------
        // 메인뷰 처리
        // ---------------------------------------------------------------------

        $pageParam               = [];
        $pageParam['file']       = '\Module\test\Views\four';
        $pageParam['pageLayout'] = '';
        $pageParam['pageDatas']  = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }

}
