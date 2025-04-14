
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
<tr data-idx="<?php echo _elm( $vData , 'ModelCode' )?>">
    <td class="body2-c nowrap">
        <div class="checkbox checkbox-single">
        <?php
            $setParam = [
                'name' => 'i_dahae_code[]',
                'id' => 'i_dahae_code'._elm( $vData, 'ModelCode'),
                'value' =>  _elm( $vData, 'ModelCode'),
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
    <td class="body2-c nowrap"><span class="gidxs"><?php echo _elm( $vData , 'G_IDX' )?></span></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData , 'ModelCode' )?></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData , 'GodomallCode' )?></td>
    <td class="body2-c nowrap" title="<?php echo _elm( $vData , 'OptionGroup1' )?>"><?php echo _elm( $vData, 'OptionGroup1' )?></td>
    <td class="body2-c nowrap" title="<?php echo _elm( $vData , 'OptionGroup2' )?>"><?php echo _elm( $vData, 'OptionGroup2' )?></td>
    <td class="body2-c nowrap" title="<?php echo _elm( $vData , 'ModelName' )?>"><?php echo _elm( $vData , 'ModelName' )?></td>
    <td class="body2-c nowrap" title="<?php echo _elm( $vData , 'ItemCatBig' ). '>' ._elm( $vData , 'ItemCatMid' ). '>' ._elm( $vData , 'ItemCatSml' )?>"><?php echo _elm( $vData , 'ItemCatBig' ). '>' ._elm( $vData , 'ItemCatMid' ). '>' ._elm( $vData , 'ItemCatSml' )?></td>
    <td class="body2-c nowrap" title="<?php echo _elm( $vData , 'BrandCatBig' ). '>' ._elm( $vData , 'BrandCatMid' ). '>' ._elm( $vData , 'BrandCatSml' )?>"><?php echo _elm( $vData , 'BrandCatBig' ). '>' ._elm( $vData , 'BrandCatMid' ). '>' ._elm( $vData , 'BrandCatSml' )?></td>
    <td class="body2-c nowrap" title="<?php echo _elm( $vData , 'ModelOrigin' )?>"><?php echo _elm( $vData , 'ModelOrigin' )?></td>
    <td class="body2-c nowrap" title="<?php echo _elm( $vData , 'InitTime' )?>">
        <?php echo _elm( $vData , 'InitID' )?><br>
        <?php echo _elm( $vData , 'InitTime' )?>
    </td>
    <td class="body2-c nowrap" title="<?php echo _elm( $vData , 'LastTime' )?>">
        <?php echo _elm( $vData , 'LastID' )?><br>
        <?php echo _elm( $vData , 'LastTime' )?>
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
        "등록된 상품 목록이 없습니다."
    </td>
</tr>
<?php
    }
?>