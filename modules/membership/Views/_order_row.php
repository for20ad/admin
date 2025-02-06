
<?php
    helper(['owens','owens_form', 'owens_url']);
    $view_datas = $owensView->getViewDatas();

    $sQUERY_STRING = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $sQUERY_STRING = _add_query_string($sQUERY_STRING, _elm($view_datas, 'query_string', []));

    $aLists        = _elm($view_datas, 'lists', []);
    $row           = _elm($view_datas, 'row', []);
    $aOrderStatus  = _elm($view_datas, 'aOrderStatus', []);
    print_r( $aOrderStatus );
    $total_rows    = _elm($view_datas, 'total_rows', []);


    if (empty($aLists) === false)
    {
        foreach ($aLists as $vData)
        {
            $row++;
            $no = $total_rows - $row + 1;
?>

<tr data-order-idx="<?php echo _elm( $vData, 'O_IDX' )?>" style="font-size:10px">
    <td class="body2-c nowrap padding-sm"><?php echo $no?></td>
    <td class="body2-c nowrap padding-sm"><?php echo date('Y-m-d H:i', strtotime( _elm( $vData, 'O_ORDER_AT' ) ) )?></td>
    <td class="body2-c nowrap padding-sm"><?php echo _elm( $vData, 'O_ORDID' )?></td>
    <td class="body2-c nowrap padding-sm">
        <?php echo _elm( $vData, 'IS_FIRST_ORDER') == true? '<div style="display: flex;padding: 4px;justify-content: center;align-items: center;gap: 8px;"><span style="padding:2px 2px;background: var(--sns---tblr-facebook, #1877F2);color:#fff">첫주문</span></div>' : '' ?>
        <?php echo _elm( $vData, 'O_TITLE' )?>
    </td>
    <td class="body2-c nowrap padding-sm"><?php $aCnt = explode( ',', _elm( $vData, 'P_QTYS' ) ); echo number_format( array_sum(array_map('intval', $aCnt)) )?></td>
    <td class="body2-c nowrap padding-sm"><?php echo number_format( _elm( $vData, 'O_TOTAL_PRICE' ) )?></td>
    <td class="body2-c nowrap padding-sm"><?php echo number_format( _elm( $vData, 'O_PG_PRICE' ) )?></td>
    <td class="body2-c nowrap padding-sm"><?php echo _elm( $aOrderStatus, _elm( $vData, 'O_STATUS' ) )?></td>
</tr>
<?php
        }
    }
    else
    {
?>
<tr>
    <td colspan="8">
        "주문 내역이 없습니다."
    </td>
</tr>
<?php
    }
?>
