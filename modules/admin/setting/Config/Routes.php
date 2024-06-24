<?php
namespace Module\admin\setting\Config;

$routes->group('admin/setting', ['namespace' => 'Module\admin\setting\Controllers'], static function($routes)
{
    // 메뉴리스트
    $routes->get( 'menu' , 'Menu::menuLists' );
    // 메뉴 등록
    $routes->post( 'menu/add', 'Menu::addMenu' );
    // 메뉴 수정
    $routes->put( 'menu/modify', "Menu::modifyMenu" );
    // 메뉴 삭제
    $routes->delete('menu', 'Menu::deleteMenu');


    //코드 리스트
    $routes->get( 'code' , 'Code::codeLists' );
    //코드 등록
    $routes->post( 'code/add', 'Code::addCode' );
    //코드 수정
    $routes->put( 'code/modify', 'Code::modifyCode' );
    // 메뉴 삭제
    $routes->delete('code', 'Code::deleteCode');


});

