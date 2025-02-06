<?php
namespace Module\promotion\Config;

$routes->group('promotion', ['namespace' => 'Module\promotion\Controllers'], static function($routes)
{
});
$routes->group('promotion/coupon', ['namespace' => 'Module\promotion\Controllers'], static function($routes)
{
    $routes->get( 'cpnLists',                       'Coupon::lists' );
    $routes->get( 'cpnRegister',                    'Coupon::register' );
    $routes->get( 'couponDetail/(:num)',            'Coupon::detail/$1' );

});

$routes->group('apis/promotion', ['namespace' => 'Module\promotion\Controllers\apis'], static function($routes)
{
    $routes->post( 'getPopCategoryManage',          'CouponApi::getPopCategoryManage' );
    $routes->post( 'getPopBrandManage',             'CouponApi::getPopBrandManage' );
    $routes->post( 'getPopProductLists',            'CouponApi::getPopProductLists' );
    $routes->post( 'goodsAddRows',                  'CouponApi::goodsAddRows' );

    $routes->post( 'couponRegisterProc',            'CouponApi::couponRegisterProc' );
    $routes->post( 'couponModifyProc',              'CouponApi::couponModifyProc' );

    $routes->post( 'getCouponLists',                'CouponApi::getCouponLists' );
    $routes->post( 'couponPopIssueLists',           'CouponApi::couponPopIssueLists' );


    $routes->post( 'couponIssueLists',              'CouponApi::couponIssueLists' );
    $routes->post( 'makeCpnIssue',                  'CouponApi::makeCpnIssue' );
    $routes->post( 'couponJoinUser',                'CouponApi::couponJoinUser' );
    $routes->post( 'deleteIssueData',               'CouponApi::deleteIssueData' );
    $routes->post( 'deleteCoupon',                  'CouponApi::deleteCoupon' );
    $routes->post( 'copyCoupon',                    'CouponApi::copyCoupon' );
    $routes->post( 'stopIssue',                     'CouponApi::stopIssue' );




});