
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


    if (empty($aLists) === false)
    {
        $nLoop = 0;
        foreach ($aLists as $vData)
        {
            $nLoop++;
            $row++;
            $no = $total_rows - $row + 1;
?>
<tr data-idx="<?php echo _elm( $vData , 'B_IDX' )?>">
    <td class="body2-c small-font">
        <div class="checkbox checkbox-single">
            <?php
                $setParam = [
                    'name' => 'o_i_idx[]',
                    'id' => 'o_i_idx_',
                    'value' =>'' ,
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
    <td class="body2-c small-font"><?php echo $no?></td>
    <td class="body2-c small-font"><?php echo _elm( $vData , 'O_ORDER_AT' ) ?><br><a href='javascript:;'  onclick="javascript:openLayer('<?php echo _elm( $vData , 'O_IDX' ) ?>', 'dataModal')">( <span style="color:#206BC4"><?php echo _elm( $vData, 'O_ORDID' )?></span> )</a></td>
    <td class="body2-c small-font"><?php echo _elm( $vData, 'MB_NM' )?></td>
    <td class="body2-c small-font"><?php echo _elm( $aPayMethods, _elm( $vData, 'O_PAY_METHOD' ) )?></td>
    <td class="body2-c small-font">
        <?php
            $aStatus = explode( ',', _elm( $vData, 'STATUSES' ) );
            $statusTxt = [];
            foreach( $aStatus as $status ){
                $statusTxt[] = _elm( $aOrderStatus, $status );
            }

            echo implode( '<br>', $statusTxt );

        ?>
    </td>
    <td class="body2-c small-font" style="text-align:left"><?php echo _elm( $vData , 'O_TITLE' )?></td>
    <td class="body2-c small-font"><?php echo number_format( _elm( $vData , 'O_TOTAL_PRICE' ) ) ?></td>
    <td class="body2-c small-font"><?php echo number_format( _elm( $vData, 'O_CPN_MINUS_PRICE', 0 ) + _elm( $vData, 'O_CPN_PLUS_MINUS_PRICE', 0 ))?></td>
    <td class="body2-c small-font"><?php echo number_format( _elm( $vData, 'O_SHIP_PRICE', 0 ) + _elm( $vData, 'O_ADD_SHIP_PRICE', 0 ) )?></td>
    <td class="body2-c small-font"><?php echo number_format( _elm( $vData, 'O_PG_PRICE' ) )?></td>
    <td class="body2-c small-font">
    <?php
        $extras                          = [
            'onclick' => 'openMemoLayer(\''._elm( $vData , 'O_IDX' ).'\', \'memoModal\')',
            'type' => 'button',
        ];
        $newIcon = '';

        if( empty( _elm( $vData, 'aMemo') ) === false ){
            $extras += [
                'onmouseover' => '$(this).closest(\'tr\').find(\'#aLastMemo\').show()',
                'onmouseout' => '$(this).closest(\'tr\').find(\'#aLastMemo\').hide()',
            ];
            $createdAt = _elm(_elm($vData, 'aMemo'), 'M_CREATE_AT'); // 생성일 가져오기
            if ($createdAt) {
                $createdTimestamp = strtotime($createdAt); // 생성일 타임스탬프
                $currentTimestamp = time(); // 현재 타임스탬프

                // 3일(초 단위) = 3 * 24 * 60 * 60
                if (($currentTimestamp - $createdTimestamp) <= (3 * 24 * 60 * 60)) {
                    $newIcon = '&nbsp;<div style="position:relative;"><div class="newBadge">'._elm($vData, 'aMemoCnt').'</div></div>';
                }
            }
        }


        echo getButton([
            'text' => '보기 '.$newIcon,
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

</tr>
<?php
        }
    }
    else
    {
?>
<tr>
    <td colspan="12">
        "주문 목록이 없습니다."
    </td>
</tr>
<?php
    }
?>