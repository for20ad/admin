<!-- 헤더 -->
<div class="header">
    <div class="logo-container">
        <img src="/dist/img/logo_blue.svg" width="110" alt="산수유람" class="logo">
    </div>
    <div class="header-right">
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
            <div class="admin_auth_timer">--:--:--</div>
        </div>
        <button type="button" class="btn btn-secondary" onclick="delay_login();">로그인 연장</button>
        <div class="divider-container">
            <div class="divider"></div>
        </div>
        <div class="profile">
            <div>
                <div class="name"><?php echo _elm($this->session->get('_memberInfo'), 'member_name')?></div>
                <div class="tier"><?php echo getGrantName()?></div>
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
        <img src="/dist/img/logo_blue.png" width="110" alt="산수유람" class="navbar-brand-image">
    </div>
    <div class="d-flex gap-2">
        <!-- 타이머 -->
        <button class="btn-sm btn-gray d-flex align-items-center" style="pading: 8px" onclick="delay_login();" >
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
            <span class="admin_auth_timer">--:--:--</span>
        </button>
        <!-- 유저 아이콘 -->
        <div class="position-relative">
            <div class="user-btn" onclick="$('#memberInfoLayer').toggle();">
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

            <!-- 드롭박스 -->
            <div class="dropbox" id="memberInfoLayer" style="display:none">
                <div class="dropbox-conateiner">
                    <div class="dropbox-menu">

                        <a href="<?php echo _link_url( '/setting/memberModify/'. _elm($this->session->get('_memberInfo'), 'member_idx') )?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                fill="none">
                                <path
                                    d="M2.5 10C2.5 10.9849 2.69399 11.9602 3.0709 12.8701C3.44781 13.7801 4.00026 14.6069 4.6967 15.3033C5.39314 15.9997 6.21993 16.5522 7.12987 16.9291C8.03982 17.306 9.01509 17.5 10 17.5C10.9849 17.5 11.9602 17.306 12.8701 16.9291C13.7801 16.5522 14.6069 15.9997 15.3033 15.3033C15.9997 14.6069 16.5522 13.7801 16.9291 12.8701C17.306 11.9602 17.5 10.9849 17.5 10C17.5 9.01509 17.306 8.03982 16.9291 7.12987C16.5522 6.21993 15.9997 5.39314 15.3033 4.6967C14.6069 4.00026 13.7801 3.44781 12.8701 3.0709C11.9602 2.69399 10.9849 2.5 10 2.5C9.01509 2.5 8.03982 2.69399 7.12987 3.0709C6.21993 3.44781 5.39314 4.00026 4.6967 4.6967C4.00026 5.39314 3.44781 6.21993 3.0709 7.12987C2.69399 8.03982 2.5 9.01509 2.5 10Z"
                                    stroke="#6C7A91" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M7.5 8.33331C7.5 8.99635 7.76339 9.63224 8.23223 10.1011C8.70107 10.5699 9.33696 10.8333 10 10.8333C10.663 10.8333 11.2989 10.5699 11.7678 10.1011C12.2366 9.63224 12.5 8.99635 12.5 8.33331C12.5 7.67027 12.2366 7.03439 11.7678 6.56555C11.2989 6.09671 10.663 5.83331 10 5.83331C9.33696 5.83331 8.70107 6.09671 8.23223 6.56555C7.76339 7.03439 7.5 7.67027 7.5 8.33331Z"
                                    stroke="#6C7A91" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M5.13965 15.7075C5.34591 15.021 5.76796 14.4193 6.34319 13.9916C6.91842 13.564 7.61619 13.3331 8.33298 13.3333H11.6663C12.384 13.3331 13.0827 13.5645 13.6583 13.9931C14.234 14.4218 14.6559 15.0248 14.8613 15.7125"
                                    stroke="#6C7A91" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            정보수정
                        </a>
                    </div>
                    <div class="dropbox-menu">
                        <a href="<?php echo _link_url( '/logOut' )?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                fill="none">
                                <path
                                    d="M11.6667 6.66665V4.99998C11.6667 4.55795 11.4911 4.13403 11.1785 3.82147C10.866 3.50891 10.442 3.33331 10 3.33331H4.16667C3.72464 3.33331 3.30072 3.50891 2.98816 3.82147C2.67559 4.13403 2.5 4.55795 2.5 4.99998V15C2.5 15.442 2.67559 15.8659 2.98816 16.1785C3.30072 16.4911 3.72464 16.6666 4.16667 16.6666H10C10.442 16.6666 10.866 16.4911 11.1785 16.1785C11.4911 15.8659 11.6667 15.442 11.6667 15V13.3333"
                                    stroke="#6C7A91" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M5.83301 10H17.4997M17.4997 10L14.9997 7.5M17.4997 10L14.9997 12.5"
                                    stroke="#6C7A91" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            로그아웃
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex">
    <?php
    $menus = getMemuData();
    $uriString = \Config\Services::request()->uri->getPath();
    ?>
    <div class="side-bar-area">

        <div class="mini-side-bar">
            <div class="icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    id="sideToggle" style="cursor:pointer">
                    <path d="M4 6H20M4 12H20M4 18H20" stroke="white" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
            <?php foreach($menus as $key => $menu): ?>
            <div class="icon-container  <?php echo strpos($uriString, _elm($menu, 'MENU_GROUP_ID')) !== false ? 'active' : '';?>" >
                <a href="<?php echo _link_url(_elm($menu, 'MENU_LINK'))?>">
                    <div
                        class="background <?php echo strpos($uriString, _elm($menu, 'MENU_GROUP_ID')) !== false ? 'active' : '';?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo _elm($menu, 'WIDTH')?>"
                            height="<?php echo _elm($menu, 'HEIGHT')?>" viewBox="<?php echo _elm($menu, 'VIEWBOX')?>"
                            fill="none">
                            <?php if (!empty(_elm($menu, 'PATH'))): ?>
                            <?php foreach(_elm($menu, 'PATH') as $path): ?>
                            <path d="<?= $path ?>" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </svg>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
            <div class="icon-container bottom">
                <a href="<?php echo _link_url('/logOut')?>">
                    <div class="background">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M14 8V6C14 5.46957 13.7893 4.96086 13.4142 4.58579C13.0391 4.21071 12.5304 4 12 4H5C4.46957 4 3.96086 4.21071 3.58579 4.58579C3.21071 4.96086 3 5.46957 3 6V18C3 18.5304 3.21071 19.0391 3.58579 19.4142C3.96086 19.7893 4.46957 20 5 20H12C12.5304 20 13.0391 19.7893 13.4142 19.4142C13.7893 19.0391 14 18.5304 14 18V16M9 12H21M21 12L18 9M21 12L18 15"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </a>
            </div>
        </div>

        <div class="side-bar-wrapper overlay">
            <div class="side-bar" style="display:none">
                <svg id="closeToggle" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" style="margin: 16px 15px; cursor:pointer">
                    <path d="M4 6H20M4 12H20M4 18H20" stroke="white" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <?php foreach($menus as $key => $menu):
                    $topGroup = _elm($menu, 'MENU_GROUP_ID');
                    ?>
                <div
                    class="category <?php echo strpos($uriString, _elm($menu, 'MENU_GROUP_ID')) !== false ? 'active' : '';?>">
                    <a
                        href="<?php echo empty(_elm($menu, 'CHILD')) ? _link_url(_elm($menu, 'MENU_LINK')) : 'javascript:void(0)'; ?>">
                        <div class="background ">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo _elm($menu, 'WIDTH')?>"
                                    height="<?php echo _elm($menu, 'HEIGHT')?>"
                                    viewBox="<?php echo _elm($menu, 'VIEWBOX')?>" fill="none">
                                    <?php if (!empty(_elm($menu, 'PATH'))): ?>
                                    <?php foreach(_elm($menu, 'PATH') as $path): ?>
                                    <path d="<?= $path ?>" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </svg>
                            </div>
                            <span class="name toggle" > <?php echo _elm($menu, 'MENU_NAME')?> </span>
                        </div>
                    </a>
                    <?php if (!empty(_elm($menu, 'CHILD'))): ?>
                    <span class="child_group">
                        <?php foreach(_elm($menu, 'CHILD') as $subKey => $subMenu): ?>
                        <div class="child" id="subMenu<?php echo _elm( $subMenu, 'MENU_IDX' )?>">
                            <a
                                href="<?php echo empty(_elm($subMenu, 'CHILD')) ? _link_url(_elm($subMenu, 'MENU_LINK')) : 'javascript:void(0)'; ?>">
                                <div class="name
                                <?php if ( empty(_elm($subMenu, 'CHILD') ) && strpos($uriString, '/'.$topGroup.'/'._elm($subMenu, 'MENU_GROUP_ID') ) !== false ){?> grand-child background active <?php }?>" >
                                    <span> <?php echo _elm($subMenu, 'MENU_NAME')?></span>
                                    <?php if( empty( _elm($subMenu, 'CHILD' ) ) === false ):?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                        fill="none">
                                        <path d="M6.75 4.5L11.25 9L6.75 13.5" stroke="white" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" class="arrow-icon" />
                                    </svg>
                                    <?php endif;?>
                                </div>
                            </a>
                            <?php if (!empty(_elm($subMenu, 'CHILD'))): ?>
                            <?php foreach(_elm($subMenu, 'CHILD') as $s_subKey => $s_subMenu):?>
                            <a href="<?php echo _link_url(_elm($s_subMenu, 'MENU_LINK'))?>">
                            <div class="grand-child background <?php echo strpos($uriString, '/'.$topGroup.'/'._elm($s_subMenu, 'MENU_GROUP_ID') ) !== false ? 'active' : '';?>"
                                style="display:none">
                                    <span><?php echo _elm($s_subMenu, 'MENU_NAME')?></span>
                            </div>
                            </a>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </span>
                </div>
                <?php endforeach; ?>

                <div class="category bottom">
                    <a href="<?php echo _link_url('/logOut')?>">
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
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard">
        <div class="dashboard-container">
            <div class="page-body">