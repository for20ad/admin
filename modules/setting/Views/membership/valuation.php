<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($pageDatas, 'aConfig', []);
    $aData            = _elm( $pageDatas, 'valuation', []);
    $bData            = _elm($pageDatas, 'gradeDatas', [] );
?>

<div class="container-fluid">
    <!-- 카트 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">등급별 평가기준</h3>
    </div>
    <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register',  'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
    <div class="row row-deck row-cards">
        <!-- 카드1 -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="input-group required">
                        <label class="label body2-c">
                            실적계산기간
                        </label>
                        <div class="form-inline">
                        <?php
                            $checked = false;
                            if( 0 == (int)_elm( $aData, 'V_PERIOD' ) ){
                                $checked = true;
                            }
                            $setParam = [
                                'name' => 'i_period_check',
                                'id' => 'i_period_check_Y',
                                'value' => 'N',
                                'label' => '기간제한 없음',
                                'checked' => $checked,
                                'extraAttributes' => [
                                    'onclick'=>'$("#i_period").prop("disabled",true)',
                                ]
                            ];
                            echo getRadioButton($setParam);
                        ?>

                        <?php
                            $checked = false;
                            if( 0 < (int)_elm( $aData, 'V_PERIOD' ) ){
                                $checked = true;
                            }
                            $setParam = [
                                'name' => 'i_period_check',
                                'id' => 'i_period_check_N',
                                'value' => 'Y',
                                'label' => '기간제한 있음 ',
                                'checked' => $checked,
                                'extraAttributes' => [
                                    'onclick'=>'$("#i_period").val("").prop("disabled",false)',
                                ]
                            ];
                            echo getRadioButton($setParam);
                        ?>
                        최근 &nbsp;&nbsp;
                        <input type="text" id="i_period" name="i_period" class="form-control" style="width:10vh;text-align:right"
                            placeholder="일단위 입력" value="<?php echo (int)_elm( $aData, 'V_PERIOD' ) != 0 ? _elm( $aData, 'V_PERIOD' ) : '' ?>"
                            <?php echo (int)_elm( $aData, 'V_PERIOD' ) == 0 ? 'disabled' : '' ?>
                        />
                        개월

                        </div>
                    </div>

                    <div class="input-group required">
                        <label class="label body2-c">
                            등급 평가일
                        </label>
                        <div class="form-inline">
                            매월 &nbsp;&nbsp;
                            <input type="text" class="form-control" name="i_schedule_days" id="i_schedule_days" style="width:10vh;text-align:right"
                                placeholder="1~30" value="<?php echo _elm( $aData, 'V_SCHEDULE_DAYS' )?>"/>
                        </div>
                        &nbsp; 일
                    </div>

                    <div class="input-group required">
                        <label class="label body2-c">
                            금액당 평가 단위
                        </label>
                        <div class="form-inline">
                            &nbsp;&nbsp;
                            <input type="text" class="form-control" name="i_amount" id="i_amount" style="width:10vh;text-align:right"
                                placeholder="금액당" value="<?php echo _elm( $aData, 'V_AMOUNT' )?>"/>
                                &nbsp; 원 당 -&nbsp;
                            <input type="text" class="form-control" name="i_to_score" id="i_to_score" style="width:10vh;text-align:right"
                                placeholder="점수로" value="<?php echo _elm( $aData, 'V_TO_SCORE' )?>"/>
                                &nbsp;점으로 환산
                        </div>
                    </div>
                </div>
            </div>
        </div>


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
                                회원등급목록
                            </p>
                        </div>

                        <!-- 아코디언 토글 버튼 -->
                        <label class="form-selectgroup-item"  onclick="toggleForm( $(this) )">
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
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <colgroup>
                                <col style="width:15%;">
                                <col style="width:40;">
                            </colgroup>
                            <thead class="thead-light">
                            <tr>
                                <th style="padding-left:2em">회원등급</th>
                                <th style="padding-left:2em">구매금액</th>
                            </tr>
                            </thead>
                            <tbody id="sortable">
                            <?php
                            if( empty( $bData ) === false ){
                                foreach( $bData as $key => $lists ){
                            ?>
                            <tr>
                                <td>
                                    <div class="form-inline">
                                        <?php echo _elm( $lists, 'G_NAME' )?>
                                    </div>
                                </td>

                                <td>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" style="width:18vh;text-align:right;max-width:100%;margin:0px !important;"   name="i_period_score_start[<?php echo _elm( $lists, 'G_IDX' )?>]" value="<?php echo _elm( $lists, 'G_PERIOD_SCORE_START', 0, true )?>" >
                                        <span class="wordCount input-group-text"
                                            style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                            만원
                                        </span>
                                        &nbsp;
                                        이상 ~
                                        &nbsp;&nbsp;
                                        <input type="text" class="form-control" style="width:18vh;text-align:right;max-width:100%;margin:0px !important;"  name="i_period_score_end[<?php echo _elm( $lists, 'G_IDX' )?>]" value="<?php echo _elm( $lists, 'G_PERIOD_SCORE_END', 0, true )?>" >
                                        <span class="wordCount input-group-text"
                                            style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                            만원
                                        </span>
                                        &nbsp; 미만
                                    </div>
                                </td>

                            </tr>
                            <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- 버튼 -->
    <div style="text-align: center; margin-top: 52px">
        <?php
        echo getIconButton([
            'txt' => '저장',
            'icon' => 'success',
            'buttonClass' => 'btn btn-success',
            'buttonStyle' => 'width:180px; height: 46px',
            'width' => '21',
            'height' => '20',
            'stroke' => 'white',
            'extra' => [
                'type' => 'submit'
            ]
        ]);
        ?>
    </div>
    <?php echo form_close();?>
</div>
<script>

</script>

<?php
$owensView->setFooterJs('/assets/js/setting/membership/valuation.js');

$script = "
    $('#sortable').sortable({
        update: function(event, ui) {
            var idsInOrder = $('#sortable').sortable('toArray',{ attribute : 'data-idx'});
            updateGradrSort(idsInOrder);
            console.log(idsInOrder);
        }
    });
";

$owensView->setFooterScript($script);
