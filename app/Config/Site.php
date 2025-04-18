<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Site extends BaseConfig
{
    public $version = '0.1.0'; // 버전

    public $adminExpireTime = 7200; // 관리자 로그인 만료 시간

    public $mobileAuthTime = 120;

    public $adminExpireAutoUpdate = false; // 관리자 로그인 만료 시간 자동 업데이트

    public $adminMenuRules = [
        'main' => [
            "width"=>"24",
            "height"=>"24",
            "viewBox"=>"0 0 24 24",
            "path"=>[
                'M9 21V15C9 14.4696 9.21071 13.9609 9.58579 13.5858C9.96086 13.2107 10.4696 13 11 13H13C13.5304 13 14.0391 13.2107 14.4142 13.5858C14.7893 13.9609 15 14.4696 15 15V21M5 12H3L12 3L21 12H19V19C19 19.5304 18.7893 20.0391 18.4142 20.4142C18.0391 20.7893 17.5304 21 17 21H7C6.46957 21 5.96086 20.7893 5.58579 20.4142C5.21071 20.0391 5 19.5304 5 19V12Z'
            ],
        ],

        'setting' => [
            "width"=>"20",
            "height"=>"20",
            "viewBox"=>"0 0 20 20",
            "path"=>[
                'M8.325 2.317C8.751 0.561 11.249 0.561 11.675 2.317C11.7389 2.5808 11.8642 2.82578 12.0407 3.032C12.2172 3.23822 12.4399 3.39985 12.6907 3.50375C12.9414 3.60764 13.2132 3.65085 13.4838 3.62987C13.7544 3.60889 14.0162 3.5243 14.248 3.383C15.791 2.443 17.558 4.209 16.618 5.753C16.4769 5.98466 16.3924 6.24634 16.3715 6.51677C16.3506 6.78721 16.3938 7.05877 16.4975 7.30938C16.6013 7.55999 16.7627 7.78258 16.9687 7.95905C17.1747 8.13553 17.4194 8.26091 17.683 8.325C19.439 8.751 19.439 11.249 17.683 11.675C17.4192 11.7389 17.1742 11.8642 16.968 12.0407C16.7618 12.2172 16.6001 12.4399 16.4963 12.6907C16.3924 12.9414 16.3491 13.2132 16.3701 13.4838C16.3911 13.7544 16.4757 14.0162 16.617 14.248C17.557 15.791 15.791 17.558 14.247 16.618C14.0153 16.4769 13.7537 16.3924 13.4832 16.3715C13.2128 16.3506 12.9412 16.3938 12.6906 16.4975C12.44 16.6013 12.2174 16.7627 12.0409 16.9687C11.8645 17.1747 11.7391 17.4194 11.675 17.683C11.249 19.439 8.751 19.439 8.325 17.683C8.26108 17.4192 8.13578 17.1742 7.95929 16.968C7.7828 16.7618 7.56011 16.6001 7.30935 16.4963C7.05859 16.3924 6.78683 16.3491 6.51621 16.3701C6.24559 16.3911 5.98375 16.4757 5.752 16.617C4.209 17.557 2.442 15.791 3.382 14.247C3.5231 14.0153 3.60755 13.7537 3.62848 13.4832C3.64942 13.2128 3.60624 12.9412 3.50247 12.6906C3.3987 12.44 3.23726 12.2174 3.03127 12.0409C2.82529 11.8645 2.58056 11.7391 2.317 11.675C0.561 11.249 0.561 8.751 2.317 8.325C2.5808 8.26108 2.82578 8.13578 3.032 7.95929C3.23822 7.7828 3.39985 7.56011 3.50375 7.30935C3.60764 7.05859 3.65085 6.78683 3.62987 6.51621C3.60889 6.24559 3.5243 5.98375 3.383 5.752C2.443 4.209 4.209 2.442 5.753 3.382C6.753 3.99 8.049 3.452 8.325 2.317Z',
                'M7 10C7 10.7956 7.31607 11.5587 7.87868 12.1213C8.44129 12.6839 9.20435 13 10 13C10.7956 13 11.5587 12.6839 12.1213 12.1213C12.6839 11.5587 13 10.7956 13 10C13 9.20435 12.6839 8.44129 12.1213 7.87868C11.5587 7.31607 10.7956 7 10 7C9.20435 7 8.44129 7.31607 7.87868 7.87868C7.31607 8.44129 7 9.20435 7 10Z'
            ],
        ],

        'membership' => [
            "width"=>"14",
            "height"=>"20",
            "viewBox"=>"0 0 14 20",
            "path"=>[
                'M1 19V17C1 15.9391 1.42143 14.9217 2.17157 14.1716C2.92172 13.4214 3.93913 13 5 13H9C10.0609 13 11.0783 13.4214 11.8284 14.1716C12.5786 14.9217 13 15.9391 13 17V19M3 5C3 6.06087 3.42143 7.07828 4.17157 7.82843C4.92172 8.57857 5.93913 9 7 9C8.06087 9 9.07828 8.57857 9.82843 7.82843C10.5786 7.07828 11 6.06087 11 5C11 3.93913 10.5786 2.92172 9.82843 2.17157C9.07828 1.42143 8.06087 1 7 1C5.93913 1 4.92172 1.42143 4.17157 2.17157C3.42143 2.92172 3 3.93913 3 5Z',
            ],
        ],

        'goods' => [
            "width"=>"24",
            "height"=>"24",
            "viewBox"=>"0 0 24 24",
            "path"=>[
                'M20 7.5L12 3L4 7.5M20 7.5V16.5L12 21M20 7.5L12 12M12 21L4 16.5V7.5M12 21V12M4 7.5L12 12M16 5.25L8 9.75',
            ],
        ],
        'order' => [
            "width"=>"24",
            "height"=>"24",
            "viewBox"=>"0 0 24 24",
            "path"=>[
                'M6 17C6.53043 17 7.03914 17.2107 7.41421 17.5858C7.78929 17.9609 8 18.4696 8 19C8 19.5304 7.78929 20.0391 7.41421 20.4142C7.03914 20.7893 6.53043 21 6 21C5.46957 21 4.96086 20.7893 4.58579 20.4142C4.21071 20.0391 4 19.5304 4 19C4 18.4696 4.21071 17.9609 4.58579 17.5858C4.96086 17.2107 5.46957 17 6 17ZM6 17H17M6 17V3H4M17 17C17.5304 17 18.0391 17.2107 18.4142 17.5858C18.7893 17.9609 19 18.4696 19 19C19 19.5304 18.7893 20.0391 18.4142 20.4142C18.0391 20.7893 17.5304 21 17 21C16.4696 21 15.9609 20.7893 15.5858 20.4142C15.2107 20.0391 15 19.5304 15 19C15 18.4696 15.2107 17.9609 15.5858 17.5858C15.9609 17.2107 16.4696 17 17 17ZM6 5L20 6L19 13H6',
            ],
        ],
        'design' => [
            "width"=>"24",
            "height"=>"24",
            "viewBox"=>"0 0 24 24",
            "path"=>[
                'M8.2002 13.2C9.21789 10.505 10.9443 8.13474 13.1973 6.33944C15.4502 4.54414 18.146 3.3904 21.0002 3C20.6098 5.85418 19.4557 8.55002 17.6604 10.8029C15.8651 13.0559 13.4948 14.7823 10.7998 15.8M10.5996 9C12.5428 9.89687 14.1027 11.4568 14.9996 13.4M3 21V17C3 16.2089 3.2346 15.4355 3.67412 14.7777C4.11365 14.1199 4.73836 13.6072 5.46927 13.3045C6.20017 13.0017 7.00444 12.9225 7.78036 13.0769C8.55629 13.2312 9.26902 13.6122 9.82843 14.1716C10.3878 14.731 10.7688 15.4437 10.9231 16.2196C11.0775 16.9956 10.9983 17.7998 10.6955 18.5307C10.3928 19.2616 9.88008 19.8864 9.22228 20.3259C8.56448 20.7654 7.79113 21 7 21H3Z',
            ],
        ],
        'promotion' => [
            "width"=>"24",
            "height"=>"24",
            "viewBox"=>"0 0 24 24",
            "path"=>[
                'M11.9998 17.75L5.82784 20.995L7.00684 14.122L2.00684 9.25495L8.90684 8.25495L11.9928 2.00195L15.0788 8.25495L21.9788 9.25495L16.9788 14.122L18.1578 20.995L11.9998 17.75Z',
            ],
        ],

        'board' => [
            "width"=>"24",
            "height"=>"24",
            "viewBox"=>"0 0 24 24",
            "path"=>[
                'M9 5H7C6.46957 5 5.96086 5.21071 5.58579 5.58579C5.21071 5.96086 5 6.46957 5 7V19C5 19.5304 5.21071 20.0391 5.58579 20.4142C5.96086 20.7893 6.46957 21 7 21H17C17.5304 21 18.0391 20.7893 18.4142 20.4142C18.7893 20.0391 19 19.5304 19 19V7C19 6.46957 18.7893 5.96086 18.4142 5.58579C18.0391 5.21071 17.5304 5 17 5H15M9 5C9 4.46957 9.21071 3.96086 9.58579 3.58579C9.96086 3.21071 10.4696 3 11 3H13C13.5304 3 14.0391 3.21071 14.4142 3.58579C14.7893 3.96086 15 4.46957 15 5M9 5C9 5.53043 9.21071 6.03914 9.58579 6.41421C9.96086 6.78929 10.4696 7 11 7H13C13.5304 7 14.0391 6.78929 14.4142 6.41421C14.7893 6.03914 15 5.53043 15 5',
            ],
        ],
        'scm' => [
            "width"=>"24",
            "height"=>"24",
            "viewBox"=>"0 0 24 24",
            "path"=>[
                'M4.5 9H9.5M7 9V15M13 15V9L16 13L19 9V15',
            ],
        ],
        'message' => [
            "width"=>"24",
            "height"=>"24",
            "viewBox"=>"0 0 24 24",
            "path"=>[
                'M3 7C3 6.46957 3.21071 5.96086 3.58579 5.58579C3.96086 5.21071 4.46957 5 5 5H19C19.5304 5 20.0391 5.21071 20.4142 5.58579C20.7893 5.96086 21 6.46957 21 7M3 7V17C3 17.5304 3.21071 18.0391 3.58579 18.4142C3.96086 18.7893 4.46957 19 5 19H19C19.5304 19 20.0391 18.7893 20.4142 18.4142C20.7893 18.0391 21 17.5304 21 17V7M3 7L12 13L21 7',
            ],
        ],
        'statistics' => [
            "width"=>"24",
            "height"=>"24",
            "viewBox"=>"0 0 24 24",
            "path"=>[
                'M9 19V13C9 12.7348 8.89464 12.4804 8.70711 12.2929C8.51957 12.1054 8.26522 12 8 12H4C3.73478 12 3.48043 12.1054 3.29289 12.2929C3.10536 12.4804 3 12.7348 3 13V19C3 19.2652 3.10536 19.5196 3.29289 19.7071C3.48043 19.8946 3.73478 20 4 20M9 19C9 19.2652 8.89464 19.5196 8.70711 19.7071C8.51957 19.8946 8.26522 20 8 20H4M9 19C9 19.2652 9.10536 19.5196 9.29289 19.7071C9.48043 19.8946 9.73478 20 10 20H14C14.2652 20 14.5196 19.8946 14.7071 19.7071C14.8946 19.5196 15 19.2652 15 19M9 19V9C9 8.73478 9.10536 8.48043 9.29289 8.29289C9.48043 8.10536 9.73478 8 10 8H14C14.2652 8 14.5196 8.10536 14.7071 8.29289C14.8946 8.48043 15 8.73478 15 9V19M4 20H18M15 19C15 19.2652 15.1054 19.5196 15.2929 19.7071C15.4804 19.8946 15.7348 20 16 20H20C20.2652 20 20.5196 19.8946 20.7071 19.7071C20.8946 19.5196 21 19.2652 21 19V5C21 4.73478 20.8946 4.48043 20.7071 4.29289C20.5196 4.10536 20.2652 4 20 4H16C15.7348 4 15.4804 4.10536 15.2929 4.29289C15.1054 4.48043 15 4.73478 15 5V19Z',
            ],
        ],


    ];

    public $mileage = [
        'max_limit_amt'         => '1000000',   //최대 보유 포인트 금액
        'min_buy_amt'           => '50000',     //최소 금액 이상 포인트 사용가능.
        'max_use_amt'           => '50000',     //1회 최대 포인트 사용 허용수치
        'delivery_use_yn'       => 'N',         //결제시 배송비 합산금액에 포함 여부
        'default_save_rate'     => '3',         //상품구매시 기본으로 상품 금액에 몇 %를 포인트 환원 수치 (% 단위)
        'use_n_save_yn'         => 'N',         //포인트 사용하여 상품 구매시 포인트 를 지급할지 여부
        'expire_days'           => '180',       //발급 시 만료일 계산 일수
    ];


    public $buttonIcon = [
        'edit' => [
            'M3.33337 16.6667H6.66671L15.4167 7.91669C15.8587 7.47467 16.1071 6.87515 16.1071 6.25003C16.1071 5.62491 15.8587 5.02539 15.4167 4.58336C14.9747 4.14133 14.3752 3.89301 13.75 3.89301C13.1249 3.89301 12.5254 4.14133 12.0834 4.58336L3.33337 13.3334V16.6667Z',
            'M11.25 5.41669L14.5833 8.75002',
        ],
        'delete' => [
            'M4 7H20',
            'M10 11V17',
            'M14 11V17',
            'M5 7L6 19C6 19.5304 6.21071 20.0391 6.58579 20.4142C6.96086 20.7893 7.46957 21 8 21H16C16.5304 21 17.0391 20.7893 17.4142 20.4142C17.7893 20.0391 18 19.5304 18 19L19 7',
            'M9 7V4C9 3.73478 9.10536 3.48043 9.29289 3.29289C9.48043 3.10536 9.73478 3 10 3H14C14.2652 3 14.5196 3.10536 14.7071 3.29289C14.8946 3.48043 15 3.73478 15 4V7,'
        ],
        'success' => [
            'M4.6665 10L8.83317 14.1667L17.1665 5.83337',
        ],
        'reset' => [
            'M16.8608 10.8675C16.7035 12.0675 16.2224 13.202 15.4693 14.1493C14.7162 15.0967 13.7194 15.8211 12.5858 16.245C11.4522 16.6689 10.2247 16.7762 9.03472 16.5555C7.84479 16.3347 6.7374 15.7942 5.8313 14.9919C4.92521 14.1896 4.2546 13.1558 3.8914 12.0014C3.5282 10.8469 3.48611 9.61538 3.76964 8.43882C4.05317 7.26226 4.65162 6.18508 5.50082 5.32279C6.35002 4.46051 7.41793 3.84565 8.59001 3.54416C11.8392 2.70916 15.2025 4.38333 16.4442 7.5',
            'M16.9167 3.33331V7.49998H12.75',
        ],
        'list' => [
            'M7.75 5H16.9167',
            'M7.75 10H16.9167',
            'M7.75 15H16.9167',
            'M4.41663 5V5.00833',
            'M4.41663 10V10.0083',
            'M4.41663 15V15.0083',
        ],
        'regist' => [
            'M10.25 4.16669V15.8334',
            'M4.41663 10H16.0833',
        ],
        'add' => [
            'M10.25 4.16669V15.8334',
            'M4.41663 10H16.0833',
        ],
        'download' => [
            'M11.6666 2.5V5.83333C11.6666 6.05435 11.7544 6.26631 11.9107 6.42259C12.067 6.57887 12.2789 6.66667 12.5 6.66667H15.8333',
            'M14.1666 17.5H5.83329C5.39127 17.5 4.96734 17.3244 4.65478 17.0118C4.34222 16.6993 4.16663 16.2754 4.16663 15.8333V4.16667C4.16663 3.72464 4.34222 3.30072 4.65478 2.98816C4.96734 2.67559 5.39127 2.5 5.83329 2.5H11.6666L15.8333 6.66667V15.8333C15.8333 16.2754 15.6577 16.6993 15.3451 17.0118C15.0326 17.3244 14.6087 17.5 14.1666 17.5Z',
            'M10 14.1666V9.16663',
            'M7.91663 12.0834L9.99996 14.1667L12.0833 12.0834',
        ],
        'search' => [
            'M17.5 17.5L12.5 12.5M2.5 8.33333C2.5 9.09938 2.65088 9.85792 2.94404 10.5657C3.23719 11.2734 3.66687 11.9164 4.20854 12.4581C4.75022 12.9998 5.39328 13.4295 6.10101 13.7226C6.80875 14.0158 7.56729 14.1667 8.33333 14.1667C9.09938 14.1667 9.85792 14.0158 10.5657 13.7226C11.2734 13.4295 11.9164 12.9998 12.4581 12.4581C12.9998 11.9164 13.4295 11.2734 13.7226 10.5657C14.0158 9.85792 14.1667 9.09938 14.1667 8.33333C14.1667 7.56729 14.0158 6.80875 13.7226 6.10101C13.4295 5.39328 12.9998 4.75022 12.4581 4.20854C11.9164 3.66687 11.2734 3.23719 10.5657 2.94404C9.85792 2.65088 9.09938 2.5 8.33333 2.5C7.56729 2.5 6.80875 2.65088 6.10101 2.94404C5.39328 3.23719 4.75022 3.66687 4.20854 4.20854C3.66687 4.75022 3.23719 5.39328 2.94404 6.10101C2.65088 6.80875 2.5 7.56729 2.5 8.33333Z'
        ],
        'help' => [
            "M8 14C11.3137 14 14 11.3137 14 8C14 4.68629 11.3137 2 8 2C4.68629 2 2 4.68629 2 8C2 11.3137 4.68629 14 8 14Z",
            'M8 11.3333V11.34',
            'M7.99996 9C7.98768 8.78358 8.04606 8.56903 8.1663 8.38867C8.28654 8.20831 8.46213 8.07191 8.66663 8C8.91721 7.90417 9.14213 7.75149 9.32367 7.55397C9.50522 7.35645 9.63844 7.11948 9.71284 6.86172C9.78725 6.60397 9.8008 6.33246 9.75245 6.06858C9.70409 5.80469 9.59514 5.55563 9.43417 5.34101C9.2732 5.12638 9.06461 4.95205 8.82482 4.83174C8.58503 4.71143 8.32059 4.64843 8.05231 4.64768C7.78403 4.64694 7.51924 4.70848 7.27879 4.82746C7.03834 4.94643 6.82878 5.1196 6.66663 5.33333',
        ],
        'box_plus' => [
            "M10.4167 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V4.16667C2.5 3.72464 2.67559 3.30072 2.98816 2.98816C3.30072 2.67559 3.72464 2.5 4.16667 2.5H15.8333C16.2754 2.5 16.6993 2.67559 17.0118 2.98816C17.3244 3.30072 17.5 3.72464 17.5 4.16667V10.4167M2.5 8.33333H17.5M8.33333 2.5V17.5M13.3333 15.8333H18.3333M15.8333 13.3333V18.3333",
        ],
        'plus' =>[
            'M5 2h14a3 3 0 0 1 3 3v14a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3z',
            'M12 8v8M8 12h8',
        ],
        'print' =>[
            'M13.1667 13.1667H14.8333C15.2754 13.1667 15.6993 12.9911 16.0118 12.6785C16.3244 12.366 16.5 11.942 16.5 11.5V8.16667C16.5 7.72464 16.3244 7.30072 16.0118 6.98816C15.6993 6.67559 15.2754 6.5 14.8333 6.5H3.16667C2.72464 6.5 2.30072 6.67559 1.98816 6.98816C1.67559 7.30072 1.5 7.72464 1.5 8.16667V11.5C1.5 11.942 1.67559 12.366 1.98816 12.6785C2.30072 12.9911 2.72464 13.1667 3.16667 13.1667H4.83333M13.1667 6.5V3.16667C13.1667 2.72464 12.9911 2.30072 12.6785 1.98816C12.366 1.67559 11.942 1.5 11.5 1.5H6.5C6.05797 1.5 5.63405 1.67559 5.32149 1.98816C5.00893 2.30072 4.83333 2.72464 4.83333 3.16667V6.5M4.83333 11.5C4.83333 11.058 5.00893 10.634 5.32149 10.3215C5.63405 10.0089 6.05797 9.83333 6.5 9.83333H11.5C11.942 9.83333 12.366 10.0089 12.6785 10.3215C12.9911 10.634 13.1667 11.058 13.1667 11.5V14.8333C13.1667 15.2754 12.9911 15.6993 12.6785 16.0118C12.366 16.3244 11.942 16.5 11.5 16.5H6.5C6.05797 16.5 5.63405 16.3244 5.32149 16.0118C5.00893 15.6993 4.83333 15.2754 4.83333 14.8333V11.5Z',
        ]

    ];
    public $validationRules = [
        // 관리자
        'i_userid' => ['trim', 'strip_tags', 'required', 'alpha_numeric', 'min_length[4]', 'max_length[16]'],
        'i_password' => ['trim', 'required', 'min_length[8]', 'differs[i_used_password]'],
        'i_mb_password' => ['trim', 'required', 'min_length[8]', 'differs[i_mb_password]'],
        'i_password_confirm' => ['trim', 'required', 'min_length[8]', 'matches[i_password]'],
        'i_username' => ['trim', 'strip_tags', 'required', 'min_length[2]', 'max_length[20]'],
        'i_mobile_num' => ['trim', 'required', 'numeric', 'min_length[10]', 'max_length[11]'],
        'i_mb_userid' => ['trim', 'strip_tags', 'required', 'alpha_numeric', 'min_length[4]', 'max_length[16]'],
        'i_mb_username' => ['trim', 'strip_tags', 'required', 'min_length[2]', 'max_length[20]'],
        'i_mb_mobile_num' => ['trim', 'required', 'numeric', 'min_length[10]'],
        'i_mb_used_password' => ['trim', 'required', 'min_length[8]'],
        'i_mb_password' => ['trim', 'required', 'min_length[8]'],
        'i_mb_password_confirm' => ['trim', 'required', 'min_length[8]', 'matches[mb_password]'],
        'i_certify_auth_num' => ['trim', 'required'],
        'i_mobile_auth_num' => ['trim', 'required'],

        // 관리자 쿠폰관련
        'coupon' => [
            'cpType' => ['trim', 'required', 'numeric'],
            'isType' => ['trim', 'required', 'numeric'],
            'autoissue' => ['trim', 'required'],
            'couponName' => ['trim', 'required', 'min_length[2]', 'max_length[50]'],
            'isWhen' => ['trim', 'required'],
            'publishStartDate' => ['trim', 'required', 'max_length[10]'],
            'publishEndDate' => ['trim', 'required', 'max_length[10]'],
            'useLimitPrice' => ['trim', 'required'],
            'accType' => ['trim', 'required'],
            'usePriceCut' => ['trim', 'required'],
            'usePriceAct' => ['trim', 'required'],
            'minLimitPrice' => ['trim', 'required'],
            'validityTime' => ['trim', 'required'],
            'useExpDate' => ['trim', 'max_length[10]'],
            'useExpStartDate' => ['trim', 'required', 'max_length[10]'],
            'useExpEndDate' => ['trim', 'required', 'max_length[10]'],
            'cpDevice' => ['trim', 'required'],
            'payLimit' => ['trim', 'required'],
            'payType' => ['trim', 'required'],
            'useMem' => ['trim', 'required'],
            'selectGrade' => ['trim', 'required'],
            'countLimit' => ['trim', 'required'],
            'countLimitValue' => ['trim', 'required'],
            'useRange' => ['trim', 'required'],
            'selectSupply' => ['trim', 'required'],
            'selectCate' => ['trim', 'required'],
            'selectProduct' => ['trim', 'required'],
            'couponOverlap' => ['trim', 'required'],
            'coupon_status' => ['trim', 'required'],
        ],

        // 관리자 쿠폰 설정 관련
        'couponConfig' => [
            'cpUse' => ['trim', 'required', 'numeric'],
            'cpOverlap' => ['trim', 'required', 'numeric'],
            'cpCancel' => ['trim', 'required', 'numeric'],
            'cpReturn' => ['trim', 'required', 'numeric'],
            'cpChange' => ['trim', 'required', 'numeric'],
        ],

        // 관리자 팝업 설정 관련
        'popup' => [
            'popup_where' => ['trim', 'required'],
            'popup_show' => ['trim', 'required'],
            'popup_start_date' => ['trim', 'required'],
            'popup_end_date' => ['trim', 'required'],
            'popup_content' => ['trim', 'required'],
            'popup_link' => ['trim'],
            'popup_width' => ['trim', 'integer'],
            'popup_height' => ['trim', 'integer'],
            'popup_day_close' => ['trim', 'numeric'],
        ],

        // 관리자 상품판매일시 설정 관련
        'prd' => [
            'i_start_date' => ['trim', 'required'],
            'i_end_date' => ['trim', 'required'],
        ],

        // 관리자 상점정보
        'shop' => [
            'i_name' => ['trim', 'required'],
            'i_code' => ['trim', 'required'],
            'i_privacy_name' => ['trim', 'required'],
            'i_privacy_tel_number' => ['trim', 'strip_tags', 'required', 'min_length[8]', 'max_length[11]'],
        ],

        // 관리자 메뉴
        'menu' => [
            'i_name' => ['trim', 'required'],
            'i_code' => ['trim', 'required', 'numeric'],
        ],

        // 관리자 회원등급
        'grade' => [
            'i_name' => ['trim', 'required'],
            'i_mileage' => ['trim', 'required', 'numeric'],
        ],
    ];



}
