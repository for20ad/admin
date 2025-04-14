<?php
    helper(['owens','owens_form', 'owens_url', 'form']);
    $view_datas = $owensView->getViewDatas();
    $sQUERY_STRING = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];

    if (isset($pageDatas) === false) {
        $pageDatas = [];
    }
    $sQueryString = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];

    $aConfig = _elm($view_datas, 'aConfig', []);
    $aData   = _elm($view_datas, 'aData', []);
    $aLists  = _elm($view_datas, 'lists', []);
    $aTargetId = _elm($view_datas, 'openGroup', '');
    $pagination = _elm( $view_datas, 'pagination' );
    $total_rows    = _elm($view_datas, 'total_rows', 0);
    $row           = _elm($view_datas, 'row', []);

?>

    <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;">
        <div>
            <div class="row row-deck row-cards col-12">
                <div class="card-body">
                    <div class="table-responsive" style="border:1px solid #E6E7E9; border-radius:0 0 4px 4px">
                        <table class="table table-vcenter">
                            <colgroup>
                                <col style="width:*">
                                <col style="width:30%">
                                <col style="width:15%">
                                <col style="width:15%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>상품명</th>
                                    <th>옵션:재고</th>
                                    <th>노출상태(PC/Mobile)</th>
                                    <th>판매상태(PC/Mobile)</th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr>
                                    <td><a href="/goods/goodsDetail/<?php echo _elm( $aData, 'G_IDX' )?>" target="_blank"><?php echo _elm( $aData, 'G_NAME' );?></a></td>
                                    <td>
                                        <?php
                                            $vOptions = str_replace(',', '<br>', _elm( $aData, 'A_OPTIONS_TEXT' ) );
                                            echo $vOptions;
                                        ?>
                                    </td>
                                    <td class="body2-c nowrap">
                                        <?php echo _elm( $aData, 'G_PC_OPEN_FLAG' ) == 'Y'? '노출':'미노출';?><br>
                                        <?php echo _elm( $aData, 'G_MOBILE_OPEN_FLAG' ) == 'Y'?  '노출':'미노출';?>
                                    </td>
                                    <td class="body2-c nowrap">
                                        <?php echo _elm( $aData, 'G_PC_SELL_FLAG' ) == 'Y'? '판매중':'판매안함';?><br>
                                        <?php echo _elm( $aData, 'G_MOBILE_SELL_FLAG' ) == 'Y'?  '판매중':'판매안함';?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;padding-top:0.3rem">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M10 17.5C14.1421 17.5 17.5 14.1421 17.5 10C17.5 5.85786 14.1421 2.5 10 2.5C5.85786 2.5 2.5 5.85786 2.5 10C2.5 14.1421 5.85786 17.5 10 17.5Z" fill="#616876" />
                            <path d="M10 6.66667H10.0083" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.1665 10H9.99984V13.3333H10.8332" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span style="color:#FF0000">
                            노란색 리스트는 상품정보(상품명/옵션명/옵션값)가 변경된 리스트이며 재고량이 다를 수 있습니다.<br>
                            빨간색 리스트는 상품이 삭제된 리스트입니다.<br>
                            해당 상품의 상품정보를 확인하신 후 재입고 알림 메세지를 전송해 주세요.
                        </span>
                    </div>
                </div>
            </div>
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
                                신청내역
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
                    <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_pop_search', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
                    <input type="hidden" name="s_goods_idx" value="<?php echo _elm( $aData, 'A_PRD_IDX' )?>">
                    <input type="hidden" name="page" id="page" value="1">
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <tbody>
                                <colgroup>
                                    <col style="width:20%;">
                                    <col style="width:70%;">
                                </colgroup>
                                <tr>
                                    <th class="no-border-bottom">발송여부</th>
                                    <td colspan="3" class="no-border-bottom">
                                        <div class="input-group required">

                                            <?php
                                            $setParam = [
                                                'name' => 's_send_gbn',
                                                'id' => 's_send_gbn_ALL',
                                                'value' => 'ALL',
                                                'label' => '전체',
                                                'checked' => 'checked',
                                                'extraAttributes' => [
                                                ]
                                            ];
                                            echo getRadioButton($setParam);
                                            ?>
                                            <?php
                                            $setParam = [
                                                'name' => 's_send_gbn',
                                                'id' => 's_send_gbn_Y',
                                                'value' => 'Y',
                                                'label' => '발송',
                                                'checked' => '',
                                                'extraAttributes' => [
                                                ]
                                            ];
                                            echo getRadioButton($setParam);
                                            ?>
                                            <?php
                                            $setParam = [
                                                'name' => 's_send_gbn',
                                                'id' => 's_send_gbn_N',
                                                'value' => 'N',
                                                'label' => '미발송',
                                                'checked' => '',
                                                'extraAttributes' => [
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
                        'buttonStyle' => 'width: 120px; height: 34px;background-color:#206BC4',
                        'width' => '21',
                        'height' => '20',
                        'stroke' => 'white',
                        'extra' => [
                            'onclick' => 'getSearchPopList();',
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
                <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_sub_lists',  'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
                    <div class="table-responsive">
                        <table class="table table-vcenter" id="listsTable">
                            <colgroup>
                                <col style="width:5%">
                                <col style="width:5%">
                                <col style="width:*">
                                <col style="width:20%">
                                <col style="width:20%">
                                <col style="width:10%">
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
                                                    'class'=>'checkAllPop',
                                                    'aria-label' => 'Single checkbox One'
                                                ]
                                            ];
                                            echo getCheckBox( $setParam );
                                            ?>
                                        </div>
                                    </th>
                                    <th>번호</th>
                                    <th>신청일시</th>
                                    <th>신청자</th>
                                    <th>휴대폰번호</th>
                                    <th>발송여부</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if( empty( $aLists ) === false ){
                                    foreach( $aLists as $vData ){
                                        $row++;
                                        $no = $total_rows - $row + 1;
                                ?>
                                <tr data-idx="<?php echo _elm( $vData , 'A_IDX' )?>">
                                    <td class="body2-c nowrap">
                                        <div class="checkbox checkbox-single">
                                        <?php
                                            $setParam = [
                                                'name' => 'i_idx[]',
                                                'id' => 'i_idx_'._elm( $vData, 'A_IDX'),
                                                'value' =>  _elm( $vData, 'A_IDX'),
                                                'label' => '',
                                                'checked' => false,
                                                'extraAttributes' => [
                                                    'aria-label' => 'Single checkbox One',
                                                    'class'=>'check-item-pop',
                                                ]
                                            ];
                                            echo getCheckBox( $setParam );
                                        ?>
                                        </div>
                                    </td>
                                    <td class="body2-c nowrap"><?php echo $no?></td>
                                    <td class="body2-c nowrap"><?php echo date('Y.m.d H:i', strtotime( _elm( $vData, 'A_CREATE_AT' ) ) );?></td>
                                    <td class="body2-c nowrap"><?php echo _elm( $vData, 'MB_NM' ).'('._elm( $vData, 'MB_USERID' ).' / '._elm( $vData, 'G_NAME' ) . ')';?></td>
                                    <td class="body2-c nowrap"><?php echo _elm( $vData, 'MB_MOBILE_NUM' );?></td>
                                    <td class="body2-c nowrap"><?php echo empty( _elm( $vData, 'A_ALIM_SEND_AT' ) ) == true ? '미발송' : '발송' ;?></td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding:15px 15px;">
                        선택한 대상에게
                        <?php
                        echo getButton([
                            'text' => 'SMS 발송',
                            'class' => 'btn btn-primary',
                            'style' => 'width: 80px; height: 30px',
                            'extra' => [
                                'onclick' => 'sendAlimConfirm()',
                            ]
                        ]);
                        ?>
                    </div>
                    <!--페이징-->
                    <div class="pagination-wrapper" id="pagination">
                        <?php echo $pagination?>
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
    const checkItems = document.querySelectorAll('.check-item-pop');

    if (checkAll && checkItems.length > 0) {
        checkAll.addEventListener('change', function () {
            const isChecked = this.checked;
            checkItems.forEach(function (checkbox) {
                checkbox.checked = isChecked;
            });
        });

        // 개별 체크박스 체크/체크 해제 시 전체 체크박스 상태 업데이트
        checkItems.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const allChecked = document.querySelectorAll('.check-item-pop:checked').length === checkItems.length;
                checkAll.checked = allChecked;
            });
        });
    }
});
function sendAlimConfirm(){

    if ($('#frm_sub_lists input:checkbox:checked').length < 1) {
        box_alert('대상을 선택하세요.', 'i');
        return false;
    }

    box_confirm( '선택한 대상에게 알림을 보내시겠습니까?', 'q', '', sendAlim );

}
function sendAlim(){
    let frm = $('#frm_sub_lists');

    $.ajax({
        url: '/apis/goods/sendRestockAlim',
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
            getSearchPopList();
            getSearchList();
            //restoreAccordionState();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            return false;
        },
        complete: function () { }
    });
}
function getSearchPopList( page ){
    let frm = $('#frm_pop_search');
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('page') != undefined) {
        page = urlParams.get('page') || 1; // 기본값 1
    }
    frm.find('[name=page]').val( page );

    $.ajax({
        url: '/apis/goods/getPopGoodsRestockAlimDetailLists',
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

            $('#frm_sub_lists #listsTable tbody').empty().html( response.page_datas.lists_row );
            $('#frm_sub_lists #pagination').empty().html(response.page_datas.pagination);
            //restoreAccordionState();

        },
        error: function (jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            return false;
        },
        complete: function () { }
    });
}
//getSearchList(1);

$(document).on('keyup', '[name=s_keyword]', function(e){
    e.preventDefault();
    if( e.keyCode == 13 ){
        getSearchList();
    }
});
/* paging 한 묶음 S */
subPagination.initPagingNumFunc(getSearchList);
subPagination.initPagingSelectFunc(getSearchList);
/* paging 한 묶음 E */




</script>

<?php


$script = "
";
$owensView->setFooterScript($script);

