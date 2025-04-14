
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
<!-- <tr data-idx="<?php echo _elm( $vData , 'goodsNo' )?>">
    <td class="body2-c nowrap"><a href="https://gdadmin.sansuyuram.com/goods/goods_register.php?popupMode=yes&goodsNo=<?php echo _elm( $vData , 'goodsNo' )?>#goodsImage" target="_blank"><?php echo _elm($vData, 'goodsNo')?></a></td>
    <td class="body2-c nowrap"><a href="https://gdadmin.sansuyuram.com/goods/goods_register.php?popupMode=yes&goodsNo=<?php echo _elm( $vData , 'goodsNo' )?>#goodsImage" target="_blank"><?php echo _elm( $vData, 'goodsNm' )?></a></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData , 'cates' ) ?></td>
    <td class="body2-c nowrap"><input type="text" name="newCateNm" class="form-control" data-org-value="<?php echo _elm( $vData, 'newCateNm' );?>" value="<?php echo _elm( $vData, 'newCateNm' );?>"></td>
</tr> -->
<tr data-idx="<?php echo _elm( $vData , 'idx' )?>">
    <td class="body2-c nowrap"><?php echo $no .' - ' ._elm( $vData, 'd_option_g_name')?></td>
    <td class="body2-c nowrap"><a href="javascript:openLayer('<?php echo _elm( $vData, 'idx')?>', 'iconsModal')"><?php echo _elm( $vData, 'd_option_name' )?></a></td>
    <td class="body2-c nowrap"><a href="javascript:openLayer('<?php echo _elm( $vData, 'idx')?>', 'iconsModal')"><?php echo _elm( $vData , 'codes' ) ?></a></td>
    <td class="body2-c nowrap"><input type="text" name="d_result" class="form-control" data-org-value="<?php echo _elm( $vData, 'd_result' );?>" value="<?php echo _elm( $vData, 'd_result' );?>"></td>
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
                'onclick' => 'deleteDahaeOptionCodeConfirm("'. _elm( $vData , 'idx' ) .'");',
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
    <td colspan="9">
        "등록된 카테고리 목록이 없습니다."
    </td>
</tr>
<?php
    }
?>