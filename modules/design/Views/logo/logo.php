<?php
if (isset($pageDatas) === false) {
    $pageDatas = [];
}
$sQueryString = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];

$aConfig = _elm($pageDatas, 'aConfig', []);

$aLists = _elm($pageDatas, 'aDatas', []);
$aCateTreeLists = _elm($pageDatas, 'cate_tree_lists', []);

$aGetData = _elm($pageDatas, 'getData', []);
?>
<!-- jQuery and jQuery UI CDN -->
<link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>

<!--
<link href="/plugins/timepicker/dist/css/timepicker.css" rel="stylesheet">
<script src="/plugins/timepicker/dist/js/timepicker.js"></script>
-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- 본문 -->
<div class="container-fluid">
    <!-- 카드 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">로고 관리</h3>
    </div>

    <div class="row row-deck row-cards">
    <!-- 카드1 -->
        <div class="col-12">
            <div class="card">
                <div class="card accordion-card"
                        style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                                viewBox="0 0 4 4" fill="none">
                                <circle cx="2" cy="2" r="2" fill="#206BC4" />
                            </svg>
                            <p class="body1-c ms-2 mt-1">
                                기본로고
                            </p>
                        </div>
                        <!-- 아코디언 토글 버튼 -->
                        <label class="form-selectgroup-item"  onclick="toggleForm( $(this) )">
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
                    <div class="title-line">
                        로고이미지 *
                    </div>
                    <div class="group-flex-wrap" >
                        <div class="input-group" style="padding-top:1.8rem;">
                            <input type="file" class="form-control" name="i_default_logo" id="i_default_logo" />
                            <img src="/dist/img/logo_blue.svg">
                        </div>
                        <div style="margin-left: auto; text-align: right;">

                        </div>
                    </div>

                    <div class="input-group-bottom-text-a">
                        <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                            viewBox="0 0 6 6" fill="none">
                            <circle cx="3" cy="3" r="3" fill="#616876" />
                        </svg>
                        가로자유 * 세로60px 이하 | 용량 : 1M이하 | 확장자 : gif, png, jpg(jpeg)
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
                                특정로고
                            </p>
                        </div>
                        <!-- 아코디언 토글 버튼 -->
                        <label class="form-selectgroup-item"  onclick="toggleForm( $(this) )">
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
                    <div class="input-group required">
                        <label class="label body2-c">
                            노출기간
                        </label>
                        <div class="form-inline">
                            <div class="input-icon">
                                <input type="text" class="form-control  datetimepicker"
                                    name="i_period_start_date_time" readonly>
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <rect x="4" y="5" width="16" height="16" rx="2" />
                                        <line x1="16" y1="3" x2="16" y2="7" />
                                        <line x1="8" y1="3" x2="8" y2="7" />
                                        <line x1="4" y1="11" x2="20" y2="11" />
                                        <line x1="11" y1="15" x2="12" y2="15" />
                                        <line x1="12" y1="15" x2="12" y2="18" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        &nbsp;&nbsp;~&nbsp;&nbsp;
                        <div class="input-icon">
                                <input type="text" class="form-control  datetimepicker"
                                    name="i_period_start_date_time" readonly>
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <rect x="4" y="5" width="16" height="16" rx="2" />
                                        <line x1="16" y1="3" x2="16" y2="7" />
                                        <line x1="8" y1="3" x2="8" y2="7" />
                                        <line x1="4" y1="11" x2="20" y2="11" />
                                        <line x1="11" y1="15" x2="12" y2="15" />
                                        <line x1="12" y1="15" x2="12" y2="18" />
                                    </svg>
                                </span>
                            </div>
                    </div>


                    <div class="title-line" style="padding-top:2.3rem;">
                        로고이미지 *
                    </div>
                    <div class="group-flex-wrap" >
                        <div class="input-group" style="padding-top:1.8rem;">
                            <input type="file" class="form-control" name="i_period_logo" id="i_period_logo" />
                            <img src="/dist/img/logo_blue.svg">
                        </div>
                        <div style="margin-left: auto; text-align: right;">

                        </div>
                    </div>

                    <div class="input-group-bottom-text-a">
                        <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                            viewBox="0 0 6 6" fill="none">
                            <circle cx="3" cy="3" r="3" fill="#616876" />
                        </svg>
                        가로자유 * 세로60px 이하 | 용량 : 1M이하 | 확장자 : gif, png, jpg(jpeg)
                    </div>


                </div>
            </div>
        </div>
        <!-- 버튼 -->
        <div style="text-align: center; margin-top: 52px">
            <?php
            echo getButton([
                'text' => '닫기',
                'class' => 'btn',
                'style' => 'width: 180px; height: 46px',
                'extra' => [
                    'onclick' => 'event.preventDefault();$(".btn-close").trigger("click")',
                ]
            ]);
            ?>

            <?php
            echo getIconButton([
                'txt' => '저장',
                'icon' => 'success',
                'buttonClass' => 'btn btn-success',
                'buttonStyle' => 'width:180px; height: 46px',
                'width' => '21',
                'height' => '20',
                'stroke' => 'white',
                'extra' => [
                    'type' => 'button',
                    'onclick' => 'frmRegiserConfirm(event);',
                ]
            ]);
            ?>
        </div>
    </div>
</div>
<!-- Modal E-->
<script>

</script>

<?php
$owensView->setFooterJs('/assets/js/goods/category/lists.js');
$owensView->setFooterJs('/assets/js/goods/category/register.js');
$owensView->setFooterJs('/assets/js/goods/category/detail.js');

$script = "
// $('.bs-timepicker').timepicker({
//     title:'time'
// });

flatpickr('.datetimepicker', {
    enableTime: true,
    dateFormat: 'Y-m-d H:i',
    time_24hr: true
});
";
$owensView->setFooterScript($script);
