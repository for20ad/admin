
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
    $aReasonGbn    = _elm($view_datas, 'reason_gbn', [] );

    if (empty($aLists) === false)
    {
        foreach ($aLists as $vData)
        {
            $row++;
            $no = $total_rows - $row + 1;
?>

<tr data-point-idx="<?php echo _elm( $vData, 'M_IDX' )?>" style="font-size:10px">
    <td class="body2-c nowrap padding-sm"><?php echo $no?></td>
    <td class="body2-c nowrap padding-sm"><?php echo _elm( $vData , 'M_GBN' ) == 'add' ? '(+)'.number_format( _elm( $vData , 'M_MILEAGE' ) ) : ''?></td>
    <td class="body2-c nowrap padding-sm"><?php echo _elm( $vData , 'M_GBN' ) != 'add' ? '(-)'.number_format( _elm( $vData , 'M_MILEAGE' ) ) : ''?></td>
    <td class="body2-c nowrap padding-sm"><?php echo number_format( _elm( $vData, 'M_AFTER_MILEAGE' ) ) ?></td>
    <td class="body2-c nowrap padding-sm"><?php echo date( 'Y-m-d', strtotime(_elm( $vData, 'M_CREATE_AT' ) ) ) .'<br>'. date( 'H:i', strtotime(_elm( $vData, 'M_CREATE_AT' ) ) )?></td>
    <td class="body2-c nowrap padding-sm"><?php echo empty( _elm( $vData, 'MB_USERID' ) ) === false?  _elm( $vData, 'MB_USERID' ).'<br>'. _elm( $vData, 'MB_USERNAME' ): '' ?></td>
    <td class="body2-c nowrap padding-sm align-left" style="font-size:11px;line-height: 1.3;">
        <span class="org_reason" >
            <?php echo _elm( $vData, 'C_NAME' ).'<br>'.
                ( empty( _elm( $vData, 'M_TYPE_CD' ) ) === false ? '('._elm( $dCate, _elm( $vData, 'M_TYPE' ) ).':'._elm( $vData, 'M_TYPE_CD' ).')<br>':'' ) .
                _elm( $vData, 'M_REASON_MSG' )?>
        </span>
        <span class="new_reason" style="display:none">
            <div>
                <?php
                    $options = [''=>'사유구분' ];
                    $options += $aReasonGbn;
                    $extras   = ['id' => 'a_reason_gbn', 'class' => 'form-select', 'style' => 'max-width: 220px;'];
                    $selected = '';
                    echo getSelectBox('a_reason_gbn', $options, $selected, $extras);
                ?>
            </div>
            <div>
                <?php
                    $options = [''=>'타입' ];
                    $options += $dCate;
                    $extras   = ['id' => 'a_m_type', 'class' => 'form-select', 'style' => 'max-width: 220px;'];
                    $selected = '';
                    echo getSelectBox('a_m_type', $options, $selected, $extras);
                ?>
            </div>
            <div>
                <input text class="form-control" name="a_reason" placeholder="사유입력"></textarea>
            </div>
        </span>
    </td>
    <td class="body2-c nowrap padding-sm align-left">
        <span class="modBtnArea">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="modifyReason( $(this) )">
                <path d="M7 7H6C5.46957 7 4.96086 7.21071 4.58579 7.58579C4.21071 7.96086 4 8.46957 4 9V18C4 18.5304 4.21071 19.0391 4.58579 19.4142C4.96086 19.7893 5.46957 20 6 20H15C15.5304 20 16.0391 19.7893 16.4142 19.4142C16.7893 19.0391 17 18.5304 17 18V17" stroke="#616876" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M20.385 6.58511C20.7788 6.19126 21.0001 5.65709 21.0001 5.10011C21.0001 4.54312 20.7788 4.00895 20.385 3.61511C19.9912 3.22126 19.457 3 18.9 3C18.343 3 17.8088 3.22126 17.415 3.61511L9 12.0001V15.0001H12L20.385 6.58511Z" stroke="#616876" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M16 5L19 8" stroke="#616876" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <span class="confirmBtnArea" style="display:none">
            <div>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="cancelReason( $(this) )">
                    <g id="tabler-icon-x">
                        <path id="Vector" d="M18 6L6 18M6 6L18 18" stroke="#616876" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                </svg>
            </div>
            <div>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="confirmModifyReason( $(this) )" >
                    <path d="M5 12L10 17L20 7" stroke="#616876" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </span>
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
        "등록된 회원 포인트내역이 없습니다."
    </td>
</tr>
<?php
    }
?>