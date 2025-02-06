
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
<tr data-idx="<?php echo _elm( $vData , 'C_IDX' )?>"  <?php if( _elm($vData, 'C_STATUS' ) == 'D' ) echo 'class="deleted"'?> >
    <td class="body2-c nowrap">
        <div class="checkbox checkbox-single">
        <?php
            $setParam = [
                'name' => 'i_cpn_idx[]',
                'id' => 'i_cpn_idx_'._elm( $vData, 'C_IDX'),
                'value' =>  _elm( $vData, 'C_IDX'),
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
    <td class="body2-c nowrap"><a href='<?php echo _link_url('/promotion/coupon/couponDetail/'._elm( $vData, 'C_IDX' ))?>' target="_blank"><?php echo  _elm( $vData , 'C_NAME' )?><p><?php echo _elm( $vData, 'C_DISCRIPTION' )?></p></a></td>
    <td class="body2-c nowrap">
        <?php echo _elm( $vData, 'C_STATUS' ) == 'Y'? '<span class="status-box status-normal">정상</span>' : ( _elm( $vData, 'C_STATUS' ) == 'W' ? '<span class="status-box status-pending">대기</span>' : ( _elm( $vData, 'C_STATUS' ) == 'N'? '<span class="status-box status-disabled">사용안함</span>':'<span class="status-box status-deleted">삭제</span>' ) );?>
    </td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'C_PUB_GBN' ) == 'P' ? '지정발행' : ( _elm( $vData, 'C_PUB_GBN' ) == 'D' ? '다운로드' : '자동발행' )?></td>
    <td class="body2-c nowrap">
        <a href='javascript:openLayer("<?php echo _elm( $vData, 'C_IDX' )?>")'>
        <?php echo _elm( $vData, 'TOTAL_COUNT' ) ?> ( <?php echo _elm( $vData, 'USED_COUNT',0 ) ?> / <?php echo _elm( $vData, 'UNUSED_COUNT',0 ) ?> )
        </a>
    </td>
    <td class="body2-c nowrap">
        <?php echo _elm( $vData, 'C_PERIDO_LIMIT' ) == 'N'? '무제한': '<p>'._elm( $vData, 'C_PERIOD_START_AT' ).'</p><p>'._elm( $vData, 'C_PERIOD_END_AT' ).'</p>' ?>
    </td>
    <td class="body2-c nowrap">
        <?php echo empty( _elm( $vData , 'C_CREATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'C_CREATE_AT' ) ) ) : '-' ?><br>
        <?php echo empty( _elm( $vData , 'C_UPDATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $vData , 'C_UPDATE_AT' ) ) ) : '-' ?>
    </td>
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
                'onclick' => 'deleteCouponConfirm(["'. _elm( $vData , 'C_IDX' ) .'"]);',
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
        "등록된 쿠폰 목록이 없습니다."
    </td>
</tr>
<?php
    }
?>