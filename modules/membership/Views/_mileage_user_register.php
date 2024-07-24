<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);
    $aMemeberData     = _elm($view_datas, 'mData', []);
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
            <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register',  'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
            <input type="hidden" name="i_mb_idx" id="i_mb_idx" value="<?php echo _elm( $aMemeberData, 'MB_IDX' );?>">
            <div class="card-body" >
            <div class="input-group required">
                    <label class="label body2-c">
                        아이디
                    </label>
                    <?php echo _elm( $aMemeberData, 'MB_USERID' );?>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        이름
                    </label>
                    <?php echo _elm( $aMemeberData, 'MB_NM' );?>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        지급/회수
                        <span>*</span>
                    </label>
                    <?php
                    $setParam = [
                        'name' => 'i_regist_type',
                        'id' => 'i_regist_type_add',
                        'value' => 'add',
                        'label' => '지급',
                        'checked' => true,
                        'extraAttributes' => [
                        ]
                    ];
                    echo getRadioButton($setParam);
                    ?>
                    <?php
                    $setParam = [
                        'name' => 'i_regist_type',
                        'id' => 'i_regist_type_minus',
                        'value' => 'minus',
                        'label' => '차감',
                        'checked' => '',
                        'extraAttributes' => [
                        ]
                    ];
                    echo getRadioButton($setParam);
                    ?>
                </div>
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