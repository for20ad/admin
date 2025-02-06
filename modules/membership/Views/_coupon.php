<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();


    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);
    $aMemeberGrade    = _elm($view_datas, 'member_grades', []);
    $aData            = _elm($view_datas, 'aData', [] );
    $aReasonGbn       = _elm($view_datas, 'reason_gbn', [] );
    $mMbIdx           = _elm($view_datas, 'm_mb_idx', 0 );
?>
<div class="row row-deck row-cards col-12"  style="padding-left:0.8rem">
    <div class="col-12">
        <div class="card">
            <div class="card accordion-card"
                style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                            viewBox="0 0 4 4" fill="none">
                            <circle cx="2" cy="2" r="2" fill="#206BC4" />
                        </svg>
                        <p class="body1-c ms-2 mt-1">
                            검색
                        </p>
                    </div>
                    <!-- 아코디언 토글 버튼 -->
                    <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
                        <span class="form-selectgroup-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8"
                                viewBox="0 0 14 8" fill="none">
                                <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </label>
                </div>
            </div>

            <div class="card-body" >
                <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_coupon_search', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
                <input type="hidden" name="mb_idx" value="<?php echo $mMbIdx?>">
                <input type="hidden" name="page" id="page" value="1">
                <div class="table-responsive">
                    <table class="table table-vcenter">
                        <tbody>
                            <colgroup>
                                <col style="width:20%;">
                                <col style="width:80%;">
                            </colgroup>
                            <tr>
                                <th class="no-border-bottom">발행일 검색</th>
                                <td class="no-border-bottom">
                                    <div class="form-inline" style="padding-top:0.8rem">
                                        <div class="input-icon">
                                            <input type="text" class="form-control datepicker-icon flatpickr-input"
                                                name="s_start_date" readonly style="max-width:130px;">
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
                                            <input type="text" class="form-control datepicker-icon flatpickr-input"
                                                name="s_end_date" readonly style="max-width:130px;">
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
                                <th class="no-border-bottom">사용구분</th>
                                <td class="no-border-bottom">
                                    <div class="form-inline">
                                    <?php
                                        $options = [''=>'전체', 'usePassible' => '사용가능', 'used' => '사용완료', 'useImPassible' => '사용불가능', ];
                                        $options += $aReasonGbn;
                                        $extras   = ['id' => 's_status', 'class' => 'form-select', 'style' => 'max-width: 220px;'];
                                        $selected = '';
                                        echo getSelectBox('s_status', $options, $selected, $extras);
                                    ?>
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
                        'buttonStyle' => 'width: 120px; height: 34px;background-color:#206BC4',
                        'width' => '21',
                        'height' => '20',
                        'stroke' => 'white',
                        'extra' => [
                            'onclick' => 'getCouponLists();',
                        ]
                    ]);
                ?>
                <?php
                    echo getIconButton([
                        'txt' => '초기화',
                        'icon' => 'reset',
                        'buttonClass' => 'btn',
                        'buttonStyle' => 'width: 120px; height: 34px',
                        'width' => '21',
                        'height' => '20',
                        'extra' => [
                            'onclick' => 'document.getElementById("frm_coupon_search").reset()',
                        ]
                    ]);
                ?>
                </div>
            </div>

        </div>
    </div>
    <!-- 카드1 -->
    <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_sub_lists', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
    <div class="col-12">
        <div class="card" style="border:0 !important" id="couponListsTableWrap">
            <div class="card-body" style="padding:0px !important;">
                <!-- 테이블 -->
                <div style="border:1px solid #E6E7E9; border-radius:0 0 4px 4px;">
                    <div class="table-responsive">
                        <table class="table table-vcenter" id="couponListsTable">
                            <colgroup>
                                <col style="width:5%;">
                                <col style="width:45%;">
                                <col style="width:15%;">
                                <col style="width:10%;">
                                <col style="width:20%">
                                <col style="width:5%;">
                            </colgroup>
                            <thead style="background-color:#F8FAFC">
                                <tr>
                                    <th>no</th>
                                    <th>쿠폰명/사용혜택명</th>
                                    <th>발급일</th>
                                    <th>사용기간</th>
                                    <th>쿠폰형식</th>
                                    <th>사용범위</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6">데이터가 없습니다.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-wrapper" id="pagination">
                    </div>
                </div>


            </div>
        </div>
    </div>
    <?php echo form_close();?>
</div>
<script>
    flatpickr('.datepicker-icon', {
        enableTime: true,
        dateFormat: 'Y-m-d',
        time_24hr: true
    });


    getCouponLists();


</script>