<?php
namespace Module\design\Config;

$routes->group('design', ['namespace' => 'Module\design\Controllers'], static function($routes)
{
    $routes->get( 'logo',                           'Logo::logoInfo' );
    $routes->get( 'popup',                          'Popup::lists' );
    $routes->get( 'banner',                         'Banner::lists' );
});


$routes->group('apis/design', ['namespace' => 'Module\design\Controllers\apis'], static function($routes)
{
    $routes->post( 'writeImage',                    'DesignApi::writeImage' );

    $routes->post( 'getPopupLists',                 'PopupApi::getPopupLists' );
    $routes->post( 'popupRegister',                 'PopupApi::popupRegister' );
    $routes->post( 'popupRegisterProc',             'PopupApi::popupRegisterProc' );
    $routes->post( 'popupDetail',                   'PopupApi::popupDetail' );
    $routes->post( 'popupDetailProc',               'PopupApi::popupDetailProc' );
    $routes->post( 'deletePopup',                   'PopupApi::deletePopup' );

    $routes->post( 'getBannerLists',                'BannerApi::getBannerLists' );
    $routes->post( 'bannerRegister',                'BannerApi::bannerRegister' );
    $routes->post( 'bannerRegisterProc',            'BannerApi::bannerRegisterProc' );
    $routes->post( 'bannerDetail',                  'BannerApi::bannerDetail' );
    $routes->post( 'bannerDetailProc',              'BannerApi::bannerDetailProc' );
    $routes->post( 'deleteBanner',                  'BannerApi::deleteBanner' );
    $routes->post( 'deleteBannerImg',               'BannerApi::deleteBannerImg' );


    $routes->get( 'loadPopup',                      'PopupApi::loadPopup' );

});