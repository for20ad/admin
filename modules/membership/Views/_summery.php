<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();


    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);
    $aMemeberGrade    = _elm($view_datas, 'member_grades', []);
    $aData            = _elm($view_datas, 'aData', [] );
    $aOrderStatus     = _elm($view_datas, 'aOrderStatus', [] );

?>
<div class="row row-deck row-cards" >
    <!-- 카드1 -->
    <div class="col-12">
        <div class="card" style="border:0 !important">
            <div class="sec-title" style="clear:both;font-size: 14px; font-weight: bold;margin-bottom:0.3rem; margin-top:0.5rem;" >
                회원정보
            </div>
            <div class="card-body" style="padding:0px !important;">
                <!-- 테이블 -->
                <div style="border:1px solid #E6E7E9; border-radius:0 0 4px 4px;">
                    <div class="table-responsive">
                        <table class="table table-vcenter" >
                            <tr>
                                <th style="background-color:#F8FAFC; border-right:1px solid #E6E7E9">회원구분</th>
                                <td><?php echo  empty(_elm( $aData, 'MB_COM_NAME' ) )== true ? '개인회원' : '기업회원'?></td>
                                <th style="background-color:#F8FAFC; border-left:1px solid #E6E7E9;border-right:1px solid #E6E7E9">상점구분</th>
                                <td>기준몰</td>
                            </tr>
                            <tr>
                                <th style="background-color:#F8FAFC; border-right:1px solid #E6E7E9">이름</th>
                                <td><?php echo _elm( $aData, 'MB_NM' );?></td>
                                <th style="background-color:#F8FAFC; border-left:1px solid #E6E7E9;border-right:1px solid #E6E7E9">아이디</th>
                                <td><?php echo _elm( $aData, 'MB_USERID' );?></td>
                            </tr>
                            <tr>
                                <th style="background-color:#F8FAFC; border-right:1px solid #E6E7E9">회원가입일</th>
                                <td><?php echo date( 'Y-m-d H:i', strtotime( _elm( $aData, 'MB_REG_AT' ) ) );?></td>
                                <th style="background-color:#F8FAFC; border-left:1px solid #E6E7E9;border-right:1px solid #E6E7E9">전화번호</th>
                                <td>(+82) -</td>
                            </tr>
                            <tr>
                                <th style="background-color:#F8FAFC; border-right:1px solid #E6E7E9">휴대폰</th>
                                <td><?php echo _elm( $aData, 'MB_MOBILE_NUM_DEC' );?></td>
                                <th style="background-color:#F8FAFC; border-left:1px solid #E6E7E9;border-right:1px solid #E6E7E9">이메일</th>
                                <td><?php echo _elm( $aData, 'MB_EMAIL_DEC' );?></td>
                            </tr>
                            <tr>
                                <th style="background-color:#F8FAFC; border-right:1px solid #E6E7E9">주소</th>
                                <td colspan=3><?php echo empty(_elm( $aData, 'MB_ZIPCD' )) === false? '('._elm( $aData, 'MB_ZIPCD' ).') '. _elm( $aData, 'MB_ADDR' ) . ' ' ._elm( $aData, 'MB_ADDR_SUB' ) : ''?></td>
                            </tr>
                            <tr>
                                <th style="background-color:#F8FAFC; border-bottom:0px;border-left:1px solid #E6E7E9;border-right:1px solid #E6E7E9">성별</th>
                                <td><?php echo _elm( $aData, 'MB_GENDER' ) == 'm' ? '남성' : ( _elm( $aData, 'MB_GENDER' ) == 'w' ? '여성' : ' - ' ) ;?></td>
                                <th style="background-color:#F8FAFC; border-bottom:0px; border-left:1px solid #E6E7E9;border-right:1px solid #E6E7E9">나이</th>
                                <td>
                                    <?php
                                    $mb_birth = _elm( $aData, 'MB_BIRTH' );
                                    if( empty($mb_birth ) === false ){
                                        $birthDate = new DateTime($mb_birth);
                                        $currentDate = new DateTime(); // 현재 날짜

                                        // 생일과 현재 날짜의 차이 계산
                                        $age = $currentDate->diff($birthDate)->y; // 나이 계산 (년만 추출)
                                    }else{
                                        $age = '';
                                    }

                                    echo $age;
                                    ?>

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- 카드1 -->
    <div class="col-12">
        <div class="card" style="border:0 !important">
            <div class="sec-title d-flex justify-content-between align-items-center"
                style="clear:both; font-size: 14px; font-weight: bold; margin-bottom: 0.3rem; margin-top:0.5rem;">
                <span>주문내역</span>
                <a href="javascript:void(0);"
                    style="font-size: 12px; color: #007bff; text-decoration: none;">더보기 &gt;</a>
            </div>

            <div class="card-body" style="padding:0px !important;">
                <!-- 테이블 -->
                <div style="border:1px solid #E6E7E9; border-radius:0 0 4px 4px">
                    <div class="table-responsive">
                        <table class="table table-vcenter" id="listsTable">
                            <colgroup>
                                <col style="width:15%">
                                <col style="width:10%">
                                <col style="width:*;">
                                <col style="width:10%;">
                                <col style="width:10%;">
                                <col style="width:15%;">
                            </colgroup>
                            <thead style="background-color:#F8FAFC">
                                <tr>
                                    <th>주문일시</th>
                                    <th>주문번호</th>
                                    <th>주문상품</th>
                                    <th>총주문금액</th>
                                    <th>총 실제결제금액</th>
                                    <th>상태</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if( empty( _elm( $aData, 'orderList' ) ) === false ){
                                    foreach( _elm( $aData, 'orderList' ) as $oKey => $oLists):
                                ?>
                                <tr>
                                    <td><?php echo date( 'y-m-d H:i', strtotime(_elm( $oLists, 'O_ORDER_AT' ) ) )?></td>
                                    <td><?php echo _elm( $oLists, 'O_ORDID' )?></td>
                                    <td><?php echo _elm( $oLists, 'O_TITLE' )?></td>
                                    <td><?php echo number_format( _elm( $oLists, 'O_TOTAL_PRICE' ) )?></td>
                                    <td><?php echo number_format( _elm( $oLists, 'O_PG_PRICE' ) )?></td>
                                    <td><?php echo _elm( $aOrderStatus, _elm( $oLists, 'O_STATUS' ) )?></td>
                                </tr>
                                <?php
                                    endforeach;
                                }else{
                                ?>
                                <tr>
                                    <td colspan="6">데이터가 없습니다.</td>
                                </tr>
                                <?php
                                }
                                ?>


                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- 카드1 -->
    <div class="col-12">
        <div class="card" style="border:0 !important">
            <div class="sec-title d-flex justify-content-between align-items-center"
                style="clear:both; font-size: 14px; font-weight: bold; margin-bottom: 0.3rem; margin-top:0.5rem;">
                <span>상담내역</span>
                <a href="javascript:void(0);" onclick="javascript:openCounselLayer('<?php echo _elm( $aData, 'MB_IDX' )?>', 'counselModal')"
                style="font-size: 12px; color: #007bff; text-decoration: none;">더보기 &gt;</a>
            </div>
            <div class="card-body" style="padding:0px !important;">
                <!-- 테이블 -->
                <div style="border:1px solid #E6E7E9; border-radius:0 0 4px 4px">
                    <div class="table-responsive">
                        <table class="table table-vcenter" id="listsTable">
                            <colgroup>
                                <col style="width:*;">
                                <col style="width:30%;">
                            </colgroup>
                            <thead style="background-color:#F8FAFC">
                                <tr>
                                    <th>상담내용</th>
                                    <th>등록일/등록자</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if( empty( _elm( $aData, 'counselList' ) ) === false ){
                                    foreach( _elm( $aData, 'counselList' ) as $cKey => $cLists ):
                                ?>
                                <tr>
                                    <td><?php echo _elm( $cLists, 'C_CONTENT' )?></td>
                                    <td><?php echo date( 'y-m-d H:i', strtotime(_elm( $cLists, 'C_CREATE_AT' ) ) )?> / <?php echo _elm( $cLists, 'C_WRITER_NAME' )?></td>
                                </tr>
                                <?php
                                    endforeach;
                                }else{
                                ?>
                                <tr>
                                    <td colspan="3">데이터가 없습니다.</td>
                                </tr>
                                <?php
                                }
                                ?>


                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- 카드1 -->
    <div class="col-12">
        <div class="card" style="border:0 !important">
            <div class="sec-title d-flex justify-content-between align-items-center"
                style="clear:both; font-size: 14px; font-weight: bold; margin-bottom: 0.3rem; margin-top:0.5rem;">
                <span>리뷰내역</span>
                <a href="javascript:void(0);"
                style="font-size: 12px; color: #007bff; text-decoration: none;">더보기 &gt;</a>
            </div>
            <div class="card-body" style="padding:0px !important;">
                <!-- 테이블 -->
                <div style="border:1px solid #E6E7E9; border-radius:0 0 4px 4px">
                    <div class="table-responsive">
                        <table class="table table-vcenter" id="QnaListsTable">
                            <thead style="background-color:#F8FAFC">
                                <tr>
                                    <th>no</th>
                                    <th>제목</th>
                                    <th>작성일</th>
                                    <th>조회</th>
                                    <th>답변여부</th>
                                    <th>답변</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6">데이터가 없습니다.</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- 카드1 -->

    <div class="col-12">
        <div class="card" style="border:0 !important">
            <div class="sec-title d-flex justify-content-between align-items-center"
                style="clear:both; font-size: 14px; font-weight: bold; margin-bottom: 0.3rem; margin-top:0.5rem;">
                <span>1:1 문의 내역</span>
                <a href="/board/boardLists/posts/QNA?s_condition=writer_idx&s_keyword=<?php echo _elm( $aData, 'MB_IDX' )?>"
                style="font-size: 12px; color: #007bff; text-decoration: none;" target="_blank">더보기 &gt;</a>
            </div>
            <div class="card-body" style="padding:0px !important;">
                <!-- 테이블 -->
                <div style="border:1px solid #E6E7E9; border-radius:0 0 4px 4px">
                    <div class="table-responsive">
                        <table class="table table-vcenter" id="listsTable">
                            <colgroup>
                                <col style="width:*;">
                                <col style="width:20%;">
                                <col style="width:15%;">
                                <col style="width:15%;">
                                <col style="width:20%;">
                            </colgroup>
                            <thead style="background-color:#F8FAFC">
                                <tr>
                                    <th>제목</th>
                                    <th>작성일</th>
                                    <th>비밀글 여부</th>
                                    <th>답변여부</th>
                                    <th>답변작성자</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if( empty( _elm( $aData, 'qnaList' ) ) === false ){
                                    foreach( _elm( $aData, 'qnaList' ) as $qKey => $qLists ):
                                ?>
                                <tr>
                                    <td><?php echo _elm( $qLists, 'P_TITLE' )?></td>
                                    <td><?php echo date( 'y-m-d H:i', strtotime( _elm( $qLists, 'P_CREATE_AT' ) ) )?></td>
                                    <td><?php echo _elm( $qLists, 'P_SECRET' ) == 'Y'? '비밀글' : '' ?></td>
                                    <td><?php echo
                                    _elm( $qLists, 'P_ANSWER_STATUS' ) == 'READY'? '문의등록' :
                                    ( _elm( $qLists, 'P_ANSWER_STATUS' ) == 'RECEIVED'? '문의접수' :
                                        ( _elm( $qLists, 'P_ANSWER_STATUS' ) == 'PREPARING'? '답변준비중' : '답변완료' )
                                    ) ?>
                                    </td>
                                    <td><?php echo _elm( $qLists, 'P_ANSWER_STATUS' ) == 'COMPLETED' ? date( 'y-m-d H:i', strtotime(_elm( $qLists, 'P_ANSWER_AT' ) ) ). ' <br>'. _elm( $qLists, 'ADM_NAME' ) : ''?></td>
                                </tr>
                                <?php
                                    endforeach;
                                }else{
                                ?>
                                <tr>
                                    <td colspan="3">데이터가 없습니다.</td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>