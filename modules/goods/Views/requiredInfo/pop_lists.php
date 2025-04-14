<?php
    helper(['owens','owens_form', 'owens_url', 'form']);
    $view_datas = $owensView->getViewDatas();
    $sQUERY_STRING = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];

    if (isset($pageDatas) === false) {
        $pageDatas = [];
    }
    $sQueryString = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];

    $aConfig = _elm($view_datas, 'aConfig', []);
    // $aLists = _elm($view_datas, 'lists', []);
    // $row           = _elm($view_datas, 'row', []);
    $total_rows    = _elm($view_datas, 'total_rows', []);
?>
    <div class="modal-header">
        <h5 class="modal-title" id="iconsModalLabel">상품정보제공고시</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;">
        <div>
            <div class="row row-deck row-cards col-12">

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
                    <input type="hidden" name="page" id="page" value="1">
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <tbody>
                                <colgroup>
                                    <col style="width:20%;">
                                    <col style="width:70%;">
                                </colgroup>
                                <tr>
                                    <th class="no-border-bottom">필수정보명</th>
                                    <td colspan="3" class="no-border-bottom">
                                        <input type="text" class="form-control" style="width:15.2rem" name="s_title" id="s_title">
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
                        'buttonStyle' => 'width: 120px; height: 34px;background-color:#206BC4',
                        'width' => '21',
                        'height' => '20',
                        'stroke' => 'white',
                        'extra' => [
                            'onclick' => 'getSearchList();',
                        ]
                    ]);
                    ?>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <br>
    <div>
        <div class="row row-deck row-cards col-12">

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
            <div class="card-body">
                <div style="text-align:right; margin-top: 36px">
                    <?php
                    echo getIconButton([
                        'txt' => '선택하기',
                        'icon' => 'add',
                        'buttonClass' => 'btn text-white btn-success',
                        'buttonStyle' => 'width: 120px; height: 34px;margin-bottom:1.3rem',
                        'width' => '21',
                        'height' => '20',
                        'stroke' => 'white',
                        'extra' => [
                            'onclick' => 'addRequiedItem();',
                        ]
                    ]);
                    ?>
                </div>
                <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_lists',  'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
                    <div class="table-responsive">
                        <table class="table table-vcenter" id="popListsTable">
                            <colgroup>
                                <col style="width:10%">
                                <col style="width:10%">
                                <col style="width:*">
                                <col style="width:25%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>
                                        <div class="checkbox checkbox-single">
                                            <?php
                                            $setParam = [
                                                'name' => '',
                                                'id' => 'checkAllPop',
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
                                    <th>팔수정보명</th>
                                    <th>등록일</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <!--페이징-->
                    <div class="pagination-wrapper" id="pagination">
                    </div>
                    <div style="text-align:right; margin-top: 36px">
                    <?php
                    echo getIconButton([
                        'txt' => '선택하기',
                        'icon' => 'add',
                        'buttonClass' => 'btn text-white btn-success',
                        'buttonStyle' => 'width: 120px; height: 34px;',
                        'width' => '21',
                        'height' => '20',
                        'stroke' => 'white',
                        'extra' => [
                            'onclick' => 'addRequiedItem();',
                        ]
                    ]);
                    ?>
                </div>
                </div>
                <?php echo form_close()?>

            </div>
        </div>

    </div>
</div>

<script>
$(document).ready(function() {
    const checkAll = document.querySelector('#checkAllPop');

    // 전체 체크박스 선택/해제
    if (checkAll) {
        checkAll.addEventListener('change', function () {
            const isChecked = this.checked;
            document.querySelectorAll('.check-item-pop').forEach(function (checkbox) {
                checkbox.checked = isChecked;
            });
        });
    }

    // 개별 체크박스 체크/해제 시 전체 선택 체크박스 상태 업데이트
    document.addEventListener('change', function (event) {
        if (event.target.classList.contains('check-item-pop')) {
            const checkItems = document.querySelectorAll('.check-item-pop');
            const allChecked = document.querySelectorAll('.check-item-pop:checked').length === checkItems.length;
            checkAll.checked = allChecked;
        }
    });
});

function addRequiedItem()
{
    var checkedLength = $('#popListsTable .check-item-pop:checked').length;
    if( checkedLength === 0 ){
        box_alert( '선택된 항목이 없습니다.', 'i' );
        return false;
    }

    var checkedItems = [];
        $('#popListsTable .check-item-pop:checked').each(function() {
            // 체크된 항목의 값을 배열에 추가
            checkedItems.push($(this).val());
        });
    $.ajax({
        url: '/apis/goods/addRequiredRow',
        method: 'POST',
        data: 'checkedItems='+checkedItems,
        dataType: 'json',
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
        },
        success: function (response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status == 'false') {
                var error_message = response.error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            $("#aListsTable tbody").append(response.details);
            //restoreAccordionState()
        },
        error: function (jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            return false;
        },
        complete: function () { }
    });

}

function getSearchList( page ){
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('page') != undefined) {
        page = urlParams.get('page') || 1; // 기본값 1
    }
    $('#frm_search [name=page]').val( page );
    let frm = $('#frm_search')
    $.ajax({
        url: '/apis/goods/getPopRequiredListsRow',
        method: 'POST',
        data: frm.serialize(),
        dataType: 'json',
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
        },
        success: function (response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status == 'false') {
                var error_message = response.error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            $('#popListsTable tbody').empty().html( response.page_datas.lists_row );
            $("#pagination").empty().html( response.page_datas.pagination );

            //restoreAccordionState()
        },
        error: function (jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            return false;
        },
        complete: function () { }
    });
}
getSearchList(1);

$(document).on('keyup', '[name=s_keyword]', function(e){
    e.preventDefault();
    if( e.keyCode == 13 ){
        getSearchList();
    }
});
/* paging 한 묶음 S */
Pagination.initPagingNumFunc(getSearchList);
Pagination.initPagingSelectFunc(getSearchList);
/* paging 한 묶음 E */




</script>

<?php


$script = "
";
$owensView->setFooterScript($script);

