<?php
namespace Module\login\Config;


$routes->group('login', ['namespace' => 'Module\login\Controllers'], function($routes)
{
    $routes->get('/', 'Login::index');
    $routes->get('logOut', 'Login::logOut');
});


$routes->group('api/login', ['namespace' => 'Module\login\Controllers\apis'], function($routes)
{
    $routes->post('loginAuth', 'LoginApi::loginAuth');
    $routes->post('loginProc', 'LoginApi::loginProc');
});