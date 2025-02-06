<?php
namespace Module\membership\Config;

$routes->group('membership', ['namespace' => 'Module\membership\Controllers'], static function($routes)
{
    $routes->get( 'lists',                          'Lists::lists' );
    $routes->get( 'memberMileageLists',             'Mileage::lists' );
});

$routes->group('apis/membership', ['namespace' => 'Module\membership\Controllers\apis'], static function($routes)
{
    $routes->post( 'getLists',                      'MembershipApi::getMembershipLists' );
    $routes->post( 'register',                      'MembershipApi::register' );
    $routes->post( 'detail',                        'MembershipApi::detail' );
    $routes->post( 'memberDelete',                  'MembershipApi::memberDelete' );

    $routes->post( 'memberRegister',                'MembershipApi::memberRegister' );
    $routes->post( 'memberModify',                  'MembershipApi::memberModify' );
    $routes->post( 'modifyMembershipStatus',        'MembershipApi::modifyMembershipStatus' );
    $routes->post( 'getListInButtonSet',            'MembershipApi::getListInButtonSet' );
    $routes->post( 'getListsExcel',                 'MembershipApi::getListsExcel' );

    $routes->get( 'simpleSearch',                  'MembershipApi::simpleSearch' );


    $routes->post( 'getMileageLists',               'MembershipApi::getMembershipMileageLists' );
    $routes->post( 'mileageRegister',               'MembershipApi::mileageRegister' );
    $routes->post( 'mileageUserRegister',           'MembershipApi::mileageUserRegister' );
    $routes->post( 'getSubMileageLists',            'MembershipApi::getSubMileageLists' );
    $routes->post( 'mileageHistoryRegister',        'MembershipApi::mileageHistoryRegister' );
    $routes->post( 'getMileageHistoryList',         'MembershipApi::getMileageHistoryList' );
    $routes->post( 'getMileageListsExcel',          'MembershipApi::getMileageListsExcel' );

    $routes->post( 'getPointLists',                 'MembershipApi::getPointLists' );
    $routes->post( 'getCouponLists',                'MembershipApi::getCouponLists' );
    $routes->post( 'getOrderLists',                 'MembershipApi::getOrderLists' );
    $routes->post( 'changePointReason',             'MembershipApi::changePointReason' );

    $routes->post( 'getCouponUseRange',             'MembershipApi::getCouponUseRange' );
    $routes->post( 'getCounselLists',               'MembershipApi::getCounselLists' );
    $routes->post( 'getCounselListsRow',            'MembershipApi::getCounselListsRow' );
    $routes->post( 'insertCounsel',                 'MembershipApi::insertCounsel' );
    $routes->post( 'deleteCounsel',                 'MembershipApi::deleteCounsel' );








});
