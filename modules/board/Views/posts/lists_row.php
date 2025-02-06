
<?php
    helper(['owens','owens_form', 'owens_url']);
    $view_datas = $owensView->getViewDatas();

    $sQUERY_STRING = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $sQUERY_STRING = _add_query_string($sQUERY_STRING, _elm($view_datas, 'query_string', []));

    $aLists        = _elm($view_datas, 'lists', []);
    $aConfig       = _elm($view_datas, 'aConfig', []);
    $row           = _elm($view_datas, 'row', []);
    $total_rows    = _elm($view_datas, 'total_rows', []);
    $aBoardId      = _elm($view_datas, 'bo_id', '');

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
    <td class="body2-c nowrap">
    <a href='javascript:;' onclick="javascript:openLayer('<?php echo _elm( $vData , 'P_IDX' ) ?>', 'dataModal')">
        <?php

            if( empty( _elm( $vData, 'categoryLabel' ) ) == false ){
                echo ''._elm( $vData, 'categoryLabel' );
                if( empty( _elm( $vData, 'subCategoryLabel' ) ) == false ){
                    echo ' ( '._elm( $vData, 'subCategoryLabel' ). ' ) ';
                }
            }

        ?>
    </a>
    </td>
    <td class="body2-c nowrap"><a href='javascript:;' onclick="javascript:openLayer('<?php echo _elm( $vData , 'P_IDX' ) ?>', 'dataModal')"><?php echo _elm( $vData , 'P_TITLE' ) ?></a></td>
    <td class="body2-c nowrap">
        <?php echo
            _elm( $vData, 'P_STATUS' ) == '1' ? '<span class="status-box status-normal">노출</span>' :
            ( _elm( $vData, 'P_STATUS' ) == '2' ? '<span class="status-box status-pending">미노출</span>' :
            ( _elm( $vData, 'P_STATUS' ) == '3' ? '<span class="status-box status-deleted">삭제</span>' : '<span class="status-box status-disabled">블라인드</span>' )   )
        ?>
    </td>
    <?php
    if( $aBoardId == 'QNA' ){
    ?>
    <td class="body2-c nowrap" >
    <span class="orgStatus"
        <?php if (_elm($vData, 'P_ANSWER_STATUS') != 'COMPLETED') : ?>
            ondblclick="showNewStatus(this )"
        <?php endif; ?>>
        <?php echo
            _elm( $vData, 'P_ANSWER_STATUS' ) == 'READY' ? '<span class="status-box status-disabled">문의등록</span>' :
            ( _elm( $vData, 'P_ANSWER_STATUS' ) == 'RECEIVED' ? '<span class="status-box status-regist">문의접수</span>' :
            ( _elm( $vData, 'P_ANSWER_STATUS' ) == 'PREPARING' ? '<span class="status-box status-ready">답변준비중</span>' : '<span class="status-box status-normal">답변완료</span>' )   )
        ?>
        </span>
        <span class="newStatus" style="display:none">
        <?php
            $options = [''=>'선택', 'RECEIVED'=>'문의접수', 'PREPARING'=>'답변준비중'];
            $extras = [
                'id' => 'i_new_status',
                'class' => 'form-select',
                'style' => 'max-width: 150px; margin-right: 0.235em;',
                'data-idx' => _elm($vData, 'P_IDX'),
                'onblur' => 'if (!window.preventOnBlur) { changeAnswerStatusConfirm($(this)); }',
                'onkeyup' => 'if (event.key === \'Escape\'){ window.preventOnBlur = true;$(this).parent().hide();$(this).parent().parent().find(\'.orgStatus\').show();setTimeout(() => { window.preventOnBlur = false; }, 100);}'
            ];
            $selected = '';
            echo getSelectBox('i_new_status', $options, $selected, $extras);
        ?>
        </span>
    </td>
    <?php
    }
    ?>
    <td class="body2-c nowrap">
        <?php

        if ( !empty( _elm( $vData, 'files' ) ) ) {
            echo '
            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                <path d="M4 3C4 1.89543 4.89543 1 6 1H17.5858C18.117 1 18.6261 1.21071 19 1.58579L21.4142 4C21.7893 4.37399 22 4.88304 22 5.41421V20C22 21.1046 21.1046 22 20 22H4C2.89543 22 2 21.1046 2 20V3ZM6 3V10H15V3H6ZM4 20H11V14H13V20H20V7H15V11C15 11.5523 14.5523 12 14 12H7C6.44772 12 6 11.5523 6 11V7H4V20ZM8 4H13V9H8V4Z"/>
            </svg>
            ';
        }

        ?>

    </td>
    <td class="body2-c nowrap"><a href='javascript:;' onclick="openLayer('<?php echo _elm( $vData , 'P_IDX' ) ?>', 'dataModal')"><?php echo _elm( $vData, 'commentCnt', 0 )?></a></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'P_WRITER_NAME' )?></td>
    <td class="body2-c nowrap"><?php echo empty( _elm( $vData , 'P_CREATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'P_CREATE_AT' ) ) ) : '-' ?></td>
    <td class="body2-c nowrap"><?php echo empty( _elm( $vData , 'P_UPDATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'P_UPDATE_AT' ) ) ) : '-' ?></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'P_HITS' ) ?? 0?></td>
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
                'onclick' => 'deletePostsConfirm("'. _elm( $vData , 'P_IDX' ) .'");',
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
    <td colspan="<?php echo $aBoardId == 'QNA' ? '13' : '12'  ?>">
        "등록된 목록이 없습니다."
    </td>
</tr>
<?php
    }
?>