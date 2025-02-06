
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
<tr data-idx="<?php echo _elm( $vData , 'N_IDX' )?>">
    <td class="body2-c nowrap">
        <div class="checkbox checkbox-single">
        <?php
            $setParam = [
                'name' => 'i_idx[]',
                'id' => 'i_idx_'._elm( $vData, 'N_IDX'),
                'value' =>  _elm( $vData, 'N_IDX'),
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
        <?php
        $nVisible = explode( ',', _elm( $vData, 'N_IS_VISIBLE' ) );

        $nVisibleHtml = array_map(function($value) {
            return '<span class="status-box status-normal">' . htmlspecialchars($value) . '</span>';
        }, $nVisible);

        echo implode(' ', $nVisibleHtml);
        ?>
        </td>
    <td class="body2-c nowrap"><a href='javascript:;' onclick="javascript:openLayer('<?php echo _elm( $vData , 'N_IDX' ) ?>', 'dataModal')"><?php echo _elm( $vData , 'N_TITLE' ) ?></a></td>
    <td class="body2-c nowrap">
        <?php echo
            _elm( $vData, 'N_STATUS' ) == 'Y' ? '<span class="status-box status-normal">노출</span>' :
            ( _elm( $vData, 'N_STATUS' ) == 'N' ? '<span class="status-box status-pending">미노출</span>' :
            ( _elm( $vData, 'N_STATUS' ) == 'D' ? '<span class="status-box status-deleted">삭제</span>' : '<span class="status-box status-disabled">대기</span>' )   )
        ?>
    </td>
    <td class="body2-c nowrap">
        <?php
            for ($i = 1; $i <= 5; $i++) {
            $filePath = _elm($vData, "N_FILES_{$i}_PATH");
            if (!empty($filePath)) {
                echo '
                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                    <path d="M4 3C4 1.89543 4.89543 1 6 1H17.5858C18.117 1 18.6261 1.21071 19 1.58579L21.4142 4C21.7893 4.37399 22 4.88304 22 5.41421V20C22 21.1046 21.1046 22 20 22H4C2.89543 22 2 21.1046 2 20V3ZM6 3V10H15V3H6ZM4 20H11V14H13V20H20V7H15V11C15 11.5523 14.5523 12 14 12H7C6.44772 12 6 11.5523 6 11V7H4V20ZM8 4H13V9H8V4Z"/>
                </svg>
                ';
                break; // 첫 번째로 조건을 만족하는 파일이 있으면 종료
            }
        }
        ?>

    </td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'CRT_MB' )?></td>
    <td class="body2-c nowrap"><?php echo empty( _elm( $vData , 'N_CREATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'N_CREATE_AT' ) ) ) : '-' ?></td>
    <td class="body2-c nowrap"><?php echo empty( _elm( $vData , 'N_UPDATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'N_UPDATE_AT' ) ) ) : '-' ?></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'N_HIT' ) ?? 0?></td>
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
                'onclick' => 'deleteNoticeConfirm("'. _elm( $vData , 'N_IDX' ) .'");',
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
        "등록된 공지사항 목록이 없습니다."
    </td>
</tr>
<?php
    }
?>