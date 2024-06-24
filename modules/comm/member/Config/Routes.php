<?php
namespace Module\comm\member\Config;

$routes->group('comm/member', ['namespace' => 'Module\comm\member\Controllers'], static function($routes)
{
    //회원가입
    $routes->get( 'step1', 'Member::registStepOne' );

    //아이디 찾가
    $routes->post( 'findUserId', 'Member::findUserId' );





});

