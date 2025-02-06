<?php
namespace Module\order\Config;

$routes->group('order', ['namespace' => 'Module\order\Controllers'], static function($routes)
{
    $routes->get( 'orderLists',                     'Lists::orderLists' );
    $routes->get( 'orderPayStatusLists',            'Lists::orderPayStatusLists' );
    $routes->get( 'orderPrdProcessLists',           'Lists::orderPrdProcessLists' );
    $routes->get( 'returnProcessLists',             'Lists::orderReturnProcessLists' );
    $routes->get( 'exchangeProcessLists',           'Lists::orderExchangeProcessLists' );

    $routes->get( 'orderDetail/(:any)',             'Detail::orderDetail/$1' );


    /* 테스트 페이지들 */
    $routes->get( 'testLogis',                      'Order::testLogis' );
    $routes->post( 'verification',                  'OrderApis::verification' );

});


$routes->group('apis/order', ['namespace' => 'Module\order\Controllers\apis'], static function($routes)
{
    $routes->post( 'getOrderLists',                 'OrderApi::getOrderLists' );
    $routes->post( 'getMemoLists',                  'OrderApi::getMemoLists' );
    $routes->post( 'insertMemo',                    'OrderApi::insertMemo' );
    $routes->post( 'getMemoListsRow',               'OrderApi::getMemoListsRow' );
    $routes->post( 'deleteMemo',                    'OrderApi::deleteMemo' );
    $routes->post( 'orderDetail',                   'OrderApi::orderDetail' );
    $routes->post( 'orderStatusDetail',             'OrderApi::orderStatusDetail' );
    $routes->post( 'orderStatusChange',             'OrderApi::orderStatusChange' );
    $routes->post( 'orderPrdProcessLists',          'OrderApi::orderPrdProcessLists' );
    $routes->post( 'updateShipInfo',                'OrderApi::updateShipInfo' );
    $routes->post( 'orderStatusOverDetail',         'OrderApi::orderStatusOverDetail' );


    $routes->post( 'orderExchangeDetail',           'OrderApi::orderExchangeDetail' );
    $routes->post( 'orderReturnsDetail',            'OrderApi::orderReturnsDetail' );


    $routes->post( 'orderExchangeProcessLists',     'OrderApi::orderExchangeProcessLists' );
    $routes->post( 'orderReturnProcessLists',       'OrderApi::orderReturnProcessLists' );




    $routes->post( 'getOrderTracking',              'OrderApi::getOrderShipTracking' );
    $routes->post( 'getOrderPayStatusLists',        'OrderApi::getOrderPayStatusLists' );

});