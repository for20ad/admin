<?php
namespace Module\login\Config;


$routes->group('login', ['namespace' => 'Module\login\Controllers'], function($routes)
{
    $routes->get('/', 'Login::index');
});
$routes->group('logOut', ['namespace' => 'Module\login\Controllers'], function($routes)
{
    $routes->get('/', 'Login::logOut');
});

$routes->group('api/login', ['namespace' => 'Module\login\Controllers\apis'], function($routes)
{
    $routes->post('reSendAuthNum', 'LoginApi::reSendAuthNum');
    $routes->post('loginAuth', 'LoginApi::loginAuth');
    $routes->post('loginProc', 'LoginApi::loginProc');
    $routes->get('checkLogin', 'LoginApi::checkLogin');
    $routes->get('delayLogin', 'LoginApi::delayLogin');


});