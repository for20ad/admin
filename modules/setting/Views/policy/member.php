<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($pageDatas, 'aConfig', []);
    $aData            = _elm($pageDatas, 'policyData', [] );

?>

<div class="container-fluid">
    <!-- 카트 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">회원 설정</h3>
    </div>
    <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
    <div class="row row-deck row-cards">
        <!-- 카드1 -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="input-group required">
                        <label class="label body2-c">
                            회원가입설정
                            <span>*</span>
                        </label>
                        <?php
                            foreach( _elm( $aConfig, 'accept') as $key => $val )
                            {
                                $checked = false;
                                if( $key == _elm( $aData, 'P_ACCEPT' ) ){
                                    $checked = true;
                                }
                                $setParam = [
                                    'name' => 'i_accept',
                                    'id' => 'i_accept',
                                    'value' => $key,
                                    'label' => $val,
                                    'checked' => $checked,
                                    'extraAttributes' => []
                                ];

                                echo getRadioButton($setParam);
                            }
                        ?>
                    </div>

                    <div class="input-group required">
                        <label class="label body2-c">
                            회원등급 사용
                            <span>*</span>
                        </label>
                        <?php
                            foreach( _elm( $aConfig, 'grade') as $key => $val )
                            {
                                $checked = false;
                                if( $key == _elm( $aData, 'P_GRADE' ) ){
                                    $checked = true;
                                }
                                $setParam = [
                                    'name' => 'i_grade',
                                    'id' => 'i_grade',
                                    'value' => $key,
                                    'label' => $val,
                                    'checked' => $checked,
                                    'extraAttributes' => []
                                ];

                                echo getRadioButton($setParam);
                            }
                        ?>
                    </div>

                    <div class="input-group required">
                        <label class="label body2-c">
                            비밀번호 변경
                        </label>
                        비밀번호 변경 후 &nbsp;&nbsp;&nbsp;
                        <?php
                            $options  = [''=>'선택'];
                            $options += _elm($aConfig, 'password_change_period', []);
                            $extras   = ['id' => 'i_password_change_period', 'class' => 'form-select', 'style' => 'max-width: 90px'];
                            $selected = _elm( $aData, 'P_PASSWORD_CHANGE_PERIOD' );
                            echo getSelectBox('i_password_change_period', $options, $selected, $extras);
                        ?>&nbsp;&nbsp;&nbsp;
                        후 비밀번호 재설정 유도
                    </div>

                    <div class="input-group required">
                        <label class="label body2-c">
                            회원가입 금지아이디
                        </label>
                        <textarea id="i_banned_ids" class="form-control" name="i_banned_ids" ><?php echo _elm( $aData, 'P_BANNED_IDS' )?></textarea>
                    </div>
                    <div class="input-group-bottom-text">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 17.5C14.1421 17.5 17.5 14.1421 17.5 10C17.5 5.85786 14.1421 2.5 10 2.5C5.85786 2.5 2.5 5.85786 2.5 10C2.5 14.1421 5.85786 17.5 10 17.5Z" fill="#616876"/>
                            <path d="M10 6.66663H10.0083" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.16675 10H10.0001V13.3333H10.8334" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        회원가입을 제한할 아이디를 쉼표(,)로 구분하여 입력하세요.
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
    </div>
    <?php echo form_close() ?>
</div>
<?php
$owensView->setFooterJs('/assets/js/setting/policy/member.js');

$script = "
";

$owensView->setFooterScript($script);
