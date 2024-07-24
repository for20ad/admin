<?php
namespace Module\goods\Config;

$routes->group('goods', ['namespace' => 'Module\goods\Controllers'], static function($routes)
{
    $routes->get( 'goodsIcons' , 'Icons::lists' );
    $routes->get( 'mileage' , 'Mileage::lists' );
});

$routes->group('apis/goods', ['namespace' => 'Module\goods\Controllers\apis'], static function($routes)
{
    $routes->post( 'getIconsLists' , 'IconsApi::getIconsLists' );
    $routes->post( 'iconRegister' , 'IconsApi::iconRegister' );
    $routes->post( 'iconDetail' , 'IconsApi::iconDetail' );
    $routes->post( 'iconRegisterProc' , 'IconsApi::iconRegisterProc' );
    $routes->post( 'iconDetailProc' , 'IconsApi::iconDetailProc' );
    $routes->post( 'deleteIcons' , 'IconsApi::deleteIcons' );




});