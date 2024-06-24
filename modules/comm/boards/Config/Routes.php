<?php
namespace Module\comm\boards\Config;

$routes->group('comm/boards', ['namespace' => 'Module\comm\boards\Controllers'], static function($routes)
{
    // 게시판 목록
    $routes->get( 'lists' , 'Lists::boardLists' );

    // 게시글 리스트
    $routes->get( "posts", "Lists::postsLists" );
    // 게시글 등록
    $routes->post( "posts/write", "Register::postsRegister" );
    //게시글 수정
    $routes->put( "posts/modify", "Register::postsModify" );
    //게시글 상세
    $routes->get( "posts/detail", "Detail::postsData" );



    // 게시글 댓글 등록
    $routes->post( "comment/write", "Register::commentRegister" );
    //게시글 editor 파일업로드
    $routes->post( "posts/upload", "Register::upload" );
    $routes->post( "posts/uploadEnc", "Register::upload_64" );

    //비밀글 보기 - 비밀번호 확인
    $routes->get( "comment/view", "Detail::commentView" );


});

