$(document).ready(function() {

    // 대시보드 마진 조정 함수
    function adjustDashboardMargin() {
        var sideBarAreaWidth = $('.side-bar-area').width();
        $('.dashboard').css('margin-left', sideBarAreaWidth + 'px');
        $('.dashboard').css('overflow', 'hidden');
        var sideBarWidth = $('.side-bar').width();

        if (localStorage.getItem('sideBarOpen') === 'true') {
            if (window.matchMedia("(min-width: 640px)").matches === true) {
                if ($('.side-bar').is(':visible')) {
                    $('.mini-side-bar').hide();

                    return;
                }
                $('.mini-side-bar').toggle();
            }

            $('.side-bar').show();
            $('.dashboard').animate({ 'margin-left': sideBarWidth + 'px' }, 0);
        }
    }

    // 초기 실행
    adjustDashboardMargin();

    // 화면 크기 변경 시 처리
    $(window).resize(function() {
        adjustDashboardMargin();
    });

    // 사이드바 토글 함수
    function toggleSideBar() {
        if (window.matchMedia("(min-width: 640px)").matches == true) {
            $('.mini-side-bar').toggle();
        }
        if ($('.side-bar').is(':visible')) {
            $('.side-bar').hide();
            $('.dashboard').animate({ 'margin-left': '53px' }, 200);
            $('.table-responsive.order-table').animate({ 'max-width': '1800px' }, 200);
            localStorage.setItem('sideBarOpen', 'false');
        } else {
            $('.side-bar').show("slide", { direction: "left" }, 400);
            $('.dashboard').animate({ 'margin-left': '290px' }, 400);
            $('.table-responsive.order-table').animate({ 'max-width': '1568px' }, 200);

            localStorage.setItem('sideBarOpen', 'true');
        }
    }

    // 사이드바 토글 이벤트
    $('.icon-container #sideToggle, #closeToggle, #header-sider-bar-toggle-btn').on('click', function() {
        toggleSideBar();
    });


    // 사이드바 바깥 클릭 시 처리
    $('.overlay').on('click', function(event) {
        if (!$(event.target).closest('.side-bar').length) {
            toggleSideBar();
        }
    });

    // 하위 메뉴 열림 상태 유지
    let expandedSubMenus = JSON.parse(localStorage.getItem('expandedSubMenus')) || [];


    expandedSubMenus = expandedSubMenus.filter(Boolean);
    expandedSubMenus.forEach(function(id) {
        const $child = $(`#${id}`);
        //$child.addClass('active');
        $child.find('.arrow-icon').attr('d', 'M6.75 4.5L11.25 9L6.75 13.5');
        $child.find('.grand-child').show();
    });

    // 하위 메뉴 클릭 이벤트
    $('.child .name').on('click', function() {
        const $clickedChild = $(this).parent().parent();
        const clickedId = $clickedChild.attr('id');
        const clickedIndex = expandedSubMenus.indexOf(clickedId);
        const $clickedIcon = $(this).find('.arrow-icon');

        // 클릭된 하위 메뉴 열기/닫기
        $clickedChild.toggleClass('active');
        if ($clickedChild.hasClass('active')) {
            $clickedIcon.attr('d', 'M6.75 4.5L11.25 9L6.75 13.5');
            $clickedChild.find('.grand-child').slideDown();
            if (clickedIndex === -1) {
                expandedSubMenus.push(clickedId);
            }
        } else {
            $clickedIcon.attr('d', 'M4.5 6.75L9 11.25L13.5 6.75');
            $clickedChild.find('.grand-child').slideUp();
            if (clickedIndex > -1) {
                expandedSubMenus.splice(clickedIndex, 1);
            }
        }
        localStorage.setItem('expandedSubMenus', JSON.stringify(expandedSubMenus));
    });

    // 상위 메뉴 클릭 이벤트
    $('.name.toggle').on('click', function(event) {
        event.preventDefault();
        const $clickedToggle = $(this).closest('.category').find('.child_group');
        $clickedToggle.slideToggle(200);
    });

    // 페이지 로드 시 현재 활성화된 메뉴의 최상위 항목을 열기
    $('.category').each(function() {
        if ($(this).hasClass('active')) {
            $(this).find('.child_group').show();
            let activeMenu = $(this).find('.background.active').attr('id');
            console.log(activeMenu);
        } else {
            $(this).find('.child_group').hide();
        }


    });
});
