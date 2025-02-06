<?php
namespace Module\goods\Config;

$routes->group('goods', ['namespace' => 'Module\goods\Controllers'], static function($routes)
{

    $routes->get( 'goodsIcons',                     'Icons::lists' );
    $routes->get( 'goodsCategory',                  'Category::lists' );
    $routes->get( 'goodsLists',                     'Lists::lists' );
    $routes->get( 'goodsRegister',                  'GoodsRegister::index' );
    $routes->get( 'goodsDetail/(:num)',             'GoodsDetail::index/$1');
    $routes->get( 'goodsRequiredInfo',              'GoodsRequiredInfo::lists' );
    $routes->get( 'goodsBrand',                     'Brand::lists' );
    $routes->get( 'bestGoods',                      'GoodsBundle::bestGoods' );
    $routes->get( 'newArrival',                     'GoodsBundle::newArrival' );
    $routes->get( 'timeSale',                       'GoodsBundle::timeSale' );
    $routes->get( 'timeSaleDetail/(:num)',          'GoodsBundle::timeSaleDetail/$1' );
    $routes->get( 'weeklyGoods',                    'GoodsBundle::weeklyGoods' );
    $routes->get( 'weeklyGoodsDetail/(:num)',       'GoodsBundle::weeklyGoodsDetail/$1' );
    $routes->get( 'defaultGoods',                   'GoodsBundle::defaultGoods' );
    $routes->get( 'goodsReview',                    'GoodsReview::lists' );
    $routes->get( 'goodsRequiredInfo',              'RequiredInfoApi::lists' );
    $routes->get( 'gdGoods',                        'GdGoods::lists' );

    $routes->get( 'hotKeyword',                     'GoodsBundle::hotKeyword' );
    $routes->get( 'hotKeywordDetail/(:num)',        'GoodsBundle::hotKeywordDetail/$1' );

    $routes->get( 'hotBrand',                       'GoodsBundle::hotBrand' );



});

$routes->group('apis/goods', ['namespace' => 'Module\goods\Controllers\apis'], static function($routes)
{
    $routes->post( 'getIconsLists',                 'IconsApi::getIconsLists' );
    $routes->post( 'iconRegister',                  'IconsApi::iconRegister' );
    $routes->post( 'iconDetail',                    'IconsApi::iconDetail' );
    $routes->post( 'iconRegisterProc',              'IconsApi::iconRegisterProc' );
    $routes->post( 'iconDetailProc',                'IconsApi::iconDetailProc' );
    $routes->post( 'deleteIcons',                   'IconsApi::deleteIcons' );
    $routes->post( 'getGoodsIconGroup',             'IconsApi::getGoodsIconGroup' );


    $routes->post( 'cateRegister',                  'CategoryApi::cateRegister' );
    $routes->post( 'cateDetail',                    'CategoryApi::cateDetail' );
    $routes->post( 'categoryRegisterProc',          'CategoryApi::categoryRegisterProc' );
    $routes->post( 'categoryDetailProc',            'CategoryApi::categoryDetailProc' );
    $routes->post( 'deleteCategory',                'CategoryApi::deleteCategory' );
    $routes->post( 'getCategoryLists',              'CategoryApi::getCategoryLists' );
    $routes->post( 'updateCategoryOrder',           'CategoryApi::updateCategoryOrder' );
    $routes->post( 'getCategoryDropDown',           'CategoryApi::getCategoryDropDown' );
    $routes->post( 'getPopCategoryManage',          'CategoryApi::getPopCategoryManage' );
    $routes->post( 'getCategoryChilds',             'CategoryApi::getCategoryChilds' );
    $routes->post( 'deleteCategoryImages',          'CategoryApi::deleteCategoryImages' );
    $routes->post( 'updateCategoryFileLink',        'CategoryApi::updateCategoryFileLink' );




    $routes->post( 'brandRegister',                 'BrandApi::brandRegister' );
    $routes->post( 'brandDetail',                   'BrandApi::brandDetail' );
    $routes->post( 'brandRegisterProc',             'BrandApi::brandRegisterProc' );
    $routes->post( 'brandDetailProc',               'BrandApi::brandDetailProc' );
    $routes->post( 'deleteBrand',                   'BrandApi::deleteBrand' );
    $routes->post( 'getBrandLists',                 'BrandApi::getBrandLists' );
    $routes->post( 'updateBradOrder',               'BrandApi::updateBradOrder' );
    $routes->post( 'getBrandDropDown',              'BrandApi::getBrandDropDown' );
    $routes->post( 'getPopBrandManage',             'BrandApi::getPopBrandManage' );
    $routes->post( 'updateBrandOrder',              'BrandApi::updateBrandOrder' );
    $routes->post( 'deleteBrandImages',             'BrandApi::deleteBrandImages' );
    $routes->post( 'getPopBrandLists',              'BrandApi::getPopBrandLists' );
    $routes->post( 'getPopBrandListsRow',           'BrandApi::getPopBrandListsRow' );
    $routes->post( 'brandAddRows',                  'BrandApi::brandAddRows' );




    $routes->post( 'goodsRegisterProc',             'GoodsApi::goodsRegisterProc' );
    $routes->post( 'getPopProductLists',            'GoodsApi::getPopProductLists' );
    $routes->post( 'goodsAddRows',                  'GoodsApi::goodsAddRows' );
    $routes->post( 'getPopGoodsLists',              'GoodsApi::getPopGoodsLists' );
    $routes->post( 'getGoodsLists',                 'GoodsApi::getGoodsLists' );
    $routes->post( 'copyGoods',                     'GoodsApi::copyGoods' );
    $routes->post( 'deleteGoods',                   'GoodsApi::deleteGoods' );
    $routes->post( 'goodsDetailProc',               'GoodsApi::goodsDetailProc' );
    $routes->post( 'reloadGroupGoods',              'GoodsApi::reloadGroupGoods' );
    $routes->post( 'deleteToCopyGoods',             'GoodsApi::deleteToCopyGoods' );
    $routes->post( 'allReloadGroupGoods',           'GoodsApi::allReloadGroupGoods' );
    $routes->post( 'goodsRegister',                 'GoodsApi::goodsRegister' );
    $routes->post( 'updateGoodsPrice',              'GoodsApi::updateGoodsPrice' );

    $routes->post( 'getGodoGoods',                  'GoodsApi::getGodoGoods' );
    $routes->post( 'updateGodoGoods',               'GoodsApi::updateGodoGoods' );





    $routes->post( 'requiredInfoRegister',          'RequiredInfoApi::requiredInfoRegister' );
    $routes->post( 'requiredInfoRegisterProc',      'RequiredInfoApi::requiredInfoRegisterProc' );

    $routes->post( 'requiredInfoDetail',            'RequiredInfoApi::requiredInfoDetail' );
    $routes->post( 'requiredInfoDetailProc',        'RequiredInfoApi::requiredInfoDetailProc' );

    $routes->post( 'getRequiredInfoLists',          'RequiredInfoApi::getRequiredInfoLists' );
    $routes->post( 'getPopRequiredLists',           'RequiredInfoApi::getPopRequiredLists' );
    $routes->post( 'getPopRequiredListsRow',        'RequiredInfoApi::getPopRequiredListsRow' );
    $routes->post( 'addRequiredRow',                'RequiredInfoApi::addRequiredRow' );


    $routes->post( 'deleteInfo',                    'RequiredInfoApi::deleteInfo' );

    $routes->post( 'bestGoodsRegister',             'BundleApi::bestGoodsRegister' );
    $routes->post( 'newGoodsRegister',              'BundleApi::newGoodsRegister' );
    $routes->post( 'defaultGoodsRegister',          'BundleApi::defaultGoodsRegister' );

    $routes->post( 'getTimeSaleLists',              'BundleApi::getTimeSaleLists' );
    $routes->post( 'timeSaleRegister',              'BundleApi::timeSaleRegister' );
    $routes->post( 'timeSaleRegisterProc',          'BundleApi::timeSaleRegisterProc' );
    $routes->post( 'timeSaleDetail',                'BundleApi::timeSaleDetail' );
    $routes->post( 'timeSaleDetailProc',            'BundleApi::timeSaleDetailProc' );
    $routes->post( 'deleteTimeSale',                'BundleApi::deleteTimeSale' );
    $routes->post( 'timeSaleDetailRegister',        'BundleApi::timeSaleDetailRegister' );

    $routes->post( 'getWeeklyGoodsLists',           'BundleApi::getWeeklyGoodsLists' );
    $routes->post( 'weeklyGoodsRegister',           'BundleApi::weeklyGoodsRegister' );
    $routes->post( 'weeklyGoodsRegisterProc',       'BundleApi::weeklyGoodsRegisterProc' );
    $routes->post( 'weeklyGoodsDetail',             'BundleApi::weeklyGoodsDetail' );
    $routes->post( 'weeklyGoodsDetailProc',         'BundleApi::weeklyGoodsDetailProc' );
    $routes->post( 'deleteWeeklyGoods',             'BundleApi::deleteWeeklyGoods' );
    $routes->post( 'weeklyGoodsDetailRegister',     'BundleApi::weeklyGoodsDetailRegister' );


    $routes->post( 'questionKeywordRegister',       'CategoryApi::questionKeywordRegister' );
    $routes->post( 'questionKeywordDelete',         'CategoryApi::questionKeywordDelete' );


    $routes->post( 'getHotKeywordLists',            'BundleApi::getHotKeywordLists' );
    $routes->post( 'hotKeywordRegister',            'BundleApi::hotKeywordRegister' );
    $routes->post( 'hotKeywordRegisterProc',        'BundleApi::hotKeywordRegisterProc' );
    $routes->post( 'hotKeywordDetailProc',          'BundleApi::hotKeywordDetailProc' );
    $routes->post( 'hotKeywordDetail',              'BundleApi::hotKeywordDetail' );
    $routes->post( 'hotKeywordDetailRegister',      'BundleApi::hotKeywordDetailRegister' );
    $routes->post( 'updateHotKeywordSort',          'BundleApi::updateHotKeywordSort' );
    $routes->post( 'hotBrandRegister',              'BundleApi::hotBrandRegister' );







});