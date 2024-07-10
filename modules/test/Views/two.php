<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <meta name="referrer" content="always">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no,address=no,email=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- No cache -->
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache; no-store; no-save">
    <!-- Summary -->
    <meta property="og:title" content="산사유람 ADMIN">
    <meta property="og:description" content="산수유람 ADMIN">
    <title>산수유람 ADMIN</title>
    <link rel="stylesheet" type="text/css" href="/plugins/jquery-ui/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/plugins/bootstrap-icons/bootstrap-icons.css" />
    <link rel="stylesheet" type="text/css" href="/plugins/jquery-modal/jquery.modal.min.css" />
    <link rel="stylesheet" type="text/css" href="/plugins/sweetalert2/sweetalert2.min.css" />
    <link rel="stylesheet" type="text/css" href="/plugins/toastr/toastr.min.css" />
    <link rel="stylesheet" type="text/css" href="/plugins/bootstrap-5/css/bootstrap.css" />
    <link href="/dist/css/tabler.css" rel="stylesheet" />
    <link href="/dist/css/tabler-flags.css" rel="stylesheet" />
    <link href="/dist/css/tabler-payments.css" rel="stylesheet" />
    <link href="/dist/css/tabler-vendors.css" rel="stylesheet" />
    <link href="/dist/css/style.css" rel="stylesheet" />
    <link rel="shortcut icon" href="/dist/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/dist/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="/plugins/common/common.css" />
    <script src="/dist/js/jquery-3.6.0.min.js"></script>
    <style>
    .active>.grand-child {
        display: block;
    }

    .arrow-up path {
        d: path("M6.75 4.5L11.25 9L6.75 13.5");
    }
    </style>
    <?php
    echo $this->getHeaderCss();
    ?>
    <script type="text/javascript" src="/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" <?php echo csp_script_nonce()?>>
    var site = '';
    <?php echo $this->getHeaderScriptVar(); ?>
    </script>

    <script>
    $(document).ready(function() {
        $('#closeToggle').on('click', function() {
            $('.side-bar-area').toggle(0);
            $('.side-bar').toggle(400);
        });
        $('#header-sider-bar-toggle-btn').on('click', function() {
            $('.side-bar-area').toggle(0);
            $('.side-bar').toggle(400);
        });

    });
    </script>
</head>

<body>

    <!-- 헤더 -->
    <div class="header">
        <div class="logo-container">
            <img src="/dist/img/logo_blue.svg" alt="산수유람" class="logo">
        </div>

        <!-- 우측 -->
        <div class="header-right">
            <!-- 셀렉트 박스 -->
            <div>
                <select class="form-select">
                    <option selected>상점선택</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>

            <!-- 로그인 남은 시간 -->
            <div class="countdown">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M5.41699 5.83325H14.5837" stroke="#616876" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M5 16.6667V15C5 13.6739 5.52678 12.4021 6.46447 11.4645C7.40215 10.5268 8.67392 10 10 10C11.3261 10 12.5979 10.5268 13.5355 11.4645C14.4732 12.4021 15 13.6739 15 15V16.6667C15 16.8877 14.9122 17.0996 14.7559 17.2559C14.5996 17.4122 14.3877 17.5 14.1667 17.5H5.83333C5.61232 17.5 5.40036 17.4122 5.24408 17.2559C5.0878 17.0996 5 16.8877 5 16.6667Z"
                            stroke="#616876" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M5 3.33333V5C5 6.32608 5.52678 7.59785 6.46447 8.53553C7.40215 9.47322 8.67392 10 10 10C11.3261 10 12.5979 9.47322 13.5355 8.53553C14.4732 7.59785 15 6.32608 15 5V3.33333C15 3.11232 14.9122 2.90036 14.7559 2.74408C14.5996 2.5878 14.3877 2.5 14.1667 2.5H5.83333C5.61232 2.5 5.40036 2.5878 5.24408 2.74408C5.0878 2.90036 5 3.11232 5 3.33333V3.33333Z"
                            stroke="#616876" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div>60:00</div>
            </div>
            <!-- 로그인 연장 버튼 -->
            <button type="button" class="btn btn-secondary" style="width:96px">로그인 연장</button>

            <!-- 구분선 -->
            <div class="divider-container">
                <div class="divider"></div>
            </div>

            <!-- 유저 정보 -->
            <div class="profile">
                <div class="profile-photo"></div>
                <div>
                    <div class="name">홍길동</div>
                    <div class="tier">최상위 관리</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 모바일 헤더 -->
    <div class="m-header">
        <!-- 메뉴 아이콘 -->
        <svg id="header-sider-bar-toggle-btn" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none">
            <path d="M4 6H20M4 12H20M4 18H20" stroke="black" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
        <!-- 로고 -->
        <div>
            <img src="../../dist/img/logo_blue.png" width="110" alt="산수유람" class="navbar-brand-image">
        </div>
        <div class="d-flex gap-2">
            <!-- 타이머 -->
            <button class="btn-sm btn-gray d-flex align-items-center" style="pading: 8px">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M5.41699 5.83331H14.5837" stroke="white" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M5 16.6667V15C5 13.6739 5.52678 12.4021 6.46447 11.4645C7.40215 10.5268 8.67392 10 10 10C11.3261 10 12.5979 10.5268 13.5355 11.4645C14.4732 12.4021 15 13.6739 15 15V16.6667C15 16.8877 14.9122 17.0996 14.7559 17.2559C14.5996 17.4122 14.3877 17.5 14.1667 17.5H5.83333C5.61232 17.5 5.40036 17.4122 5.24408 17.2559C5.0878 17.0996 5 16.8877 5 16.6667Z"
                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M5 3.33333V5C5 6.32608 5.52678 7.59785 6.46447 8.53553C7.40215 9.47322 8.67392 10 10 10C11.3261 10 12.5979 9.47322 13.5355 8.53553C14.4732 7.59785 15 6.32608 15 5V3.33333C15 3.11232 14.9122 2.90036 14.7559 2.74408C14.5996 2.5878 14.3877 2.5 14.1667 2.5H5.83333C5.61232 2.5 5.40036 2.5878 5.24408 2.74408C5.0878 2.90036 5 3.11232 5 3.33333V3.33333Z"
                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                01:10:00
            </button>
            <!-- 유저 아이콘 -->
            <div>
                <div class="user-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none">
                        <path
                            d="M1.66699 5.00002C1.66699 5.88408 2.01818 6.73192 2.6433 7.35704C3.26842 7.98216 4.11627 8.33335 5.00033 8.33335C5.88438 8.33335 6.73223 7.98216 7.35735 7.35704C7.98247 6.73192 8.33366 5.88408 8.33366 5.00002C8.33366 4.11597 7.98247 3.26812 7.35735 2.643C6.73223 2.01788 5.88438 1.66669 5.00033 1.66669C4.11627 1.66669 3.26842 2.01788 2.6433 2.643C2.01818 3.26812 1.66699 4.11597 1.66699 5.00002Z"
                            stroke="#206BC4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="8" viewBox="0 0 12 8" fill="none">
                        <path
                            d="M1 6.33331V4.66665C1 3.78259 1.35119 2.93475 1.97631 2.30962C2.60143 1.6845 3.44928 1.33331 4.33333 1.33331H7.66667C8.55072 1.33331 9.39857 1.6845 10.0237 2.30962C10.6488 2.93475 11 3.78259 11 4.66665V6.33331"
                            stroke="#206BC4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex">
        <!-- 사이드바 -->
        <div class="side-bar-area">
            <!-- 사이드바 (full.ver) -->
            <div class="side-bar-wrapper overlay">
                <div class="side-bar">
                    <!-- 최상단 메뉴 아이콘 -->
                    <svg id="closeToggle" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" style="margin: 16px 15px">
                        <path d="M4 6H20M4 12H20M4 18H20" stroke="white" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>

                    <!-- 홈 -->
                    <div class="category">
                        <div class="background">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M9 21V15C9 14.4696 9.21071 13.9609 9.58579 13.5858C9.96086 13.2107 10.4696 13 11 13H13C13.5304 13 14.0391 13.2107 14.4142 13.5858C14.7893 13.9609 15 14.4696 15 15V21M5 12H3L12 3L21 12H19V19C19 19.5304 18.7893 20.0391 18.4142 20.4142C18.0391 20.7893 17.5304 21 17 21H7C6.46957 21 5.96086 20.7893 5.58579 20.4142C5.21071 20.0391 5 19.5304 5 19V12Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="name"> 홈 </span>
                        </div>
                    </div>

                    <!-- 환경설정 -->
                    <div class="category active">
                        <div class="background">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path
                                        d="M8.325 2.317C8.751 0.561 11.249 0.561 11.675 2.317C11.7389 2.5808 11.8642 2.82578 12.0407 3.032C12.2172 3.23822 12.4399 3.39985 12.6907 3.50375C12.9414 3.60764 13.2132 3.65085 13.4838 3.62987C13.7544 3.60889 14.0162 3.5243 14.248 3.383C15.791 2.443 17.558 4.209 16.618 5.753C16.4769 5.98466 16.3924 6.24634 16.3715 6.51677C16.3506 6.78721 16.3938 7.05877 16.4975 7.30938C16.6013 7.55999 16.7627 7.78258 16.9687 7.95905C17.1747 8.13553 17.4194 8.26091 17.683 8.325C19.439 8.751 19.439 11.249 17.683 11.675C17.4192 11.7389 17.1742 11.8642 16.968 12.0407C16.7618 12.2172 16.6001 12.4399 16.4963 12.6907C16.3924 12.9414 16.3491 13.2132 16.3701 13.4838C16.3911 13.7544 16.4757 14.0162 16.617 14.248C17.557 15.791 15.791 17.558 14.247 16.618C14.0153 16.4769 13.7537 16.3924 13.4832 16.3715C13.2128 16.3506 12.9412 16.3938 12.6906 16.4975C12.44 16.6013 12.2174 16.7627 12.0409 16.9687C11.8645 17.1747 11.7391 17.4194 11.675 17.683C11.249 19.439 8.751 19.439 8.325 17.683C8.26108 17.4192 8.13578 17.1742 7.95929 16.968C7.7828 16.7618 7.56011 16.6001 7.30935 16.4963C7.05859 16.3924 6.78683 16.3491 6.51621 16.3701C6.24559 16.3911 5.98375 16.4757 5.752 16.617C4.209 17.557 2.442 15.791 3.382 14.247C3.5231 14.0153 3.60755 13.7537 3.62848 13.4832C3.64942 13.2128 3.60624 12.9412 3.50247 12.6906C3.3987 12.44 3.23726 12.2174 3.03127 12.0409C2.82529 11.8645 2.58056 11.7391 2.317 11.675C0.561 11.249 0.561 8.751 2.317 8.325C2.5808 8.26108 2.82578 8.13578 3.032 7.95929C3.23822 7.7828 3.39985 7.56011 3.50375 7.30935C3.60764 7.05859 3.65085 6.78683 3.62987 6.51621C3.60889 6.24559 3.5243 5.98375 3.383 5.752C2.443 4.209 4.209 2.442 5.753 3.382C6.753 3.99 8.049 3.452 8.325 2.317Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M7 10C7 10.7956 7.31607 11.5587 7.87868 12.1213C8.44129 12.6839 9.20435 13 10 13C10.7956 13 11.5587 12.6839 12.1213 12.1213C12.6839 11.5587 13 10.7956 13 10C13 9.20435 12.6839 8.44129 12.1213 7.87868C11.5587 7.31607 10.7956 7 10 7C9.20435 7 8.44129 7.31607 7.87868 7.87868C7.31607 8.44129 7 9.20435 7 10Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="name"> 환경설정 </span>
                        </div>

                        <!-- 하위 메뉴 -->
                        <div>
                            <!-- 상점정보 -->
                            <div class="child active">
                                <div class="name">
                                    <span> 상점정보 </span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                        fill="none">
                                        <path d="M4.5 6.75L9 11.25L13.5 6.75" stroke="white" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>

                                <!-- 하위 메뉴의 하위 메뉴 -->
                                <div class="grand-child background active">
                                    <span>상점목록</span>
                                </div>

                                <div class="grand-child background">
                                    <span>상점등록</span>
                                </div>

                                <div class="grand-child background">
                                    <span>이용약관</span>
                                </div>
                            </div>


                            <!-- 운영정책 -->
                            <div class="child">
                                <div class="name">
                                    <span> 운영정책 </span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                        fill="none">
                                        <path d="M6.75 4.5L11.25 9L6.75 13.5" stroke="white" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>

                            <!-- 회원관리 -->
                            <div class="child">
                                <div class="name">
                                    <span> 회원관리 </span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                        fill="none">
                                        <path d="M6.75 4.5L11.25 9L6.75 13.5" stroke="white" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>

                            <!-- 배송관리 -->
                            <div class="child">
                                <div class="name">
                                    <span> 배송관리 </span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                        fill="none">
                                        <path d="M6.75 4.5L11.25 9L6.75 13.5" stroke="white" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>

                            <!-- 관리자관리 -->
                            <div class="child">
                                <div class="name">
                                    <span> 관리자관리 </span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                        fill="none">
                                        <path d="M6.75 4.5L11.25 9L6.75 13.5" stroke="white" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>

                            <!-- 메뉴관리 -->
                            <div class="child">
                                <div class="name">
                                    <span> 메뉴관리 </span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                        fill="none">
                                        <path d="M6.75 4.5L11.25 9L6.75 13.5" stroke="white" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 회원 -->
                    <div class="category">
                        <div class="background">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="20" viewBox="0 0 14 20"
                                    fill="none">
                                    <path
                                        d="M1 19V17C1 15.9391 1.42143 14.9217 2.17157 14.1716C2.92172 13.4214 3.93913 13 5 13H9C10.0609 13 11.0783 13.4214 11.8284 14.1716C12.5786 14.9217 13 15.9391 13 17V19M3 5C3 6.06087 3.42143 7.07828 4.17157 7.82843C4.92172 8.57857 5.93913 9 7 9C8.06087 9 9.07828 8.57857 9.82843 7.82843C10.5786 7.07828 11 6.06087 11 5C11 3.93913 10.5786 2.92172 9.82843 2.17157C9.07828 1.42143 8.06087 1 7 1C5.93913 1 4.92172 1.42143 4.17157 2.17157C3.42143 2.92172 3 3.93913 3 5Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="name"> 회원 </span>
                        </div>
                    </div>

                    <!-- 상품 -->
                    <div class="category">
                        <div class="background">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M20 7.5L12 3L4 7.5M20 7.5V16.5L12 21M20 7.5L12 12M12 21L4 16.5V7.5M12 21V12M4 7.5L12 12M16 5.25L8 9.75"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="name"> 상품 </span>
                        </div>
                    </div>

                    <!-- 주문 -->
                    <div class="category">
                        <div class="background">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M6 17C6.53043 17 7.03914 17.2107 7.41421 17.5858C7.78929 17.9609 8 18.4696 8 19C8 19.5304 7.78929 20.0391 7.41421 20.4142C7.03914 20.7893 6.53043 21 6 21C5.46957 21 4.96086 20.7893 4.58579 20.4142C4.21071 20.0391 4 19.5304 4 19C4 18.4696 4.21071 17.9609 4.58579 17.5858C4.96086 17.2107 5.46957 17 6 17ZM6 17H17M6 17V3H4M17 17C17.5304 17 18.0391 17.2107 18.4142 17.5858C18.7893 17.9609 19 18.4696 19 19C19 19.5304 18.7893 20.0391 18.4142 20.4142C18.0391 20.7893 17.5304 21 17 21C16.4696 21 15.9609 20.7893 15.5858 20.4142C15.2107 20.0391 15 19.5304 15 19C15 18.4696 15.2107 17.9609 15.5858 17.5858C15.9609 17.2107 16.4696 17 17 17ZM6 5L20 6L19 13H6"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="name"> 주문 </span>
                        </div>
                    </div>

                    <!-- 디자인 -->
                    <div class="category">
                        <div class="background">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M8.2002 13.2C9.21789 10.505 10.9443 8.13474 13.1973 6.33944C15.4502 4.54414 18.146 3.3904 21.0002 3C20.6098 5.85418 19.4557 8.55002 17.6604 10.8029C15.8651 13.0559 13.4948 14.7823 10.7998 15.8M10.5996 9C12.5428 9.89687 14.1027 11.4568 14.9996 13.4M3 21V17C3 16.2089 3.2346 15.4355 3.67412 14.7777C4.11365 14.1199 4.73836 13.6072 5.46927 13.3045C6.20017 13.0017 7.00444 12.9225 7.78036 13.0769C8.55629 13.2312 9.26902 13.6122 9.82843 14.1716C10.3878 14.731 10.7688 15.4437 10.9231 16.2196C11.0775 16.9956 10.9983 17.7998 10.6955 18.5307C10.3928 19.2616 9.88008 19.8864 9.22228 20.3259C8.56448 20.7654 7.79113 21 7 21H3Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="name"> 디자인 </span>
                        </div>
                    </div>

                    <!-- 프로모션 -->
                    <div class="category">
                        <div class="background">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M11.9998 17.75L5.82784 20.995L7.00684 14.122L2.00684 9.25495L8.90684 8.25495L11.9928 2.00195L15.0788 8.25495L21.9788 9.25495L16.9788 14.122L18.1578 20.995L11.9998 17.75Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="name"> 프로모션 </span>
                        </div>
                    </div>

                    <!-- 게시판 -->
                    <div class="category">
                        <div class="background">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M9 5H7C6.46957 5 5.96086 5.21071 5.58579 5.58579C5.21071 5.96086 5 6.46957 5 7V19C5 19.5304 5.21071 20.0391 5.58579 20.4142C5.96086 20.7893 6.46957 21 7 21H17C17.5304 21 18.0391 20.7893 18.4142 20.4142C18.7893 20.0391 19 19.5304 19 19V7C19 6.46957 18.7893 5.96086 18.4142 5.58579C18.0391 5.21071 17.5304 5 17 5H15M9 5C9 4.46957 9.21071 3.96086 9.58579 3.58579C9.96086 3.21071 10.4696 3 11 3H13C13.5304 3 14.0391 3.21071 14.4142 3.58579C14.7893 3.96086 15 4.46957 15 5M9 5C9 5.53043 9.21071 6.03914 9.58579 6.41421C9.96086 6.78929 10.4696 7 11 7H13C13.5304 7 14.0391 6.78929 14.4142 6.41421C14.7893 6.03914 15 5.53043 15 5"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="name"> 게시판 </span>
                        </div>
                    </div>

                    <!-- 공급사 -->
                    <div class="category">
                        <div class="background">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path d="M4.5 9H9.5M7 9V15M13 15V9L16 13L19 9V15" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="name"> 공급사 </span>
                        </div>
                    </div>

                    <!-- 메세지 -->
                    <div class="category">
                        <div class="background">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M3 7C3 6.46957 3.21071 5.96086 3.58579 5.58579C3.96086 5.21071 4.46957 5 5 5H19C19.5304 5 20.0391 5.21071 20.4142 5.58579C20.7893 5.96086 21 6.46957 21 7M3 7V17C3 17.5304 3.21071 18.0391 3.58579 18.4142C3.96086 18.7893 4.46957 19 5 19H19C19.5304 19 20.0391 18.7893 20.4142 18.4142C20.7893 18.0391 21 17.5304 21 17V7M3 7L12 13L21 7"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="name"> 메세지 </span>
                        </div>
                    </div>

                    <!-- 통계 -->
                    <div class="category">
                        <div class="background">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M9 19V13C9 12.7348 8.89464 12.4804 8.70711 12.2929C8.51957 12.1054 8.26522 12 8 12H4C3.73478 12 3.48043 12.1054 3.29289 12.2929C3.10536 12.4804 3 12.7348 3 13V19C3 19.2652 3.10536 19.5196 3.29289 19.7071C3.48043 19.8946 3.73478 20 4 20M9 19C9 19.2652 8.89464 19.5196 8.70711 19.7071C8.51957 19.8946 8.26522 20 8 20H4M9 19C9 19.2652 9.10536 19.5196 9.29289 19.7071C9.48043 19.8946 9.73478 20 10 20H14C14.2652 20 14.5196 19.8946 14.7071 19.7071C14.8946 19.5196 15 19.2652 15 19M9 19V9C9 8.73478 9.10536 8.48043 9.29289 8.29289C9.48043 8.10536 9.73478 8 10 8H14C14.2652 8 14.5196 8.10536 14.7071 8.29289C14.8946 8.48043 15 8.73478 15 9V19M4 20H18M15 19C15 19.2652 15.1054 19.5196 15.2929 19.7071C15.4804 19.8946 15.7348 20 16 20H20C20.2652 20 20.5196 19.8946 20.7071 19.7071C20.8946 19.5196 21 19.2652 21 19V5C21 4.73478 20.8946 4.48043 20.7071 4.29289C20.5196 4.10536 20.2652 4 20 4H16C15.7348 4 15.4804 4.10536 15.2929 4.29289C15.1054 4.48043 15 4.73478 15 5V19Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="name"> 통계 </span>
                        </div>
                    </div>

                    <!-- 로그아웃 -->
                    <div class="category bottom">
                        <div class="background">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M14 8V6C14 5.46957 13.7893 4.96086 13.4142 4.58579C13.0391 4.21071 12.5304 4 12 4H5C4.46957 4 3.96086 4.21071 3.58579 4.58579C3.21071 4.96086 3 5.46957 3 6V18C3 18.5304 3.21071 19.0391 3.58579 19.4142C3.96086 19.7893 4.46957 20 5 20H12C12.5304 20 13.0391 19.7893 13.4142 19.4142C13.7893 19.0391 14 18.5304 14 18V16M9 12H21M21 12L18 9M21 12L18 15"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="name"> 로그아웃 </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 본문 -->
        <div class="dashboard">
            <div class="dashboard-container">
                <div class="page-body">
                    <div class="container-fluid">

                        <!-- 카트 타이틀 -->
                        <div class="card-title">
                            <h3 class="h3-c">정보 수정</h3>
                        </div>

                        <div class="row row-deck row-cards">

                            <!-- 카드1 -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="input-group required">
                                            <label class="label body2-c">
                                                아이디
                                                <span>*</span>
                                            </label>
                                            <input type="text" class="form-control" name="example-text-input"
                                                placeholder="admin1" disabled />
                                        </div>

                                        <div class="input-group required">
                                            <label class="label body2-c">
                                                비밀번호
                                                <span>*</span>
                                            </label>
                                            <button type="button" class="btn" style="width: 81px">변경하기</button>
                                        </div>

                                        <div class="input-group required">
                                            <label class="label body2-c">
                                                비밀번호
                                                <span>*</span>
                                            </label>
                                            <input type="password" class="form-control" name="example-text-input" />
                                        </div>

                                        <div class="input-group required">
                                            <label class="label body2-c">
                                                비밀번호 확인
                                                <span>*</span>
                                            </label>
                                            <input type="password" class="form-control" name="example-text-input" />
                                        </div>

                                        <div class="input-group required">
                                            <label class="label body2-c">
                                                이름
                                                <span>*</span>
                                            </label>
                                            <input type="text" class="form-control" placeholder="홍길동"
                                                style="border-top-right-radius:0px; border-bottom-right-radius: 0px" />
                                            <span class="input-group-text"
                                                style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                                3/20
                                            </span>
                                        </div>

                                        <div class="input-group required">
                                            <label class="label body2-c">
                                                휴대폰번호
                                                <span>*</span>
                                            </label>
                                            <input type="text" class="form-control" name="example-text-input" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 카드2 -->
                            <div class="col-12">
                                <div class="card">

                                    <!-- 카드 타이틀 -->
                                    <div class="card accordion-card"
                                        style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                                                    viewBox="0 0 4 4" fill="none">
                                                    <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                                </svg>
                                                <p class="body1-c ms-2 mt-1">
                                                    기본정보
                                                </p>
                                            </div>
                                            <!-- 아코디언 토글 버튼 -->
                                            <label class="form-selectgroup-item">
                                                <input type="radio" name="icons" value="home"
                                                    class="form-selectgroup-input" checked />
                                                <span class="form-selectgroup-label">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8"
                                                        viewBox="0 0 14 8" fill="none">
                                                        <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="input-group">
                                            <label class="label body2-c">
                                                전화번호
                                                <span>*</span>
                                            </label>
                                            <input type="text" class="form-control" name="example-text-input" />
                                        </div>

                                        <div class="input-group">
                                            <label class="label body2-c">
                                                이메일주소
                                                <span>*</span>
                                            </label>
                                            <input type="text" class="form-control" name="example-text-input" />
                                        </div>

                                        <div class="input-group">
                                            <label class="label body2-c">
                                                부서
                                                <span>*</span>
                                            </label>
                                            <input type="text" class="form-control" name="example-text-input" />
                                        </div>

                                        <div class="input-group">
                                            <label class="label body2-c">
                                                직급
                                                <span>*</span>
                                            </label>
                                            <input type="text" class="form-control" name="example-text-input" />
                                        </div>

                                        <div class="input-group">
                                            <label class="label body2-c">
                                                로그인제한
                                                <span>*</span>
                                            </label>
                                            <select class="form-select" style="max-width: 174px">
                                                <option selected>정상</option>
                                                <option value="1">비정상</option>
                                            </select>
                                        </div>
                                        <div class="input-group-bottom-text" style="margin-bottom: 0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                                                viewBox="0 0 6 6" fill="none">
                                                <circle cx="3" cy="3" r="3" fill="#616876" />
                                            </svg>
                                            상태가”대기”, “정지”인 경우 관리자가 접속할 수 없습니다
                                        </div>
                                        <div class="input-group-bottom-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                                                viewBox="0 0 6 6" fill="none">
                                                <circle cx="3" cy="3" r="3" fill="#616876" />
                                            </svg>
                                            로그인을 5회 연속으로 실패할 경우 자동으로 “정지”상태로 변경됩니다
                                        </div>

                                        <div class="input-group required" style="margin-bottom:0">
                                            <label class="label body2-c ">
                                                관리등급
                                                <span>*</span>
                                            </label>
                                            <div>
                                                <label class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="radios-inline"
                                                        checked="">
                                                    <span class="form-check-label">최상위관리</span>
                                                </label>
                                                <label class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="radios-inline">
                                                    <span class="form-check-label">쇼팡몰 운영</span>
                                                </label>
                                                <label class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="radios-inline">
                                                    <span class="form-check-label">MD관리</span>
                                                </label>
                                                <label class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="radios-inline">
                                                    <span class="form-check-label">시스템관리자</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="input-group-bottom-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path
                                                    d="M10 17.5C14.1421 17.5 17.5 14.1421 17.5 10C17.5 5.85786 14.1421 2.5 10 2.5C5.85786 2.5 2.5 5.85786 2.5 10C2.5 14.1421 5.85786 17.5 10 17.5Z"
                                                    fill="#616876" />
                                                <path d="M10 6.66667H10.0083" stroke="white" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M9.1665 10H9.99984V13.3333H10.8332" stroke="white"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            관리자 등급에 따른 세부적인 메뉴 접근권한은
                                            <a style="color: #1D273B">
                                                권한관리에서 확인 및 설정
                                            </a>
                                            이 가능합니다
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- 버튼 -->
                            <div style="text-align: center; margin-top: 52px">
                                <button type="button" class="btn" style="width: 180px; height: 46px">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20"
                                        fill="none">
                                        <path d="M8 5H17.1667" stroke="#1D273B" stroke-width="1.25"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M8 10H17.1667" stroke="#1D273B" stroke-width="1.25"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M8 15H17.1667" stroke="#1D273B" stroke-width="1.25"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M4.6665 5V5.00833" stroke="#1D273B" stroke-width="1.25"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M4.6665 10V10.0083" stroke="#1D273B" stroke-width="1.25"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M4.6665 15V15.0083" stroke="#1D273B" stroke-width="1.25"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    목록
                                </button>

                                <button class="btn btn-secondary" style="width: 180px; height: 46px">
                                    삭제
                                </button>

                                <button class="btn btn-success" style="width:180px; height: 46px">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20"
                                        fill="none">
                                        <path d="M4.6665 10L8.83317 14.1667L17.1665 5.83337" stroke="white"
                                            stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    저장
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 푸터 -->
            <div class="footer">
                <div class="d-flex justify-content-between">
                    <p>
                        Copyright© (주)팀버앤브릭스 All Right Reserved.
                    </p>
                    <p>
                        aaa
                    </p>
                </div>
            </div>
        </div>
    </div>



</body>

</html>