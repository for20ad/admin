<!-- 헤더 -->
<div class="header">
    <div>
        <img src="/dist/img/logo_blue.png" width="110" alt="산수유람" class="navbar-brand-image">
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
            <div id="admin_auth_timer">02:00:00</div>
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
            <div class="icon-container">
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
    </div>


    <div class="side-bar" style="display:none">
        <svg id="closeToggle" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            style="margin: 16px 15px; cursor:pointer">
            <path d="M4 6H20M4 12H20M4 18H20" stroke="white" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
        <?php foreach($menus as $key => $menu):
                $topGroup = _elm($menu, 'MENU_GROUP_ID');
                ?>
        <div class="category <?php echo strpos($uriString, _elm($menu, 'MENU_GROUP_ID')) !== false ? 'active' : '';?>">
            <a
                href="<?php echo empty(_elm($menu, 'CHILD')) ? _link_url(_elm($menu, 'MENU_LINK')) : 'javascript:void(0)'; ?>">
                <div class="background ">
                    <div class="icon-container">
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
                    <span class="name"> <?php echo _elm($menu, 'MENU_NAME')?> </span>
                </div>
            </a>
            <?php if (!empty(_elm($menu, 'CHILD'))): ?>
            <?php foreach(_elm($menu, 'CHILD') as $subKey => $subMenu): ?>
            <div class="child" id="subMenu<?php echo _elm( $subMenu, 'MENU_IDX' )?>">
                <a
                    href="<?php echo empty(_elm($subMenu, 'CHILD')) ? _link_url(_elm($subMenu, 'MENU_LINK')) : 'javascript:void(0)'; ?>">
                    <div class="name">
                        <span> <?php echo _elm($subMenu, 'MENU_NAME')?></span>
                        <?php if( empty( _elm($subMenu, 'CHILD' ) ) === false ):?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M4.5 6.75L9 11.25L13.5 6.75" stroke="white" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" class="arrow-icon" />
                        </svg>
                        <?php endif;?>
                    </div>
                </a>
                <?php if (!empty(_elm($subMenu, 'CHILD'))): ?>
                <?php foreach(_elm($subMenu, 'CHILD') as $s_subKey => $s_subMenu):?>
                <div class="grand-child background <?php echo strpos($uriString, '/'.$topGroup.'/'._elm($s_subMenu, 'MENU_GROUP_ID')) !== false ? 'active' : '';?>"
                    style="display:none">
                    <a href="<?php echo _link_url(_elm($s_subMenu, 'MENU_LINK'))?>">
                        <span><?php echo _elm($s_subMenu, 'MENU_NAME')?></span>
                    </a>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <div class="category bottom">
            <a href="<?php echo _link_url('/logOut')?>">
                <div class="background">
                    <div class="icon-container">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M14 8V6C14 5.46957 13.7893 4.96086 13.4142 4.58579C13.0391 4.21071 12.5304 4 12 4H5C4.46957 4 3.96086 4.21071 3.58579 4.58579C3.21071 4.96086 3 5.46957 3 6V18C3 18.5304 3.21071 19.0391 3.58579 19.4142C3.96086 19.7893 4.46957 20 5 20H12C12.5304 20 13.0391 19.7893 13.4142 19.4142C13.7893 19.0391 14 18.5304 14 18V16M9 12H21M21 12L18 9M21 12L18 15"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <span class="name"> 로그아웃 </span>
                </div>
            </a>
        </div>
    </div>


    <div class="dashboard">
        <div class="dashboard-container">
            <div class="page-body">