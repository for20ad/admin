<?php
namespace Module\setting\Config;

$routes->group('setting', ['namespace' => 'Module\setting\Controllers'], static function($routes) {
    #------------------------------------------------------------------
    # FIXME: 매뉴
    #------------------------------------------------------------------
    $routes->get('menu', 'Menu::index');

    #------------------------------------------------------------------
    # FIXME: 관리자관리
    #------------------------------------------------------------------
    $routes->get('memberLists', 'Member::lists');
    $routes->get('memberRegister', 'Member::register');
    $routes->get('memberDetail/(:num)', 'Member::detail/$1');

    $routes->get('policyMember', 'Policy::memberData');
    $routes->get('policyPurchase', 'Policy::policyPurchase');

    $routes->get('cart', 'Cart::defaultSetting' );

    $routes->get('membershipGrade', 'Membership::grade' );
    $routes->get('membershipValuation', 'Membership::valuation' );

});
$routes->group('apis/setting', ['namespace' => 'Module\setting\Controllers\apis'], static function($routes) {
    $routes->post('writeMenu', 'MenuApi::writeMenu');
    $routes->post('modifyMenu', 'MenuApi::modifyMenu');
    $routes->post('deleteMenu', 'MenuApi::deleteMenu');

    $routes->post('duplicateId', 'MemberApi::duplicateId');
    $routes->post('memberRegister', 'MemberApi::memberRegister');
    $routes->post('memberModify', 'MemberApi::memberModify');
    $routes->post('memberDelete', 'MemberApi::memberDelete');
    $routes->post('getAdminLists', 'MemberApi::getAdminLists');

    $routes->post('policyMemberSet', 'PolicyApi::policyMemberSet');
    $routes->post('policyPuchaseSet', 'PolicyApi::policyPuchaseSet');

    $routes->post('setCartSetting', 'CartApi::setData');

    $routes->post('addMembershipGrade', 'MembershipApi::addGrade');
    $routes->post('modifyMembershipGrade', 'MembershipApi::modifyGrade');
    $routes->post('updateGradeSort', 'MembershipApi::updateGradeSort');
    $routes->post('deleteMembershipGrade', 'MembershipApi::deleteMembershipGrade');
    $routes->post('deleteMembershipGradeIcon', 'MembershipApi::deleteMembershipGradeIcon');




});

