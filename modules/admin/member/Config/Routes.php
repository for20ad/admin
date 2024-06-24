<?php
namespace Module\admin\member\Config;

$routes->group('admin/member', ['namespace' => 'Module\admin\member\Controllers'], static function($routes)
{
    //회원가입
    $routes->get( 'step1', 'Member::registStepOne' );

    //아이디 찾가
    $routes->post( 'findUserId', 'Member::findUserId' );

    // $routes->put(  );
    // $routes->delete( );




});

