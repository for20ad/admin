<?php
namespace Module\setting\Config;

$routes->group('setting', ['namespace' => 'Module\setting\Controllers'], static function($routes) {

    #------------------------------------------------------------------
    # FIXME: 매뉴
    #------------------------------------------------------------------
    $routes->get('menu',                            'Menu::index');

    #------------------------------------------------------------------
    # FIXME: 관리자관리
    #------------------------------------------------------------------
    $routes->get('memberLists',                     'Member::lists');
    $routes->get('memberRegister',                  'Member::register');
    $routes->get('memberDetail/(:num)',             'Member::detail/$1');

    $routes->get('mallPolicy',                      'Mall::mallPolicy');

    $routes->get('policyMember',                    'Policy::memberData');
    $routes->get('policyPurchase',                  'Policy::policyPurchase');

    $routes->get('cart',                            'Cart::defaultSetting' );

    $routes->get('memberGrade',                     'Membership::grade' );
    $routes->get('memberValuation',                 'Membership::valuation' );

    $routes->get('deliveryComp',                    'Delivery::deliveryComp' );

    $routes->get('excelFormLists',                  'ExcelForm::formLists' );

    $routes->get('code',                            'Code::getCodeLists' );





});
$routes->group('apis/setting', ['namespace' => 'Module\setting\Controllers\apis'], static function($routes) {
    $routes->post('writeMenu',                      'MenuApi::writeMenu');
    $routes->post('modifyMenu',                     'MenuApi::modifyMenu');
    $routes->post('deleteMenu',                     'MenuApi::deleteMenu');

    $routes->post('duplicateId',                    'MemberApi::duplicateId');
    $routes->post('memberRegister',                 'MemberApi::memberRegister');
    $routes->post('memberModify',                   'MemberApi::memberModify');
    $routes->post('memberDelete',                   'MemberApi::memberDelete');
    $routes->post('getAdminLists',                  'MemberApi::getAdminLists');
    $routes->post('adminDetail',                    'MemberApi::adminDetail');

    $routes->post('policyMemberSet',                'PolicyApi::policyMemberSet');
    $routes->post('policyPuchaseSet',               'PolicyApi::policyPuchaseSet');

    $routes->post('policyTermsSet',                 'MallApi::policyTermsSet');

    $routes->post('setCartSetting',                 'CartApi::setData');

    $routes->post('addMembershipGrade',             'MembershipApi::addGrade');
    $routes->post('modifyMembershipGrade',          'MembershipApi::modifyGrade');
    $routes->post('updateGradeSort',                'MembershipApi::updateGradeSort');
    $routes->post('deleteMembershipGrade',          'MembershipApi::deleteMembershipGrade');
    $routes->post('deleteMembershipGradeIcon',      'MembershipApi::deleteMembershipGradeIcon');
    $routes->post('setMembershipGradeValuation',    'MembershipApi::setMembershipGradeValuation');

    $routes->post('addDeliveryCompany',             'DeliveryApi::addDeliveryCompany');
    $routes->post('modifyDeliveryComp',             'DeliveryApi::modifyDeliveryComp');
    $routes->post('deletedeliveryComp',             'DeliveryApi::deletedeliveryComp');
    $routes->post('updateDeliveryCompanySort',      'DeliveryApi::updateDeliveryCompanySort');

    $routes->post('getFormLists',                   'ExcelFormApi::getFormLists');
    $routes->post('formRegister',                   'ExcelFormApi::formRegister');
    $routes->post('formDetail',                     'ExcelFormApi::formDetail');
    $routes->post('getFormFileds',                  'ExcelFormApi::getFormFileds');
    $routes->post('saveForm',                       'ExcelFormApi::saveForm');
    $routes->post('deleteForms',                    'ExcelFormApi::deleteForms');

    $routes->post('writeCode',                      'CodeApi::writeCode');
    $routes->post('modifyCode',                     'CodeApi::modifyCode');
    $routes->post('deleteCode',                     'CodeApi::deleteCode');

});

