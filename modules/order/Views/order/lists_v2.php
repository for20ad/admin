<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }
    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $orderStatus      = [
        'wait'=>[
            'PayChecking',  // 결제대기
            'PayWaiting',   // 입금확인
        ],
        'compalte' =>[
            'PayComplete',  //결제완료
        ]
    ];
    $aOrderConf        = _elm( $pageDatas, 'aOrderConf' );
    $aPayMethodConf    = _elm( $pageDatas, 'aPayMethodConf' );

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
        <h3 class="h3-c">통합주문목록 .v2</h3>
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
                    <input type="hidden" name="s_ordStatus" id="s_ordStatus" value="<?php empty( $_GET['navi'] )? 'all' : $_GET['navi'] ?>">

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
                                                    name="s_start_date" value="<?php echo empty( $_GET['startDate'] ) == true ? date('Y-m-d'): $_GET['startDate'] ?>" readonly>
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
                                                    name="s_end_date" value="<?php echo empty( $_GET['endDate'] ) == true ? date('Y-m-d'): $_GET['endDate'] ?>" readonly>
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
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?php echo empty( $_GET['navi'] )==true || $_GET['navi'] == 'all'? 'active' : '' ?>" id="all-tab" data-bs-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">전체 <span id="allCnt" class="f-blue"></span></a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'payWait'? 'active' : '' ?>" id="payWait-tab" data-bs-toggle="tab" href="#payWait" role="tab" aria-controls="payWait" aria-selected="false">결제대기<span id="payWaitCnt" class="f-blue"></span></a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'prdWait'? 'active' : '' ?>" id="prdWait-tab" data-bs-toggle="tab" href="#prdWait" role="tab" aria-controls="fail" aria-selected="false">상품준비중<span id="prdWaitCnt" class="f-blue"></span></a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'cancel'? 'active' : '' ?>" id="cancel-tab" data-bs-toggle="tab" href="#cancel" role="tab" aria-controls="cancel" aria-selected="false">취소접수<span id="cancelCnt" class="f-blue"></span></a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'return'? 'active' : '' ?>" id="return-tab" data-bs-toggle="tab" href="#return" role="tab" aria-controls="return" aria-selected="true">반품접수 <span id="returnCnt" class="f-blue"></span></a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'exchange'? 'active' : '' ?>" id="exchange-tab" data-bs-toggle="tab" href="#exchange" role="tab" aria-controls="exchange" aria-selected="false">교환접수<span id="exchangeCnt" class="f-blue"></span></a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'shipWait'? 'active' : '' ?>" id="shipWait-tab" data-bs-toggle="tab" href="#shipWait" role="tab" aria-controls="shipWait" aria-selected="false">배송대기<span id="shipWaitCnt" class="f-blue"></span></a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'shippingProgress'? 'active' : '' ?>" id="shippingProgress-tab" data-bs-toggle="tab" href="#shippingProgress" role="tab" aria-controls="shippingProgress" aria-selected="false">배송중<span id="shippingProgressCnt" class="f-blue"></span></a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'shippingComplete'? 'active' : '' ?>" id="shippingComplete-tab" data-bs-toggle="tab" href="#shippingComplete" role="tab" aria-controls="shippingComplete" aria-selected="false">배송완료<span id="shippingCompleteCnt" class="f-blue"></span></a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'buyDecision'? 'active' : '' ?>" id="buyDecision-tab" data-bs-toggle="tab" href="#buyDecision" role="tab" aria-controls="buyDecision" aria-selected="false">구매결정<span id="buyDecisionCnt" class="f-blue"></span></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">

                <!-- 탭 메뉴 -->
                <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_lists',  'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
                <!-- 탭 콘텐츠 -->
                <div class="tab-content" id="myTabContent">
                    <!-- 전체 탭 내용 -->
                    <div class="tab-pane fade show <?php echo empty( $_GET['navi'] )==true || $_GET['navi'] == 'all'? 'active show' : '' ?>"" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <div class="table-responsive order-table" style="overflow-x: auto; min-width: 1200px;">
                            <table class="table table-vcenter" id="AllOrdersTable">
                                <colgroup>
                                    <col style="width:40px">
                                    <col style="width:15%">
                                    <col style="width:40%">
                                    <col style="width:15%">
                                    <col style="width:35%;">
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
                                        <th>주문일(주문번호)</th>
                                        <th>등록 배송지</th>
                                        <th>결제정보</th>
                                        <th>주문 추가정보 메모</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 결제대기 탭 내용 -->
                    <div class="tab-pane fade <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'payWait'? 'active show' : '' ?>" id="payWait" role="tabpanel" aria-labelledby="payWait-tab">
                        <div class="table-responsive order-table" style="overflow-x: auto; min-width: 1200px;">
                            <table class="table table-vcenter" id="PayWaitOrdersTable">
                                <colgroup>
                                    <col style="width:40px">
                                    <col style="width:15%">
                                    <col style="width:35%">
                                    <col style="width:15%">
                                    <col style="width:35%;">
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
                                        <th>주문일(주문번호)</th>
                                        <th>등록 배송지</th>
                                        <th>결제정보</th>
                                        <th>주문 추가정보 메모</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- 상품준비중 탭 내용 -->
                    <div class="tab-pane fade <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'prdWait'? 'active show' : '' ?>" id="prdWait" role="tabpanel" aria-labelledby="prdWait-tab">
                        <div class="table-responsive order-table" style="overflow-x: auto; min-width: 1200px;">
                            <table class="table table-vcenter" id="PrdWaitOrdersTable">
                                <colgroup>
                                    <col style="width:40px">
                                    <col style="width:15%">
                                    <col style="width:35%">
                                    <col style="width:15%">
                                    <col style="width:35%;">
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
                                        <th>주문일(주문번호)</th>
                                        <th>등록 배송지</th>
                                        <th>결제정보</th>
                                        <th>주문 추가정보 메모</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- 취소 탭 내용 -->
                    <div class="tab-pane fade <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'cancel'? 'active show' : '' ?>" id="cancel" role="tabpanel" aria-labelledby="cancel-tab">
                        <div class="table-responsive order-table" style="overflow-x: auto; min-width: 1200px;">
                            <table class="table table-vcenter" id="CancelOrdersTable">
                                <colgroup>
                                    <col style="width:40px">
                                    <col style="width:15%">
                                    <col style="width:35%">
                                    <col style="width:15%">
                                    <col style="width:35%;">
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
                                        <th>주문일(주문번호)</th>
                                        <th>등록 배송지</th>
                                        <th>결제정보</th>
                                        <th>주문 추가정보 메모</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- 반품접수 탭 내용 -->
                    <div class="tab-pane fade <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'return'? 'active show' : '' ?>" id="return" role="tabpanel" aria-labelledby="return-tab">
                        <div class="table-responsive order-table" style="overflow-x: auto; min-width: 1200px;">
                            <table class="table table-vcenter" id="ReturnOrdersTable">
                                <colgroup>
                                    <col style="width:40px">
                                    <col style="width:15%">
                                    <col style="width:35%">
                                    <col style="width:15%">
                                    <col style="width:35%;">
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
                                        <th>주문일(주문번호)</th>
                                        <th>등록 배송지</th>
                                        <th>결제정보</th>
                                        <th>주문 추가정보 메모</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 교환접수 탭 내용 -->
                    <div class="tab-pane fade <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'exchange'? 'active show' : '' ?>" id="exchange" role="tabpanel" aria-labelledby="exchange-tab">
                        <div class="table-responsive order-table" style="overflow-x: auto; min-width: 1200px;">
                            <table class="table table-vcenter" id="ExchangeOrdersTable">
                                <colgroup>
                                    <col style="width:40px">
                                    <col style="width:15%">
                                    <col style="width:35%">
                                    <col style="width:15%">
                                    <col style="width:35%;">
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
                                        <th>주문일(주문번호)</th>
                                        <th>등록 배송지</th>
                                        <th>결제정보</th>
                                        <th>주문 추가정보 메모</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- 배송대기 탭 내용 -->
                    <div class="tab-pane fade <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'shipWait'? 'active show' : '' ?>" id="shipWait" role="tabpanel" aria-labelledby="shipWait-tab">
                        <div class="table-responsive order-table" style="overflow-x: auto; min-width: 1200px;">
                            <table class="table table-vcenter" id="ShipWaitOrdersTable">
                                <colgroup>
                                    <col style="width:40px">
                                    <col style="width:15%">
                                    <col style="width:35%">
                                    <col style="width:15%">
                                    <col style="width:35%;">
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
                                        <th>주문일(주문번호)</th>
                                        <th>등록 배송지</th>
                                        <th>결제정보</th>
                                        <th>주문 추가정보 메모</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 배송중 탭 내용 -->
                    <div class="tab-pane fade <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'shippingProgress'? 'active show' : '' ?>" id="shippingProgress" role="tabpanel" aria-labelledby="shippingProgress-tab">
                        <div class="table-responsive order-table" style="overflow-x: auto; min-width: 1200px;">
                            <table class="table table-vcenter" id="ShippingProgressOrdersTable">
                                <colgroup>
                                    <col style="width:40px">
                                    <col style="width:15%">
                                    <col style="width:35%">
                                    <col style="width:15%">
                                    <col style="width:35%;">
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
                                        <th>주문일(주문번호)</th>
                                        <th>등록 배송지</th>
                                        <th>결제정보</th>
                                        <th>주문 추가정보 메모</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 배송완료 탭 내용 -->
                    <div class="tab-pane fade <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'shippingComplete'? 'active show' : '' ?>" id="shippingComplete" role="tabpanel" aria-labelledby="shippingComplete-tab">
                        <div class="table-responsive order-table" style="overflow-x: auto; min-width: 1200px;">
                            <table class="table table-vcenter" id="ShippingCompleteOrdersTable">
                                <colgroup>
                                    <col style="width:40px">
                                    <col style="width:15%">
                                    <col style="width:35%">
                                    <col style="width:15%">
                                    <col style="width:35%;">
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
                                        <th>주문일(주문번호)</th>
                                        <th>등록 배송지</th>
                                        <th>결제정보</th>
                                        <th>주문 추가정보 메모</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 구매결정 탭 내용 -->
                    <div class="tab-pane fade <?php echo empty( $_GET['navi'] )==false && $_GET['navi'] == 'buyDecision'? 'active show' : '' ?>" id="buyDecision" role="tabpanel" aria-labelledby="buyDecision-tab">
                        <div class="table-responsive order-table" style="overflow-x: auto; min-width: 1200px;">
                            <table class="table table-vcenter" id="BuyDecisionOrdersTable">
                                <colgroup>
                                    <col style="width:40px">
                                    <col style="width:15%">
                                    <col style="width:35%">
                                    <col style="width:15%">
                                    <col style="width:35%;">
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
                                        <th>주문일(주문번호)</th>
                                        <th>등록 배송지</th>
                                        <th>결제정보</th>
                                        <th>주문 추가정보 메모</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>


                <!-- 페이지네이션 -->
                <div class="pagination-wrapper" id="pagination">


                </div>
            </div>


            <?php echo form_close()?>

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

<div class="modal fade" id="memoModal" tabindex="-1" style="margin-top:3em;top:20%;left:10%;z-index:9999" aria-labelledby="memoLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:40vh;display:flex;flex-direction: column;width:30vh">
            <div class="modal-header">
                <h5 class="modal-title" id="dataModalLabel">매모추가</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;height:200px;">
                <div class="viewData memo-content-wrap">
                    <textarea class="memo-textarea form-control" rows="5" data-max-length="1000" name="i_memo_content"></textarea>
                    <div class="wordCount" style="text-align:right;">
                        0/1000
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-primary" onclick="submitMemo()">저장</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function(){
    if( $('[s_start_date').val() == '' && $('[s_end_date').val() == '' ){
        setPeriodDate('1Day');
    }

    $(document).on('mouseover', '.memoBox .flex-right', function () {
        const $row = $(this).closest('.memoBox');
        $row.find('.flex-right').hide();
        $row.find('.right-float').addClass('show');
    });

    // 마우스를 right-float 전체에서 벗어났을 때
    $(document).on('mouseleave', '.memoBox .right-float', function (e) {
        const $float = $(this);
        const $row = $float.closest('.memoBox');

        // mouseleave 발생한 다음, 마우스가 떠난 대상이 right-float 내부인지 체크
        const related = e.relatedTarget;
        if (!related || ($float[0] !== related && !$float.has(related).length)) {
            $float.removeClass('show');
            $row.find('.flex-right').show();
        }
    });

    $(document).on('click', '.toggleSvg', function () {
        const $svg = $(this);
        const $box = $svg.closest('.memoBox').find('.box-radius');
        const memoIdx = $svg.closest('.memoBox').data('memo-idx');
        let memoStatus = 'READY';

        if( !$svg.hasClass('active') ){
            memoStatus = 'COMPLATE';
        }

        data = 'i_memoIdx=' + memoIdx + '&i_status=' + memoStatus;
        url = '/apis/order/changeMemoStatus';
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
                if( $svg.hasClass('active') ){
                    $svg.removeClass('active');
                    $box.text('미해결');
                    $box.removeClass('active');
                }else{
                    $svg.addClass('active');
                    $box.text('해결');
                    $box.addClass('active');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                submitError(jqXHR.status, errorThrown);
                console.log(textStatus);

                return false;
            },
            complete: function() {}
        });
    });
});

function deleteMemoConfirm( obj ){
    box_confirm('메모를 삭제하시겠습니까?', 'q', '', deleteMemo, obj);
}
function deleteMemo( obj ){
    const memoIdx = $(obj).closest('.memoBox').data('memo-idx');
    data = 'i_memoIdx=' + memoIdx;
    url = '/apis/order/deleteMemo';
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
            getMemoRows( obj );
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);

            return false;
        },
        complete: function() {}
    });
}
function getMemoRows( obj ){

    const s_ordid = $(obj).closest('tr').data('ord-id');
    const $memoArea = $(obj).closest('.memoArea');
    const $memoTitle = $(obj).closest('tr').find('.memoTitle');
    const $memoCntArea = $(obj).closest('td').find('.memoCnt');
    alert( s_ordid );
    data = 's_ordid=' + s_ordid;
    url = '/apis/order/getMemoListsRow';
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
            $memoArea.empty().html( response.page_datas.rows );
            if(  parseInt(response.page_datas.memoCnt) > 0 ){
                $memoCntArea.empty().text( response.page_datas.memoCnt+'개 메모' );
            }else{
                $memoTitle.hide();
                $('.memoArea').trigger('mouseenter');
            }

        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);

            return false;
        },
        complete: function() {}
    });
}
function modifyMemo( obj ){

}

function openLayer(ordIdx, id) {

    data = 'ordIdx=' + ordIdx;
    url = '/apis/order/orderStatusDetail';
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

function openMemoLayer(ordId, obj) {

    //var modal = new bootstrap.Modal(document.getElementById(id));
    var modalElement = document.getElementById('memoModal');
    var modal = new bootstrap.Modal(modalElement, {
        backdrop: 'static', // 마스크 클릭해도 닫히지 않게 설정
        keyboard: false // esc 키로 닫히지 않게 설정
    });

    modal.show();
    const $modal = $('#memoModal');

    // 데이터 저장 (obj는 문자열로 저장)
    $modal.data('ordId', ordId);
    $modal.data('obj', obj);

}
function submitMemo() {
    const $modal = $('#memoModal');
    const ordId = $modal.data('ordId');
    const obj = $modal.data('obj'); // 객체 그대로 불러올 수 있음
    const memoText = $modal.find('.memo-textarea').val();

    data = 'i_ordid=' + ordId + '&i_memo='+memoText;
    url = '/apis/order/insertMemo';
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
            getMemoRows(obj);
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
// 주문 상태 배열
const orderStatus = {
    'wait': [
        'PayRequest',           //'결제 요청',
        'PayWaiting',           //'결제 대기',
        'PayChecking',          //'입금 확인 중',
    ],
    'complete': [
        'PayComplete',          // 결제완료
    ],
    'fail': [
        'PayCancelRequest',     //'결제 취소 예정',
        'PayCancelComplete',    //'결제 취소 완료',
        'UnpaidCancelComplete', //'미입금 취소 완료',
        'OrderCancelComplete',  //'판매 취소 완료',
    ],
    'overStock':[
        'ProductOutStock'       //'상품 품절',
    ],

};

// 탭 클릭 시 s_ordStatus 값 업데이트
$('#orderStatusTabs .nav-link').on('click', function() {
    // 클릭된 탭의 ID를 가져오기 (예: 'wait' 또는 'complete')
    var tabId = $(this).attr('id').split('-')[0];  // 예: 'wait', 'complete'

    // 해당 탭의 상태 값을 s_ordStatus에 설정
    var statusValues = tabId;
    $('#s_ordStatus').val(statusValues);
    $('#successBtn').hide();
    if( tabId == 'payWait' || tabId == 'all' ){
        $('#successBtn').show();
    }


    // 페이지 번호를 URL에서 가져오기 (기존 페이지 번호가 있으면 사용, 없으면 1로 설정)
    var urlParams = new URLSearchParams(window.location.search);
    var page = 1;
    var navi = statusValues;
    var _startDate = urlParams.get('startDate')??'';
    var _endDate = urlParams.get('endDate')??'';

    //새 URL 생성 (기존의 페이지 번호와 탭 상태를 포함)
    var newUrl = window.location.origin + window.location.pathname + '?page=' + page + '&navi=' + navi + '&startDate=' + _startDate + '&endDate=' + _endDate;

    // URL 업데이트
    history.pushState({ page: page, s_ordStatus: statusValues, startDate: _startDate, endDate: _endDate }, null, newUrl);

    getSearchList(1);

});

// 페이지 로드 시 기본값 설정
var initialTabId = $('#orderStatusTabs .nav-link.active').attr('id').split('-')[0];
//var initialStatusValues = orderStatus[initialTabId].join(',');
var initialStatusValues = initialTabId;

$('#s_ordStatus').val(initialStatusValues);

const parentContainer = document.getElementById('myTabContent');
// 부모 컨테이너에 클릭 이벤트를 위임합니다.
parentContainer.addEventListener('click', function(event) {

});

function openPrdBox(obj) {
    // 클릭된 svg 아이콘이 속한 .tab-pane 요소 찾기
    const tabPane = $(obj).closest('tr');
    // 해당 .tab-pane 내에서 .prd-box 요소 찾기
    const prdBox = $(tabPane).find('.prd-box');

    // 토글된 아이콘의 회전 효과
    $(obj).toggleClass("rotate");

    prdBox.each(function() {
        $(this).toggle();
        $(this).toggleClass('open');
    });
}
function openDropDownLayer(obj){
    const dropdown = $(obj).parent().find('.dropdown');
    dropdown.toggle();  // display: none / display: block 토글
    $(obj).toggleClass("rotate");
}
function openMemoDropDownLayer(obj){
    const dropdown = $(obj).parent().find('.memo-dropdown');
    dropdown.toggle();  // display: none / display: block 토글
    $(obj).toggleClass("rotate");
}

function copyToClipboard(obj) {
    // .ordid 요소를 찾아 그 값을 복사
    const ordidText = $(obj).closest('.form-inline').find('.ordid').text();

    // 클립보드에 텍스트 복사
    navigator.clipboard.writeText(ordidText).then(function() {
        // 성공 시 알림
        box_alert_autoclose('주문 ID가 클립보드에 복사되었습니다: ', 's');
    }).catch(function(err) {
        // 오류 발생 시 처리
        console.error('복사 실패:', err);
    });
}
//주문별 order_idx 로 상태 업데이트
function changeOrderInfoStatusConfirm( status, obj ){
    const alertText = $(obj).text()
    const submitData = {
        'status': status,
        'obj' : obj
    }
    box_confirm( alertText+' 하시겠습니까?', 'q', '', changeOrderInfoStatus, submitData  );
}

function changeOrderInfoStatus(submitData){
    const status = submitData.status;
    const obj    = submitData.obj;
    const idxs   = [];
    const idxsWrap = $(obj).closest('.prd-box').find('.goodsWrap');
    idxsWrap.each(function(){
        idxs.push( $(this).data('ordidx') );
    });

    data = 'i_status=' + status + '&i_order_idxs='+idxs;
    url = '/apis/order/changeOrderInfoStatus';
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
            getSearchList();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);

            return false;
        },
        complete: function() {}
    });


}
//주번채결번호로 상태값 업데이트
function confirmChangeOrderStatus( status, ordid ){

    const submitData = {
        'status': status,
        'ordid' : ordid
    }
    if( status == 'PayComplete'){
        box_confirm( '주문건을 입금 확인 처리 하시겠습니까?', 'q', '', changeOrderStatus, submitData );
    }else{
        box_confirm( '주문건을 취소 처리 하시겠습니까?', 'q', '', changeOrderStatus, submitData );
    }

}

function changeOrderStatus( submitData ){
    const status = submitData.status;
    const ordid = submitData.ordid;
    data = 'i_status='+ status +'&i_ordid=' + ordid;
    url = '/apis/order/changeOrderStatus';
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
            getSearchList();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);

            return false;
        },
        complete: function() {}
    });
}
</script>

<?php
$owensView->setFooterJs('/assets/js/order/order/lists.js');
$owensView->setFooterJs('/assets/js/order/order/register.js');
$owensView->setFooterJs('/assets/js/order/order/detail.js');

$script = "

";

$owensView->setFooterScript($script);