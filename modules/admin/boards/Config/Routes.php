<?php
namespace Module\admin\boards\Config;

$routes->group('admin/boards', ['namespace' => 'Module\admin\boards\Controllers'], static function($routes)
{
    // 게시판 목록
    $routes->get( 'lists' , 'Lists::boardLists' );
    // 게시판 등록
    $routes->post( 'add', 'Register::addBoards' );
    // 게시핀 수정
    $routes->put( 'modify', 'Register::modBoards' );
    // 게시판 삭제
    $routes->delete( 'delete', "Delete::deleteBoards" );

    // 게시글 리스트
    $routes->get( "posts", "Lists::postsLists" );
    // 게시글 등록
    $routes->post( "posts/write", "Register::postsRegister" );
    //게시글 수정
    $routes->put( "posts/modify", "Register::postsModify" );
    //게시글 상세
    $routes->get( "posts/detail", "Detail::postsData" );
    //게시글 삭제
    $routes->delete( 'posts/delete', "Delete::deletePosts" );
    //게시글중 파일 삭제
    $routes->delete( 'posts/fileDelete', "Delete::fileDelete" );




    // 게시글 댓글 등록
    $routes->post( "comment/write", "Register::commentRegister" );
    //게시글 editor 파일업로드
    $routes->post( "posts/upload", "Register::upload" );
    $routes->post( "posts/uploadEnc", "Register::upload_64" );





});

