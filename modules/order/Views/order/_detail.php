<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aData            = _elm($view_datas, 'aData', []);
    $aOrderGoodsInfo  = _elm( $view_datas, 'orderGoodsInfo', [] );
    $aOdrStatus       = _elm( $view_datas, 'aOrderConf', [] );
    $aPayMethodConf   = _elm( $view_datas, 'aPayMethodConf', [] );
    $aMemberInfo      = _elm( $view_datas, 'aMemberInfo', [] );
    $aMemberGrade     = _elm( $view_datas, 'aMemberGrade', [] );
    $aDeliveryInfo    = _elm( $view_datas, 'aDeliveryInfo', [] );
    // echo '<pre>';
    // print_r( $aData );
    // echo '</pre>';
?>
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #dataModal, #dataModal * {
        visibility: visible;
        transform: scale(0.97);
        transform-origin: top left;
        margin-left: 0;
        margin-top: 0;
    }

    .col-md {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .print-hidden {
        display: none !important;
    }

    .aTable > td,th {
        padding: 14px 12px !important;
        padding-left: 35px !important;
        border: 1px solid #e6e7e9 !important; /* 패딩 적용을 확인하기 위해 border를 남겨놓음 */
        background-color: transparent !important; /* 배경색 제거 */
    }
    .bTable th{
        border:0 !important; /* 배경색 제거 */
        border-top: 1px solid #e6e7e9 !important; /* 패딩 적용을 확인하기 위해 border를 남겨놓음 */
        border-bottom: 1px solid #e6e7e9 !important; /* 패딩 적용을 확인하기 위해 border를 남겨놓음 */
    }
    .aTable {
        width: 100% !important;
    }
}
</style>
<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_modify', 'autocomplete' => 'off']); ?>
<input type="hidden" name="o_idx" value="<?php echo _elm( $aData, 'O_IDX' )?>">
<div  id="contentToPrint">
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
                                주문내역
                            </p>
                        </div>
                        <div class="print-hidden ">
                        <!-- 아코디언 토글 버튼과 getButton을 한 줄로 배치 -->
                            <div class=" d-flex align-items-center"  style="text-align: right;">

                                <?php
                                echo getIconButton([
                                    'txt' => '인쇄하기',
                                    'icon' => 'print',
                                    'buttonClass' => 'btn',
                                    'buttonStyle' => 'width:130px; height: 32px',
                                    'width' => '18',
                                    'height' => '18',
                                    'stroke' => 'black',
                                    'extra' => [
                                        'id'   => 'printButton',
                                        'type' => 'button',
                                    ]
                                ]);
                                ?>

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
                    </div>
                </div>

                <div class="card-body">
                    <!-- 카드 내용 -->
                    <div class="table-responsive bTable" style="border-bottom:1px solid #ccc;">
                        <table class="table table-vcenter">
                            <colgroup>
                                <col style="width:3%">
                                <col style="width:15%">
                                <col style="width:7%">
                                <col style="width:30%">
                                <col style="width:8%">
                                <col style="width:15%">
                                <col style="width:20%">
                                <col style="width:*%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>번호</th>
                                    <th>주문번호</th>
                                    <th>이미지</th>
                                    <th>주문상품</th>
                                    <th>수량</th>
                                    <th>금액</th>
                                    <th>처리상태</th>
                                    <th>상품코드</th>
                                </tr>
                            </thead>
                            <tbody id="rows">
                            <?php
                            if( empty( _elm( $aData, 'orderInfos' ) ) === false ):
                                foreach( _elm( $aData, 'orderInfos' ) as $aKey => $aInfo ):
                            ?>
                                <tr>
                                <td>
                                        <div class="form-inline">
                                        <?php
                                            $setParam = [
                                                'name' => 'o_i_idx[]',
                                                'id' => 'o_i_idx_'._elm( $aInfo, 'O_IDX' ),
                                                'value' => _elm( $aInfo, 'O_IDX' ) ,
                                                'label' => '',
                                                'style' => 'padding-left:0;margin-left:0',
                                                'checked' => false,
                                                'extraAttributes' => [
                                                    'aria-label' => 'Single checkbox One',
                                                    'class'=>'check-item',

                                                ]
                                            ];
                                            echo getCheckBox( $setParam );
                                        ?>
                                    <?php echo $aKey+1?>
                                        </div>
                                    </td>
                                    <td><?php echo count( _elm( $aData, 'orderInfos' ) ) > 1 ? _elm( $aInfo, 'ordIdPrd' ) : _elm( $aInfo, 'O_ORDID' ) ?></td>
                                    <td><img src="/<?php echo _elm( $aInfo, 'goodsImg' )?>" style="width:80px"></td>
                                    <td class="" title="<?php echo _elm( _elm( $aInfo, 'goodsInfo' ), 'P_NAME' )?>"><?php echo _elm( _elm( $aInfo, 'goodsInfo' ), 'P_NAME' )?></td>
                                    <td><?php echo _elm( $aInfo, 'O_TOTAL_COUNT' ) ?? _elm( _elm( $aInfo, 'goodsInfo' ), 'P_NUM' )?></td>
                                    <td>
                                        <?php echo number_format( _elm( $aInfo, 'O_TOTAL_PRICE' ) )?>
                                        <?php echo empty( _elm( $aInfo, 'O_USE_CPN_MINUS_PRICE' ) ) === false
                                        ? '<br> (-'.number_format( _elm( $aInfo, 'O_USE_CPN_MINUS_PRICE' ) ).' ) '
                                        : ''
                                        ?>
                                    </td>
                                    <td><?php echo _elm( $aOdrStatus, _elm( $aInfo, 'O_STATUS' ) )?></td>
                                    <td><?php echo _elm( _elm( $aInfo, 'goodsInfo' ), 'P_PRID' )?></td>
                                </tr>
                            <?php
                                endforeach;
                            endif;
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="text-align:right;background-color:#FFFBE2;line-height:38px;padding-right:1.2rem">
                        <b>총 결제 금액</b> :
                        <?php echo number_format( _elm( $aData, 'O_TOTAL_PRICE' ) ) ?> +
                        <?php echo number_format( _elm( $aData, 'O_SHIP_PRICE' ) + _elm( $aData, 'O_ADD_SHIP_PRICE' ) )?> ( 배송비 )
                        <?php echo _elm( $aData, 'O_USE_MILEAGE' ) > 0 ? ' - '.number_format( _elm( $aData, 'O_USE_MILEAGE' ) ). ' ( 마일리지 )' : '' ?>
                        <?php echo _elm( $aData, 'O_CPN_MINUS_PRICE' ) > 0 ? ' - '.number_format( _elm( $aData, 'O_CPN_MINUS_PRICE' ) ). ' ( 쿠폰사용 )' : '' ?>
                        <?php echo _elm( $aData, 'O_CPN_PLUS_MINUS_PRICE' ) > 0 ? ' -'.number_format( _elm( $aData, 'O_CPN_PLUS_MINUS_PRICE' ) ). ' ( 추가쿠폰사용 )' : '' ?>
                        = <b><?php echo number_format( _elm( $aData, 'O_PG_PRICE' ) )?></b>

                    </div>
                    <div class="col-md" style="padding-top:1.45rem">
                        <div class="card" style="border:0px">
                            <div class="card-body" style="padding:0px ">
                                <div class="form-inline" >
                                선택된 항목을
                                <?php
                                    $options  = [''=>'선택'];
                                    $options += $aOdrStatus;
                                    $extras   = ['id' => 'i_status', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-left:0.235em;margin-right:0.235em;',];
                                    $selected = '';
                                    echo getSelectBox('i_status', $options, $selected, $extras);
                                ?>
                                으로 변경.
                                <?php
                                    $setParam = [
                                        'name' => 'i_aTalkWithSendFlag',
                                        'id' => 'i_aTalkWithSendFlag_Y',
                                        'value' => 'Y',
                                        'label' => '알림톡,푸시 발송',
                                        'style' => 'width:160px;padding-left:2.5rem;margin:0.7rem',
                                        'checked' => true,
                                        'extraAttributes' => [
                                            'aria-label' => 'Single checkbox One',
                                            'class'=>'check-item',
                                            'onchange'=>'$(this).is(\':checked\') ? $(\'#i_aTalkOnlySendFlag_Y\').prop(\'checked\', false) : \'\';'
                                        ]
                                    ];
                                    echo getCheckBox($setParam);
                                ?>

                                <?php
                                    $setParam = [
                                        'name' => 'i_aTalkOnlySendFlag',
                                        'id' => 'i_aTalkOnlySendFlag_Y',
                                        'value' => 'Y',
                                        'label' => '알림톡만 발송',
                                        'style' => 'width:100px;margin:0.7rem',
                                        'checked' => false,
                                        'extraAttributes' => [
                                            'aria-label' => 'Single checkbox One',
                                            'class'=>'check-item',
                                            'onchange'=>'$(this).is(\':checked\') ? $(\'#i_aTalkWithSendFlag_Y\').prop(\'checked\', false) : \'\';'
                                        ]
                                    ];
                                    echo getCheckBox($setParam);
                                ?>

                                <?php
                                    echo getButton([
                                        'text' => '상태변경',
                                        'class' => 'btn btn-success',
                                        'style' => 'width: 180px; height: 36px',
                                        'extra' => [
                                            'onclick' => 'orderStatusChangeConfirm()',
                                            'type' => 'button',
                                        ]
                                    ]);
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2">
        <!-- 좌측 영역 -->
        <div class="col-md-12">
            <!-- 첫번째 행 -->
            <div class="row row-deck row-cards">
                <!-- 이미지 카드 -->
                <div class="col-md">
                    <div class="card" style="border:0px">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        결제정보
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body" style="padding:0px ">
                            <table class="table table-vcenter border aTable" style="margin:0 !important;border-top:0px !important;">
                                <colgroup>
                                    <col style="width:40%">
                                    <col style="width:*">
                                </colgroup>
                                <tr>
                                    <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">상품명</th>
                                    <td style="padding:14px 12px">
                                        <b><?php echo number_format( _elm( $aData, 'O_TOTAL_PRICE' ) ) ?></b> 원
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">배송비</th>
                                    <td style="padding:14px 12px">
                                    <b style="color:#206BC4 !important">(+) <?php echo number_format( _elm( $aData, 'O_SHIP_PRICE' ) +  _elm( $aData, 'O_ADD_SHIP_PRICE' ) )?></b> 원
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">할인액</th>
                                    <td style="padding:14px 12px">
                                    <b style="color:#D63939">(-) <?php echo number_format( _elm( $aData, 'O_CPN_MINUS_PRICE' ) +  _elm( $aData, 'O_CPN_PLUS_MINUS_PRICE' ) )?></b> 원
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">결제금액</th>
                                    <td style="padding:14px 12px">
                                    <b> <?php echo number_format( _elm( $aData, 'O_PG_PRICE' ) )?></b> 원
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">적립금액</th>
                                    <td style="padding:14px 12px">
                                    <b> <?php echo number_format( _elm( $aData, 'O_SAVE_MILEAGE' ) )?></b> 원
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md">
                    <div class="card" style="border:0px">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        결제수단
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body" style="padding:0px ">
                            <table class="table table-vcenter border aTable" style="margin:0 !important;border-top:0px !important;">
                                <colgroup>
                                    <col style="width:40%">
                                    <col style="width:*">
                                </colgroup>
                                <tr>
                                    <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">결제방법</th>
                                    <td style="padding:14px 12px">
                                        <b><?php echo number_format( _elm( $aData, 'O_TOTAL_PRICE' ) ) ?></b> 원
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">카드사명</th>
                                    <td style="padding:14px 12px">
                                    <b style="color:#206BC4">(+) <?php echo number_format( _elm( $aData, 'O_SHIP_PRICE' ) +  _elm( $aData, 'O_ADD_SHIP_PRICE' ) )?></b> 원
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">결제확인일</th>
                                    <td style="padding:14px 12px">
                                    <b style="color:#D63939 !important">(-) <?php echo number_format( _elm( $aData, 'O_CPN_MINUS_PRICE' ) +  _elm( $aData, 'O_CPN_PLUS_MINUS_PRICE' ) )?></b> 원
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">영수증 신청여부</th>
                                    <td style="padding:14px 12px">
                                    <b> <?php echo number_format( _elm( $aData, 'O_PG_PRICE' ) )?></b> 원
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row row-deck row-cards">
                <!-- 이미지 카드 -->
                <div class="col-md">
                    <div class="card" style="border:0px">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        주문자정보
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body" style="padding:0px ">
                            <table class="table table-vcenter border aTable" style="margin:0 !important;border-top:0px !important;">
                                <colgroup>
                                    <col style="width:40%">
                                    <col style="width:*">
                                </colgroup>
                                <tr>
                                    <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">주문자명</th>
                                    <td style="padding:14px 12px">
                                        <?php echo _elm( $aMemberInfo, 'MB_NM' )?>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">회원등급</th>
                                    <td style="padding:14px 12px">
                                        <?php echo _elm( $aMemberGrade, _elm( $aMemberInfo, 'MB_GRADE_IDX' ) )?>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">구매자 IP</th>
                                    <td style="padding:14px 12px">
                                        <?php echo _elm( $aData, 'O_ORDER_IP' ) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">휴대폰번호</th>
                                    <td style="padding:14px 12px">
                                        <?php echo _elm( $aMemberInfo, 'MB_MOBILE_DEC' )?? "-" ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">이메일</th>
                                    <td style="padding:14px 12px">
                                        <?php echo _elm( $aMemberInfo, 'MB_EMAIL_DEC' )?? "-" ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md">
                    <div class="card" style="border:0px">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        배송정보
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body" style="padding:0px ">
                            <?php
                                if( empty( $aDeliveryInfo ) === false ):
                                    foreach( $aDeliveryInfo as $dKey => $delivery ):
                            ?>
                                <table class="table table-vcenter border aTable" style="margin:0 !important;border-top:0px !important;<?php echo $dKey > 0 ? 'padding-top:1.1rem' : ''?>">
                                    <colgroup>
                                        <col style="width:40%">
                                        <col style="width:*">
                                    </colgroup>
                                    <tr>
                                        <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">주문번호</th>
                                        <td style="padding:14px 12px">
                                            <?php echo _elm( $delivery, 'O_ORDIDPRD' )?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">수령자명</th>
                                        <td style="padding:14px 12px">
                                            <?php echo _elm( $delivery, 'O_RCV_NAME' )?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">연락처</th>
                                        <td style="padding:14px 12px;">
                                            <?php echo _elm( $delivery, 'O_RCV_MOBILE_NUM' ) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">주소</th>
                                        <td style="padding:14px 12px">
                                            <?php echo _elm( $delivery, 'O_ZIPCODE' ) ?> <?php echo _elm( $delivery, 'O_ADDRESS1' )?> <?php echo _elm( $delivery, 'O_ADDRESS2' )?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="background-color:#F8FAFC;border-right:1px solid #E6E7E9; padding:4px 12px">배송 메시지</th>
                                        <td style="padding:14px 12px">
                                        <b> <?php echo _elm( $delivery, 'O_SHIP_MSG' ) ?>
                                        </td>
                                    </tr>
                                </table>
                            <?php
                                    endforeach;
                                endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- 버튼 -->
    <div class="print-hidden" style="text-align: center; margin-top: 52px">
        <?php
        echo getButton([
            'text' => '닫기',
            'class' => 'btn',
            'style' => 'width: 180px; height: 46px',
            'extra' => [
                'onclick' => 'event.preventDefault();$(".btn-close").trigger("click")',
                'type' => 'button',
            ]
        ]);
        ?>
    </div>
</div>
<?php echo form_close() ?>
<script>
    $('#printButton').on('click', function() {
        window.print();  // print 창을 띄운다
    });
</script>

