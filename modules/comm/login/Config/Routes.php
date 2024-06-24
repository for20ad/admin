<?php
#------------------------------------------------------------------
# Routes.php
# 로그인 그룹 라우터
# 김우진
# 2024-05-13 09:03:02
# @Desc : 로그인 그룹 라우터
#------------------------------------------------------------------
namespace Module\comm\login\Config;

$routes->group('/comm/login', ['namespace' => 'Module\comm\login\Controllers'], static function($routes)
{
    // 로그인
    $routes->post( '/',                'Signin::index' );

    //토큰강제발급(테스트용)
    $routes->post( 'forceToken',       'Signin::tokenForceIssue'  );

    //토큰 검증
    $routes->get( 'test',              'Signin::tokenValidation');

    //sns login
    $routes->post( 'snsLogin',         'Signin::snsRlogin'  );

    // regen token
    $routes->post( 'regenToken',      'Signin::refreshAuthToken' );

    //로그아웃
    $routes->post( 'signout',         'Signin::signout' );

});

