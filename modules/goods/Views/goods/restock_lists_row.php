
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
<tr data-idx="<?php echo _elm( $vData , 'G_IDX' )?>">
    <td class="body2-c nowrap">
        <div class="checkbox checkbox-single">
        <?php
            $setParam = [
                'name' => 'i_idx[]',
                'id' => 'i_idx_'._elm( $vData, 'G_IDX'),
                'value' =>  _elm( $vData, 'G_IDX'),
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
    <td class="body2-c nowrap"><a href='<?php echo _link_url('/goods/goodsDetail/'._elm( $vData , 'G_IDX' ))?>' ><?php echo  _elm( $vData , 'G_IDX' )?></a></td>
    <td class="body2-c nowrap prd-title">
        <a href='<?php echo _link_url('/goods/goodsDetail/'._elm( $vData , 'G_IDX' ))?>'>
            <img src="/<?php echo _elm( $vData, 'I_IMG_PATH' )?>" width="60"> &nbsp;&nbsp;&nbsp;
            <?php echo _elm( $vData, 'G_NAME' )?>
        </a>
    </td>
    <td class="body2-c nowrap prd-options" >
        <?php
            $vOptions = str_replace(',', '<br>', _elm( $vData, 'A_OPTIONS_TEXT' ) );
            echo $vOptions;
        ?>
    </td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'ALIM_CNT' ) ?> 명</td>
    <td class="body2-c nowrap">
        <?php echo _elm( $vData, 'SEND_CNT', 0, true ) ?> /
        <?php echo _elm( $vData, 'NOT_SENC_CNT', 0, true )?>
    </td>
    <td class="body2-c">
        <?php
        echo getButton([
            'text' => '신청자 목록',
            'class' => 'btn',
            'style' => 'width: 100px; height: 36px',
            'extra' => [
                'onclick' => 'event.preventDefault();openLayer( \''._elm( $vData , 'G_IDX' ).'\', \'restockModalLabel\', $(this) )',
                'type' => 'button',
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
    <td colspan="8">
        "등록된 상품 목록이 없습니다."
    </td>
</tr>
<?php
    }
?>