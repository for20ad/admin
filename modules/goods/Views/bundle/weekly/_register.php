<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);


?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register', 'autocomplete' => 'off']); ?>

<div class="row row-deck row-cards">
    <!-- 카드1 -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="input-group required">
                    <label class="label body2-c">
                        제목
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" placeholder="노출 제목을 입력하세요." name="i_title" id="i_title" data-max-length="30" style="border-top-right-radius:0px; border-bottom-right-radius: 0px" value=""/>
                    <span class="wordCount input-group-text" style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        0/30
                    </span>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        메인페이지 노출 수량
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" placeholder="숫자만 입력" name="i_limit_cnt" id="i_limit_cnt" style="border-top-right-radius:0px; border-bottom-right-radius: 0px" value=""/>
                </div>

                <div class="input-group required">
                    <label class="label body2-c">
                        기간 선택
                    </label>
                    <div class="form-inline">
                        <div class="input-icon">
                            <input type="text" class="form-control  datepicker-icon flatpickr-input"
                                name="i_start_date" id="i_start_date"readonly>
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
                            <input type="text" class="form-control  datepicker-icon flatpickr-input"
                                name="i_end_date" id="i_end_date" readonly>
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
                <div class="input-group required">
                    <label class="label body2-c">
                        노출상태
                    </label>
                    <?php
                        $checked = true;
                        $setParam = [
                            'name' => 'i_status',
                            'id' => 'i_status_Y',
                            'value' => 'Y',
                            'label' => '사용함',
                            'checked' => $checked,
                            'extraAttributes' => [
                            ]
                        ];
                        echo getRadioButton($setParam);
                    ?>
                    <?php
                        $checked = false;
                        $setParam = [
                            'name' => 'i_status',
                            'id' => 'i_status_N',
                            'value' => 'N',
                            'label' => '사용안함',
                            'checked' => $checked,
                            'extraAttributes' => [
                            ]
                        ];
                        echo getRadioButton($setParam);
                    ?>
                </div>
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
                'onclick' => 'frmRegiserConfirm(event);',
            ]
        ]);
        ?>
    </div>
</div>

<script>

flatpickr('.datepicker-icon', {
    enableTime: true,
    dateFormat: 'Y-m-d',
    time_24hr: true
});
</script>
<?php echo form_close() ?>
