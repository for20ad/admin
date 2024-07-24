<?php
namespace Module\membership\Config;

$routes->group('membership', ['namespace' => 'Module\membership\Controllers'], static function($routes)
{
    // 강의목록과 강의 데이터, 모든 정보 가져오기
    $routes->get( 'lists' , 'Lists::lists' );
    $routes->get( 'mileage' , 'Mileage::lists' );
});

$routes->group('apis/membership', ['namespace' => 'Module\membership\Controllers\apis'], static function($routes)
{

    $routes->post( 'getLists' , 'MembershipApi::getMembershipLists' );
    $routes->post( 'register' , 'MembershipApi::register' );
    $routes->post( 'detail' , 'MembershipApi::detail' );
    $routes->post( 'memberDelete' , 'MembershipApi::memberDelete' );

    $routes->post( 'memberRegister' , 'MembershipApi::memberRegister' );
    $routes->post( 'memberModify' , 'MembershipApi::memberModify' );
    $routes->post( 'modifyMembershipStatus' , 'MembershipApi::modifyMembershipStatus' );
    $routes->post( 'getListInButtonSet' , 'MembershipApi::getListInButtonSet' );
    $routes->post( 'getListsExcel' , 'MembershipApi::getListsExcel' );

    $routes->post( 'getMileageLists' , 'MembershipApi::getMembershipMileageLists' );
    $routes->post( 'mileageRegister' , 'MembershipApi::mileageRegister' );
    $routes->post( 'mileageUserRegister' , 'MembershipApi::mileageUserRegister' );
    $routes->post( 'getSubMileageLists' , 'MembershipApi::getSubMileageLists' );
    $routes->post( 'mileageHistoryRegister' , 'MembershipApi::mileageHistoryRegister' );
    $routes->post( 'getMileageHistoryList' , 'MembershipApi::getMileageHistoryList' );




});
