
<?php
    helper(['owens','owens_form', 'owens_url']);
    $view_datas = $owensView->getViewDatas();

    $sQUERY_STRING = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $sQUERY_STRING = _add_query_string($sQUERY_STRING, _elm($view_datas, 'query_string', []));

    $aLists        = _elm($view_datas, 'lists', []);
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
<tr data-idx="<?php echo _elm( $vData , 'MB_IDX' )?>">
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
    <td class="body2-c nowrap"><a href='javascript:;' onclick="javascript:openLayer('<?php echo _elm( $vData , 'MB_IDX' ) ?>', 'memberModal')" ><?php echo _elm( $vData , 'MB_USERID' )?></a></td>
    <td class="body2-c nowrap"><a href='javascript:;' onclick="javascript:openLayer('<?php echo _elm( $vData , 'MB_IDX' ) ?>', 'memberModal')"><?php echo _elm( $vData , 'MB_USERNAME' )?></a></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'MB_GROUP_NAME' )?> </td>
    <td class="body2-c nowrap"><?php echo _add_dash_tel_num( _elm( $vData , 'MB_MOBILE_NUM_DEC' ) )?></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData , 'MB_STATUS' ) == '0' ? '<span class="status-box status-pending">대기</span>' : ( _elm( $vData , 'MB_STATUS' ) == '1' ? '<span class="status-box status-normal">정상</span>' : '<span class="status-box status-deleted">삭제</span>   ' ) ?></td>
    <td class="body2-c nowrap"><?php echo date( 'Y-m-d H:i' , strtotime( _elm( $vData , 'MB_CREATE_AT' ) ) )?></td>
    <td class="body2-c nowrap"><?php echo empty( _elm( $vData , 'MB_LAST_LOGIN_AT' ) ) === false ? date( 'Y-m-d H:i' , strtotime( _elm( $vData , 'MB_LAST_LOGIN_AT' ) ) ) : '-'?></td>
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