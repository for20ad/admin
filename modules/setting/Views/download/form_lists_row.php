
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
<tr data-idx="<?php echo _elm( $vData , 'F_IDX' )?>">
    <td class="body2-c nowrap">
        <div class="checkbox checkbox-single">
        <?php
            $setParam = [
                'name' => 'i_form_idx[]',
                'id' => 'i_form_idx_'._elm( $vData, 'F_IDX'),
                'value' =>  _elm( $vData, 'F_IDX'),
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
    <td class="body2-c nowrap"><a href='javascript:void(0);' onclick="javascript:openLayer('<?php echo _elm( $vData , 'F_IDX' ) ?>', 'formsModal')" ><?php echo _elm( _elm( $aConfig, 'forms'), _elm( $vData , 'F_MENU' ) )?></a></td>
    <td class="body2-c nowrap"><a href='javascript:void(0);' onclick="javascript:openLayer('<?php echo _elm( $vData , 'F_IDX' ) ?>', 'formsModal')"><?php echo _elm( $vData , 'F_TITLE' )?></a></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'MB_USERID' )?> (<?php echo _elm( $vData, 'MB_USERNAME' )?> )</td>
    <td class="body2-c nowrap"><?php echo date( 'Y-m-d H:i' , strtotime( _elm( $vData , 'F_CREATE_AT' ) ) )?></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'F_STATUS' ) == 'Y' ? '노출': '비노출'?></td>
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
                'onclick' => 'deleteFormsConfirm("'. _elm( $vData , 'F_IDX' ) .'");',
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
        "등록된 관리자 목록이 없습니다."
    </td>
</tr>
<?php
    }
?>