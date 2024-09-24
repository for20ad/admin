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
    $aTargetId = _elm($view_datas, 'openGroup', '');
?>

    <?php
    if( empty( $aLists ) === false ){
        foreach( $aLists as $aKey => $vData ){
    ?>
    <tr>
        <td>
            <div class="checkbox checkbox-single">
            <?php
                $setParam = [
                    'name' => 'v_idx[]',
                    'id' => 'v_idx_'._elm( $vData, 'G_IDX'),
                    'value' =>  _elm( $vData, 'G_IDX'),
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
        <td>
            <?php echo _elm( $vData, 'G_IDX' );?>
        </td>
        <td>
            <img src="/<?php echo _elm( $vData, 'I_IMG_PATH' );?>">
        </td>
        <td>
            <?php echo _elm( $vData, 'G_NAME' )?>
        </td>
    </tr>
    <?php
        }
    }else{
    ?>
    <tr><td colspan=4>데이터가 없습니다.</td></tr>
    <?php
    }

    ?>


