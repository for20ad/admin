<?php
namespace Config;

class Talk
{
    public array $talk = [
        'item' => [
            '#{이름}'      => 'user_name',
            '#{인증번호}'   => 'auth_num',
            '#{아이디}'     => 'user_id',
            '#{추가안내}'   => 'add_info',
            '#{URL1}'     => 'link_url'
        ],
        'KakaoInfo' => [
            'YELLOWID_KEY' => '477c26d7bf50b8b6c6e814b2ad769bf662e442c0',
            'UUID' => '@성우edu',
            'PROFILE' => '도서출판 성우 EDU'
        ],
        'kakaoTemplate' => [
            'sungwoo_edu_007'   => '비밀번호재설정안내',
            'sungwoo_edu_001' => '인증번호3',
            'sungwoo_edu_003'   => '아이디찾기',
            'sungwoo_edu_004'   => '비밀번호변경',
        ]
    ];
}

