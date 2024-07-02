<?php
namespace Module\setting\Config;

$routes->group('setting/member', ['namespace' => 'Module\setting\Controllers'], static function($routes) {
    $routes->get('', 'Member::index');
});
$routes->group('apis/setting', ['namespace' => 'Module\setting\Controllers\apis'], static function($routes) {
    $routes->post('writeMenu', 'MenuApi::writeMenu');
    $routes->post('modifyMenu', 'MenuApi::modifyMenu');
    $routes->post('deleteMenu', 'MenuApi::deleteMenu');

});


$routes->group('setting/menu', ['namespace' => 'Module\setting\Controllers'], static function($routes) {
    $routes->get('', 'Menu::index');
});
