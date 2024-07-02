<?php
    $requestUri = trim(_elm($_SERVER, 'REQUEST_URI', ''), '/');
    $requestUris = explode('/', $requestUri);
    $memberInfo = $this->session->get('_memberInfo');

    $vMENU_ALL_LISTS  = $menuLib->getAdminGroupMenu( _elm( $memberInfo, 'member_group_idx' ) );

?>

<header class="header-section">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <div class="header-logo"><a class="navbar-brand" href="/">산수유람</a></div>

            <button class="navbar-toggler ms-auto p-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="토글 메뉴">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-collapse collapse" id="navbarCollapse">
                <ul class="navbar-nav navbar-nav-scroll ms-auto">
                    <li class="nav-item"><a href="/"class="nav-link <?php echo in_array('main', $requestUris)? 'active' : ''; ?>"><i class="bi bi-house-door me-1"></i> 홈</a></li>
                    <li class="nav-item"><a href="/report"class="nav-link <?php echo in_array('report', $requestUris) ? 'active' : ''; ?>"><i class="bi bi-list-ul me-1"></i> 생산리스트</a></li>
                    <li class="nav-item"><a href="/kpi"class="nav-link <?php echo in_array('kpi', $requestUris) ? 'active' : ''; ?>"><i class="bi bi-file-bar-graph me-1"></i> KPI 지표</a></li>
                    <li class="nav-item"><a href="/download"class="nav-link <?php echo in_array('download', $requestUris) ? 'active' : ''; ?>"><i class="bi bi-download me-1"></i> 통계 다운로드</a></li>
                    <?php
                    if (_elm($memberInfo, 'member_level') == 9)
                    {
                    ?>
                    <li class="nav-item"><a href="/member"class="nav-link <?php echo in_array('member', $requestUris) ? 'active' : ''; ?>"><i class="bi bi-person-lines-fill me-1"></i> 관리자 관리</a></li>
                    <?php
                    }
                    ?>
                    <li class="nav-item"><a href="/member/logout"class="nav-link <?php echo in_array('logout', $requestUris) ? 'active' : ''; ?>"><i class="bi bi-box-arrow-right me-1"></i> 로그아웃</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Logo Nav END -->
</header>

