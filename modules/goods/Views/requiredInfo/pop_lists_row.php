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
        <td>
            <div class="checkbox checkbox-single">
            <?php
                $setParam = [
                    'name' => 'v_idx[]',
                    'id' => 'v_idx_'._elm( $vData, 'R_IDX'),
                    'value' =>  _elm( $vData, 'R_IDX'),
                    'label' => '',
                    'checked' => false,
                    'extraAttributes' => [
                        'aria-label' => 'Single checkbox One',
                        'class'=>'check-item-pop ',
                    ]
                ];
                echo getCheckBox( $setParam );
            ?>
            </div>
        </td>
        <td>
            <?php echo $no;?>
        </td>
        <td>
            <?php echo _elm( $vData, 'R_TITLE' )?>
        </td>
        <td>
            <?php echo date( 'Y-m-d' , strtotime(_elm( $vData, 'R_CREATE_AT' ) ) )?>
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


