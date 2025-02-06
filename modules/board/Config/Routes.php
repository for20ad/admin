<?php
namespace Module\board\Config;

$routes->group('board', ['namespace' => 'Module\board\Controllers'], static function($routes)
{
    $routes->get( 'boardLists',                     'Lists::index' );
    $routes->get( 'noticeLists',                    'Lists::noticeLists' );
    $routes->get( 'faqLists',                       'Lists::faqLists' );
    $routes->get( 'faqListsl',                       'Lists::faqListsl' );

    $routes->get('boardLists/posts/(:any)', 'Lists::postsLists/$1'); // 문자열 파라미터
});




$routes->group('apis/board', ['namespace' => 'Module\board\Controllers\apis'], static function($routes)
{
    $routes->post( 'writeImage',                    'DesignApi::writeImage' );
    $routes->post( 'getBoardLists',                 'BoardApi::getBoardLists' );
    $routes->post( 'boardRegister',                 'BoardApi::boardRegister' );
    $routes->post( 'boardRegisterProc',             'BoardApi::boardRegisterProc' );
    $routes->post( 'boardDetail',                   'BoardApi::boardDetail' );
    $routes->post( 'boardDetailProc',               'BoardApi::boardDetailProc' );
    $routes->post( 'deleteboardIcon',               'BoardApi::deleteboardIcon' );
    $routes->post( 'deleteBoard',                   'BoardApi::deleteBoard' );

    $routes->post( 'getNoticeLists',                'BoardApi::getNoticeLists' );
    $routes->post( 'noticeRegister',                'BoardApi::noticeRegister' );
    $routes->post( 'noticeRegisterProc',            'BoardApi::noticeRegisterProc' );
    $routes->post( 'noticeDetail',                  'BoardApi::noticeDetail' );
    $routes->post( 'noticeDetailProc',              'BoardApi::noticeDetailProc' );
    $routes->post( 'deleteNotice',                  'BoardApi::deleteNotice' );


    $routes->post( 'getFaqLists',                   'BoardApi::getFaqLists' );
    $routes->post( 'faqRegister',                   'BoardApi::faqRegister' );
    $routes->post( 'faqRegisterProc',               'BoardApi::faqRegisterProc' );
    $routes->post( 'faqDetail',                     'BoardApi::faqDetail' );
    $routes->post( 'faqDetailProc',                 'BoardApi::faqDetailProc' );
    $routes->post( 'deleteFaq',                     'BoardApi::deleteFaq' );

    $routes->post( 'getPostsLists',                 'BoardApi::getPostsLists' );
    $routes->post( 'postsRegister',                 'BoardApi::postsRegister' );
    $routes->post( 'postsRegisterProc',             'BoardApi::postsRegisterProc' );
    $routes->post( 'postsDetail',                   'BoardApi::postsDetail' );
    $routes->post( 'postsDetailProc',               'BoardApi::postsDetailProc' );

    $routes->post( 'getPostsComments',              'BoardApi::getPostsComments' );
    $routes->post( 'addPostsReply',                 'BoardApi::addPostsReply' );
    $routes->post( 'changePostsStatus',             'BoardApi::changePostsStatus' );
    $routes->post( 'changePostsAnswerStatus',       'BoardApi::changePostsAnswerStatus' );

    $routes->post( 'deletePostsFile',               'BoardApi::deletePostsFile' );



});