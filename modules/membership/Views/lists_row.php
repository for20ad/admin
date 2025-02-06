
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
    <td class="body2-c nowrap">
        <div class="checkbox checkbox-single">
        <?php
            $setParam = [
                'name' => 'i_member_idx[]',
                'id' => 'i_member_idx_'._elm( $vData, 'MB_IDX'),
                'value' =>  _elm( $vData, 'MB_IDX'),
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
    <td class="body2-c nowrap"><a href='javascript:;' onclick="javascript:openLayer('<?php echo _elm( $vData , 'MB_IDX' ) ?>', 'dataModal')" ><?php echo _elm( $vData , 'MB_USERID' )?></a></td>
    <td class="body2-c nowrap" ><a href='javascript:;' onclick="javascript:openLayer('<?php echo _elm( $vData , 'MB_IDX' ) ?>', 'dataModal')"><?php echo _elm( $vData , 'MB_NM' )?></a></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'G_NAME' )?> </td>
    <td class="body2-c nowrap"><?php echo empty( _elm( $vData, 'MB_COM_NAME') ) ? '일반' : '공급처'?></td>
    <td class="body2-c nowrap"><?php echo number_format( _elm( $vData, 'ADD_MILEAGE', 0, true ) - (_elm( $vData, 'USE_MILEAGE', 0, true ) + _elm( $vData, 'DED_MILEAGE', 0, true ) + _elm( $vData, 'EXP_MILEAGE', 0, true )  ) )?> 원</td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'MB_SALE_CNT', 0, true )?> 건</td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'MB_SALE_AMT', 0, true )?> 원</td>
    <td class="body2-c nowrap"><?php echo  _elm( $aConfig , _elm($vData , 'MB_STATUS' ) )?></td>
    <td class="body2-c nowrap"><a href='javascript:;' onclick="javascript:openCounselLayer('<?php echo _elm( $vData , 'MB_IDX' ) ?>', 'counselModal')">내역보기</a></td>
    <td class="body2-c nowrap"><?php echo date( 'Y-m-d H:i' , strtotime( _elm( $vData , 'MB_REG_AT' ) ) )?></td>
    <td class="body2-c nowrap"><?php echo empty( _elm( $vData , 'MB_LAST_LOGIN' ) ) === false ? date( 'Y-m-d H:i' , strtotime( _elm( $vData , 'MB_LAST_LOGIN' ) ) ) : '-'?></td>
</tr>
<?php
        }
    }
    else
    {
?>
<tr>
    <td colspan="12">
        "등록된 회원 목록이 없습니다."
    </td>
</tr>
<?php
    }
?>