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
                <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_point_search', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
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
                                <th class="no-border-bottom">검색</th>
                                <td class="no-border-bottom">
                                    <div class="form-inline">
                                    <?php
                                        $options = ['orderNo' => '주문번호'];
                                        $extras   = ['id' => 's_condition', 'class' => 'form-select', 'style' => 'max-width: 174px;'];
                                        $selected = '';
                                        echo getSelectBox('s_condition', $options, $selected, $extras);
                                    ?>
                                    <input type="text" class="form-control" style="width:15.2rem" name="s_keyword" id="s_keyword">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="no-border-bottom">지급/차감일</th>
                                <td class="no-border-bottom">
                                    <div class="form-inline">

                                        <div>
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

                                    </div>
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
                                <th class="no-border-bottom">지급/차감사유</th>
                                <td class="no-border-bottom">
                                    <div class="form-inline">
                                    <?php
                                        $options = [''=>'전체' ];
                                        $options += $aReasonGbn;
                                        $extras   = ['id' => 's_reason_gbn', 'class' => 'form-select', 'style' => 'max-width: 220px;'];
                                        $selected = '';
                                        echo getSelectBox('s_reason_gbn', $options, $selected, $extras);
                                    ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="no-border-bottom">지급/차감구분</th>
                                <td class="no-border-bottom">
                                    <div class="form-inline">
                                    <?php
                                        $options = [''=>'전체', 'add' => '지급', 'minus' => '차감', 'use' => '사용', 'expire' => '만료' ];
                                        $extras   = ['id' => 's_gbn', 'class' => 'form-select', 'style' => 'max-width: 220px;'];
                                        $selected = '';
                                        echo getSelectBox('s_gbn', $options, $selected, $extras);
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
                            'onclick' => 'getPointLists();',
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
                            'onclick' => 'document.getElementById("frm_point_search").reset()',
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
        <div class="card" style="border:0 !important" id="pointListsTableWrap">
            <div class="card-body" style="padding:0px !important;">
                <!-- 테이블 -->
                <div style="border:1px solid #E6E7E9; border-radius:0 0 4px 4px;">
                    <div class="table-responsive">
                        <table class="table table-vcenter" id="pointListsTable">
                            <colgroup>
                                <col style="width:5%;">
                                <col style="width:15%;">
                                <col style="width:15%">
                                <col style="width:15%;">
                                <col style="width:15%;">
                                <col style="width:10%;">
                                <col style="width:20%">
                                <col style="width:5%;">
                            </colgroup>
                            <thead style="background-color:#F8FAFC">
                                <tr>
                                    <th>no</th>
                                    <th>지급액</th>
                                    <th>차감액</th>
                                    <th>잔액</th>
                                    <th>지급/차감일</th>
                                    <th>처리자</th>
                                    <th>사유</th>
                                    <th>수정</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8">데이터가 없습니다.</td>
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
    function setPeriodDate(period) {
        var startDate = new Date();
        var endDate = new Date();

        switch(period) {
            case '1Day':
                endDate;
                break;
            case '7Day':
                startDate.setDate(endDate.getDate() - 7);
                break;
            case '15Day':
                startDate.setDate(endDate.getDate() - 15);
                break;
            case '1Month':
                startDate.setMonth(endDate.getMonth() - 1);
                break;
            case '3Month':
                startDate.setMonth(endDate.getMonth() - 3);
                break;
            case '6Month':
                startDate.setMonth(endDate.getMonth() - 6);
                break;
            case '1Years':
                startDate.setFullYear(endDate.getFullYear() - 1);
                break;
            default:
                console.error('Invalid period specified');
                return;
        }

        // yyyy-mm-dd 형식으로 날짜를 포맷
        var formatDate = function(date) {
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var day = ('0' + date.getDate()).slice(-2);
            return year + '-' + month + '-' + day;
        };

        // 날짜 필드 업데이트
        $('#frm_point_search [name=s_start_date]').val(formatDate(startDate));
        $('#frm_point_search [name=s_end_date]').val(formatDate(endDate));
    }

    getPointLists();


</script>