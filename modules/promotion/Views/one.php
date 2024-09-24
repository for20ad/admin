<!doctype html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="/plugins/bootstrap-5/css/bootstrap.css" />
    <link href="/dist/css/tabler.css" rel="stylesheet" />
    <link href="/dist/css/tabler-flags.css" rel="stylesheet" />
    <link href="/dist/css/tabler-payments.css" rel="stylesheet" />
    <link href="/dist/css/tabler-vendors.css" rel="stylesheet" />
    <link href="/dist/css/style.css" rel="stylesheet" />
    <link rel="shortcut icon" href="/dist/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/dist/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="/plugins/common/common.css" />
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
            <button type="button" class="btn btn-secondary">로그인 연장</button>

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
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M4 6H20M4 12H20M4 18H20" stroke="black" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
        <div>
            <img src="../../dist/img/logo_blue.png" width="110" alt="산수유람" class="navbar-brand-image">
        </div>
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

    <div class="d-flex">
        <!-- 사이드바 -->
        <div class="side-bar-area">
            <!-- 사이드바 (mini.ver) -->
            <div class="mini-side-bar">
                <!-- 메뉴 아이콘 -->
                <div class="icon-container">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M4 6H20M4 12H20M4 18H20" stroke="white" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>

                <!-- 홈 -->
                <div class="icon-container active">
                    <div class="background active">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M9 21V15C9 14.4696 9.21071 13.9609 9.58579 13.5858C9.96086 13.2107 10.4696 13 11 13H13C13.5304 13 14.0391 13.2107 14.4142 13.5858C14.7893 13.9609 15 14.4696 15 15V21M5 12H3L12 3L21 12H19V19C19 19.5304 18.7893 20.0391 18.4142 20.4142C18.0391 20.7893 17.5304 21 17 21H7C6.46957 21 5.96086 20.7893 5.58579 20.4142C5.21071 20.0391 5 19.5304 5 19V12Z"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

                <!-- 환경설정 -->
                <div class="icon-container">
                    <div class="background">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path
                                d="M8.325 2.317C8.751 0.561 11.249 0.561 11.675 2.317C11.7389 2.5808 11.8642 2.82578 12.0407 3.032C12.2172 3.23822 12.4399 3.39985 12.6907 3.50375C12.9414 3.60764 13.2132 3.65085 13.4838 3.62987C13.7544 3.60889 14.0162 3.5243 14.248 3.383C15.791 2.443 17.558 4.209 16.618 5.753C16.4769 5.98466 16.3924 6.24634 16.3715 6.51677C16.3506 6.78721 16.3938 7.05877 16.4975 7.30938C16.6013 7.55999 16.7627 7.78258 16.9687 7.95905C17.1747 8.13553 17.4194 8.26091 17.683 8.325C19.439 8.751 19.439 11.249 17.683 11.675C17.4192 11.7389 17.1742 11.8642 16.968 12.0407C16.7618 12.2172 16.6001 12.4399 16.4963 12.6907C16.3924 12.9414 16.3491 13.2132 16.3701 13.4838C16.3911 13.7544 16.4757 14.0162 16.617 14.248C17.557 15.791 15.791 17.558 14.247 16.618C14.0153 16.4769 13.7537 16.3924 13.4832 16.3715C13.2128 16.3506 12.9412 16.3938 12.6906 16.4975C12.44 16.6013 12.2174 16.7627 12.0409 16.9687C11.8645 17.1747 11.7391 17.4194 11.675 17.683C11.249 19.439 8.751 19.439 8.325 17.683C8.26108 17.4192 8.13578 17.1742 7.95929 16.968C7.7828 16.7618 7.56011 16.6001 7.30935 16.4963C7.05859 16.3924 6.78683 16.3491 6.51621 16.3701C6.24559 16.3911 5.98375 16.4757 5.752 16.617C4.209 17.557 2.442 15.791 3.382 14.247C3.5231 14.0153 3.60755 13.7537 3.62848 13.4832C3.64942 13.2128 3.60624 12.9412 3.50247 12.6906C3.3987 12.44 3.23726 12.2174 3.03127 12.0409C2.82529 11.8645 2.58056 11.7391 2.317 11.675C0.561 11.249 0.561 8.751 2.317 8.325C2.5808 8.26108 2.82578 8.13578 3.032 7.95929C3.23822 7.7828 3.39985 7.56011 3.50375 7.30935C3.60764 7.05859 3.65085 6.78683 3.62987 6.51621C3.60889 6.24559 3.5243 5.98375 3.383 5.752C2.443 4.209 4.209 2.442 5.753 3.382C6.753 3.99 8.049 3.452 8.325 2.317Z"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M7 10C7 10.7956 7.31607 11.5587 7.87868 12.1213C8.44129 12.6839 9.20435 13 10 13C10.7956 13 11.5587 12.6839 12.1213 12.1213C12.6839 11.5587 13 10.7956 13 10C13 9.20435 12.6839 8.44129 12.1213 7.87868C11.5587 7.31607 10.7956 7 10 7C9.20435 7 8.44129 7.31607 7.87868 7.87868C7.31607 8.44129 7 9.20435 7 10Z"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

                <!-- 회원 -->
                <div class="icon-container">
                    <div class="background">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="20" viewBox="0 0 14 20" fill="none">
                            <path
                                d="M1 19V17C1 15.9391 1.42143 14.9217 2.17157 14.1716C2.92172 13.4214 3.93913 13 5 13H9C10.0609 13 11.0783 13.4214 11.8284 14.1716C12.5786 14.9217 13 15.9391 13 17V19M3 5C3 6.06087 3.42143 7.07828 4.17157 7.82843C4.92172 8.57857 5.93913 9 7 9C8.06087 9 9.07828 8.57857 9.82843 7.82843C10.5786 7.07828 11 6.06087 11 5C11 3.93913 10.5786 2.92172 9.82843 2.17157C9.07828 1.42143 8.06087 1 7 1C5.93913 1 4.92172 1.42143 4.17157 2.17157C3.42143 2.92172 3 3.93913 3 5Z"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

                <!-- 상품 -->
                <div class="icon-container">
                    <div class="background">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M20 7.5L12 3L4 7.5M20 7.5V16.5L12 21M20 7.5L12 12M12 21L4 16.5V7.5M12 21V12M4 7.5L12 12M16 5.25L8 9.75"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

                <!-- 주문 -->
                <div class="icon-container">
                    <div class="background">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M6 17C6.53043 17 7.03914 17.2107 7.41421 17.5858C7.78929 17.9609 8 18.4696 8 19C8 19.5304 7.78929 20.0391 7.41421 20.4142C7.03914 20.7893 6.53043 21 6 21C5.46957 21 4.96086 20.7893 4.58579 20.4142C4.21071 20.0391 4 19.5304 4 19C4 18.4696 4.21071 17.9609 4.58579 17.5858C4.96086 17.2107 5.46957 17 6 17ZM6 17H17M6 17V3H4M17 17C17.5304 17 18.0391 17.2107 18.4142 17.5858C18.7893 17.9609 19 18.4696 19 19C19 19.5304 18.7893 20.0391 18.4142 20.4142C18.0391 20.7893 17.5304 21 17 21C16.4696 21 15.9609 20.7893 15.5858 20.4142C15.2107 20.0391 15 19.5304 15 19C15 18.4696 15.2107 17.9609 15.5858 17.5858C15.9609 17.2107 16.4696 17 17 17ZM6 5L20 6L19 13H6"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

                <!-- 디자인 -->
                <div class="icon-container">
                    <div class="background">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M8.2002 13.2C9.21789 10.505 10.9443 8.13474 13.1973 6.33944C15.4502 4.54414 18.146 3.3904 21.0002 3C20.6098 5.85418 19.4557 8.55002 17.6604 10.8029C15.8651 13.0559 13.4948 14.7823 10.7998 15.8M10.5996 9C12.5428 9.89687 14.1027 11.4568 14.9996 13.4M3 21V17C3 16.2089 3.2346 15.4355 3.67412 14.7777C4.11365 14.1199 4.73836 13.6072 5.46927 13.3045C6.20017 13.0017 7.00444 12.9225 7.78036 13.0769C8.55629 13.2312 9.26902 13.6122 9.82843 14.1716C10.3878 14.731 10.7688 15.4437 10.9231 16.2196C11.0775 16.9956 10.9983 17.7998 10.6955 18.5307C10.3928 19.2616 9.88008 19.8864 9.22228 20.3259C8.56448 20.7654 7.79113 21 7 21H3Z"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

                <!-- 프로모션 -->
                <div class="icon-container">
                    <div class="background">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M11.9998 17.75L5.82784 20.995L7.00684 14.122L2.00684 9.25495L8.90684 8.25495L11.9928 2.00195L15.0788 8.25495L21.9788 9.25495L16.9788 14.122L18.1578 20.995L11.9998 17.75Z"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

                <!-- 게시판 -->
                <div class="icon-container">
                    <div class="background">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M9 5H7C6.46957 5 5.96086 5.21071 5.58579 5.58579C5.21071 5.96086 5 6.46957 5 7V19C5 19.5304 5.21071 20.0391 5.58579 20.4142C5.96086 20.7893 6.46957 21 7 21H17C17.5304 21 18.0391 20.7893 18.4142 20.4142C18.7893 20.0391 19 19.5304 19 19V7C19 6.46957 18.7893 5.96086 18.4142 5.58579C18.0391 5.21071 17.5304 5 17 5H15M9 5C9 4.46957 9.21071 3.96086 9.58579 3.58579C9.96086 3.21071 10.4696 3 11 3H13C13.5304 3 14.0391 3.21071 14.4142 3.58579C14.7893 3.96086 15 4.46957 15 5M9 5C9 5.53043 9.21071 6.03914 9.58579 6.41421C9.96086 6.78929 10.4696 7 11 7H13C13.5304 7 14.0391 6.78929 14.4142 6.41421C14.7893 6.03914 15 5.53043 15 5"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

                <!-- 공급사 -->
                <div class="icon-container">
                    <div class="background">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M4.5 9H9.5M7 9V15M13 15V9L16 13L19 9V15" stroke="white" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

                <!-- 메세지 -->
                <div class="icon-container">
                    <div class="background">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M3 7C3 6.46957 3.21071 5.96086 3.58579 5.58579C3.96086 5.21071 4.46957 5 5 5H19C19.5304 5 20.0391 5.21071 20.4142 5.58579C20.7893 5.96086 21 6.46957 21 7M3 7V17C3 17.5304 3.21071 18.0391 3.58579 18.4142C3.96086 18.7893 4.46957 19 5 19H19C19.5304 19 20.0391 18.7893 20.4142 18.4142C20.7893 18.0391 21 17.5304 21 17V7M3 7L12 13L21 7"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

                <!-- 통계 -->
                <div class="icon-container">
                    <div class="background">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M9 19V13C9 12.7348 8.89464 12.4804 8.70711 12.2929C8.51957 12.1054 8.26522 12 8 12H4C3.73478 12 3.48043 12.1054 3.29289 12.2929C3.10536 12.4804 3 12.7348 3 13V19C3 19.2652 3.10536 19.5196 3.29289 19.7071C3.48043 19.8946 3.73478 20 4 20M9 19C9 19.2652 8.89464 19.5196 8.70711 19.7071C8.51957 19.8946 8.26522 20 8 20H4M9 19C9 19.2652 9.10536 19.5196 9.29289 19.7071C9.48043 19.8946 9.73478 20 10 20H14C14.2652 20 14.5196 19.8946 14.7071 19.7071C14.8946 19.5196 15 19.2652 15 19M9 19V9C9 8.73478 9.10536 8.48043 9.29289 8.29289C9.48043 8.10536 9.73478 8 10 8H14C14.2652 8 14.5196 8.10536 14.7071 8.29289C14.8946 8.48043 15 8.73478 15 9V19M4 20H18M15 19C15 19.2652 15.1054 19.5196 15.2929 19.7071C15.4804 19.8946 15.7348 20 16 20H20C20.2652 20 20.5196 19.8946 20.7071 19.7071C20.8946 19.5196 21 19.2652 21 19V5C21 4.73478 20.8946 4.48043 20.7071 4.29289C20.5196 4.10536 20.2652 4 20 4H16C15.7348 4 15.4804 4.10536 15.2929 4.29289C15.1054 4.48043 15 4.73478 15 5V19Z"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

                <!-- 로그아웃 -->
                <div class="icon-container bottom">
                    <div class="background">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M14 8V6C14 5.46957 13.7893 4.96086 13.4142 4.58579C13.0391 4.21071 12.5304 4 12 4H5C4.46957 4 3.96086 4.21071 3.58579 4.58579C3.21071 4.96086 3 5.46957 3 6V18C3 18.5304 3.21071 19.0391 3.58579 19.4142C3.96086 19.7893 4.46957 20 5 20H12C12.5304 20 13.0391 19.7893 13.4142 19.4142C13.7893 19.0391 14 18.5304 14 18V16M9 12H21M21 12L18 9M21 12L18 15"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        <!-- 대시보드 -->
        <div class="dashboard">
            <div class="dashboard-container">
                <div class="page-body">
                    <!-- 오늘의 매출 현황 -->
                    <div class="container-fluid" style="margin-bottom: 32px">

                        <!-- 카트 타이틀 -->
                        <div class="card-title">
                            <h3 class="h3-c">오늘의 매출 현황</h3>
                            <p class="body2-c" style="color: #616876">6월 27일 기준</p>
                        </div>

                        <div class="row row-deck row-cards">

                            <!-- 카드1 -->
                            <div class="col-md-4">
                                <div class="card sales-card">
                                    <div class="card-body">
                                        <div class="subtitle">
                                            <p>주문</p>
                                            <select class="selectbox form-select">
                                                <option selected>최근 7일간</option>
                                                <option value="1">14일</option>
                                                <option value="2">30일</option>
                                            </select>
                                        </div>
                                        <h2 class="h2-c">₩100,000원</h2>
                                        <div class="progress-title body2-c">
                                            <p>주문량</p>
                                            <div class="d-flex align-items-center">
                                                <span class="increase">
                                                    1건
                                                </span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 20 20" fill="none">
                                                    <path d="M2.5 14.1666L7.5 9.16659L10.8333 12.4999L17.5 5.83325"
                                                        stroke="#74B816" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M11.667 5.83325H17.5003V11.6666" stroke="#74B816"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="progress" style="height: 4px">
                                            <div class="progress-bar" style="width: 56%; background-color: #206BC4"
                                                role="progressbar" aria-valuenow="56" aria-valuemin="0"
                                                aria-valuemax="100" aria-label="56% Complete">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 카드2 -->
                            <div class="col-md-4">
                                <div class="card sales-card">
                                    <div class="card-body">
                                        <div class="subtitle">
                                            <p>결제</p>
                                            <select class="selectbox form-select">
                                                <option selected>최근 7일간</option>
                                                <option value="1">14일</option>
                                                <option value="2">30일</option>
                                            </select>
                                        </div>
                                        <h2 class="h2-c">₩100,000원</h2>
                                        <div class="progress-title body2-c">
                                            <p>주문량</p>
                                            <div class="d-flex align-items-center">
                                                <span class="stable">
                                                    3건
                                                </span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 20 20" fill="none">
                                                    <path d="M4.16699 10H15.8337" stroke="#F59F00"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="progress" style="height: 4px">
                                            <div class="progress-bar" style="width: 56%; background-color: #206BC4"
                                                role="progressbar" aria-valuenow="56" aria-valuemin="0"
                                                aria-valuemax="100" aria-label="56% Complete">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 카드3 -->
                            <div class="col-md-4">
                                <div class="card sales-card">
                                    <div class="card-body">
                                        <div class="subtitle">
                                            <p>주문</p>
                                            <select class="selectbox form-select">
                                                <option selected>최근 7일간</option>
                                                <option value="1">14일</option>
                                                <option value="2">30일</option>
                                            </select>
                                        </div>
                                        <h2 class="h2-c">₩100,000원</h2>
                                        <div class="progress-title body2-c">
                                            <p>주문량</p>
                                            <div class="d-flex align-items-center">
                                                <span class="decrease">
                                                    1건
                                                </span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 20 20" fill="none">
                                                    <path d="M2.5 5.83325L7.5 10.8333L10.8333 7.49992L17.5 14.1666"
                                                        stroke="#D63939" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M17.5003 8.33325V14.1666H11.667" stroke="#D63939"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="progress" style="height: 4px">
                                            <div class="progress-bar" style="width: 56%; background-color: #206BC4"
                                                role="progressbar" aria-valuenow="56" aria-valuemin="0"
                                                aria-valuemax="100" aria-label="56% Complete">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 주문 현황 -->
                    <div class="container-fluid" style="margin-bottom: 32px">
                        <!-- 카트 타이틀 -->
                        <div class="card-title">
                            <h3 class="h3-c">주문 현황</h3>
                            <p class="body2-c" style="color: #616876">최근 1개월 기준</p>
                        </div>

                        <div class="row row-deck row-cards">
                            <!-- 카드1 -->
                            <div class="col-md-3">
                                <div class="card icon-card">
                                    <div class="card-body">
                                        <div class="icon-container" style="background-color: #206BC4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M18 5H6C4.34315 5 3 6.34315 3 8V16C3 17.6569 4.34315 19 6 19H18C19.6569 19 21 17.6569 21 16V8C21 6.34315 19.6569 5 18 5Z"
                                                    stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M3 10H21" stroke="white" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M7 15H7.01" stroke="white" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M11 15H13" stroke="white" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="body2-c">
                                                결제완료
                                            </p>
                                            <p class="body2-c text-secondary" style="line-height: 20px">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 카드2 -->
                            <div class="col-md-3">
                                <div class="card icon-card">
                                    <div class="card-body">
                                        <div class="icon-container" style="background-color: #206BC4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24"
                                                viewBox="0 0 25 24" fill="none">
                                                <path
                                                    d="M10.365 20H7.75C7.21957 20 6.71086 19.7893 6.33579 19.4142C5.96071 19.0391 5.75 18.5304 5.75 18V6C5.75 5.46957 5.96071 4.96086 6.33579 4.58579C6.71086 4.21071 7.21957 4 7.75 4H15.75C16.2804 4 16.7891 4.21071 17.1642 4.58579C17.5393 4.96086 17.75 5.46957 17.75 6V14"
                                                    stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M14.75 19L16.75 21L20.75 17" stroke="white" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M9.75 8H13.75" stroke="white" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M9.75 12H11.75" stroke="white" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="body2-c">
                                                주문접수
                                            </p>
                                            <p class="body2-c text-secondary" style="line-height: 20px">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 카드3 -->
                            <div class="col-md-3">
                                <div class="card icon-card">
                                    <div class="card-body">
                                        <div class="icon-container" style="background-color: #206BC4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24"
                                                viewBox="0 0 25 24" fill="none">
                                                <path
                                                    d="M7.5 19C8.60457 19 9.5 18.1046 9.5 17C9.5 15.8954 8.60457 15 7.5 15C6.39543 15 5.5 15.8954 5.5 17C5.5 18.1046 6.39543 19 7.5 19Z"
                                                    stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M17.5 19C18.6046 19 19.5 18.1046 19.5 17C19.5 15.8954 18.6046 15 17.5 15C16.3954 15 15.5 15.8954 15.5 17C15.5 18.1046 16.3954 19 17.5 19Z"
                                                    stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M5.5 17H3.5V13M2.5 5H13.5V17M9.5 17H15.5M19.5 17H21.5V11H13.5M13.5 6H18.5L21.5 11"
                                                    stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M3.5 9H7.5" stroke="white" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="body2-c">
                                                배송준비중
                                            </p>
                                            <p class="body2-c text-secondary" style="line-height: 20px">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 카드4 -->
                            <div class="col-md-3">
                                <div class="card icon-card">
                                    <div class="card-body">
                                        <div class="icon-container" style="background-color: #206BC4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24"
                                                viewBox="0 0 25 24" fill="none">
                                                <path
                                                    d="M12.25 21C17.2206 21 21.25 16.9706 21.25 12C21.25 7.02944 17.2206 3 12.25 3C7.27944 3 3.25 7.02944 3.25 12C3.25 16.9706 7.27944 21 12.25 21Z"
                                                    stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M9.25 12L11.25 14L15.25 10" stroke="white" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="body2-c">
                                                배송완료
                                            </p>
                                            <p class="body2-c text-secondary" style="line-height: 20px">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 취소ㆍ교환ㆍ반품ㆍ환불ㆍ현황 -->
                    <div class="container-fluid" style="margin-bottom: 32px">
                        <!-- 카트 타이틀 -->
                        <div class="card-title">
                            <h3 class="h3-c">취소ㆍ교환ㆍ반품ㆍ환불ㆍ현황</h3>
                            <p class="body2-c" style="color: #616876">최근 1개월 기준</p>
                        </div>

                        <div class="row row-deck row-cards">
                            <!-- 카드1 -->
                            <div class="col-md-3">
                                <div class="card icon-card">
                                    <div class="card-body">
                                        <div class="icon-container" style="background: rgba(214, 57, 57, 0.10)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path d="M18 6L6 18" stroke="#D63939" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M6 6L18 18" stroke="#D63939" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="body2-c">
                                                취소
                                            </p>
                                            <p class="body2-c text-secondary" style="line-height: 20px">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 카드2 -->
                            <div class="col-md-3">
                                <div class="card icon-card">
                                    <div class="card-body">
                                        <div class="icon-container" style="background: rgba(214, 57, 57, 0.10)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24"
                                                viewBox="0 0 25 24" fill="none">
                                                <path
                                                    d="M4.75 12V9C4.75 8.20435 5.06607 7.44129 5.62868 6.87868C6.19129 6.31607 6.95435 6 7.75 6H20.75M17.75 3L20.75 6L17.75 9"
                                                    stroke="#D63939" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M20.75 12V15C20.75 15.7956 20.4339 16.5587 19.8713 17.1213C19.3087 17.6839 18.5456 18 17.75 18H4.75M7.75 21L4.75 18L7.75 15"
                                                    stroke="#D63939" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="body2-c">
                                                교환
                                            </p>
                                            <p class="body2-c text-secondary" style="line-height: 20px">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 카드3 -->
                            <div class="col-md-3">
                                <div class="card icon-card">
                                    <div class="card-body">
                                        <div class="icon-container" style="background: rgba(214, 57, 57, 0.10)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24"
                                                viewBox="0 0 25 24" fill="none">
                                                <path d="M12.5 21L4.5 16.5V7.5L12.5 3L20.5 7.5V12" stroke="#D63939"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M12.5 12L20.5 7.5" stroke="#D63939" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M12.5 12V21" stroke="#D63939" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M12.5 12L4.5 7.5" stroke="#D63939" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M22.5 18H15.5" stroke="#D63939" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M18.5 15L15.5 18L18.5 21" stroke="#D63939" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="body2-c">
                                                반품
                                            </p>
                                            <p class="body2-c text-secondary" style="line-height: 20px">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 카드4 -->
                            <div class="col-md-3">
                                <div class="card icon-card">
                                    <div class="card-body">
                                        <div class="icon-container" style="background: rgba(214, 57, 57, 0.10)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24"
                                                viewBox="0 0 25 24" fill="none">
                                                <path
                                                    d="M9.25 13L5.25 9M5.25 9L9.25 5M5.25 9H16.25C17.3109 9 18.3283 9.42143 19.0784 10.1716C19.8286 10.9217 20.25 11.9391 20.25 13C20.25 14.0609 19.8286 15.0783 19.0784 15.8284C18.3283 16.5786 17.3109 17 16.25 17H15.25"
                                                    stroke="#D63939" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="body2-c">
                                                반품
                                            </p>
                                            <p class="body2-c text-secondary" style="line-height: 20px">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 문의 및 답변관리 -->
                    <div class="container-fluid" style="margin-bottom: 32px">
                        <!-- 카트 타이틀 -->
                        <div class="card-title">
                            <h3 class="h3-c">문의 및 답변관리</h3>
                            <p class="body2-c" style="color: #616876">최근 1개월 기준</p>
                        </div>

                        <div class="row row-deck row-cards">
                            <!-- 카드1 -->
                            <div class="col-md-4">
                                <div class="card icon-card">
                                    <div class="card-body">
                                        <div class="icon-container" style="background: rgba(29, 39, 59, 0.10)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M7 7H6C5.46957 7 4.96086 7.21071 4.58579 7.58579C4.21071 7.96086 4 8.46957 4 9V18C4 18.5304 4.21071 19.0391 4.58579 19.4142C4.96086 19.7893 5.46957 20 6 20H15C15.5304 20 16.0391 19.7893 16.4142 19.4142C16.7893 19.0391 17 18.5304 17 18V17"
                                                    stroke="#1D273B" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M20.385 6.58499C20.7788 6.19114 21.0001 5.65697 21.0001 5.09998C21.0001 4.543 20.7788 4.00883 20.385 3.61498C19.9912 3.22114 19.457 2.99988 18.9 2.99988C18.343 2.99988 17.8088 3.22114 17.415 3.61498L9 12V15H12L20.385 6.58499V6.58499Z"
                                                    stroke="#1D273B" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M16 5L19 8" stroke="#1D273B" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="body2-c">
                                                상품후기
                                            </p>
                                            <p class="body2-c text-secondary" style="line-height: 20px">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 카드2 -->
                            <div class="col-md-4">
                                <div class="card icon-card">
                                    <div class="card-body">
                                        <div class="icon-container" style="background: rgba(29, 39, 59, 0.10)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path d="M9 6H20" stroke="#1D273B" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M9 12H20" stroke="#1D273B" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M9 18H20" stroke="#1D273B" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M5 6V6.01" stroke="#1D273B" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M5 12V12.01" stroke="#1D273B" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M5 18V18.01" stroke="#1D273B" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="body2-c">
                                                상품문의
                                            </p>
                                            <p class="body2-c text-secondary" style="line-height: 20px">0 / 0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 카드3 -->
                            <div class="col-md-4">
                                <div class="card icon-card">
                                    <div class="card-body">
                                        <div class="icon-container" style="background: rgba(29, 39, 59, 0.10)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M21 14L18 11H11C10.7348 11 10.4804 10.8946 10.2929 10.7071C10.1054 10.5196 10 10.2652 10 10V4C10 3.73478 10.1054 3.48043 10.2929 3.29289C10.4804 3.10536 10.7348 3 11 3H20C20.2652 3 20.5196 3.10536 20.7071 3.29289C20.8946 3.48043 21 3.73478 21 4V14Z"
                                                    stroke="#1D273B" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M14 15V17C14 17.2652 13.8946 17.5196 13.7071 17.7071C13.5196 17.8946 13.2652 18 13 18H6L3 21V11C3 10.7348 3.10536 10.4804 3.29289 10.2929C3.48043 10.1054 3.73478 10 4 10H6"
                                                    stroke="#1D273B" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="body2-c">
                                                1:1문의
                                            </p>
                                            <p class="body2-c text-secondary" style="line-height: 20px">0 / 0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- 공지사항 -->
                    <div class="container-fluid">
                        <div class="col">
                            <!-- 카드 타이틀 -->
                            <div class="card accordion-card" style="padding: 17px 24px; border-radius: 4px 4px 0 0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                            fill="none">
                                            <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                        </svg>
                                        <p class="body1-c ms-2 mt-1">
                                            공지사항
                                        </p>
                                    </div>
                                    <!-- 아코디언 토글 버튼 -->
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="icons" value="home" class="form-selectgroup-input"
                                            checked />
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

                            <!-- 테이블 -->
                            <div style="border:1px solid #E6E7E9; border-top: 0px; border-radius:0 0 4px 4px">
                                <div class="table-responsive">
                                    <table class="table table-vcenter">
                                        <thead>
                                            <tr>
                                                <th>제목</th>
                                                <th>날짜</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="body2-c nowrap">
                                                    <a href="#" class="text-reset">[공지] 4월 13일 시스템 점검 안내</a>
                                                </td>
                                                <td class="text-dark body2-c">
                                                    2024-05-24
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="body2-c nowrap">
                                                    <a href="#" class="text-reset">[공지] 4월 13일 시스템 점검 안내</a>
                                                </td>
                                                <td class="text-dark body2-c">
                                                    2024-05-24
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="body2-c nowrap">
                                                    <a href="#" class="text-reset">[공지] 4월 13일 시스템 점검 안내</a>
                                                </td>
                                                <td class="text-dark body2-c">
                                                    2024-05-24
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="body2-c nowrap">
                                                    <a href="#" class="text-reset">[공지] 4월 13일 시스템 점검 안내</a>
                                                </td>
                                                <td class="text-dark body2-c">
                                                    2024-05-24
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="pagination-wrapper">
                                    <!-- 페이지네이션 -->
                                    <ul class="pagination align-items-center body2-c">
                                        <li class="page-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 16 16" fill="none">
                                                <path d="M10 4L6 8L10 12" stroke="#ADB5BD" stroke-width="1.25"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </li>
                                        <li class="page-item active">1</li>
                                        <li class="page-item">2</li>
                                        <li class="page-item">3</li>
                                        <li class="page-item">4</li>
                                        <li class="page-item">5</li>
                                        <li class="page-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 16 16" fill="none">
                                                <path d="M6 4L10 8L6 12" stroke="#616876" stroke-width="1.25"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </li>
                                    </ul>

                                    <!-- 토탈 페이지 -->
                                    <div class="pagination-goto" style="gap: 8px">
                                        <p>
                                            페이지
                                        </p>
                                        <select class="form-select">
                                            <option selected>1</option>
                                            <option value="1">2</option>
                                            <option value="2">3</option>
                                            <option value="3">4</option>
                                        </select>
                                        <p>총 120</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- 푸터 -->
            <div class="footer">
                <p>
                    Copyright© (주)팀버앤브릭스 All Right Reserved.
                </p>
                <p>
                    aaa
                </p>
            </div>
        </div>
    </div>
</body>

</html>

<div class="mini-side-bar">
    <!-- 메뉴 아이콘 -->
    <div class="icon-container">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M4 6H20M4 12H20M4 18H20" stroke="white" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
    </div>

    <!-- 홈 -->
    <div class="icon-container active">
        <div class="background active">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path
                    d="M9 21V15C9 14.4696 9.21071 13.9609 9.58579 13.5858C9.96086 13.2107 10.4696 13 11 13H13C13.5304 13 14.0391 13.2107 14.4142 13.5858C14.7893 13.9609 15 14.4696 15 15V21M5 12H3L12 3L21 12H19V19C19 19.5304 18.7893 20.0391 18.4142 20.4142C18.0391 20.7893 17.5304 21 17 21H7C6.46957 21 5.96086 20.7893 5.58579 20.4142C5.21071 20.0391 5 19.5304 5 19V12Z"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>

    <!-- 환경설정 -->
    <div class="icon-container">
        <div class="background">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path
                    d="M8.325 2.317C8.751 0.561 11.249 0.561 11.675 2.317C11.7389 2.5808 11.8642 2.82578 12.0407 3.032C12.2172 3.23822 12.4399 3.39985 12.6907 3.50375C12.9414 3.60764 13.2132 3.65085 13.4838 3.62987C13.7544 3.60889 14.0162 3.5243 14.248 3.383C15.791 2.443 17.558 4.209 16.618 5.753C16.4769 5.98466 16.3924 6.24634 16.3715 6.51677C16.3506 6.78721 16.3938 7.05877 16.4975 7.30938C16.6013 7.55999 16.7627 7.78258 16.9687 7.95905C17.1747 8.13553 17.4194 8.26091 17.683 8.325C19.439 8.751 19.439 11.249 17.683 11.675C17.4192 11.7389 17.1742 11.8642 16.968 12.0407C16.7618 12.2172 16.6001 12.4399 16.4963 12.6907C16.3924 12.9414 16.3491 13.2132 16.3701 13.4838C16.3911 13.7544 16.4757 14.0162 16.617 14.248C17.557 15.791 15.791 17.558 14.247 16.618C14.0153 16.4769 13.7537 16.3924 13.4832 16.3715C13.2128 16.3506 12.9412 16.3938 12.6906 16.4975C12.44 16.6013 12.2174 16.7627 12.0409 16.9687C11.8645 17.1747 11.7391 17.4194 11.675 17.683C11.249 19.439 8.751 19.439 8.325 17.683C8.26108 17.4192 8.13578 17.1742 7.95929 16.968C7.7828 16.7618 7.56011 16.6001 7.30935 16.4963C7.05859 16.3924 6.78683 16.3491 6.51621 16.3701C6.24559 16.3911 5.98375 16.4757 5.752 16.617C4.209 17.557 2.442 15.791 3.382 14.247C3.5231 14.0153 3.60755 13.7537 3.62848 13.4832C3.64942 13.2128 3.60624 12.9412 3.50247 12.6906C3.3987 12.44 3.23726 12.2174 3.03127 12.0409C2.82529 11.8645 2.58056 11.7391 2.317 11.675C0.561 11.249 0.561 8.751 2.317 8.325C2.5808 8.26108 2.82578 8.13578 3.032 7.95929C3.23822 7.7828 3.39985 7.56011 3.50375 7.30935C3.60764 7.05859 3.65085 6.78683 3.62987 6.51621C3.60889 6.24559 3.5243 5.98375 3.383 5.752C2.443 4.209 4.209 2.442 5.753 3.382C6.753 3.99 8.049 3.452 8.325 2.317Z"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path
                    d="M7 10C7 10.7956 7.31607 11.5587 7.87868 12.1213C8.44129 12.6839 9.20435 13 10 13C10.7956 13 11.5587 12.6839 12.1213 12.1213C12.6839 11.5587 13 10.7956 13 10C13 9.20435 12.6839 8.44129 12.1213 7.87868C11.5587 7.31607 10.7956 7 10 7C9.20435 7 8.44129 7.31607 7.87868 7.87868C7.31607 8.44129 7 9.20435 7 10Z"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>

    <!-- 회원 -->
    <div class="icon-container">
        <div class="background">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="20" viewBox="0 0 14 20" fill="none">
                <path
                    d="M1 19V17C1 15.9391 1.42143 14.9217 2.17157 14.1716C2.92172 13.4214 3.93913 13 5 13H9C10.0609 13 11.0783 13.4214 11.8284 14.1716C12.5786 14.9217 13 15.9391 13 17V19M3 5C3 6.06087 3.42143 7.07828 4.17157 7.82843C4.92172 8.57857 5.93913 9 7 9C8.06087 9 9.07828 8.57857 9.82843 7.82843C10.5786 7.07828 11 6.06087 11 5C11 3.93913 10.5786 2.92172 9.82843 2.17157C9.07828 1.42143 8.06087 1 7 1C5.93913 1 4.92172 1.42143 4.17157 2.17157C3.42143 2.92172 3 3.93913 3 5Z"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>

    <!-- 상품 -->
    <div class="icon-container">
        <div class="background">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path
                    d="M20 7.5L12 3L4 7.5M20 7.5V16.5L12 21M20 7.5L12 12M12 21L4 16.5V7.5M12 21V12M4 7.5L12 12M16 5.25L8 9.75"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>

    <!-- 주문 -->
    <div class="icon-container">
        <div class="background">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path
                    d="M6 17C6.53043 17 7.03914 17.2107 7.41421 17.5858C7.78929 17.9609 8 18.4696 8 19C8 19.5304 7.78929 20.0391 7.41421 20.4142C7.03914 20.7893 6.53043 21 6 21C5.46957 21 4.96086 20.7893 4.58579 20.4142C4.21071 20.0391 4 19.5304 4 19C4 18.4696 4.21071 17.9609 4.58579 17.5858C4.96086 17.2107 5.46957 17 6 17ZM6 17H17M6 17V3H4M17 17C17.5304 17 18.0391 17.2107 18.4142 17.5858C18.7893 17.9609 19 18.4696 19 19C19 19.5304 18.7893 20.0391 18.4142 20.4142C18.0391 20.7893 17.5304 21 17 21C16.4696 21 15.9609 20.7893 15.5858 20.4142C15.2107 20.0391 15 19.5304 15 19C15 18.4696 15.2107 17.9609 15.5858 17.5858C15.9609 17.2107 16.4696 17 17 17ZM6 5L20 6L19 13H6"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>

    <!-- 디자인 -->
    <div class="icon-container">
        <div class="background">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path
                    d="M8.2002 13.2C9.21789 10.505 10.9443 8.13474 13.1973 6.33944C15.4502 4.54414 18.146 3.3904 21.0002 3C20.6098 5.85418 19.4557 8.55002 17.6604 10.8029C15.8651 13.0559 13.4948 14.7823 10.7998 15.8M10.5996 9C12.5428 9.89687 14.1027 11.4568 14.9996 13.4M3 21V17C3 16.2089 3.2346 15.4355 3.67412 14.7777C4.11365 14.1199 4.73836 13.6072 5.46927 13.3045C6.20017 13.0017 7.00444 12.9225 7.78036 13.0769C8.55629 13.2312 9.26902 13.6122 9.82843 14.1716C10.3878 14.731 10.7688 15.4437 10.9231 16.2196C11.0775 16.9956 10.9983 17.7998 10.6955 18.5307C10.3928 19.2616 9.88008 19.8864 9.22228 20.3259C8.56448 20.7654 7.79113 21 7 21H3Z"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>

    <!-- 프로모션 -->
    <div class="icon-container">
        <div class="background">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path
                    d="M11.9998 17.75L5.82784 20.995L7.00684 14.122L2.00684 9.25495L8.90684 8.25495L11.9928 2.00195L15.0788 8.25495L21.9788 9.25495L16.9788 14.122L18.1578 20.995L11.9998 17.75Z"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>

    <!-- 게시판 -->
    <div class="icon-container">
        <div class="background">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path
                    d="M9 5H7C6.46957 5 5.96086 5.21071 5.58579 5.58579C5.21071 5.96086 5 6.46957 5 7V19C5 19.5304 5.21071 20.0391 5.58579 20.4142C5.96086 20.7893 6.46957 21 7 21H17C17.5304 21 18.0391 20.7893 18.4142 20.4142C18.7893 20.0391 19 19.5304 19 19V7C19 6.46957 18.7893 5.96086 18.4142 5.58579C18.0391 5.21071 17.5304 5 17 5H15M9 5C9 4.46957 9.21071 3.96086 9.58579 3.58579C9.96086 3.21071 10.4696 3 11 3H13C13.5304 3 14.0391 3.21071 14.4142 3.58579C14.7893 3.96086 15 4.46957 15 5M9 5C9 5.53043 9.21071 6.03914 9.58579 6.41421C9.96086 6.78929 10.4696 7 11 7H13C13.5304 7 14.0391 6.78929 14.4142 6.41421C14.7893 6.03914 15 5.53043 15 5"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>

    <!-- 공급사 -->
    <div class="icon-container">
        <div class="background">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M4.5 9H9.5M7 9V15M13 15V9L16 13L19 9V15" stroke="white" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>

    <!-- 메세지 -->
    <div class="icon-container">
        <div class="background">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path
                    d="M3 7C3 6.46957 3.21071 5.96086 3.58579 5.58579C3.96086 5.21071 4.46957 5 5 5H19C19.5304 5 20.0391 5.21071 20.4142 5.58579C20.7893 5.96086 21 6.46957 21 7M3 7V17C3 17.5304 3.21071 18.0391 3.58579 18.4142C3.96086 18.7893 4.46957 19 5 19H19C19.5304 19 20.0391 18.7893 20.4142 18.4142C20.7893 18.0391 21 17.5304 21 17V7M3 7L12 13L21 7"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>

    <!-- 통계 -->
    <div class="icon-container">
        <div class="background">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path
                    d="M9 19V13C9 12.7348 8.89464 12.4804 8.70711 12.2929C8.51957 12.1054 8.26522 12 8 12H4C3.73478 12 3.48043 12.1054 3.29289 12.2929C3.10536 12.4804 3 12.7348 3 13V19C3 19.2652 3.10536 19.5196 3.29289 19.7071C3.48043 19.8946 3.73478 20 4 20M9 19C9 19.2652 8.89464 19.5196 8.70711 19.7071C8.51957 19.8946 8.26522 20 8 20H4M9 19C9 19.2652 9.10536 19.5196 9.29289 19.7071C9.48043 19.8946 9.73478 20 10 20H14C14.2652 20 14.5196 19.8946 14.7071 19.7071C14.8946 19.5196 15 19.2652 15 19M9 19V9C9 8.73478 9.10536 8.48043 9.29289 8.29289C9.48043 8.10536 9.73478 8 10 8H14C14.2652 8 14.5196 8.10536 14.7071 8.29289C14.8946 8.48043 15 8.73478 15 9V19M4 20H18M15 19C15 19.2652 15.1054 19.5196 15.2929 19.7071C15.4804 19.8946 15.7348 20 16 20H20C20.2652 20 20.5196 19.8946 20.7071 19.7071C20.8946 19.5196 21 19.2652 21 19V5C21 4.73478 20.8946 4.48043 20.7071 4.29289C20.5196 4.10536 20.2652 4 20 4H16C15.7348 4 15.4804 4.10536 15.2929 4.29289C15.1054 4.48043 15 4.73478 15 5V19Z"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>

    <!-- 로그아웃 -->
    <div class="icon-container bottom">
        <div class="background">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path
                    d="M14 8V6C14 5.46957 13.7893 4.96086 13.4142 4.58579C13.0391 4.21071 12.5304 4 12 4H5C4.46957 4 3.96086 4.21071 3.58579 4.58579C3.21071 4.96086 3 5.46957 3 6V18C3 18.5304 3.21071 19.0391 3.58579 19.4142C3.96086 19.7893 4.46957 20 5 20H12C12.5304 20 13.0391 19.7893 13.4142 19.4142C13.7893 19.0391 14 18.5304 14 18V16M9 12H21M21 12L18 9M21 12L18 15"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>
</div>