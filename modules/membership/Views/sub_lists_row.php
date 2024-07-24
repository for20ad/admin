
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

<tr data-idx="<?php echo _elm( $vData , 'MB_IDX' )?>" <?php if( _elm($vData, 'MB_STATUS' ) == 9 ) echo 'class="deleted"'?>>
    <td class="body2-c nowrap"><?php echo _elm( $vData , 'MB_USERID' )?></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData , 'MB_NM' )?></td>
    <td class="body2-c nowrap"><?php echo _add_dash_tel_num( _elm( $vData, 'MB_MOBILE_NUM_DEC') )?></td></td>
    <td class="body2-c nowrap">
    <?php
        echo getButton([
            'text' => '선택',
            'class' => 'btn',
            'style' => 'width: 110px; height: 46px',
            'extra' => [
                'onclick' => 'event.preventDefault();setRegistMbidxConfirm("'._elm( $vData , 'MB_IDX' ).'")',
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
    <td colspan="11">
        "등록된 회원 목록이 없습니다."
    </td>
</tr>
<?php
    }
?>