
<?php
    helper(['owens','owens_form', 'owens_url']);
    $view_datas = $owensView->getViewDatas();

    $sQUERY_STRING = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $sQUERY_STRING = _add_query_string($sQUERY_STRING, _elm($view_datas, 'query_string', []));

    $aLists        = _elm($view_datas, 'lists', []);
    $row           = _elm($view_datas, 'row', []);
    $aConfig       = _elm( _elm($view_datas, 'aConfig'), 'status', []);
    $total_rows    = _elm($view_datas, 'total_rows', []);


    if (empty($aLists) === false)
    {
        foreach ($aLists as $vData)
        {
            $row++;
            $no = $total_rows - $row + 1;
?>

<tr data-point-idx="<?php echo _elm( $vData, 'M_IDX' )?>" style="font-size:10px">
    <td class="body2-c nowrap padding-sm"><?php echo $no?></td>
    <td class="body2-c nowrap padding-sm align-left"><?php echo _elm( $vData, 'C_NAME' )?><br><?php echo _elm( $vData, 'C_DISCRIPTION' )?></td>
    <td class="body2-c nowrap padding-sm"><?php echo date( 'Y-m-d H:i' , strtotime( _elm( $vData , 'I_ISSUE_AT' ) ) )?></td>
    <td class="body2-c nowrap padding-sm"><?php echo _elm( $vData, 'C_PERIDO_LIMIT' ) == 'N' ? '무제한' : date( 'Y-m-d' , strtotime( _elm( $vData , 'C_PERIOD_START_AT' ) ) ) .'~'.  date( 'Y-m-d' , strtotime( _elm( $vData , 'C_PERIOD_END_AT' ) ) )?></td>
    <td class="body2-c nowrap padding-sm"><?php echo _elm( $vData, 'C_PUB_GBN' ) == 'P'? '지정발행' : ( _elm( $vData, 'C_PUB_GBN' ) == 'D' ? '다운로드' : '자동발행' ) ?></td>
    <td class="body2-c nowrap padding-sm">
        <?php
            echo getButton([
                'text' => '보기',
                'class' => 'btn btn-secondary',
                'style' => 'width: 40px; height: 25px; font-size:11px',
                'extra' => [
                    'onclick' => 'event.preventDefault();openLayerPopup( \''. _elm( $vData, 'C_IDX' ) .'\' )',
                ]
            ]);
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
    <td colspan="8">
        "쿠폰 발급내역이 없습니다."
    </td>
</tr>
<?php
    }
?>