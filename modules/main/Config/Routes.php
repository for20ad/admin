<?php
namespace Module\main\Config;

$routes->group('/main', ['namespace' => 'Module\main\Controllers'], static function($routes)
{
    // 강의목록과 강의 데이터, 모든 정보 가져오기
    $routes->get( '' , 'Main::index' );

});

