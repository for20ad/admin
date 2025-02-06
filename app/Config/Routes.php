<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();


$routes->setAutoRoute(true);
$routes->get('', 'Main::index');


if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

// -----------------------------------------------------------------------------
// module routes 가져오기
// -----------------------------------------------------------------------------
$module_path     = ROOTPATH . 'modules/';
$module_scan_dir = new \RecursiveDirectoryIterator($module_path, \FilesystemIterator::SKIP_DOTS);
$iterator        = new \RecursiveIteratorIterator($module_scan_dir, \RecursiveIteratorIterator::SELF_FIRST);

$iterator->setMaxDepth(1);

foreach ($iterator as $fileinfo)
{
    if ($fileinfo->isDir() === true)
    {
        $routes_file = $fileinfo->getPathname() . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'Routes.php';

        if (file_exists($routes_file) === true)
        {
            require $routes_file;
        }
    }
}

$routes->cli('start-websocket', 'WebSocketController::startServer');

