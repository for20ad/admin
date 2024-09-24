
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
?>

<tr data-idx="<?php echo _elm( $vData , 'G_IDX' )?>" onmouseover="$(this).find('.relation-move-icons').show()" onmouseout="$(this).find('.relation-move-icons').hide()">
    <input type="hidden" name="i_relation_goods_idxs[]" value="<?php echo _elm( $vData , 'G_IDX' )?>">
    <td>
        <div class="relation-move-icons" style="display:none;cursor:pointer" >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32" fill="none">
                <g clip-path="url(#clip0_492_18580)">
                    <path d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z" fill="#616876"/>
                    <path d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z" fill="#616876"/>
                    <path d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z" fill="#616876"/>
                    <path d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z" fill="#616876"/>
                    <path d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z" fill="#616876"/>
                    <path d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z" fill="#616876"/>
                </g>
                <defs>
                    <clipPath id="clip0_492_18580">
                    <rect width="32" height="32" fill="white"/>
                    </clipPath>
                </defs>
            </svg>
        </div>
    </td>
    <td class="body2-c nowrap">
        <div class="checkbox checkbox-single">
        <?php
            $setParam = [
                'name' => 'i_relation_goods_idxs[]',
                'id' => 'i_relation_goods_idxs_'._elm( $vData, 'G_IDX'),
                'value' =>  _elm( $vData, 'G_IDX'),
                'label' => '',
                'checked' => false,
                'extraAttributes' => [
                    'aria-label' => 'Single checkbox One',
                    'class'=>'check-item-pro',
                ]
            ];
            echo getCheckBox( $setParam );
        ?>
        </div>
    </td>
    <td class="body2-c nowrap"><img src="/<?php echo _elm( $vData, 'I_IMG_PATH' )?>" width="80" loading="lazy"></td>
    <td class="body2-c nowrap"><a href='<?php echo _link_url('/goods/goodsDetail/'._elm( $vData , 'G_IDX' ) )?>' target='_blank'><?php echo  _elm( $vData , 'G_NAME' )?></a></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'G_CATEGORY_MAIN' )?></td>
    <td class="body2-c nowrap"><?php echo number_format( _elm( $vData, 'G_PRICE' ) )?></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'G_PC_OPEN_FLAG' ) == 'Y' ? '노출' : '미노출' ?></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData, 'G_PC_SELL_FLAG' ) == 'Y' ? '판매중' : '판매중단' ?></td>
    <td class="body2-c nowrap">
        <?php
            $options  = ['nomal'=>'일방등록', 'dup'=>'상호등록'];
            $extras   = ['id' => 'i_relation_goods_add_gbn[]', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
            $selected = '';
            echo getSelectBox('i_relation_goods_add_gbn[]', $options, $selected, $extras);
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
    <td colspan="9">
        "등록된 상품 목록이 없습니다."
    </td>
</tr>
<?php
    }
?>
