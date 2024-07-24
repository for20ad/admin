<?php
namespace Module\test\Config;

$routes->group('test', ['namespace' => 'Module\test\Controllers'], static function($routes)
{
    $routes->get( '/' , 'Test::index' );
    $routes->get( 'exchangeShinhan' , 'Test::exchangeShinhan' );

});

