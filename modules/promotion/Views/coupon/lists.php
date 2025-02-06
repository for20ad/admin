<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }
    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];

    $aConfig          = _elm($pageDatas, 'aConfig', []);

    $aLists           = _elm($pageDatas, 'aDatas', []);

    $aGetData         = _elm( $pageDatas, 'getData', [] );
    $cateTopList      = _elm( $pageDatas, 'cateTopList', [] );
?>
<link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>
<style>
    .form-inline .form-check-inline{
        width:100%;
    }
    .form-inline .form-check-label{
        margin-left: 1.5rem;
    }
</style>
<div class="container-fluid">
    <!-- 카트 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">쿠폰 목록</h3>
    </div>

    <div class="row row-deck row-cards">
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
                                검색
                            </p>
                        </div>
                        <!-- 아코디언 토글 버튼 -->
                        <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
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

                    <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_search', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
                    <input type="hidden" name="page" id="page" value="<?php echo _elm( $aGetData, 'page' );?>">
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <tbody>
                                <colgroup>
                                    <col style="width:10%;">
                                    <col style="width:40%;">
                                    <col style="width:10%;">
                                    <col style="width:50%;">
                                </colgroup>
                                <tr>
                                    <th class="no-border-bottom">쿠폰명</th>
                                    <td colspan="3" class="no-border-bottom">
                                        <div class="input-group required">
                                            <input type="text" class="form-control" placeholder="쿠폰명을 입력해주세요." name="s_coupon_name" id="s_coupon_name"  data-max-length="20" /
                                                style="border-top-right-radius:0px; border-bottom-right-radius: 0px" value=""/>
                                            <span class="wordCount input-group-text"
                                                style="border-top-left-radius:0px; border-bottom-left-radi us: 0px">
                                                0/20
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="no-border-bottom">상태</th>
                                    <td colspan="3" class="no-border-bottom">
                                        <?php
                                            $options  = [''=>'전체', 'Y'=>'정상', 'W'=>'대기', 'N'=>'사용중지','D'=>'삭제' ];
                                            $extras   = ['id' => 's_status', 'class' => 'form-select', 'style' => 'max-width: 150px;', ];
                                            $selected = 'Y';
                                            echo getSelectBox('s_status', $options, $selected, $extras);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="no-border-bottom">적용범위</th>
                                    <td colspan="3" class="no-border-bottom">
                                        <?php
                                            $options  = [''=>'전체', 'all'=>'모든상품', 'category'=>'특정카테고리', 'brand'=>'특정브랜드', 'product'=>'특정상품'];
                                            $extras   = ['id' => 's_scope_gbn', 'class' => 'form-select', 'style' => 'max-width: 150px;', ];
                                            $selected = '';
                                            echo getSelectBox('s_scope_gbn', $options, $selected, $extras);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="no-border-bottom">기간검색</th>
                                    <td colspan="3" class="no-border-bottom">
                                        <div class="form-inline">
                                            <?php
                                                $options  = ['C_CREATE_AT'=>'등록일', 'C_UPDATE_AT'=>'수정일', 'C_PERIOD'=>'사용기간'];
                                                $extras   = ['id' => 's_date_condition', 'class' => 'form-select', 'style' => 'max-width: 150px;', ];
                                                $selected = '';
                                                echo getSelectBox('s_date_condition', $options, $selected, $extras);
                                            ?>
                                            <?php
                                            echo getButton([
                                                'text' => '오늘',
                                                'class' => 'btn',
                                                'style' => 'width: 60px; height: 36px',
                                                'extra' => [
                                                    'onclick' => 'setPeriodDate( \'1Day\' )',
                                                ]
                                            ]);
                                            ?>

                                            <?php
                                            echo getButton([
                                                'text' => '7일',
                                                'class' => 'btn',
                                                'style' => 'width: 60px; height: 36px',
                                                'extra' => [
                                                    'onclick' => 'setPeriodDate( \'7Day\' )',
                                                ]
                                            ]);
                                            ?>

                                            <?php
                                            echo getButton([
                                                'text' => '15일',
                                                'class' => 'btn',
                                                'style' => 'width: 60px; height: 36px',
                                                'extra' => [
                                                    'onclick' => 'setPeriodDate( \'15Day\' )',
                                                ]
                                            ]);
                                            ?>

                                            <?php
                                            echo getButton([
                                                'text' => '1개월',
                                                'class' => 'btn',
                                                'style' => 'width: 60px; height: 36px',
                                                'extra' => [
                                                    'onclick' => 'setPeriodDate( \'1Month\' )',
                                                ]
                                            ]);
                                            ?>

                                            <?php
                                            echo getButton([
                                                'text' => '3개월',
                                                'class' => 'btn',
                                                'style' => 'width: 60px; height: 36px',
                                                'extra' => [
                                                    'onclick' => 'setPeriodDate( \'3Month\' )',
                                                ]
                                            ]);
                                            ?>

                                            <?php
                                            echo getButton([
                                                'text' => '6개월',
                                                'class' => 'btn',
                                                'style' => 'width: 60px; height: 36px',
                                                'extra' => [
                                                    'onclick' => 'setPeriodDate( \'6Month\' )',
                                                ]
                                            ]);
                                            ?>

                                            <?php
                                            echo getButton([
                                                'text' => '12개월',
                                                'class' => 'btn',
                                                'style' => 'width: 60px; height: 36px',
                                                'extra' => [
                                                    'onclick' => 'setPeriodDate( \'1Years\' )',
                                                ]
                                            ]);
                                            ?>
                                            <div class="input-icon">
                                                <input type="text" class="form-control datepicker-icon"
                                                    name="s_start_date" >
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
                                            ~
                                            <div class="input-icon">
                                                <input type="text" class="form-control datepicker-icon"
                                                    name="s_end_date" >
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
                                    </td>
                                </tr>
                                <tr>
                                    <th class="no-border-bottom">쿠폰유형</th>
                                    <td class="no-border-bottom">
                                        <div class="form-inline" style="max-width:400px;margin-left:-1.3rem;paddign:-0.3rem !important">
                                            <?php
                                                $checked = false;

                                                $setParam = [
                                                    'name' => 's_coupon_gbn',
                                                    'id' => 's_coupon_gbn_P',
                                                    'value' => 'P',
                                                    'label' => '지정발행',
                                                    'checked' => $checked,
                                                    'extraAttributes' => [
                                                        'style'=>'margin-left:-0.3rem !important;',
                                                    ]
                                                ];
                                                echo getRadioButton($setParam);
                                            ?>
                                            <?php
                                                $checked = false;

                                                $setParam = [
                                                    'name' => 's_coupon_gbn',
                                                    'id' => 's_coupon_gbn_D',
                                                    'value' => 'P',
                                                    'label' => '고객다운로드',
                                                    'checked' => $checked,
                                                    'extraAttributes' => [
                                                        'style'=>'padding-left:-0rem !important',
                                                    ]
                                                ];
                                                echo getRadioButton($setParam);
                                            ?>
                                            <?php
                                                $checked = false;

                                                $setParam = [
                                                    'name' => 's_coupon_gbn',
                                                    'id' => 's_coupon_gbn_A',
                                                    'value' => 'A',
                                                    'label' => '자동발행',
                                                    'checked' => $checked,
                                                    'extraAttributes' => [
                                                        'style'=>'margin-left:-0.3rem !important',
                                                    ]
                                                ];
                                                echo getRadioButton($setParam);
                                            ?>
                                        </div>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php echo form_close();?>
                    <div style="text-align: center; margin-top: 36px">
                    <?php
                    echo getIconButton([
                        'txt' => '검색',
                        'icon' => 'search',
                        'buttonClass' => 'btn text-white',
                        'buttonStyle' => 'width: 180px; height: 46px;background-color:#206BC4',
                        'width' => '21',
                        'height' => '20',
                        'stroke' => 'white',
                        'extra' => [
                            'onclick' => 'getSearchList();',
                        ]
                    ]);
                    ?>
                    <?php
                    echo getIconButton([
                        'txt' => '초기화',
                        'icon' => 'reset',
                        'buttonClass' => 'btn',
                        'buttonStyle' => 'width: 180px; height: 46px',
                        'width' => '21',
                        'height' => '20',
                        'extra' => [
                            'onclick' => 'document.location.reload()',
                        ]
                    ]);
                    ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<br>
<div class="container-fluid">
    <div class="col-12 col">
        <!-- 카드 타이틀 -->
        <div class="card accordion-card" style="padding: 17px 24px; border-radius: 4px 4px 0 0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                        fill="none">
                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                    </svg>
                    <p class="body1-c ms-2 mt-1">
                        목록
                    </p>
                </div>
                <!-- 아코디언 토글 버튼 -->
                <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
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

        <div class="card accordion-card" style="padding: 17px 24px; border-top:0">
            <div class="form-inline" style="justify-content: space-between;width: 100%;">
                <!-- 버튼 그룹 왼쪽정렬-->
                <!-- 버튼 그룹 왼쪽정렬-->
                <div id="buttons">
                    <?php
                        echo getButton([
                            'text' => '선택삭제',
                            'class' => 'btn btn-secondary',
                            'style' => 'width: 90px; height: 36px',
                            'extra' => [
                                'onclick' => 'deleteCouponCheckedAll()',
                            ]
                        ]);
                    ?>
                    <span style="color:#ccc;font-size:25px;vertical-align:middle;">|</span>

                    <?php
                        echo getButton([
                            'text' => '복사',
                            'class' => 'btn btn-info',
                            'style' => 'width: 60px; height: 36px',
                            'extra' => [
                                'onclick' => 'checkCopyConfirm()',
                            ]
                        ]);
                    ?>

                    <?php
                        echo getButton([
                            'text' => '발행중지',
                            'class' => 'btn btn-secondary',
                            'style' => 'width: 90px; height: 36px',
                            'extra' => [
                                'onclick' => 'stopIssueConfirm()',
                            ]
                        ]);
                    ?>
                </div>
                <!-- 버튼 그룹 오른쪽정렬-->
                <div>
                    <?php
                        echo getIconButton([
                            'txt' => '등록',
                            'icon' => 'add',
                            'buttonClass' => 'btn ',
                            'buttonStyle' => 'width: 100px; height: 34px',
                            'width' => '20',
                            'height' => '20',
                            'stroke' => '#1D273B',
                            'extra' => [
                                'onclick' => "location.href='/promotion/coupon/cpnRegister'",
                            ]
                        ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- 테이블 -->
            <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_lists',  'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
            <div style="border:1px solid #E6E7E9; border-top: 0px; border-radius:0 0 4px 4px">
                <div class="table-responsive">
                    <table class="table table-vcenter" id="listsTable">
                        <colgroup>
                            <col style="width:5%">
                            <col style="width:5%">
                            <col style="width:*">
                            <col style="width:10%">
                            <col style="width:10%">
                            <col style="width:10%">
                            <col style="width:15%">
                            <col style="width:8%">
                            <col style="width:5%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>
                                    <div class="checkbox checkbox-single">
                                        <?php
                                        $setParam = [
                                            'name' => '',
                                            'id' => 'checkAll',
                                            'value' => '',
                                            'label' => '',
                                            'checked' => false,
                                            'extraAttributes' => [
                                                'class'=>'checkAll',
                                                'aria-label' => 'Single checkbox One'
                                            ]
                                        ];
                                        echo getCheckBox( $setParam );
                                        ?>
                                    </div>
                                </th>
                                <th>번호</th>
                                <th>쿠폰명/사용혜택</th>
                                <th>상태</th>
                                <th>쿠폰형식</th>
                                <th>발행수(사용/미사용)</th>
                                <th>사용기간</th>
                                <th>등록일/수정일</th>
                                <th>삭제</th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
                <!--페이징-->
                <div class="pagination-wrapper" id="pagination">


                </div>
            </div>
            <?php echo form_close()?>
        </div>
        <div class="card accordion-card" style="padding: 17px 24px; border-top:0">
            <div class="form-inline" style="justify-content: space-between;width: 100%;">
                <!-- 버튼 그룹 왼쪽정렬-->
                <div id="buttons">
                    <?php
                        echo getButton([
                            'text' => '선택삭제',
                            'class' => 'btn btn-secondary',
                            'style' => 'width: 90px; height: 36px',
                            'extra' => [
                                'onclick' => 'deleteCouponCheckedAll()',
                            ]
                        ]);
                    ?>
                    <span style="color:#ccc;font-size:25px;vertical-align:middle;">|</span>
                    <?php
                        echo getButton([
                            'text' => '복사',
                            'class' => 'btn btn-info',
                            'style' => 'width: 60px; height: 36px',
                            'extra' => [
                                'onclick' => 'checkCopyConfirm()',
                            ]
                        ]);
                    ?>
                    <?php
                        echo getButton([
                            'text' => '발행중지',
                            'class' => 'btn btn-secondary',
                            'style' => 'width: 90px; height: 36px',
                            'extra' => [
                                'onclick' => 'stopIssueConfirm()',
                            ]
                        ]);
                    ?>
                </div>
                <!-- 버튼 그룹 오른쪽정렬-->
                <div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal S-->
<div class="modal fade" id="dataModal" tabindex="-1" style="margin-top:3em;" aria-labelledby="dataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;width:90vh">
            <div class="modal-header">
                <h5 class="modal-title" id="dataModalLabel">쿠폰정보</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;">
                <div class="viewData">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal E-->
<link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>


function openLayer( cpnIdx ){
    let data = 'coupon_idx='+cpnIdx;
    let url  = '/apis/promotion/couponPopIssueLists';

    $.ajax({
        url: url,
        type: 'post',
        data: data,
        processData: false,
        cache: false,
        beforeSend: function() {
            $('#preloader').show();
        },
        success: function(response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status == 'false')
            {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }

                return false;
            }
            $('#dataModal .viewData').empty().html( response.page_datas.detail );
            $("#dataModal .viewData #pagination").empty().html( response.page_datas.pagination );
            //var modal = new bootstrap.Modal(document.getElementById(id));
            var modalElement = document.getElementById('dataModal');
            var modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static', // 마스크 클릭해도 닫히지 않게 설정
                keyboard: false     // esc 키로 닫히지 않게 설정
            });

            modal.show();
            setTimeout(() => {
                getSubSearchList();
            }, 300);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            return false;
        },
        complete: function() { }
    });
}

$(document).on('click', '.tabler-icon-circle-x', function() {
    var listItem = $(this).closest('.dropdown-list-item');
    var id = listItem.data('id');
    $('#'+id).prop('checked', false);
    listItem.remove();
});
flatpickr('.datepicker-icon', {
    enableTime: true,
    dateFormat: 'Y-m-d',
    time_24hr: true
});
// setPeriodDate 함수 정의
function setPeriodDate(period) {
    var startDate = new Date();
    var endDate = new Date();

    switch(period) {
        case '1Day':
            endDate;
            break;
        case '7Day':
            startDate.setDate(endDate.getDate() - 7);
            break;
        case '15Day':
            startDate.setDate(endDate.getDate() - 15);
            break;
        case '1Month':
            startDate.setMonth(endDate.getMonth() - 1);
            break;
        case '3Month':
            startDate.setMonth(endDate.getMonth() - 3);
            break;
        case '6Month':
            startDate.setMonth(endDate.getMonth() - 6);
            break;
        case '1Years':
            startDate.setFullYear(endDate.getFullYear() - 1);
            break;
        default:
            console.error('Invalid period specified');
            return;
    }

    // yyyy-mm-dd 형식으로 날짜를 포맷
    var formatDate = function(date) {
        var year = date.getFullYear();
        var month = ('0' + (date.getMonth() + 1)).slice(-2);
        var day = ('0' + date.getDate()).slice(-2);
        return year + '-' + month + '-' + day;
    };

    // 날짜 필드 업데이트
    $('[name=s_start_date]').val(formatDate(startDate));
    $('[name=s_end_date]').val(formatDate(endDate));
}



$(function(){

// const exampleData = {
//     mainMessage: "잘못 입력된 데이터가 있어 등록에 실패했습니다",
//     subMessage: "아래 실패사유 확인 후 재 등록 부탁드립니다",
//     registeredData: "10,000건",
//     validData: "9,000건",
//     failedData: "1,000건",
//     failureReasons: [
//         { lineNumber: 15, reason: "미입력 : 판매가, 공급사 입력오류 : 공급가, 소비자가" },
//         { lineNumber: 16, reason: "미입력 : 재고수량, 유효기간 입력오류 : 가격" }
//     ]
// };

// box_alert_resultView('업로드실패', 'e', exampleData, function(data) {
//     console.log('Callback executed with data:', data);
// });

$(document).on('keyup', '#frm_search [name=s_keyword]', function(e){
    e.preventDefault();
    if( e.keyCode == 13 ){
        getSearchList();
    }
});
/* paging 한 묶음 S */
Pagination.initPagingNumFunc(getSearchList);
Pagination.initPagingSelectFunc(getSearchList);
/* paging 한 묶음 E */

});


</script>

<?php
$owensView->setFooterJs('/assets/js/promotion/coupon/lists.js');
//$owensView->setFooterJs('/assets/js/promotion/coupon/issue.js');


$script = "
";

$owensView->setFooterScript($script);

