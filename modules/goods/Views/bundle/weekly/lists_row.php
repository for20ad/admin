
<?php
    helper(['owens','owens_form', 'owens_url']);
    $view_datas = $owensView->getViewDatas();

    $sQUERY_STRING = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $sQUERY_STRING = _add_query_string($sQUERY_STRING, _elm($view_datas, 'query_string', []));

    $aLists        = _elm($view_datas, 'lists', []);
    $aConfig       = _elm($view_datas, 'aConfig', []);
    $row           = _elm($view_datas, 'row', []);
    $aCATE         = _elm($view_datas, 'cates', []);
    $total_rows    = _elm($view_datas, 'total_rows', []);


    if (empty($aLists) === false)
    {
        foreach ($aLists as $vData)
        {
            $row++;
            $no = $total_rows - $row + 1;
?>
<tr data-idx="<?php echo _elm( $vData , 'A_IDX' )?>">
    <td class="body2-c nowrap"><?php echo $no?></td>
    <td class="body2-c nowrap"><a href='#' onclick="javascript:openLayer('<?php echo _elm( $vData , 'A_IDX' ) ?>', 'dataModal')" ><?php echo  _elm( $vData , 'A_TITLE' )?></a></td>
    <td class="body2-c nowrap">
        <a href='#' onclick="javascript:openLayer('<?php echo _elm( $vData , 'A_IDX' ) ?>', 'dataModal')">
            <?php echo date( 'Y-m-d', strtotime( _elm( $vData, 'A_PERIOD_START_AT' ) ) )?> ~ <?php echo date( 'Y-m-d', strtotime( _elm( $vData, 'A_PERIOD_END_AT' ) ) )?>
        </a>
    </td>
    <td class="body2-c nowrap"><a href="<?php echo _link_url( '/goods/weeklyGoodsDetail/'._elm( $vData , 'A_IDX' ) )?>"><?php echo _elm( $vData, 'GOODS_CNT' ) ?></a></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'A_OPEN_STATUS' ) == 'Y' ? '노출' : '미노출' ?></td>
    <td class="body2-c nowrap">
        <?php echo empty( _elm( $vData , 'A_CREATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'A_CREATE_AT' ) ) ) : '-' ?><br>
        <?php echo empty( _elm( $vData , 'A_UPDATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'A_UPDATE_AT' ) ) ) : '-' ?>
    </td>
    <td class="body2-c nowrap">
    <?php
        echo getIconAnchor([
            'txt' => '',
            'icon' => 'delete',
            'buttonClass' => '',
            'buttonStyle' => '',
            'width' => '24',
            'height' => '24',
            'stroke' => '#616876',
            'extra' => [
                'onclick' => 'deleteTimeSaleConfirm("'. _elm( $vData , 'A_IDX' ) .'");',
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
    <td colspan="7">
        "등록된 주간상품 목록이 없습니다."
    </td>
</tr>
<?php
    }
?>