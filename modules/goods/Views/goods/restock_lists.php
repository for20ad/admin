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
        <h3 class="h3-c">재입고 알림 신청 목록</h3>
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
                                상품 검색
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
                    <input type="hidden" name="getData" id="getData" value="<?php echo _elm( $aGetData, 'page' );?>">
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
                                    <th class="no-border-bottom">검색</th>
                                    <td colspan="3" class="no-border-bottom">
                                        <div class="form-inline">
                                        <?php
                                            $options  = ['goods_name'=>'상품명', 'member_id'=>'회원아이디', 'member_name'=>'회원명'];
                                            $extras   = ['id' => 's_condition', 'class' => 'form-select', 'style' => 'max-width: 150px;', ];
                                            $selected = '';
                                            echo getSelectBox('s_condition', $options, $selected, $extras);
                                        ?>
                                        <input type="text" class="form-control" style="width:15.2rem" name="s_keyword" id="s_keyword">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="no-border-bottom">기간</th>
                                    <td colspan="3" class="no-border-bottom">
                                        <div class="form-inline">
                                            <?php
                                                $options  = ['create_at'=>'등록일'];
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
                                                    name="s_start_date" readonly>
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
                                                    name="s_end_date" readonly>
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
                                    <th class="no-border-bottom">카테고리</th>
                                    <td class="no-border-bottom" colspan="3">
                                        <div class="form-inline">

                                            <?php
                                                $options  = [''=>'전체'];
                                                $options += $cateTopList;
                                                $extras   = ['id' => 's_category', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;', 'onChange'=>'changeChilds( this.value )'];
                                                $selected = '';
                                                echo getSelectBox('s_category', $options, $selected, $extras);
                                            ?>
                                            <?php
                                                $options  = [''=>'전체'];
                                                $extras   = ['id' => 's_child_category', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;', 'onChange'=>'changeGrandChilds( this.value )'];
                                                $selected = '';
                                                echo getSelectBox('s_child_category', $options, $selected, $extras);
                                            ?>
                                            <?php
                                                $options  = [''=>'전체'];
                                                $extras   = ['id' => 's_grand_child_category', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;',];
                                                $selected = '';
                                                echo getSelectBox('s_grand_child_category', $options, $selected, $extras);
                                            ?>

                                            <?php
                                                $setParam = [
                                                    'name' => 's_is_not_category',
                                                    'id' => 's_is_not_category',
                                                    'value' => 'Y',
                                                    'label' => '카테고리 미지정 상품',
                                                    'checked' => '',
                                                    'extraAttributes' => []
                                                ];

                                                echo getCheckBox($setParam);
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
                            'onclick' => 'getSearchList(1);',
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
                <div id="buttons">
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
                            'onclick' => "location.href='/goods/goodsRegister'",
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
                            <col style="width:5%">
                            <col style="width:*">
                            <col style="width:25%">
                            <col style="width:10%">
                            <col style="width:5%">
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
                                <th>고유번호</th>
                                <th>상품명</th>
                                <th>옵션</th>
                                <th>신청자</th>
                                <th>발송/미발송</th>
                                <th>상세보기</th>
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
    </div>
</div>
<!-- Modal S-->
<div class="modal fade" id="goodsModal" tabindex="-1" style="margin-top:3em;" aria-labelledby="goodsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;width:80vh">
            <div class="modal-header">
                <h5 class="modal-title" id="goodsModalLabel">상품 등록/수정</h5>
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
<!-- Modal S-->
<div class="modal fade" id="restockModalLabel" tabindex="-1" style="margin-top:3em;" aria-labelledby="restockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;width:80vh">
            <div class="modal-header">
                <h5 class="modal-title" id="restockModalLabel">상품 재입고 알림 신청 내역</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="viewData" style="overflow:auto">

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
function openLayer( goodsIdx, id, obj ){
    let data = 's_goods_idx='+goodsIdx;
    let url  = '/apis/goods/goodsRestockDetailList';


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
            $('#'+id+' .viewData').empty().html( response.page_datas.detail );
            //var modal = new bootstrap.Modal(document.getElementById(id));
            var modalElement = document.getElementById(id);
            var modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static', // 마스크 클릭해도 닫히지 않게 설정
                keyboard: true     // esc 키로 닫히지 않게 설정
            });

            modal.show();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            return false;
        },
        complete: function() { }
    });
        // 모달 열기

}

function dropDownLayer(layerId) {
    var dropdownLayer = $("#dropdown-layer-" + layerId);

    if (dropdownLayer.css("display") === "none") {
        dropdownLayer.css("display", "block");
    } else {
        dropdownLayer.css("display", "none");
    }

    // 클릭된 요소 외부를 클릭하면 레이어를 닫도록 설정
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.input-group').length) {
            dropdownLayer.hide();
        }
    });
}



// 카테고리 로드
function changeChilds()
{
    if( $('[name=s_category]').val() == '' ){
        return;
    }
    $.ajax({
        url: '/apis/goods/getCategoryChilds',
        method: 'POST',
        data: 'cate_idx='+$('[name=s_category]').val(),
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
        },
        complete: function() { },
        success: function(response)
        {
            //submitSuccess(response);
            $('#preloader').hide();
            // if (response.status != 200)
            // {
            //     var error_message = '';
            //     error_message = response.errors.join('<br />');
            //     if (error_message != '') {
            //         box_alert(error_message, 'e');
            //     }
            //     return false;
            // }

            $("[name=s_child_category]").empty().html( response.options );
            $('[name=s_grand_child_category]').empty().html('<option value="">전체</option>' )
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            console.log(textStatus);
            return false;
        }
    });
}
function changeGrandChilds()
{

    if( $('[name=s_child_category]').val() == '' ){
        return;
    }
    $.ajax({
        url: '/apis/goods/getCategoryChilds',
        method: 'POST',
        data: 'cate_idx='+$('[name=s_child_category]').val(),
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
        },
        complete: function() { },
        success: function(response)
        {
            //submitSuccess(response);
            $('#preloader').hide();
            // if (response.status != 200)
            // {
            //     var error_message = '';
            //     error_message = response.errors.join('<br />');
            //     if (error_message != '') {
            //         box_alert(error_message, 'e');
            //     }
            //     return false;
            // }

            $('[name=s_grand_child_category]').empty().html( response.options );
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            console.log(textStatus);
            return false;
        }
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

</script>

<?php
$owensView->setFooterJs('/assets/js/goods/goods/restock_lists.js');


$script = "
";

$owensView->setFooterScript($script);

