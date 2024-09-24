
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
                'name' => 'i_idx[]',
                'id' => 'i_idx_'._elm( $vData, 'I_IDX'),
                'value' =>  _elm( $vData, 'I_IDX'),
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
    <td class="body2-c nowrap"><?php echo $no?></td>
    <td class="body2-c nowrap"><a href='#' onclick="javascript:openLayer('<?php echo _elm( $vData , 'I_IDX' ) ?>', 'iconsModal')" ><?php echo  _elm( $vData , 'I_NAME' )?></a></td>
    <td class="body2-c nowrap"><a href='#' onclick="javascript:openLayer('<?php echo _elm( $vData , 'I_IDX' ) ?>', 'iconsModal')"><img src="/<?php echo _elm( $vData , 'I_IMG_PATH' )?>"></a></td>
    <td class="body2-c nowrap"><?php echo _elm( _elm( $aConfig, 'icon_gbn' ), _elm( $vData, 'I_GBN' ) )?></td>
    <td class="body2-c nowrap"><?php echo _elm( _elm( $aConfig, 'status' ), _elm( $vData, 'I_STATUS' ) )?></td>
    <td class="body2-c nowrap"><?php echo empty( _elm( $vData , 'I_CREATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'I_CREATE_AT' ) ) ) : '-' ?></td>
    <td class="body2-c nowrap"><?php echo empty( _elm( $vData , 'I_UPDATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'I_UPDATE_AT' ) ) ) : '-' ?></td>
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
                'onclick' => 'deleteIconsConfirm("'. _elm( $vData , 'I_IDX' ) .'");',
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
        "등록된 아이콘 목록이 없습니다."
    </td>
</tr>
<?php
    }
?>