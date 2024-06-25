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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- No cache -->
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache; no-store; no-save">
    <!-- Summary -->
    <meta property="og:title" content="산사유람 ADMIN">
    <meta property="og:description" content="산수유람 ADMIN">

    <title>산수유람 ADMIN</title>

    <link rel="stylesheet" type="text/css" href="/plugins/bootstrap-5/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/plugins/jquery-ui/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/plugins/bootstrap-icons/bootstrap-icons.css" />

    <link rel="stylesheet" type="text/css" href="/plugins/jquery-modal/jquery.modal.min.css" />
    <link rel="stylesheet" type="text/css" href="/plugins/sweetalert2/sweetalert2.min.css" />
    <link rel="stylesheet" type="text/css" href="/plugins/toastr/toastr.min.css" />

    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" /> -->
    <!-- <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.6/dist/web/static/pretendard.css" /> -->

    <!-- <link rel="stylesheet" type="text/css" href="/assets/css/common.css" /> -->
    <!-- <link rel="stylesheet" type="text/css" href="/assets/css/contents.css" /> -->

    <!-- CSS files -->
    <link href="/dist/css/tabler.css" rel="stylesheet"/>
    <link href="/dist/css/tabler-flags.css" rel="stylesheet"/>
    <link href="/dist/css/tabler-payments.css" rel="stylesheet"/>
    <link href="/dist/css/tabler-vendors.css" rel="stylesheet"/>
    <link href="/dist/css/style.css" rel="stylesheet"/>
    <link rel="shortcut icon" href="/dist/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/dist/favicon.ico" type="image/x-icon">
    <script src="/dist/js/jquery-3.6.0.min.js"></script>

    <?php
    echo $this->getHeaderCss();
    ?>
    <script type="text/javascript" src="/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript">
    var site = '';
    <?php echo $this->getHeaderScriptVar(); ?>
    </script>
</head>
<body>
<!-- <body class="hold-transition layout-navbar-fixed layout-fixed"> -->
