<?php
    if (empty($pageDatas) == true)
    {
        $pageDatas = [];
    }
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <meta name="referrer" content="always">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no,address=no,email=no">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" /> -->
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
    <link rel="stylesheet" type="text/css" href="/plugins/sweetalert2/sweetalert2.custom.css" />
    <link rel="stylesheet" type="text/css" href="/plugins/toastr/toastr.min.css" />

    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" /> -->
    <!-- <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.6/dist/web/static/pretendard.css" /> -->

    <!-- <link rel="stylesheet" type="text/css" href="/assets/css/common.css" /> -->
    <!-- <link rel="stylesheet" type="text/css" href="/assets/css/contents.css" /> -->

    <!-- CSS files -->
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

    .active > .grand-child {
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
        //localStorage.removeItem('expandedSubMenus');
        if (localStorage.getItem('sideBarOpen') === 'true') {
            $('.side-bar-area').toggle();
            $('.side-bar').show();
        }
        $('.icon-container #sideToggle').on('click', function() {
            $('.side-bar-area').toggle();
            $('.side-bar').toggle(400);
            localStorage.setItem('sideBarOpen', 'true'); // 열림 상태 저장
        });
        $('#closeToggle').on('click', function() {
            $('.side-bar-area').toggle(0);
            $('.side-bar').toggle(0);
            localStorage.setItem('sideBarOpen', 'false'); // 닫힘 상태 저장
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
            $child.find('.arrow-icon').attr('d', 'M4.5 11.25L9 6.75L13.5 11.25');
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
                    $child.find('.arrow-icon').attr('d', 'M6.75 4.5L11.25 9L6.75 13.5');
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
                $clickedIcon.attr('d', 'M4.5 11.25L9 6.75L13.5 11.25');
                $clickedChild.find('.grand-child').slideDown();
                if (clickedIndex === -1) {
                    expandedSubMenus.push(clickedId);
                }
            } else {
                $clickedIcon.attr('d', 'M6.75 4.5L11.25 9L6.75 13.5');
                $clickedChild.find('.grand-child').slideUp();
                if (clickedIndex > -1) {
                    expandedSubMenus.splice(clickedIndex, 1);
                }
            }

            console.log('Saving expandedSubMenus:', expandedSubMenus);
            localStorage.setItem('expandedSubMenus', JSON.stringify(expandedSubMenus));
        });



    });
    </script>
</head>
<body>
<!-- <body class="hold-transition layout-navbar-fixed layout-fixed"> -->
