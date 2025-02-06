<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();


    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aOrdIdx          = _elm( $view_datas, 'ordIdx' );
    $aOrderGoodsInfo  = _elm( $view_datas, 'orderGoodsInfo', [] );
    $aOdrStatus       = _elm( $view_datas, 'aOdrStatus', [] );
?>


<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_memo_register', 'autocomplete' => 'off']); ?>
<input type="hidden" name="ordIdPrdTxt">
<div class="card-body" style="border:1px solid #ccc;border-radius:5px;">
    <div class="table-responsive">
        <table class="table table-vcenter">
            <colgroup>
                <col style="width:5%">
                <col style="width:20%">
                <col style="width:*">
                <col style="width:15%">
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
                    <th>주문상품번호</th>
                    <th>주문상품</th>
                    <th>처리상태</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if( empty( $aOrderGoodsInfo ) === false ):
                    foreach( $aOrderGoodsInfo as $aKey => $info ):
                ?>
                <tr>
                    <td class="body2-c nowrap">
                        <div class="checkbox checkbox-single">
                        <?php
                            $setParam = [
                                'name' => 'o_i_idx[]',
                                'id' => 'o_i_idx_'._elm( $info, 'infoIdx' ),
                                'value' => _elm( $info, 'infoIdx' ),
                                'label' => '',
                                'checked' => false,
                                'extraAttributes' => [
                                    'aria-label' => 'Single checkbox One',
                                    'class'=>'check-item',
                                ]
                            ];
                            echo getCheckBox( $setParam );
                        ?>
                        </div>
                    </td>
                    <td class="body2-c nowrap small-font">
                        <span id="ordIdPrd"><?php echo _elm( $info, 'ordIdPrd' )?></span>
                    </td>
                    <td class="body2-c nowrap small-font">
                        <div style="display: flex; align-items: center;">
                            <!-- 이미지 추가 -->
                            <img src="/<?php echo _elm( $info, 'goodsImg' )?>" alt="Product Image" style="width: 60px; height: 60px; margin-right: 10px;">

                            <!-- 텍스트 내용 -->
                            <div>
                                <div>
                                    <?php echo _elm($info, 'productTitle') ?>
                                </div>
                                <div>
                                    색상 : <?php echo _elm($info, 'goodsColor') ?>  <?php echo _elm($info, 'productOptions') ?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="body2-c nowrap small-font">
                        <?php echo _elm( $aOdrStatus, _elm( $info, 'orderStatus' ) );?>
                    </td>
                </tr>
                <?php
                    endforeach;
                endif;
                ?>
            </tbody>

        </table>
    </div>
</div>
<input type="hidden" name="ordIdx" value="<?php echo $aOrdIdx?>">
<div id="reply-box" style=" margin-top: 10px;">
    <span class="form-inline" style="padding:5px 10px">
        상태 선택 :
    <?php
        $options  = ['' => '선택'];
        $options  += $aOdrStatus;
        $extras   = ['id' => 'i_status', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
        $selected = '';
        echo getSelectBox('i_status', $options, $selected, $extras);
    ?>
    </span>

    <textarea placeholder="메모를 입력하세요..." name="content" id="reply-textarea"  style="width: 100%; height: 80px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;"></textarea>
    <div style="margin-top: 5px; text-align: right;">
        <button type="button"  class="reply-submit-btn" style="background: #28a745; color: white; border: none; border-radius: 5px; padding: 5px 10px; font-size: 12px; cursor: pointer;">
            등록
        </button>
    </div>
</div>
<?php echo form_close();?>
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
            <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_sub_lists', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>

            <input type="hidden" name="page" id="page" value="1">
            <div class="table-responsive">
                <table class="table table-vcenter">
                    <colgroup>
                        <col style="width:15%">
                        <col style="width:13%">
                        <col style="width:15%">
                        <col style="width:20%">
                        <col style="width:*">
                        <col style="width:15%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>작성일</th>
                            <th>작성자</th>
                            <th>메모구분</th>
                            <th>상품주문번호</th>
                            <th>메모내용</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody id="commentLists">

                    </tbody>

                </table>
            </div>
            <div class="pagination-wrapper" id="pagination">
            </div>
        </div>
    </div>
</div>
<div style="overflow-y: scroll;" id="scrollContainer">
    <div style="max-height: 600px;" ></div>
</div>

<script>
var ordIdx = <?php echo $aOrdIdx?>;
var isCalling = false;

getMemoLists( ordIdx );
$('.reply-submit-btn').off('click').on('click', function () {
    if ($('input[name="o_i_idx[]"]:checked').length === 0) {
        box_alert('주문상품을 선택해주세요.', 'i');
        return false;
    }else{
        var selectedOrdIds = [];
        $('input[name="o_i_idx[]"]:checked').each(function () {
            var ordIdPrd = $(this).closest('tr').find('#ordIdPrd').text().trim(); // 해당 행의 #ordIdPrd 텍스트 값
            selectedOrdIds.push(ordIdPrd); // 배열에 추가
        });

        // 배열을 콤마로 묶어서 name="ordIdPrdTxt"에 설정
        $('[name="ordIdPrdTxt"]').val(selectedOrdIds.join(', ')); // 콤마로 묶기
    }
    if( $('[name=i_status]').val() == '' ){
        box_alert( '요청상태를 선택해주세요.', 'i' );
        return false;
    }
    var memoFrm = $('#frm_memo_register');
    if( memoFrm.find('[name=content]').val().trim() == '' ){
        box_alert('메모를 입력해주세요.','e');
        return false;
    }
    console.log( memoFrm.serialize() );
    var inputs = memoFrm.find('input, button, select');


    $.ajax({
        url: '/apis/order/insertMemo',
        type: 'post',
        data: memoFrm.serialize(),
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
            inputs.prop('disabled', true);
        },
        success: function (response) {
            $('#preloader').hide();
            inputs.prop('disabled', false);
            if (response.status === 'false') {
                let error_message = error_lists.join('<br />');
                if (error_message !== '') {
                    box_alert(error_message, 'e');
                }
                return;
            }
            memoFrm.find('[name=content]').val('');
            getMemoLists( ordIdx );
            getSearchList();

        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#preloader').hide();
            inputs.prop('disabled', false);
            console.error(textStatus);
        },
        complete: function () {
            isCalling = false;
        },
    });
});
function deleteMemoConfirm( m_idx ){
    box_confirm( '메모를 삭제 하시겠습니까?', 'q', '', deleteMemo, m_idx );
}
function deleteMemo( m_idx ){
    $.ajax({
        url: '/apis/order/deleteMemo',
        type: 'post',
        data: 'm_idx='+m_idx,
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
        },
        success: function (response) {
            $('#preloader').hide();
            if (response.status === 'false') {
                let error_message = error_lists.join('<br />');
                if (error_message !== '') {
                    box_alert(error_message, 'e');
                }
                return;
            }

            getMemoLists( ordIdx, 'none' );
            getSearchList();

        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#preloader').hide();
            console.error(textStatus);
        },
        complete: function () {
            isCalling = false;
        },
    });
}
function getMemoLists(ordIdx, nonScrollFlag) {
    if (isCalling) return;
    isCalling = true;

    let data = 'ordIdx=' + ordIdx;
    let url = '/apis/order/getMemoListsRow';

    $.ajax({
        url: url,
        type: 'post',
        data: data,
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
        },
        success: function (response) {
            $('#preloader').hide();
            if (response.status === 'false') {
                let error_message = error_lists.join('<br />');
                if (error_message !== '') {
                    box_alert(error_message, 'e');
                }
                return;
            }

            $('#commentLists').empty().html(response.page_datas.rows);
            console.log( nonScrollFlag );
            if( nonScrollFlag != 'none' ){
                // 스크롤을 맨 아래로 이동
                setTimeout(function(){
                    const $commentLists = $('#scrollContainer');
                    $commentLists.animate({ scrollTop: $commentLists.prop('scrollHeight') }, 300); // 300ms 애니메이션
                }, 300);
            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#preloader').hide();
            console.error(textStatus);
        },
        complete: function () {
            isCalling = false;
        },
    });
}

initCheckAll();

</script>
