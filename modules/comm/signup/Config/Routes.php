<?php
namespace Module\comm\signup\Config;

//아이디 찾기
$routes->get( 'comm/dupUserId', '\Module\comm\signup\Controllers\Signup::dupUserId' );


$routes->group('comm/signup', ['namespace' => 'Module\comm\signup\Controllers'], static function($routes)
{
    // 회원가입
    $routes->post( '/' , 'Signup::index' );

});

