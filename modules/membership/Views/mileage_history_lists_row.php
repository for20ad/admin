
<?php
    helper(['owens','owens_form', 'owens_url']);
    $view_datas = $owensView->getViewDatas();

    $sQUERY_STRING = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $sQUERY_STRING = _add_query_string($sQUERY_STRING, _elm($view_datas, 'query_string', []));

    $aLists        = _elm($view_datas, 'lists', []);
    $row           = _elm($view_datas, 'row', []);
    $aConfig       = _elm( _elm($view_datas, 'aConfig'), 'status', []);
    $dCate         = [ 'm' => '회원', 'o'=>'주문','b'=>'게시판','r'=>'신규회원추천','c'=>'쿠폰','e'=>'기타'];
    $total_rows    = _elm($view_datas, 'total_rows', []);

    if (empty($aLists) === false)
    {
        foreach ($aLists as $vData)
        {
            $row++;
            $no = $total_rows - $row + 1;
?>

<tr data-idx="<?php echo _elm( $vData , 'MB_IDX' )?>">
    <td class="body2-c nowrap"><?php echo $no?></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData , 'M_GBN' ) == 'add' ? number_format( _elm( $vData , 'M_MILEAGE' ) ) : ''?></td>
    <td class="body2-c nowrap"><?php echo _elm( $vData , 'M_GBN' ) != 'add' ? number_format( _elm( $vData , 'M_MILEAGE' ) ) : ''?></td>
    <td class="body2-c nowrap"><?php echo number_format( _elm( $vData, 'M_AFTER_MILEAGE' ) ) ?></td>
    <td class="body2-c nowrap"><?php echo empty( _elm( $vData, 'MB_USERID' ) ) === false?  _elm( $vData, 'MB_USERID' ).'<br>'. _elm( $vData, 'MB_USERNAME' ): '' ?></td>
    <td class="body2-c nowrap" style="font-size:12px;"><?php echo _elm( $vData, 'C_NAME' ).'<br>'.
    ( empty( _elm( $vData, 'M_TYPE_CD' ) ) === false ? '('._elm( $dCate, _elm( $vData, 'M_TYPE' ) ).':'._elm( $vData, 'M_TYPE_CD' ).')<br>':'' ) .
    _elm( $vData, 'M_REASON_MSG' )?>
    </td>
</tr>
<?php
        }
    }
    else
    {
?>
<tr>
    <td colspan="6">
        "등록된 회원 포인트내역이 없습니다."
    </td>
</tr>
<?php
    }
?>