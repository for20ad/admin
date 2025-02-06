<?php
helper(['form', 'owens_form']);
$view_datas = $owensView->getViewDatas();

// $groupedByDate 데이터가 있다고 가정합니다.
$aLists      = _elm($view_datas, 'lists', []);
$aOdrStatus  = _elm($view_datas, 'aOdrStatus', []);
// 그룹화된 데이터가 비어 있지 않을 경우 출력
if (!empty($aLists)) {
    foreach ($aLists as $aKey => $vData) :
        ?>
    <tr>
        <td><?php echo _elm( $vData, 'M_CREATE_AT' )?></td>
        <td><?php echo _elm( $vData, 'M_WRITER_NAME' )?></td>
        <td><?php echo _elm( $aOdrStatus, _elm( $vData, 'M_STATUS' ) )?></td>
        <td><?php echo str_replace( ', ', '<br>', _elm( $vData, 'M_ORD_INFO_TXT') )?></td>
        <td><?php echo str_replace( ', ', '<br>', _elm( $vData, 'M_CONTENT') )?></td>
        <td>
            <?php
                echo getIconAnchor([
                    'txt' => '',
                    'icon' => 'delete',
                    'buttonClass' => '',
                    'buttonStyle' => 'display: inline-flex; align-items: center; justify-content: flex-end;',
                    'width' => '24',
                    'height' => '24',
                    'stroke' => '#616876',
                    'extra' => [
                        'onclick' => 'deleteMemoConfirm(\''. (int)_elm($vData, 'M_IDX').'\' , 3)',

                    ]
                ]);
            ?>
        </td>
<?php
    endforeach;

}
?>
