<?php
    helper(['owens','owens_form', 'owens_url', 'form']);
    $view_datas = $owensView->getViewDatas();
    $sQUERY_STRING = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];

    if (isset($pageDatas) === false) {
        $pageDatas = [];
    }
    $sQueryString = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];

    $aLists  = _elm($view_datas, 'lists', []);
    $aTargetId = _elm($view_datas, 'openGroup', '');
    $pagination = _elm( $view_datas, 'pagination' );
    $total_rows    = _elm($view_datas, 'total_rows', 0);
    $row           = _elm($view_datas, 'row', []);
?>

<?php
    if( empty( $aLists ) === false ){
        foreach( $aLists as $vData ){
            $row++;
            $no = $total_rows - $row + 1;
    ?>
        <tr data-idx="<?php echo _elm( $vData , 'A_IDX' )?>">
            <td class="body2-c nowrap">
                <div class="checkbox checkbox-single">
                <?php
                    $setParam = [
                        'name' => 'i_idx[]',
                        'id' => 'i_idx_'._elm( $vData, 'A_IDX'),
                        'value' =>  _elm( $vData, 'A_IDX'),
                        'label' => '',
                        'checked' => false,
                        'extraAttributes' => [
                            'aria-label' => 'Single checkbox One',
                            'class'=>'check-item-pop',
                        ]
                    ];
                    echo getCheckBox( $setParam );
                ?>
                </div>
            </td>
            <td class="body2-c nowrap"><?php echo $no?></td>
            <td class="body2-c nowrap"><?php echo date('Y.m.d H:i', strtotime( _elm( $vData, 'A_CREATE_AT' ) ) );?></td>
            <td class="body2-c nowrap"><?php echo _elm( $vData, 'MB_NM' ).'('._elm( $vData, 'MB_USERID' ).' / '._elm( $vData, 'G_NAME' ) . ')';?></td>
            <td class="body2-c nowrap"><?php echo _elm( $vData, 'MB_MOBILE_NUM' );?></td>
            <td class="body2-c nowrap"><?php echo empty( _elm( $vData, 'A_ALIM_SEND_AT' ) ) == true ? '미발송' : '발송' ;?></td>
        </tr>

    <?php
        }
    }else{
    ?>
    <tr><td colspan=4>데이터가 없습니다.</td></tr>
    <?php
    }

    ?>


