<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($pageDatas, 'aConfig', []);
    $aData            = _elm($pageDatas, 'policyData', [] );
    $i_pay_default    = explode( '|', _elm( $aData, 'P_PAY_DEFAULT' ) );
    $i_pay_simple     = explode( '|', _elm( $aData, 'P_PAY_SIMPLE' ) );

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
                            기본결제
                        </label>
                        <?php
                            foreach( _elm( $aConfig, 'pay_default') as $key => $val )
                            {
                                $checked = false;
                                if (in_array($key, $i_pay_default)) {
                                    $checked = true;
                                }

                                $setParam = [
                                    'name' => 'i_pay_default[]',
                                    'id' => 'i_pay_default_'.$key,
                                    'value' => $key,
                                    'label' => $val,
                                    'checked' => $checked,
                                    'extraAttributes' => []
                                ];

                                echo getCheckBox($setParam);
                            }
                        ?>
                    </div>

                    <div class="input-group required">
                        <label class="label body2-c">
                            간편결제
                            <span>*</span>
                        </label>
                        <?php
                            foreach( _elm( $aConfig, 'pay_simple') as $key => $val )
                            {
                                $checked = false;

                                if (in_array($key, $i_pay_simple)) {
                                    $checked = true;
                                }

                                $setParam = [
                                    'name' => 'i_pay_simple[]',
                                    'id' => 'i_pay_simple',
                                    'value' => $key,
                                    'label' => $val,
                                    'checked' => $checked,
                                    'extraAttributes' => []
                                ];

                                echo getCheckBox($setParam);
                            }
                        ?>
                    </div>
                    <div class="input-group-bottom-text">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 17.5C14.1421 17.5 17.5 14.1421 17.5 10C17.5 5.85786 14.1421 2.5 10 2.5C5.85786 2.5 2.5 5.85786 2.5 10C2.5 14.1421 5.85786 17.5 10 17.5Z" fill="#616876"/>
                            <path d="M10 6.66663H10.0083" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.16675 10H10.0001V13.3333H10.8334" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        간편결제는 시스템운영사와 협의를 통해 별도 계약을 진행하셔야 합니다
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
                                자동설정
                            </p>
                        </div>
                        <!-- 아코디언 토글 버튼 -->
                        <label class="form-selectgroup-item"  onclick="toggleForm( $(this) )">
                            <input type="radio" name="icons" value="home"
                                class="form-selectgroup-input" checked />
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
                    <div class="input-group required">
                        <label class="label body2-c">
                            자동배송완료
                        </label>
                        '발송완료' 상태에서 &nbsp;&nbsp;&nbsp;
                        <?php
                            $options  = [''=>'선택'];
                            $options += _elm($aConfig, 'delivery_end_days', []);
                            $extras   = ['id' => 'i_delivery_end_days', 'class' => 'form-select', 'style' => 'max-width: 90px'];
                            $selected = _elm( $aData, 'P_DELIVERY_END_DAYS' );
                            echo getSelectBox('i_delivery_end_days', $options, $selected, $extras);
                        ?>&nbsp;&nbsp;&nbsp;
                        일 뒤 '배송완료' 상태로 자동변경
                    </div>

                    <div class="input-group required">
                        <label class="label body2-c">
                            자동구매완료
                        </label>
                        '배송완료' 상태에서 &nbsp;&nbsp;&nbsp;
                        <?php
                            $options  = [''=>'선택'];
                            $options += _elm($aConfig, 'purchase_conf_days', []);
                            $extras   = ['id' => 'i_purchase_conf_days', 'class' => 'form-select', 'style' => 'max-width: 90px'];
                            $selected = _elm( $aData, 'P_PURCHASE_CONF_DAYS' );
                            echo getSelectBox('i_purchase_conf_days', $options, $selected, $extras);
                        ?>&nbsp;&nbsp;&nbsp;
                        일 뒤 '구매확정' 상태로 자동변경
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
                                취소/반품/교환
                            </p>
                        </div>
                        <!-- 아코디언 토글 버튼 -->
                        <label class="form-selectgroup-item"  onclick="toggleForm( $(this) )">
                            <input type="radio" name="icons" value="home"
                                class="form-selectgroup-input" checked />
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
                    <div class="input-group required">
                        <label class="label body2-c">
                            주문취소
                        </label>
                        <?php
                            foreach( _elm( $aConfig, 'defaultYn') as $key => $val )
                            {
                                $checked = false;
                                if( $key == _elm( $aData, 'P_PURCHASE_CANCLE' ) ){
                                    $checked = true;
                                }
                                $setParam = [
                                    'name' => 'i_purchase_cancle',
                                    'id' => 'i_purchase_cancle',
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
                            기본결제
                        </label>
                        <?php
                            foreach( _elm( $aConfig, 'defaultYn') as $key => $val )
                            {
                                $checked = false;
                                if( $key == _elm( $aData, 'P_PURCHASE_RETURN' ) ){
                                    $checked = true;
                                }
                                $setParam = [
                                    'name' => 'i_purchase_return',
                                    'id' => 'i_purchase_return',
                                    'value' => $key,
                                    'label' => $val,
                                    'checked' => $checked,
                                    'extraAttributes' => []
                                ];

                                echo getRadioButton($setParam);
                            }
                        ?>
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
$owensView->setFooterJs('/assets/js/setting/policy/purchase.js');
$script = "
";

$owensView->setFooterScript($script);
