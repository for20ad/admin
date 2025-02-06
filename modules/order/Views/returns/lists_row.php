
<?php
    helper(['owens','owens_form', 'owens_url']);
    $view_datas = $owensView->getViewDatas();

    $sQUERY_STRING = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $sQUERY_STRING = _add_query_string($sQUERY_STRING, _elm($view_datas, 'query_string', []));

    $aLists        = _elm($view_datas, 'lists', []);
    $aPayMethods   = _elm($view_datas, 'aPayMethodConf', []);
    $aOrderStatus  = _elm($view_datas, 'aOrderConf', []);
    $row           = _elm($view_datas, 'row', []);
    $total_rows    = _elm($view_datas, 'total_rows', []);
    $aDeliveryCompanys  = _elm($view_datas, 'deliveryCompanys', []);
    $sDeliverMain  = _elm($view_datas, 'deliverMain', []);

    $statusTextClass = [
        'PayComplete'=>'status-normal',
        'RefundRequest'=>'status-ready',
        'ShippingCancelRequest' => 'status-deleted',
        'ProductWaiting'=>'status-normal',
        'ShippingWaiting'=>'status-normal',
    ];

    if (empty($aLists) === false) {
        $nLoop = 0;
        $previousOrderId = null; // 이전 ORDER_ID 저장
        $orderRowspanCount = []; // ORDER_ID별 rowspan 개수 저장

        // ORDER_ID별 rowspan 개수 계산
        foreach ($aLists as $vData) {
            $orderId = _elm($vData, 'ORDER_ID');
            if (!isset($orderRowspanCount[$orderId])) {
                $orderRowspanCount[$orderId] = 0;
            }
            $orderRowspanCount[$orderId]++;
        }

        $row = _elm($view_datas, 'row', []);
        foreach ($aLists as $vData) {
            $nLoop++;
            $row++;
            $no = $total_rows - $row + 1;
            $currentOrderId = _elm($vData, 'ORDER_ID');
    ?>
    <tr data-idx="<?php echo _elm($vData, 'B_IDX') ?>">

        <td class="body2-c small-font">
            <div class="checkbox checkbox-single">
                <?php
                $setParam = [
                    'name' => 'o_i_idx[]',
                    'id' => 'o_i_idx_'._elm( $vData, 'O_IDX' ),
                    'value' => _elm( $vData, 'O_IDX' ),
                    'label' => '',
                    'checked' => false,
                    'extraAttributes' => [
                        'aria-label' => 'Single checkbox One',
                        'class' => 'check-item',
                    ]
                ];
                echo getCheckBox($setParam);
                ?>
            </div>
        </td>

            <td class="body2-c small-font">
                <?php echo $no ?>
            </td>
        <?php if ($previousOrderId !== $currentOrderId):?>
            <td class="body2-c small-font" rowspan="<?php echo $orderRowspanCount[$currentOrderId]; ?>">
                <?php echo _elm($vData, 'O_ORDER_AT') ?><br>
                <a href='javascript:;' onclick="javascript:openLayer('<?php echo _elm($vData, 'ORDER_IDX') ?>', 'dataModal')">
                    (<span style="color:#206BC4"><?php echo _elm($vData, 'O_ORDID') ?></span>)
                </a>
            </td>
            <td class="body2-c small-font" rowspan="<?php echo $orderRowspanCount[$currentOrderId]; ?>">
                <?php echo _elm($vData, 'MB_NM') ?>
            </td>
            <td class="body2-c small-font" rowspan="<?php echo $orderRowspanCount[$currentOrderId]; ?>">
                <?php echo _elm($vData, 'O_RCV_NAME') ?>
            </td>
            <td class="body2-c small-font" rowspan="<?php echo $orderRowspanCount[$currentOrderId]; ?>">
                <?php echo _elm($aPayMethods, _elm($vData, 'O_PAY_METHOD')) ?>
            </td>
        <?php endif; ?>
        <td class="body2-c small-font">
            <span class="status-box <?php echo _elm( $statusTextClass, _elm($vData, 'O_STATUS') )?>"><?php echo _elm($aOrderStatus, _elm($vData, 'O_STATUS')); ?></span>
        </td>

        <td class="body2-c small-font">
            <?php
                $options  = $aDeliveryCompanys;
                $extras   = ['id' => 'i_delivery_comp', 'class' => 'form-select', 'style' => 'width:200px;max-width: 200px;margin-right:0.235em;'];
                $selected = _elm( $vData, 'O_SHIP_COMPANY' ) ?? $sDeliverMain;
                echo getSelectBox('i_delivery_comp', $options, $selected, $extras);
            ?>
            <div class="form-inline" style="padding:0;margin:0" >
                <input type="text" name="i_ship_number" class="form-control"
                    value="<?php echo _elm( $vData, 'O_SHIP_NUMBER' ) ?>" data-orgShipNum="<?php echo _elm( $vData, 'O_SHIP_NUMBER' )?>"
                    data-idx="<?php echo _elm($vData, 'O_IDX')?>"
                    onblur="if (!window.preventOnBlur) {
                        if( $(this).val() != $(this).data('orgShipNum') ){
                            if( $(this).val().trim() == '' ){
                                $(this).prop('disabled', true);
                            }else{
                                changeShipNumConfirm($(this));
                            }
                        }else{
                            $(this).prop('disabled', true);
                        }

                    }"
                    onkeyup="if (event.key === 'Escape') {
                        window.preventOnBlur = true;
                        $(this).prop('disabled', true);
                        setTimeout(() => { window.preventOnBlur = false; }, 100);
                    }"
                    style="width:150px;margin-left: 0px !important;" disabled='true'
                >
                <?php
                echo getButton([
                    'text' => '수정',
                    'class' => 'btn btn-primary',
                    'style' => 'width: 40px; height: 30px',
                    'extra' => [
                        'onclick' => 'enableEdit(this)'
                    ],
                ]);
                ?><?php
                echo getButton([
                    'text' => '추적',
                    'class' => 'btn ',
                    'style' => 'width: 40px; height: 30px',
                    'extra' => [
                        'onclick' => 'openTrackingLayer(\'' . _elm($vData, 'O_IDX') . '\', \'trackModal\')'
                    ],
                ]);
                ?>
            </div>
        </td>

        <td class="body2-c small-font" style="text-align:left"><?php echo _elm($vData, 'P_NAME'). ' '._elm($vData, 'P_OPTION_NAME')  ?></td>

        <?php if ($previousOrderId !== $currentOrderId): ?>
            <td class="body2-c small-font" rowspan="<?php echo $orderRowspanCount[$currentOrderId]; ?>"><?php echo number_format(_elm($vData, 'O_TOTAL_PRICE')) ?></td>
            <td class="body2-c small-font" rowspan="<?php echo $orderRowspanCount[$currentOrderId]; ?>"><?php echo number_format(_elm($vData, 'O_CPN_MINUS_PRICE', 0) + _elm($vData, 'O_CPN_PLUS_MINUS_PRICE', 0)) ?></td>
            <td class="body2-c small-font" rowspan="<?php echo $orderRowspanCount[$currentOrderId]; ?>"><?php echo number_format(_elm($vData, 'O_SHIP_PRICE', 0) + _elm($vData, 'O_ADD_SHIP_PRICE', 0)) ?></td>
            <td class="body2-c small-font" rowspan="<?php echo $orderRowspanCount[$currentOrderId]; ?>"><?php echo number_format(_elm($vData, 'O_PG_PRICE')) ?></td>
            <td class="body2-c small-font" rowspan="<?php echo $orderRowspanCount[$currentOrderId]; ?>">
                <?php
                $extras = [
                    'onclick' => 'openMemoLayer(\'' . _elm($vData, 'ORDER_IDX') . '\', \'memoModal\')',
                    'type' => 'button',
                ];
                $newIcon = '';

                if (!empty(_elm($vData, 'aMemo'))) {
                    $extras += [
                        'onmouseover' => '$(this).closest(\'tr\').find(\'#aLastMemo\').show()',
                        'onmouseout' => '$(this).closest(\'tr\').find(\'#aLastMemo\').hide()',
                    ];
                    $createdAt = _elm(_elm($vData, 'aMemo'), 'M_CREATE_AT'); // 생성일 가져오기
                    if ($createdAt) {
                        $createdTimestamp = strtotime($createdAt); // 생성일 타임스탬프
                        $currentTimestamp = time(); // 현재 타임스탬프

                        if (($currentTimestamp - $createdTimestamp) <= (3 * 24 * 60 * 60)) { // 3일 이내인지 확인
                            $newIcon = '&nbsp;<div style="position:relative;"><div class="newBadge">' . _elm($vData, 'aMemoCnt') . '</div></div>';
                        }
                    }
                }

                echo getButton([
                    'text' => '보기 ' . $newIcon,
                    'class' => 'btn',
                    'style' => 'width: 80px; height: 30px',
                    'extra' => $extras,
                ]);
                ?>
                <?php
                if( empty( _elm( $vData, 'aMemo') ) === false ):
                    $aMemo = _elm( $vData, 'aMemo');
                ?>
                <div style="position:relative;" >
                    <div id="aLastMemo" class="memo-layer" style="width:650px;display: none; position: absolute; right:5.3rem; top:<?php echo $nLoop == 1 ? '-1.8rem;': '-6rem;' ?> background: #fff; border: 1px solid #ccc; padding: 10px; z-index: 1000;">
                        <!-- Header -->
                        <div class="memo-header" style="display: flex; font-weight: bold; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 5px;">
                            <div style="flex: 2; text-align: center; box-sizing: border-box; padding: 5px;">작성일</div>
                            <div style="flex: 1.2; text-align: center; box-sizing: border-box; padding: 5px;">작성자</div>
                            <div style="flex: 1; text-align: center; box-sizing: border-box; padding: 5px;">메모구분</div>
                            <div style="flex: 1.3; text-align: center; box-sizing: border-box; padding: 5px;">상품주문번호</div>
                            <div style="flex: 3; text-align: center; box-sizing: border-box; padding: 5px;">메모내용</div>

                        </div>
                        <!-- Content -->
                        <div class="memo-content" style="display: flex; padding-top: 5px;">
                            <div style="flex: 2; text-align: center; box-sizing: border-box; padding: 5px;"><?php echo _elm($aMemo, 'M_CREATE_AT'); ?></div>
                            <div style="flex: 1.2; text-align: center; box-sizing: border-box; padding: 5px;"><?php echo _elm($aMemo, 'M_WRITER_NAME'); ?></div>
                            <div style="flex: 1; text-align: center; box-sizing: border-box; padding: 5px;"><?php echo _elm( $aOrderStatus, _elm($aMemo, 'M_STATUS') ); ?></div>
                            <div style="flex: 1.3; text-align: center; box-sizing: border-box; padding: 5px;"><?php echo str_replace( ',', '<br>',_elm($aMemo, 'M_ORD_INFO_TXT') ); ?></div>
                            <div style="flex: 3; text-align: center; box-sizing: border-box; padding: 5px;"><?php echo _elm($aMemo, 'M_CONTENT'); ?></div>
                        </div>
                    </div>
                </div>
                <?php
                endif;
                ?>
            </td>
        <?php endif; ?>
    </tr>
    <?php
            $previousOrderId = $currentOrderId; // 이전 ORDER_ID 갱신
        }
    } else {
    ?>
    <tr>
        <td colspan="14">"주문 목록이 없습니다."</td>
    </tr>
    <?php
    }
    ?>
<script>


</script>