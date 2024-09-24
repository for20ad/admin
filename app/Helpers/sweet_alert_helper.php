<?php
/**
 * @File   : sweet_alert_helper.php
 * @Date   : 2024-04-02 18:09:22
 * @Desc   : 자바스크립트 sweetalert2
*/
// -----------------------------------------------------------------------------
// Alert 스크립트 헤더
// -----------------------------------------------------------------------------
if (! function_exists('box_alert_header')) {
    function box_alert_header()
    {
        echo '
            <html lang="ko">
            <head>
                <meta http-equiv="content-type" content="text/html; charset=UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
                <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
                <script src="/plugins/jquery/jquery.min.js"></script>
                <link href="/plugins/sweetalert2/sweetalert2.min.css" type="text/css" rel="stylesheet" />
                <script src="/plugins/sweetalert2/sweetalert2.all.min.js"></script>
            </head>
            <body>
        ';
    }
}


// -----------------------------------------------------------------------------
// Alert 스크립트 푸터
// -----------------------------------------------------------------------------
if (! function_exists('box_alert_footer')) {
    function box_alert_footer()
    {
        echo '
            </body>
            </html>
        ';
    }
}


// -----------------------------------------------------------------------------
// Alert 스크립트 초기화
// -----------------------------------------------------------------------------
if (! function_exists('box_alert_init')) {
    function box_alert_init()
    {
        echo '
            <script src="/plugins/sweetalert2/box_alert.js"></script>
        ';
    }
}


// -----------------------------------------------------------------------------
// Alert 띄우기
// -----------------------------------------------------------------------------
if (! function_exists('box_alert')) {
    function box_alert($msg = '', $type = '', $title = '', $standAlone = false)
    {
        if ($standAlone === false) {
            box_alert_header();
        }

        box_alert_init();

        echo '
            <script type="text/javascript">
                box_alert("' . $msg . '", "' . $type . '", "' . $title . '");
            </script>
        ';

        if ($standAlone === false) {
            box_alert_footer();
        }
        exit;
    }
}


// -----------------------------------------------------------------------------
// Alert 띄우기 후 Back
// -----------------------------------------------------------------------------
if (! function_exists('box_alert_back')) {
    function box_alert_back($msg = '', $type = '', $title = '', $standAlone = false)
    {
        if ($standAlone === false) {
            box_alert_header();
        }

        box_alert_init();

        echo '
            <script type="text/javascript">
                box_alert_back("' . $msg . '", "' . $type . '", "' . $title . '");
            </script>
        ';

        if ($standAlone === false) {
            box_alert_footer();
        }
        exit;
    }
}


// -----------------------------------------------------------------------------
// Alert 띄우기 후 창 Close
// -----------------------------------------------------------------------------
if (! function_exists('box_alert_close'))
{
    function box_alert_close($msg = '', $type = '', $title = '', $standAlone = false)
    {
        if ($standAlone === false)
        {
            box_alert_header();
        }

        box_alert_init();

        echo '
            <script type="text/javascript">
                box_alert_close("' . $msg . '", "' . $type . '", "' . $title . '");
            </script>
        ';

        if ($standAlone === false)
        {
            box_alert_footer();
        }
        exit;
    }
}

// -----------------------------------------------------------------------------
// Alert 띄우기 후 창 Close
// -----------------------------------------------------------------------------
if (! function_exists('box_alert_target_close'))
{
    function box_alert_target_close($msg = '', $type = '', $title = '', $standAlone = false)
    {
        if ($standAlone === false)
        {
            box_alert_header();
        }

        box_alert_init();

        echo '
            <script type="text/javascript">
                box_alert("' . $msg . '", "' . $type . '", "' . $title . '").then((result) => {
                    if (result.isConfirmed) {
                        self.close();
                    }
                });
            </script>
        ';

        if ($standAlone === false)
        {
            box_alert_footer();
        }
        exit;
    }
}


// -----------------------------------------------------------------------------
// Alert 띄우기 후 redirect
// -----------------------------------------------------------------------------
if (! function_exists('box_alert_redirect'))
{
    function box_alert_redirect($msg = '', $url = '', $type = '', $title = '', $standAlone = false)
    {
        if ($standAlone === false)
        {
            box_alert_header();
        }

        box_alert_init();

        echo '
            <script type="text/javascript">
                box_alert_redirect("' . $msg . '", "' . $url . '", "' . $type . '", "' . $title . '");
            </script>
        ';

        if ($standAlone === false)
        {
            box_alert_footer();
        }
        exit;
    }
}


// -----------------------------------------------------------------------------
// Alert 띄우기 후 opener redirect
// -----------------------------------------------------------------------------
if (! function_exists('box_alert_opener_redirect'))
{
	function box_alert_opener_redirect($msg = '', $url = '', $type = '', $title = '', $standAlone = false)
	{
		if ($standAlone === false)
        {
			box_alert_header();
		}

		box_alert_init();

		echo '
            <script type="text/javascript">
                box_alert_opener_redirect("' . $msg . '", "' . $url . '", "' . $type . '", "' . $title . '");
            </script>
        ';

		if ($standAlone === false)
        {
			box_alert_footer();
		}
		exit;
	}
}


// -----------------------------------------------------------------------------
// Alert 띄우기 후 replace
// -----------------------------------------------------------------------------
if (! function_exists('box_alert_replace'))
{
    function box_alert_replace($msg = '', $url = '', $type = '', $title = '', $standAlone = false)
    {
        if ($standAlone === false)
        {
            box_alert_header();
        }

        box_alert_init();

        echo '
            <script type="text/javascript">
                box_alert_replace("' . $msg . '", "' . $url . '", "' . $type . '", "' . $title . '");
            </script>
        ';

        if ($standAlone === false)
        {
            box_alert_footer();
        }
        exit;
    }
}


// -----------------------------------------------------------------------------
// Alert 띄우기 후 reload
// -----------------------------------------------------------------------------
if (! function_exists('box_alert_reload')) {
    function box_alert_reload($msg = '', $type = '', $title = '', $standAlone = false)
    {
        if ($standAlone === false) {
            box_alert_header();
        }

        box_alert_init();

        echo '
            <script type="text/javascript">
                box_alert_reload("' . $msg . '", "' . $type . '", "' . $title . '");
            </script>
        ';

        if ($standAlone === false) {
            box_alert_footer();
        }
        exit;
    }
}
