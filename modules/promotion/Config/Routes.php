<?php
namespace Module\promotion\Config;

$routes->group('promotion', ['namespace' => 'Module\promotion\Controllers'], static function($routes)
{
    // 강의목록과 강의 데이터, 모든 정보 가져오기
    $routes->get( 'one' , 'Test::pageOne' );
    $routes->get( 'two' , 'Test::pageTwo' );
    $routes->get( 'three' , 'Test::pageThree' );
    $routes->get( 'four' , 'Test::pagefour' );

});

$routes->group('apis/promotion', ['namespace' => 'Module\promotion\Controllers\apis'], static function($routes)
{

});