<?php
namespace Module\comm\test\Config;

$routes->group('comm/test', ['namespace' => 'Module\comm\test\Controllers'], static function($routes)
{
    // 강의목록과 강의 데이터, 모든 정보 가져오기
    $routes->get( '/' , 'Test::index' );
    $routes->get( 'exchangeShinhan' , 'Test::exchangeShinhan' );

});

