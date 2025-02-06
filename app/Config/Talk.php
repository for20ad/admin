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
            'YELLOWID_KEY' => '1ad105e0e3ddb720742cc1a16f791c8bbbeb33df',
            'UUID' => '@산수유람온라인',
            'PROFILE' => '산수유람'
        ],
        'kakaoTemplate' => [
            'timber_007'   => '비밀번호재설정안내',
            'timber_001' => '인증번호3',
            'timber_003'   => '아이디찾기',
            'timber_004'   => '비밀번호변경',
        ]
    ];
}

