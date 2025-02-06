<?php
namespace Module\test\Config;

$routes->group('dahae', ['namespace' => 'Module\dahae\Controllers'], static function($routes)
{
    // 강의목록과 강의 데이터, 모든 정보 가져오기
    $routes->get( 'getProductHeaders/(:any)' , 'Dahae::getProductHeaders/$1' );
    $routes->get( 'setGoodsPrid/(:num)' , 'Dahae::setGoodsPrid/$1' );
    $routes->get( 'setGoodsImages/(:num)' , 'Dahae::setGoodsImages/$1' );
    $routes->get( 'moveTmpTosamiTmp' , 'Dahae::moveTmpTosamiTmp' );


    $routes->get( 'contentSaveGodo' , 'Dahae::contentSaveGodo' );

    $routes->get( 'truncateTmp' , 'Dahae::truncateTmp' );



});

