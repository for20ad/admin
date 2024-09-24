<?php
namespace Module\test\Config;

$routes->group('dahae', ['namespace' => 'Module\dahae\Controllers'], static function($routes)
{
    // 강의목록과 강의 데이터, 모든 정보 가져오기
    $routes->get( 'getProductHeaders' , 'Dahae::getProductHeaders' );

});

