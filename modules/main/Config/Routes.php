<?php
namespace Module\main\Config;

$routes->group('/main', ['namespace' => 'Module\main\Controllers'], static function($routes)
{
    $routes->get( '/',                               'Main::index' );
});
$routes->group('/', ['namespace' => 'Module\main\Controllers'], static function($routes)
{
    $routes->get( '',                               'Main::index' );
});

