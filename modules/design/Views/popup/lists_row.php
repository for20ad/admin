
<?php
    helper(['owens','owens_form', 'owens_url']);
    $view_datas = $owensView->getViewDatas();

    $sQUERY_STRING = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $sQUERY_STRING = _add_query_string($sQUERY_STRING, _elm($view_datas, 'query_string', []));

    $aLists        = _elm($view_datas, 'lists', []);
    $aConfig       = _elm($view_datas, 'aConfig', []);
    $row           = _elm($view_datas, 'row', []);
    $total_rows    = _elm($view_datas, 'total_rows', []);


    if (empty($aLists) === false)
    {
        foreach ($aLists as $vData)
        {
            $row++;
            $no = $total_rows - $row + 1;
?>
<tr data-idx="<?php echo _elm( $vData , 'P_IDX' )?>">
    <td class="body2-c nowrap">
        <div class="checkbox checkbox-single">
        <?php
            $setParam = [
                'name' => 'i_idx[]',
                'id' => 'i_idx_'._elm( $vData, 'P_IDX'),
                'value' =>  _elm( $vData, 'P_IDX'),
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
    <td class="body2-c nowrap"><a href='#' onclick="javascript:openLayer('<?php echo _elm( $vData , 'P_IDX' ) ?>', 'dataModal')"><?php echo  _elm( _elm( $aConfig,'viewGbn' ), _elm( $vData , 'P_VIEW_GBN' ) ) ?></a></td>
    <td class="body2-c nowrap"><a href='#' onclick="javascript:openLayer('<?php echo _elm( $vData , 'P_IDX' ) ?>', 'dataModal')"><?php echo _elm( $vData , 'P_TITLE' )?></a></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'P_PERIOD_START_AT' ) . ' ~ ' . _elm( $vData, 'P_PERIOD_END_AT' ) ?></td>
    <td class="body2-c nowrap"><?php echo _elm( _elm( $aConfig, 'status' ), _elm( $vData, 'P_STATUS' ) )?></td>
    <td class="body2-c nowrap"><?php echo _elm( _elm( $aConfig, 'viewLoc' ), _elm( $vData, 'P_LOCATE' ) )?></td>
    <td class="body2-c nowrap"><?php echo empty( _elm( $vData , 'P_CREATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'P_CREATE_AT' ) ) ) : '-' ?></td>
    <td class="body2-c nowrap"><?php echo empty( _elm( $vData , 'P_UPDATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'P_UPDATE_AT' ) ) ) : '-' ?></td>
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
                'onclick' => 'deletePopupConfirm("'. _elm( $vData , 'P_IDX' ) .'");',
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
    <td colspan="10">
        "등록된 팝업 목록이 없습니다."
    </td>
</tr>
<?php
    }
?>