<?php
namespace Module\core\Controllers;

use Module\core\Controllers\CoreController;

class Errors extends CoreController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show404()
    {
        $pageParam               = [];
        $pageParam['file']       = '\Module\core\Views\errors\error_404';
        $pageParam['pageLayout'] = 'blank';

        $this->owensView->loadLayoutView($pageParam);
    }
}
