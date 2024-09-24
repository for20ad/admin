<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }
    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];

    $aConfig          = _elm( $pageDatas, 'aConfig', [] );
    $aData            = _elm( $pageDatas, 'aData', [] );

    $aProductLists    = _elm( $aData, 'productLists', [] );
    $productIdxs      = _elm( $pageDatas, 'productIdxs', [] );
    $aLimitCnt        = _elm( $pageDatas, 'limitCnt', '' );
    $aColorConfig     = _elm( $pageDatas, 'aColorConfig', [] );

?>
<link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>

<link rel="stylesheet" href="/plugins/common/mCustomScrollbar-min.css"
    integrity="sha512-6qkvBbDyl5TDJtNJiC8foyEVuB6gxMBkrKy67XpqnIDxyvLLPJzmTjAj1dRJfNdmXWqD10VbJoeN4pOQqDwvRA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="/plugins/common/mCustomScrollbar.js"></script>

<div class="container-fluid">
    <!-- 카트 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c"><?php echo _elm( $aData, 'A_TITLE' )?></h3>
    </div>
</div>
<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
<input type="hidden" name="i_p_idx" value="<?php echo _elm( $aData, 'A_IDX' )?>">
<div class="container-fluid">
    <div class="col-12">
        <!-- 카드 타이틀 -->
        <div class="card accordion-card" style="padding: 17px 24px; border-radius: 4px 4px 0 0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4" fill="none">
                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                    </svg>
                    <p class="body1-c ms-2 mt-1">
                        상품목록
                    </p>
                </div>
                <div style="display: flex">
                    <div style="padding-right:2.6rem">

                    </div>
                    <!-- 아코디언 토글 버튼 -->
                    <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
                        <span class="form-selectgroup-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8" fill="none">
                                <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </span>

                    </label>
                </div>
            </div>
        </div>

        <div class="card accordion-card" style="padding: 17px 24px; border-top:0">
            <div class="form-inline" style="justify-content: space-between;width: 100%;">
                <div style="display: flex; align-items: center;">
                    <span style="padding-right:0.6rem">메인페이지 노출수 입력 :</span>
                    <input type="text" class="form-control" name="i_limit_cnt" style="max-width: 60px; margin-left: 8px;text-align:right" placeholder="수량" value="<?php echo $aLimitCnt?>">
                </div>
                <div>
                <?php
                    echo getIconButton([
                        'txt' => '상품 추가',
                        'icon' => 'add',
                        'buttonClass' => 'btn btn-blue',
                        'buttonStyle' => 'width:120px; height: 36px',
                        'width' => '21',
                        'height' => '20',
                        'stroke' => 'white',
                        'extra' => [
                            'type' => 'button',
                            'onclick' => 'openDataLayer(\'best\');',
                        ]
                    ]);
                ?>
                <?php
                    echo getButton([
                        'text' => '선택삭제',
                        'class' => 'btn btn-secondary',
                        'style' => 'width: 80px; height: 36px',
                        'extra' => [
                            'onclick' => 'event.preventDefault();removeLine(\'gListsTable\')',
                        ]
                    ]);
                ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div style="border:1px solid #E6E7E9; border-top: 0px; border-radius:0 0 4px 4px">
                <div class="table-responsive">
                    <table class="table table-vcenter" id="gListsTable">
                        <colgroup>
                            <col style="width:6%;">
                            <col style="width:5%;">
                            <col style="width:8%;">
                            <col style="*">
                            <col style="width:13%;">
                            <col style="width:10%;">
                            <col style="width:5%;">
                            <col style="width:5%;">
                            <col style="width:5%;">
                            <col style="width:10%;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th></th>
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
                                <th></th>
                                <th>상품명</th>
                                <th>색상</th>
                                <th>판매가<br>판매가수정-더블클릭</th>
                                <th>노출상태<br>
                                    PC/MOBILE
                                </th>
                                <th>판매상태<br>
                                    PC/MOBILE
                                </th>
                                <th>재고</th>
                                <th>등록일/수정일</th>
                            </tr>
                        </thead>
                        <tbody id="tableSort">
                            <?php
                            if( empty( $aProductLists ) === false ){
                                foreach( $aProductLists as $key => $vData ){
                            ?>
                                <tr data-idx="<?php echo _elm( $vData , 'G_IDX' )?>" onmouseover="$(this).find('.group-move-icons').show()" onmouseout="$(this).find('.group-move-icons').hide()">
                                    <input type="hidden" name="i_group_goods_idxs[]" value="<?php echo _elm( $vData , 'G_IDX' )?>">
                                    <td>
                                        <div class="group-move-icons" style="display:none;cursor:pointer" >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32" fill="none">
                                                <g clip-path="url(#clip0_492_18580)">
                                                    <path d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z" fill="#616876"/>
                                                    <path d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z" fill="#616876"/>
                                                    <path d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z" fill="#616876"/>
                                                    <path d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z" fill="#616876"/>
                                                    <path d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z" fill="#616876"/>
                                                    <path d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z" fill="#616876"/>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_492_18580">
                                                    <rect width="32" height="32" fill="white"/>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </div>
                                    </td>
                                    <td class="body2-c nowrap">
                                        <div class="checkbox checkbox-single">

                                        <?php
                                            $setParam = [
                                                'name' => 'i_group_goods_idxs[]',
                                                'id' => 'i_group_goods_idxs_'._elm( $vData, 'G_IDX'),
                                                'value' =>  _elm( $vData, 'G_IDX'),
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
                                    <td class="body2-c nowrap"><img src="/<?php echo _elm( $vData, 'I_IMG_PATH' )?>" width="40" loading="lazy"></td>
                                    <td class="body2-c nowrap"><a href="javascript:window.open('<?php echo _link_url('/goods/goodsDetail/'._elm( $vData , 'G_IDX' ) )?>', '_blank');"><?php echo  _elm( $vData , 'G_NAME' )?></a></td>
                                    <td class="body2-c nowrap">

                                    <?php if( !empty( $aColorConfig ) ){
                                        $colorData = _elm($aColorConfig, _elm( $vData, 'G_COLOR' ) );
                                    ?>
                                        <span>
                                            <span style="border:1px solid #ccc;background-color:<?php echo _elm( $colorData, 'color' )?>width: 18px; height: 18px; display: inline-block; margin-right: 10px; border-radius: 3px; vertical-align: middle;"></span>
                                            <span style="vertical-align: middle;"><?php echo _elm( $colorData, 'text' )?></span>
                                        </span>
                                    <?php
                                    }
                                    ?>

                                    </td>
                                    <td class="body2-c nowrap">
                                        <span class="price"  data-g-idx="<?php echo _elm( $vData, 'G_IDX' )?>"><?php echo number_format( _elm( $vData, 'G_PRICE' ) ) ?></span>
                                    </td>
                                    <td class="body2-c nowrap">
                                        <?php echo _elm( $vData, 'G_PC_OPEN_FLAG' ) == 'Y' ? '노출' : '미노출' ?><br>
                                        <?php echo _elm( $vData, 'G_MOBILE_OPEN_FLAG' ) == 'Y' ? '노출' : '미노출' ?>
                                    </td>
                                    <td class="body2-c nowrap">
                                        <?php echo _elm( $vData, 'G_PC_SELL_FLAG' ) == 'Y' ? '판매중' : '판매중단' ?><br>
                                        <?php echo _elm( $vData, 'G_MOBILE_SELL_FLAG' ) == 'Y' ? '판매중' : '판매중단' ?><br>
                                    </td>
                                    <td class="body2-c nowrap">
                                        <?php echo _elm( $vData, 'G_STOCK_CNT' )?>
                                    </td>
                                    <td class="body2-c nowrap">
                                        <?php echo empty( _elm( $vData , 'G_CREATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'G_CREATE_AT' ) ) ) : '-' ?><br>
                                        <?php echo empty( _elm( $vData , 'G_UPDATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'G_UPDATE_AT' ) ) ) : '-' ?>
                                    </td>
                                </tr>
                            <?php
                                }
                            }else{
                            ?>

                            <?php
                            }
                            ?>


                        </tbody>
                    </table>
                </div>
            <?php echo form_close()?>
        </div>
        <div style="text-align: center; margin-top: 52px">
            <?php
            echo getIconButton([
                'txt' => '목록',
                'icon' => 'list',
                'buttonClass' => 'btn',
                'buttonStyle' => 'width:180px; height: 46px',
                'width' => '21',
                'height' => '20',
                'stroke' => 'black',
                'extra' => [
                    'type' => 'button',
                    'onclick' => 'location.href=\''._link_url('/goods/timeSale').'\'',

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
                    'onclick' => 'frmRegisterConfirm();',
                ]
            ]);
            ?>
        </div>
    </div>
</div>
</div>
<!-- Modal S-->
<div class="modal fade" id="dataModal" tabindex="-1" style="margin-top:3em;" aria-labelledby="dataModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;width:80vh">
            <div class="modal-header">
                <h5 class="modal-title" id="dataModalLabel">정보 등록</h5>
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

<script>
let productPcikList = [];
let bestProductPcikList = <?php echo json_encode( $productIdxs ); ?>;
let addProductPickList =[];
let groupProductLists = [];
$(document).on('dblclick', '.price', function(){

    var amt =  $(this).text();
    $(this).html( '<input type="text" name="i_price" style="width:80px !important; height:24px !important;text-align:left;padding:0;margin:0 !important;margin-top:12px !important;" value="'+amt+'" class="form-control" numberwithcomma>' );
    // input에 focus를 설정하고, 높이가 유지되도록 설정
    $(this).find('input').focus().css({
        height: '36px',  // input 높이를 고정
        marginTop: '0', // margin-top 없애기
        lineHeight: '36px', // input 안의 텍스트 높이 맞추기
        paddingBottom: '0px',
    });

    $(this).find('[name=i_price]').on('blur keydown', function(){
        if (event.type === 'blur' || ( event.keyCode === 13)) {
            if( amt == $(this).val() ){
                $(this).parent().text(amt);
            }else{
                $this = $(this);
                var price   = $(this).val();
                var g_idx = $(this).parent().data('g-idx');

                $.ajax({
                    url: '/apis/goods/updateGoodsPrice',
                    type: 'post',
                    data: 'g_idx='+g_idx+'&amt='+price,
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
                        $this.parent().text(price);
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
        }

    });
});

$('#tableSort').sortable({
    handle: '.group-move-icons',
    update: function(event, ui) {
        var idsInOrder = $('#tableSort').sortable('toArray',{ attribute : 'data-idx'});

        console.log(idsInOrder);
    }
});
function removeLine( id )
{
    $('#' + id + ' tbody input[type="checkbox"]:checked').each(function() {
        var targetIdx = $(this).val(); // 체크박스의 값을 가져옵니다.

        // data-idx 속성이 targetIdx인 <tr> 요소를 찾아서 삭제합니다.
        $('#' + id + ' tbody tr[data-idx="' + targetIdx + '"]').remove();

        var index = bestProductPcikList.indexOf(targetIdx); // 해당 값의 인덱스를 찾습니다.
        if (index !== -1) {
            bestProductPcikList.splice(index, 1); // 해당 인덱스에서 1개 요소를 제거합니다.
        }

    });
}


function openDataLayer( gbn ){

    let url = '/apis/goods/getPopProductLists';
    let data='openGroup='+gbn+'&picLists='+bestProductPcikList;
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
            $('#dataModal .modal-content').empty().html( response.page_datas.lists_row );
            $('.dropdown-layer').hide();
            //var modal = new bootstrap.Modal(document.getElementById(id));
            var modalElement = document.getElementById('dataModal');
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
    });       // 모달 열기

}
function getCheckedItems(){
    var checkedValues = $('.check-item-pop:checked').map(function() {
        return $(this).val();
    }).get();

    return checkedValues;
}

function addProductItem( id ){
    let goods_idxs = getCheckedItems();
    $.ajax({
        url: '/apis/goods/goodsAddRows',
        type: 'post',
        data: 'goodsIdxs='+goods_idxs+'&targetId='+id,
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

            goods_idxs.forEach(function(p_idx) {
                bestProductPcikList.push(p_idx);
            });


            $("#gListsTable tbody").append( response.page_datas.lists_row );


            setTimeout(() => {
                getSearchList();
            }, 200);

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
function frmRegisterConfirm()
{
    box_confirm('저장 하시겠습니까?', 'q', '', frmRegister );
}
function frmRegister() {
    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_register').find('input, button, select');

    var isSubmit = true;

    var formData = new FormData($('#frm_register')[0]);


    $.ajax({
        url: '/apis/goods/weeklyGoodsDetailRegister',
        method: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {
            // inputs.prop('disabled', true);
            $('#preloader').show();
        },
        complete: function() {
            inputs.prop('disabled', false);

        },
        success: function(response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status != 200) {
                var error_message = response.errors.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }
            if (window.opener) {
                // 부모 창의 특정 함수 호출 (부모 창에서 정의된 함수)
                window.opener.reloadGroupGoods();
            } else {
                console.log("Parent window not accessible");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            inputs.prop('disabled', false);
            $('#preloader').hide();
            return false;
        }
    });
}
</script>
<?php echo form_close();?>
<!-- Form List Modal E-->
<?php

$script = "

";

$owensView->setFooterScript($script);