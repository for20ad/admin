<?php
namespace Module\musinsa\Config;

$routes->group('musinsa', ['namespace' => 'Module\musinsa\Controllers'], static function($routes)
{
    // 강의목록과 강의 데이터, 모든 정보 가져오기
    $routes->get( '/' , 'Musinsa::index' );
    $routes->get( 'exchangeShinhan' , 'Test::exchangeShinhan' );

});