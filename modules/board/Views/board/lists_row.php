
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
<tr data-idx="<?php echo _elm( $vData , 'B_IDX' )?>">
    <td class="body2-c nowrap">
        <div class="checkbox checkbox-single">
        <?php
            $setParam = [
                'name' => 'i_idx[]',
                'id' => 'i_idx_'._elm( $vData, 'B_IDX'),
                'value' =>  _elm( $vData, 'B_IDX'),
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
    <td class="body2-c nowrap"><a href='javascript:;' onclick="javascript:openLayer('<?php echo _elm( $vData , 'B_IDX' ) ?>', 'dataModal')"><?php echo _elm( $vData , 'B_ID' ) ?></a></td>
    <td class="body2-c nowrap"><a href='javascript:;' onclick="javascript:openLayer('<?php echo _elm( $vData , 'B_IDX' ) ?>', 'dataModal')"><?php echo _elm( $vData , 'B_TITLE' )?></a></td>
    <td class="body2-c nowrap">
        <?php echo
            _elm( $vData, 'B_STATUS' ) == '1' ? '<span class="status-box status-normal">노출</span>' :
            ( _elm( $vData, 'B_STATUS' ) == '2' ? '<span class="status-box status-pending">미노출</span>' : '<span class="status-box status-deleted">삭제</span>' )
        ?>
    </td>
    <td class="body2-c nowrap"><a href="/board/boardLists/posts/<?php echo _elm( $vData , 'B_ID' ) ?>"><?php echo _elm( $vData,'POSTS_CNT' ) ?></a></td>
    <td class="body2-c nowrap"><?php echo empty( _elm( $vData , 'B_CREATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'B_CREATE_AT' ) ) ) : '-' ?></td>
    <td class="body2-c nowrap"><?php echo empty( _elm( $vData , 'B_UPDATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'B_UPDATE_AT' ) ) ) : '-' ?></td>
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
                'onclick' => 'deleteBoardConfirm("'. _elm( $vData , 'B_IDX' ) .'");',
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
        "등록된 게시판 목록이 없습니다."
    </td>
</tr>
<?php
    }
?>