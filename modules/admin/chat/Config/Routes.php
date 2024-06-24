<?php
namespace Module\admin\chat\Config;

$routes->group('admin/chat', ['namespace' => 'Module\admin\chat\Controllers'], static function($routes)
{
    //tarantool DB

    // 채팅 생성
    $routes->post('createChat', 'Chat::addRoom');

    //채팅룸 리스트
    $routes->get( 'roomLists', 'Chat::getRoomLists' );

    //메시지 전송
    $routes->post('sendMessage', 'Chat::sendMessage');

    //룸 메시지 로드
    $routes->get('getMessages', 'Chat::getMessages');

    //채팅방 정보
    $routes->get('getRoomInfo', 'Chat::getRoomInfo');



});

