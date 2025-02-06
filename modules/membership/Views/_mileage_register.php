<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();


    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);
    $aMemeberGrade    = _elm($view_datas, 'member_grades', []);
    $aCodes           = _elm($view_datas, 'aCodes', []);
    $mConfig          = _elm($view_datas, 'mConfig', []);
    $expire_days      = _elm($mConfig, 'expire_days', null, true) ?? 180;
?>
<link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>

<div class="row row-deck row-cards">
    <!-- 카드1 -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_sub_search', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
                <input type="hidden" name="page" value="1">
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
                                <th class="no-border-bottom">검색</th>
                                <td colspan="3" class="no-border-bottom">
                                    <div class="form-inline" style="display:flex;align-items:center;justify-content: space-between;">
                                        <div style="display:flex;align-items:center;" >
                                        <?php
                                            $options = [''=>'전체'];
                                            $options+= ['mb_id'=>'아이디', 'mb_name'=>'이름', 'mb_mobile'=>'휴대폰번호'];
                                            $extras   = ['id' => 's_condition', 'class' => 'form-select', 'style' => 'max-width: 110px;margin-right:0.235em;'];
                                            $selected = 'mb_name';
                                            echo getSelectBox('s_condition', $options, $selected, $extras);
                                        ?>
                                        <input type="text" id="ssubject" name="s_keyword" class="form-control" style="width:160%;" value="" >
                                        </div>
                                            <?php
                                            echo getIconButton([
                                                'txt' => '검색',
                                                'icon' => 'search',
                                                'buttonClass' => 'btn text-white',
                                                'buttonStyle' => 'width: 100px; height: 36px; float:right;background-color:#206BC4',
                                                'width' => '21',
                                                'height' => '20',
                                                'extra' => [
                                                    'onclick' => 'event.preventDefault();getSubSearchList()',
                                                ]
                                            ]);
                                            ?>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php echo form_close()?>
            </div>
            <div class="card-body" id="subList">
                <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_sub_lists',  'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
                <div class="table-responsive">
                    <table class="table table-vcenter" id="subListsTable">
                        <thead>
                            <tr>
                                <th>아이디</th>
                                <th>이름</th>
                                <th>휴대폰번호</th>
                                <th>선택</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!--페이징-->
                <div class="pagination-wrapper" id="pagination">
                </div>
                <?php echo form_close()?>
            </div>
            <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register',  'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
            <input type="hidden" name="i_mb_idx" id="i_mb_idx">
            <input type="hidden" name="i_regist_type" id="i_regist_type" value="add">
            <div class="card-body" >
                <div class="input-group required">
                    <label class="label body2-c">
                        포인트
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" placeholder="지급액" name="i_mileage" id="i_mileage" /
                        style="border-top-right-radius:0px; border-bottom-right-radius: 0px" />
                    <span class="input-group-text"
                        style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        원
                    </span>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        거래내역
                        <span>*</span>
                    </label>
                    <?php
                        $options = [''=>'전체'];
                        $options+= $aCodes;
                        $extras   = ['id' => 'i_reason_cd', 'class' => 'form-select', 'style' => 'max-width: 320px;margin-right:0.235em;'];
                        $selected = 'mb_name';
                        echo getSelectBox('i_reason_cd', $options, $selected, $extras);
                    ?>

                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                    </label>
                    <textarea class="form-control" name="i_reason_msg" id="i_reason_msg" data-max-length="100"></textarea>
                </div>

                <div class="input-group">
                    <label class="label body2-c">
                        만료일
                        <span>*</span>
                    </label>
                    <div class="form-inline">
                        <div class="input-icon">
                            <input type="text" class="form-control datepicker-icon"
                                name="i_expire_date" id="i_expire_date" readonly value="<?php echo  date('Y-m-d', strtotime("+$expire_days days"));?>" >
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
                </div>
                <?php echo form_close()?>
            </div>
        </div>
    </div>


    <!-- 버튼 -->
    <div style="text-align: center; margin-top: 52px">
        <?php
        echo getButton([
            'text' => '닫기',
            'class' => 'btn',
            'style' => 'width: 180px; height: 46px',
            'extra' => [
                'onclick' => 'event.preventDefault();$(".btn-close").trigger("click")',
            ]
        ]);
        ?>
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
                    'type' => 'button',
                    'onclick' => 'registerMileageConfirm();',
                ]
            ]);
        ?>


    </div>
</div>
<script>
initializeDatepickers();

</script>
<?php echo form_close() ?>