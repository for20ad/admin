<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }
    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];

    $orderTitle       = [
        'request'     => '교환요청',
        'handle'      => '승인/보류',
        'processing'  => '반송/재배송',
        'complate'    => '교환완료',
        'reject'      => '불가/철회',
    ];
    $orderBtns        = [
        'ExchangeRequest'             => '교환 요청',
        'ExchangePickupRequest'       => '교환 수거 요청',
        'ExchangeApproved'            => '교환 승인',
        'ExchangePending'             => '교환 보류',
        'ExchangeReturning'           => '교환 반송 중',
        'ExchangeReturnComplete'      => '교환 반송 완료',
        'ExchangeShippingProgress'    => '교환 재배송 중',
        'ExchangeShippingComplete'    => '교환 완료',
        'ExchangeRequestRetract'      => '교환 요청 철회',
        'ExchangeRejected'            => '교환 불가',
    ];
    $orderStatus      = [
        'request'=>[
            'ExchangeRequest',           //'교환 요청',
            'ExchangePickupRequest',     //'교환 수거 요청',
        ],
        'handle' =>[
            'ExchangeApproved',          //'교환 승인',
            'ExchangePending',           //'교환 보류',
        ],
        'processing' => [
            'ExchangeReturning',         //'교환 반송 중',
            'ExchangeReturnComplete',    //'교환 반송 완료',
            'ExchangeShippingProgress',  //'교환 재배송 중',
        ],
        'complate' => [
            'ExchangeShippingComplete',  //'교환 완료',
        ],
        'reject' => [
            'ExchangeRequestRetract',    //'교환 요청 철회',
            'ExchangeRejected',          //'교환 불가',
        ],
    ];
    $aOrderStatus = [];
    foreach (_elm( $orderStatus, 'request' ) as $statusKey) {
        if (isset($orderBtns[$statusKey])) {
            $aOrderStatus[$statusKey] = $orderBtns[$statusKey];
        }
    }
    $aOrderConf        = _elm( $pageDatas, 'aOrderConf' );
    $aPayMethodConf    = _elm( $pageDatas, 'aPayMethodConf' );



    $aStatusGroupOptions     = getSelectStatusGroupOptions($orderTitle, $orderStatus, $orderBtns);
?>
<link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
    /* 헤더 스타일 */
    #listsTable th, #listsTable td {
        text-align: center; /* 텍스트 가운데 정렬 */
        white-space: nowrap; /* 텍스트 줄바꿈 방지 */

    }
    .memo-layer {
        background-color: #ffffff;
        border: 1px solid #ccc;
        padding: 10px;
        position: absolute;
        z-index: 1000;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 250px; /* 원하는 너비로 조정 */
    }

    .memo-header {
        font-weight: bold;
        margin-bottom: 5px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 5px;
    }

    .memo-content {
        font-size: 12px;
        line-height: 1.5;
    }
    .nav-tabs .nav-link {


    }
    .tab-wrap{
        display: flex;
        height: 45px;
        padding: 8px 24px 0px 12px;
        align-items: center;
        align-self: stretch;
        border-radius: 4px 4px 0px 0px;
        border: 1px solid var(--Neutrals-Gray-200, #E6E7E9);
        background: #F8FAFC;

    }
    .nav-tabs .nav-link.active {

        border-radius: 4px 4px 0px 0px;
        border-top: 1px solid var(--Neutrals-Gray-200, #E6E7E9);
        border-right: 1px solid var(--Neutrals-Gray-200, #E6E7E9);
        border-left: 1px solid var(--Neutrals-Gray-200, #E6E7E9);
        border-bottom: 1px solid var(--Neutrals-Gray-200, #FFF);

        background: #FFF;
    }
    .nav-tabs {
        border-bottom: 0; /* 하단 보더 제거 */
    }

    .nav-tabs .nav-link {
        border-bottom: none; /* 각 탭의 하단 보더 제거 */
    }

</style>
<div class="container-fluid">
    <!-- 카트 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">교환프로세스 목록</h3>
    </div>

    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card accordion-card"
                    style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4" fill="none">
                                <circle cx="2" cy="2" r="2" fill="#206BC4" />
                            </svg>
                            <p class="body1-c ms-2 mt-1">
                                검색하기
                            </p>
                        </div>
                        <!-- 아코디언 토글 버튼 -->
                        <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
                            <span class="form-selectgroup-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8"
                                    fill="none">
                                    <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="card-body">

                    <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_search', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
                    <input type="hidden" name="page" id="page" value="1">
                    <input type="hidden" name="s_ordStatus" id="s_ordStatus" value="ExchangeRequest,ExchangePickupRequest">

                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <tbody>
                                <colgroup>
                                    <col style="width:10%;">
                                    <col style="width:40%;">
                                    <col style="width:10%;">
                                    <col style="width:50%;">
                                </colgroup>


                                <tr>
                                    <th class="no-border-bottom">기간</th>
                                    <td colspan="3" class="no-border-bottom">
                                        <div class="form-inline">
                                            <div style="margin-right:1.2rem;">
                                                <?php
                                            echo getButton([
                                                'text' => '오늘',
                                                'class' => 'btn',
                                                'style' => 'width: 60px; height: 36px',
                                                'extra' => [
                                                    'onclick' => 'setPeriodDate( \'1Day\' )',
                                                ]
                                            ]);
                                            ?>

                                                <?php
                                            echo getButton([
                                                'text' => '7일',
                                                'class' => 'btn',
                                                'style' => 'width: 60px; height: 36px',
                                                'extra' => [
                                                    'onclick' => 'setPeriodDate( \'7Day\' )',
                                                ]
                                            ]);
                                            ?>

                                                <?php
                                            echo getButton([
                                                'text' => '15일',
                                                'class' => 'btn',
                                                'style' => 'width: 60px; height: 36px',
                                                'extra' => [
                                                    'onclick' => 'setPeriodDate( \'15Day\' )',
                                                ]
                                            ]);
                                            ?>

                                                <?php
                                            echo getButton([
                                                'text' => '1개월',
                                                'class' => 'btn',
                                                'style' => 'width: 60px; height: 36px',
                                                'extra' => [
                                                    'onclick' => 'setPeriodDate( \'1Month\' )',
                                                ]
                                            ]);
                                            ?>

                                                <?php
                                            echo getButton([
                                                'text' => '3개월',
                                                'class' => 'btn',
                                                'style' => 'width: 60px; height: 36px',
                                                'extra' => [
                                                    'onclick' => 'setPeriodDate( \'3Month\' )',
                                                ]
                                            ]);
                                            ?>

                                                <?php
                                            echo getButton([
                                                'text' => '6개월',
                                                'class' => 'btn',
                                                'style' => 'width: 60px; height: 36px',
                                                'extra' => [
                                                    'onclick' => 'setPeriodDate( \'6Month\' )',
                                                ]
                                            ]);
                                            ?>

                                                <?php
                                            echo getButton([
                                                'text' => '12개월',
                                                'class' => 'btn',
                                                'style' => 'width: 60px; height: 36px',
                                                'extra' => [
                                                    'onclick' => 'setPeriodDate( \'1Years\' )',
                                                ]
                                            ]);
                                            ?>
                                            </div>
                                            <div class="input-icon">
                                                <input type="text" class="form-control datepicker-icon"
                                                    name="s_start_date" readonly>
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <rect x="4" y="5" width="16" height="16" rx="2" />
                                                        <line x1="16" y1="3" x2="16" y2="7" />
                                                        <line x1="8" y1="3" x2="8" y2="7" />
                                                        <line x1="4" y1="11" x2="20" y2="11" />
                                                        <line x1="11" y1="15" x2="12" y2="15" />
                                                        <line x1="12" y1="15" x2="12" y2="18" />
                                                    </svg>
                                                </span>
                                            </div>
                                            ~
                                            <div class="input-icon">
                                                <input type="text" class="form-control datepicker-icon"
                                                    name="s_end_date" readonly>
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <rect x="4" y="5" width="16" height="16" rx="2" />
                                                        <line x1="16" y1="3" x2="16" y2="7" />
                                                        <line x1="8" y1="3" x2="8" y2="7" />
                                                        <line x1="4" y1="11" x2="20" y2="11" />
                                                        <line x1="11" y1="15" x2="12" y2="15" />
                                                        <line x1="12" y1="15" x2="12" y2="18" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="no-border-bottom">상태</th>
                                    <td colspan="3" class="no-border-bottom">
                                        <div class="form-inline">
                                            <?php
                                                $options = [''=>'선택'];
                                                $options += $aOrderStatus;
                                                $extras   = ['id' => 's_status', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                                                $selected = '';
                                                echo getSelectBox('s_status', $options, $selected, $extras);
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="no-border-bottom">결제구분</th>
                                    <td colspan="3" class="no-border-bottom">
                                        <div class="form-inline">
                                            <?php
                                                $options = [''=>'선택'];
                                                $options += $aPayMethodConf;
                                                $extras   = ['id' => 's_paymethod', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                                                $selected = '1';
                                                echo getSelectBox('s_paymethod', $options, $selected, $extras);
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="no-border-bottom">검색</th>
                                    <td colspan="3" class="no-border-bottom">
                                        <div class="form-inline">
                                            <?php
                                                $options = ['orderNo'=>'주문번호', 'prdName'=>'상품명','memberId'=>'주문자ID', 'memberName'=>'주문자명','mobile'=>'휴대폰',];
                                                $extras   = ['id' => 's_condition', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                                                $selected = '1';
                                                echo getSelectBox('s_condition', $options, $selected, $extras);
                                            ?>
                                            <input type="text" class="form-control" style="width:20rem" name="s_keyword"
                                                id="s_keyword">
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <?php echo form_close();?>
                    <div style="text-align: center; margin-top: 36px">
                        <?php
                    echo getIconButton([
                        'txt' => '검색',
                        'icon' => 'search',
                        'buttonClass' => 'btn text-white',
                        'buttonStyle' => 'width: 180px; height: 46px;background-color:#206BC4',
                        'width' => '21',
                        'height' => '20',
                        'stroke' => 'white',
                        'extra' => [
                            'onclick' => 'getSearchList();',
                        ]
                    ]);
                    ?>
                        <?php
                    echo getIconButton([
                        'txt' => '초기화',
                        'icon' => 'reset',
                        'buttonClass' => 'btn',
                        'buttonStyle' => 'width: 180px; height: 46px',
                        'width' => '21',
                        'height' => '20',
                        'extra' => [
                            'onclick' => 'document.location.reload()',
                        ]
                    ]);
                    ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<br>
<div class="container-fluid" style="width: 100%; overflow-x: hidden;">
    <div class="col-12 col">
        <!-- 카드 타이틀 -->
        <div class="card accordion-card" style="padding: 17px 24px; border-radius: 4px 4px 0 0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4" fill="none">
                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                    </svg>
                    <p class="body1-c ms-2 mt-1">
                        목록
                    </p>
                </div>
                <!-- 아코디언 토글 버튼 -->
                <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
                    <span class="form-selectgroup-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8" fill="none">
                            <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                </label>
            </div>
        </div>

        <div class="card accordion-card" style="padding: 17px 24px; border-top:0">
            <div class="form-inline" style="justify-content: space-between;width: 100%;">
                <!-- 버튼 그룹 왼쪽정렬-->
                <div id="buttons">
                </div>

                <!-- 버튼 그룹 오른쪽정렬-->
                <!-- <div>
                    <?php
                    echo getIconButton([
                        'txt' => '등록',
                        'icon' => 'add',
                        'buttonClass' => 'btn ',
                        'buttonStyle' => 'width: 100px; height: 34px',
                        'width' => '20',
                        'height' => '20',
                        'stroke' => '#1D273B',
                        'extra' => [
                            'onclick' => "openLayer('', 'dataModal')",
                        ]
                    ]);
                    ?>
                </div> -->
            </div>
            <div class='tab-wrap'>
                <ul class="nav nav-tabs" id="orderStatusTabs" role="tablist">
                    <?php
                        $i = 0 ;
                        if( empty( $orderStatus ) == false ):
                            foreach ($orderStatus as $statusKey => $statusValues):
                                $i++;
                    ?>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?php echo $i== 1? 'active' : '' ?>" id="<?php echo $statusKey?>-tab" data-bs-toggle="tab" href="#<?php echo $statusKey?>" role="tab"
                            aria-controls="<?php echo $statusKey?>" aria-selected="true"><?php echo _elm( $orderTitle, $statusKey)?><span id="<?php echo $statusKey?>Cnt"></span>
                        </a>
                    </li>
                    <?php
                            endforeach;
                        endif;
                    ?>
                </ul>
            </div>
            <div style="padding-top:0.9rem;overflow-x: auto; min-width: 800px;">
                <div class="form-inline" style="min-width: 1200px; width: 100%; table-layout: fixed; word-wrap: break-word;">
                    <select id="is_status" class="form-select" style="max-width: 200px; margin-right: 0.235em;">
                        <option value="">선택</option>
                        <?php echo $aStatusGroupOptions; ?>
                    </select>
                    &nbsp;&nbsp;

                    <?php
                        echo getIconAnchor([
                            'txt' => '(으)로 변경',
                            'icon' => 'success',
                            'buttonClass' => '',
                            'buttonStyle' => '',
                            'width' => '24',
                            'height' => '24',
                            'stroke' => '#616876',
                            'extra' => [
                                'onclick' => 'orderStatusChangeListsConfirm( $(\'#is_status\').val() );',
                            ]
                        ]);
                    ?>
                    <?php
                    // if( empty( $orderBtns ) === false ):
                    //     $nLoop = 0;
                    //     foreach( $orderBtns as $btnValue => $btnTxt ):
                    //         echo getButton([
                    //             'text' =>  $btnTxt.' (으)로 변경',
                    //             'class' => 'btn btn-secondary',
                    //             'style' => 'padding: 5px 15px; white-space: nowrap; font-size: 13px;',
                    //             'extra' => [
                    //                 'onclick' => 'orderStatusChangeListsConfirm(\''. $btnValue .'\')',
                    //             ]
                    //         ]);
                    //         if( $nLoop != 0 && $nLoop % 8 ==0 ){
                    //             echo '</div><div class="form-inline" style="padding-top:10px;">';
                    //         }
                    //         $nLoop ++;
                    //     endforeach;
                    // endif;
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">

            <!-- 탭 콘텐츠 -->
            <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_lists', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
            <div class="tab-content" id="myTabContent" style="min-width: 1200px !important;" >

                <?php
                    $i = 0 ;
                    if( empty( $orderStatus ) == false ):
                        foreach ($orderStatus as $statusKey => $statusValues):
                            $i++;
                ?>

                <div class="tab-pane fade show  <?php echo $i == 1 ? 'active' : '' ?>"  id="<?php echo $statusKey?>" role="tabpanel" aria-labelledby="<?php echo $statusKey?>-tab" >
                    <div class="table-responsive order-table" style="overflow-x: auto; min-width: 1200px;">
                        <table class="table table-vcenter" id="<?php echo $statusKey?>OrdersTable" style="min-width: 1200px; width: 100%; table-layout: fixed; word-wrap: break-word;">
                            <colgroup>
                                <col style="width:80px">
                                <col style="width:80px;">
                                <col style="width:200px;">
                                <col style="width:80px;">
                                <col style="width:80px;">
                                <col style="width:100px;">
                                <col style="width:130px;">
                                <col style="width:280px;">
                                <col style="width:320px;">
                                <col style="width:100px;">
                                <col style="width:100px;">
                                <col style="width:100px;">
                                <col style="width:100px;">
                                <col style="width:125px;">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>
                                        <div class="checkbox checkbox-single">
                                            <?php
                                                $setParam = [
                                                    'name' => '',
                                                    'id' => 'checkAll',
                                                    'value' => '',
                                                    'label' => '',
                                                    'checked' => false,
                                                    'extraAttributes' => [
                                                        'class'=>'checkAll',
                                                        'aria-label' => 'Single checkbox One'
                                                    ]
                                                ];
                                                echo getCheckBox( $setParam );
                                            ?>
                                        </div>
                                    </th>
                                    <th>번호</th>
                                    <th>주문일(주문번호)</th>
                                    <th>주문자</th>
                                    <th>수령인</th>
                                    <th>결제수단</th>
                                    <th>상태</th>
                                    <th>송장번호</th>
                                    <th>주문상품</th>
                                    <th>총 상품금액</th>
                                    <th>총 할인금액</th>
                                    <th>총 배송비</th>
                                    <th>총 결제금액</th>
                                    <th>관리자메모</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- 동적으로 삽입될 '결제완료' 주문 리스트 -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
                        endforeach;
                    endif;
                ?>

            </div>
            <?php echo form_close() ?>
            <!-- 페이지네이션 -->
            <div class="pagination-wrapper" id="pagination">
                <!-- 페이지네이션 내용이 여기에 동적으로 추가됩니다 -->
            </div>



        </div>
    </div>
</div>
<!-- Modal S-->
<div class="modal fade" id="dataModal" tabindex="-1" style="margin-top:3em;" style="z-index:auto"
    aria-labelledby="dataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;width:100vh">
            <div class="modal-header">
                <h5 class="modal-title" id="dataModalLabel">등록/수정</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;">
                <div class="viewData">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal E-->

<div class="modal fade" id="memoModal" tabindex="-1" style="margin-top:3em;z-index:9999" aria-labelledby="memoLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:100vh;display:flex;flex-direction: column;width:90vh">
            <div class="modal-header">
                <h5 class="modal-title" id="memoLabel">주문 메모</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;">
                <div class="viewData">

                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="trackModal" tabindex="-1" style="margin-top:3em;z-index:9999" aria-labelledby="trackLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:70vh;display:flex;flex-direction: column;width:60vh">
            <div class="modal-header">
                <h5 class="modal-title" id="trackLabel">배송추적</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;">
                <div class="viewData">

                </div>
            </div>
        </div>
    </div>
</div>
<script>
function showSimpleMemoLayer(button) {

    // 레이어 가져오기
    const memoLayer = button.closest('td').querySelector('.memo-layer');

    // 버튼 위치 계산
    const buttonRect = button.getBoundingClientRect();
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const scrollLeft = window.scrollX || document.documentElement.scrollLeft;

    // 레이어 위치 설정
    memoLayer.style.display = 'block';
    memoLayer.style.position = 'absolute';
    memoLayer.style.top = `${buttonRect.top + scrollTop + button.offsetHeight}px`;
    memoLayer.style.left = `${buttonRect.left + scrollLeft - memoLayer.offsetWidth - 10}px`; // 버튼 왼쪽에 배치
    memoLayer.style.zIndex = 1000;
}

// 레이어 숨기기 함수
function hideSimpleMemoLayer(button) {
    const memoLayer = button.closest('td').querySelector('.memo-layer');
    memoLayer.style.display = 'none';
}
function openLayer(ordIdx, id) {

    data = 'ordIdx=' + ordIdx;
    url = '/apis/order/orderExchangeDetail';
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        processData: false,
        cache: false,
        beforeSend: function() {},
        success: function(response) {
            submitSuccess(response);

            if (response.status == 'false') {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }

                return false;
            }
            $('#' + id + ' .viewData').empty().html(response.page_datas.detail);
            //var modal = new bootstrap.Modal(document.getElementById(id));
            var modalElement = document.getElementById(id);
            var modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static', // 마스크 클릭해도 닫히지 않게 설정
                keyboard: false // esc 키로 닫히지 않게 설정
            });

            modal.show();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);

            return false;
        },
        complete: function() {}
    });
}
let show_ord_idx = '';
const orderBtns = <?php echo json_encode( $orderBtns )?>;
const orderStatus = <?php echo json_encode( $orderStatus )?>;

// 탭 클릭 시 s_ordStatus 값 업데이트
$('#orderStatusTabs .nav-link').on('click', function() {
    // 클릭된 탭의 ID를 가져오기 (예: 'wait' 또는 'complete')
    var tabId = $(this).attr('id').split('-')[0];  // 예: 'wait', 'complete'

    // 해당 탭의 상태 값을 s_ordStatus에 설정
    var statusValues = orderStatus[tabId].join(',');
    $('#s_ordStatus').val(statusValues);

    var statusOptions = orderStatus[tabId] || [];
    var $select = $('#s_status');
    $select.empty(); // 기존 옵션 제거
    $select.append('<option value="">선택</option>'); // 기본 옵션 추가

    // 새로운 옵션 추가
    statusOptions.forEach(function(statusKey) {
        if (orderBtns[statusKey]) {
            $select.append('<option value="' + statusKey + '">' + orderBtns[statusKey] + '</option>');
        }
    });

    getSearchList(1);

});

// 페이지 로드 시 기본값 설정
var initialTabId = $('#orderStatusTabs .nav-link.active').attr('id').split('-')[0];
var initialStatusValues = orderStatus[initialTabId].join(',');
$('#s_ordStatus').val(initialStatusValues);



</script>

<?php
$owensView->setFooterJs('/assets/js/order/exchanges/lists.js');
$owensView->setFooterJs('/assets/js/order/exchanges/register.js');
$owensView->setFooterJs('/assets/js/order/exchanges/detail.js');

$script = "

";

$owensView->setFooterScript($script);