<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();


    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);
    $aMemeberGrade    = _elm($view_datas, 'member_grades', []);
    $aData            = _elm($view_datas, 'aData', [] );
?>
<div class="d-flex" style="width:100% !important;padding:0.3rem 0.8rem 0.8rem 0.9rem;">
    <!-- 왼쪽 60% 정렬 (사용자 정보) -->
    <div style="flex: 0 0 60%;">
        <h3><?php echo _elm( $aData, 'MB_NM' )?>( <?php echo _elm( $aData, 'MB_USERID')?> )</h3><br>
        <b><?php echo _elm( $aMemeberGrade, _elm( $aData, 'MB_GRADE_IDX' ) );?></b> 최종로그인일: <?php echo _elm( $aData, 'MB_LAST_LOGIN' )?? '-';?>
    </div>

    <!-- 오른쪽 40% 정렬 (포인트 및 쿠폰), 텍스트 상단 정렬 -->
    <div class="d-flex justify-content-end" style="flex: 0 0 40%;">
        <div class="text-end me-3" style="align-self: flex-start;">
        <h3><svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.5 14C9.5 15.657 12.186 17 15.5 17C18.814 17 21.5 15.657 21.5 14M9.5 14C9.5 12.343 12.186 11 15.5 11C18.814 11 21.5 12.343 21.5 14M9.5 14V18C9.5 19.656 12.186 21 15.5 21C18.814 21 21.5 19.656 21.5 18V14M3.5 6C3.5 7.072 4.644 8.062 6.5 8.598C8.356 9.134 10.644 9.134 12.5 8.598C14.356 8.062 15.5 7.072 15.5 6C15.5 4.928 14.356 3.938 12.5 3.402C10.644 2.866 8.356 2.866 6.5 3.402C4.644 3.938 3.5 4.928 3.5 6ZM3.5 6V16C3.5 16.888 4.272 17.45 5.5 18M3.5 11C3.5 11.888 4.272 12.45 5.5 13" stroke="#1D273B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            포인트</h3><br>
            <span class="color-blue align-center">
                <?php
                echo empty( _elm( $aData, 'pointData' ) ) === true ? '0' :
                number_format( ( _elm( _elm( $aData, 'pointData' ), 'ADD_MILEAGE' ) -
                ( _elm( _elm( $aData, 'pointData' ), 'USE_MILEAGE' ) +  _elm( _elm( $aData, 'pointData' ), 'DED_MILEAGE' ) + _elm( _elm( $aData, 'pointData' ), 'EXP_MILEAGE' ) ) ) );
                ?>
                원
            </span>
        </div>
        <span style="align-self: flex-start;">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_580_18362)">
                    <path d="M16 3V29" stroke="#E6E7E9" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
                <defs>
                    <clipPath id="clip0_580_18362">
                        <rect width="32" height="32" fill="white"/>
                    </clipPath>
                </defs>
            </svg>
        </span>
        <div class="text-end me-3" style="align-self: flex-start;">
        <h3><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 5V7M15 11V13M15 17V19M5 5H19C19.5304 5 20.0391 5.21071 20.4142 5.58579C20.7893 5.96086 21 6.46957 21 7V10C20.4696 10 19.9609 10.2107 19.5858 10.5858C19.2107 10.9609 19 11.4696 19 12C19 12.5304 19.2107 13.0391 19.5858 13.4142C19.9609 13.7893 20.4696 14 21 14V17C21 17.5304 20.7893 18.0391 20.4142 18.4142C20.0391 18.7893 19.5304 19 19 19H5C4.46957 19 3.96086 18.7893 3.58579 18.4142C3.21071 18.0391 3 17.5304 3 17V14C3.53043 14 4.03914 13.7893 4.41421 13.4142C4.78929 13.0391 5 12.5304 5 12C5 11.4696 4.78929 10.9609 4.41421 10.5858C4.03914 10.2107 3.53043 10 3 10V7C3 6.46957 3.21071 5.96086 3.58579 5.58579C3.96086 5.21071 4.46957 5 5 5Z" stroke="#1D273B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            쿠폰</h3><br>
            <span class="color-blue align-center">
                <?php echo _elm( $aData, 'couponCnt' )?>
            </span>
        </div>
    </div>
</div>
<div class="row row-deck row-cards" style="padding-bottom:1.1rem;">
    <!-- 카드1 -->
    <div class="col-12">
        <div class="card">
            <div class="form-inline d-flex justify-content-between align-items-center  align-center" style="gap: 0px; flex-wrap: wrap; font: size 12px;padding:-0.3rem 0px">
                <div class="in-item" style="padding-left:1.5rem">총 주문금액 / 총 결제금액 <div class="color-blue"><?php echo number_format( _elm( $aData, 'orderTotalAmt' ) )?>원 / <?php echo number_format( _elm( $aData, 'billTotalAmt' ) )?>원</div> </div>
                <span style="margin:0 0.6rem 0 0.4rem;color:#ccc;font-size:25px;">|</span>
                <div class="in-item">총 상품주문건수 <div class="color-blue "><?php echo number_format( _elm( $aData, 'orderProductCnt' ) )?></div></div>
                <span style="margin:0 0.6rem 0 0.4rem;color:#ccc;font-size:25px;">|</span>
                <div class="in-item">총 상담건수 <div class="color-blue"><?php echo number_format( _elm( $aData, 'counselCnt' ) )?></div></div>
                <span style="margin:0 0.6rem 0 0.4rem;color:#ccc;font-size:25px;">|</span>
                <div class="in-item">1:1문의건수 <div class="color-blue"><?php echo number_format( _elm( $aData, 'qnaCnt' ) )?></div> </div>
                <span style="margin:0 0.6rem 0 0.4rem;color:#ccc;font-size:25px;">|</span>
                <div class="in-item" style="padding-right:1.5rem">총 리뷰건수 <div class="color-blue">21,242</div> </div>
            </div>
        </div>
    </div>
</div>