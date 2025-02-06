<?php
    helper(['owens','owens_form', 'owens_url', 'form']);
    $view_datas = $owensView->getViewDatas();
    $sQUERY_STRING = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];

    if (isset($pageDatas) === false) {
        $pageDatas = [];
    }
    $sQueryString = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];


    $aConfig = _elm($view_datas, 'aConfig', []);
    $aLists = _elm($view_datas, 'lists', []);
    $aTargetId = _elm($view_datas, 'aTargetId', '');
    $row           = _elm($view_datas, 'row', []);
    $total_rows    = _elm($view_datas, 'total_rows', []);
?>

    <?php
    if( empty( $aLists ) === false ){
        foreach( $aLists as $aKey => $vData ){
            $row++;
            $no = $total_rows - $row + 1;
    ?>
    <tr>
        <!-- <td>
            <div class="checkbox checkbox-single">
            <?php
                $setParam = [
                    'name' => 'v_idx[]',
                    'id' => 'v_idx_'._elm( $vData, 'I_IDX'),
                    'value' =>  _elm( $vData, 'I_IDX'),
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
        </td> -->
        <td>
            <?php echo $no?>
        </td>
        <td>
            <?php echo _elm( $vData, 'I_COUPON_CODE' );?>
        </td>
        <td class="userInput" data-issue-idx="<?php echo _elm( $vData, 'I_IDX' )?>">
            <div class="usedUserInfo">
                <?php echo !empty( _elm( $vData, 'MB_NM' ) ) ? _elm( $vData, 'MB_NM' ) . ' ( '. _elm( $vData, 'MB_USERID' ) . ' )': ''?>
            </div>
            <div class="insertUserForm"></div>
        </td>
        <td>
            <?php echo !empty( _elm( $vData, 'I_USED_AT' ) ) ? date('Y-m-d H:i', strtotime( _elm( $vData, 'I_USED_AT' ) ) ) : '미사용'  ?>
        </td>
        <td></td>
        <td>
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
                        'onclick' => 'deleteIssueConfirm("'. _elm( $vData , 'I_IDX' ) .'");',
                    ]
                ]);
            ?>
        </td>
    </tr>
    <?php
        }
    }else{
    ?>
    <tr><td colspan=6>발급된 쿠폰이 없습니다.</td></tr>
    <?php
    }

    ?>


