$(document).ready(function() {
    /*
    * 초기 스토리지 체크시 마진 넣기 추가
     */
    if (localStorage.getItem('sideBarOpen') === 'true') {
        if (window.matchMedia("(min-width: 640px)").matches === true) {
            $('.mini-side-bar').toggle();
        }
        $('.side-bar').show();

        if($('.side-bar').is(':visible')){
            $('.dashboard').css( 'margin-left','290px' );
        }else{
            $('.dashboard').css( 'margin-left','53px' );
        }
    }else{
        $('.dashboard').css( 'margin-left','53px' );
    }

    $('.icon-container #sideToggle').on('click', function() {

        if (window.matchMedia("(min-width: 640px)").matches == true) {
            $('.mini-side-bar').toggle();
        }
        $('.side-bar').toggle(400);
        if($('.side-bar').is(':visible')){
            $('.dashboard').css( 'margin-left','290px' );
        }else{
            $('.dashboard').css( 'margin-left','53px' );
        }
        localStorage.setItem('sideBarOpen', 'true'); // 열림 상태 저장
    });
    $('#closeToggle').on('click', function() {
        if (window.matchMedia("(min-width: 640px)").matches == true) {
            $('.mini-side-bar').toggle(0);
        }
        $('.side-bar').toggle(0);
        if($('.side-bar').is(':visible')){
            $('.dashboard').css( 'margin-left','290px' );
        }else{
            $('.dashboard').css( 'margin-left','53px' );
        }
        localStorage.setItem('sideBarOpen', 'false'); // 닫힘 상태 저장
    });

    $('#header-sider-bar-toggle-btn').on('click', function() {
        //$('.side-bar-area').toggle(0);
        $('.side-bar').toggle(400);


    });
    $('.overlay').on('click', function(event) {
        if (!$(event.target).closest('.side-bar').length) {
            //$('.side-bar-area').toggle(0);
            $('.side-bar').toggle(400);
        }
    });

    // 하위 메뉴 열림 상태 유지
    console.log(localStorage.getItem('expandedSubMenus'));



    // 하위 메뉴 열림 상태 유지
    let expandedSubMenus = JSON.parse(localStorage.getItem('expandedSubMenus')) || [];
    expandedSubMenus = expandedSubMenus.filter(Boolean); // null 값 제거
    console.log('Loaded expandedSubMenus:', expandedSubMenus);

    expandedSubMenus.forEach(function(id) {
        const $child = $(`#${id}`);
        $child.addClass('active');
        $child.find('.arrow-icon').attr('d', 'M6.75 4.5L11.25 9L6.75 13.5');
        $child.find('.grand-child').show();
    });

    // 하위 메뉴 클릭 이벤트
    $('.child .name').on('click', function() {
        const $clickedChild = $(this).parent().parent();
        const clickedId = $clickedChild.attr('id');
        const clickedIndex = expandedSubMenus.indexOf(clickedId);
        const $clickedIcon = $(this).find('.arrow-icon');

        // 모든 하위 메뉴 닫기
        $('.child').each(function() {
            const $child = $(this);
            const id = $child.attr('id');
            if ($child.hasClass('active') && id !== clickedId) {
                $child.removeClass('active');
                $child.find('.arrow-icon').attr('d', 'M4.5 6.75L9 11.25L13.5 6.75');
                $child.find('.grand-child').slideUp();
                const index = expandedSubMenus.indexOf(id);
                if (index > -1) {
                    expandedSubMenus.splice(index, 1);
                }
            }
        });

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

        console.log('Saving expandedSubMenus:', expandedSubMenus);
        localStorage.setItem('expandedSubMenus', JSON.stringify(expandedSubMenus));
    });



});