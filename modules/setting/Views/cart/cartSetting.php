<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($pageDatas, 'aConfig', []);
    $aData            = _elm($pageDatas, 'cartSettingData', [] );


?>

<div class="container-fluid">
    <!-- 카트 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">장바구니 설정</h3>
    </div>
    <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
    <div class="row row-deck row-cards">
        <!-- 카드1 -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="input-group required">
                        <label class="label body2-c">
                            상품 보관기간
                        </label>
                        <div class="form-inline">
                        <?php
                            $checked = false;
                            if( 0 < (int)_elm( $aData, 'C_SAVE_PERIOD' ) ){
                                $checked = true;
                            }
                            $setParam = [
                                'name' => 'i_save_period_check',
                                'id' => 'i_save_period_check_Y',
                                'value' => 'Y',
                                'label' => '',
                                'checked' => $checked,
                                'extraAttributes' => [
                                    'onclick'=>'$("#i_save_period").prop("disabled",false)',
                                ]
                            ];
                            echo getRadioButton($setParam);
                        ?>
                        <input type="text" id="i_save_period" name="i_save_period" class="form-control" style="width:10vh;text-align:right"
                            placeholder="일단위 입력" value="<?php echo (int)_elm( $aData, 'C_SAVE_PERIOD' ) != 0 ? _elm( $aData, 'C_SAVE_PERIOD' ) : '' ?>"
                            <?php echo (int)_elm( $aData, 'C_SAVE_PERIOD' ) == 0 ? 'disabled' : '' ?>
                        />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php
                            $checked = false;
                            if( 0 == (int)_elm( $aData, 'C_SAVE_PERIOD' ) ){
                                $checked = true;
                            }
                            $setParam = [
                                'name' => 'i_save_period_check',
                                'id' => 'i_save_period_check_N',
                                'value' => 'N',
                                'label' => '고객이 삭제할 때 까지 보관',
                                'checked' => $checked,
                                'extraAttributes' => [
                                    'onclick'=>'$("#i_save_period").val("").prop("disabled",true)',
                                ]
                            ];
                            echo getRadioButton($setParam);
                        ?>
                        </div>
                    </div>

                    <div class="input-group required">
                        <label class="label body2-c">
                            보관 상품 개수
                        </label>
                        <div class="form-inline">
                            <input type="text" class="form-control" name="i_save_cnt" id="i_save_cnt" style="width:10vh;text-align:right"
                                placeholder="수량 입력" value="<?php echo _elm( $aData, 'C_SAVE_CNT' )?>"/>
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
                                관련상품 설정
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
                    <div class="input-group required">
                        <label class="label body2-c">
                            사용여부
                        </label>
                        <div class="form-inline">
                        <?php
                            foreach( _elm( $aConfig, 'defaultYn') as $key => $val )
                            {
                                $checked = false;
                                if( $key == _elm( $aData, 'C_REL_GOODS_USE_YN' ) ){
                                    $checked = true;
                                }
                                if( $key == 'Y' ){
                                    $setParam = [
                                        'name' => 'i_rel_goods_use_yn',
                                        'id' => 'i_rel_goods_use_yn',
                                        'value' => $key,
                                        'label' => $val,
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                            'onclick'=>'$("[name=i_rel_goods_check]").prop("disabled",false);$("[name=i_rel_goods_check]:checked").val()=="Y"?$("#i_rel_goods_cnt").prop("disabled",false):"";',
                                        ]
                                    ];
                                }else{
                                    $setParam = [
                                        'name' => 'i_rel_goods_use_yn',
                                        'id' => 'i_rel_goods_use_yn',
                                        'value' => $key,
                                        'label' => $val,
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                            'onclick'=>'$("[name=i_rel_goods_check]").prop("disabled",true);$("[name=i_rel_goods_check]:checked").val()=="Y"?$("#i_rel_goods_cnt").prop("disabled",true):"";',
                                        ]
                                    ];
                                }


                                echo getRadioButton($setParam);
                            }
                        ?>
                        </div>
                    </div>

                    <div class="input-group required">
                        <label class="label body2-c">
                            보관상품개수
                        </label>
                        <div class="form-inline">
                        <?php
                            $checked = false;
                            if( 0 < _elm( $aData, 'C_REL_GOODS_CNT' ) ){
                                $checked = true;
                            }
                            $setParam = [
                                'name' => 'i_rel_goods_check',
                                'id' => 'i_rel_goods_check_Y',
                                'value' => 'Y',
                                'label' => '',
                                'checked' => $checked,
                                'extraAttributes' => [
                                    'onclick'=>'$("#i_rel_goods_cnt").prop("disabled",false)',
                                ]
                            ];
                            echo getRadioButton($setParam);
                        ?>
                        <input type="text" class="form-control" name="i_rel_goods_cnt" id="i_rel_goods_cnt" style="width:10vh;text-align:right"
                            placeholder="수량 입력" value="<?php echo (int)_elm( $aData, 'C_REL_GOODS_CNT' ) != 0 ? _elm( $aData, 'C_REL_GOODS_CNT' ) : '' ?>"
                            <?php echo (int)_elm( $aData, 'C_REL_GOODS_CNT' ) == 0 ? 'disabled' : '' ?>
                        />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php
                            $checked = false;
                            if( 0 == (int) _elm( $aData, 'C_REL_GOODS_CNT' ) ){
                                $checked = true;
                            }
                            $setParam = [
                                'name' => 'i_rel_goods_check',
                                'id' => 'i_rel_goods_check_N',
                                'value' => 'N',
                                'label' => '제한없음',
                                'checked' => $checked,
                                'extraAttributes' => [
                                    'onclick'=>'$("#i_rel_goods_cnt").val("").prop("disabled",true)',
                                ]
                            ];
                            echo getRadioButton($setParam);
                        ?>
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
                                최근 본 상품
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
                    <div class="input-group required">
                        <label class="label body2-c">
                            상품보관기간
                        </label>
                        <div class="form-inline">
                        <?php
                            $checked = false;
                            if( 0 < (int)_elm( $aData, 'C_RECENT_PERIOD' ) ){
                                $checked = true;
                            }
                            $setParam = [
                                'name' => 'i_recent_period_check',
                                'id' => 'i_recent_period_check_Y',
                                'value' => 'Y',
                                'label' => '',
                                'checked' => $checked,
                                'extraAttributes' => [
                                    'onclick'=>'$("#i_recent_period").prop("disabled",false)',
                                ]
                            ];
                            echo getRadioButton($setParam);
                        ?>
                        <input type="text" class="form-control" name="i_recent_period" id="i_recent_period" style="width:10vh;text-align:right"
                            placeholder="일단위 입력" value="<?php echo (int)_elm( $aData, 'C_RECENT_PERIOD' ) != 0 ? _elm( $aData, 'C_RECENT_PERIOD' ) : '' ?>"
                            <?php echo (int)_elm( $aData, 'C_RECENT_PERIOD' ) == 0 ? 'disabled' : '' ?>
                        />
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php
                            $checked = false;
                            if( 0 == (int)_elm( $aData, 'C_RECENT_PERIOD' ) ){
                                $checked = true;
                            }
                            $setParam = [
                                'name' => 'i_recent_period_check',
                                'id' => 'i_recent_period_check_N',
                                'value' => 'N',
                                'label' => '고객이 삭제할 때 까지 보관',
                                'checked' => $checked,
                                'extraAttributes' => [
                                    'onclick'=>'$("#i_recent_period").val("").prop("disabled",true)',
                                ]
                            ];
                            echo getRadioButton($setParam);
                        ?>
                        </div>
                    </div>

                    <div class="input-group required">
                        <label class="label body2-c">
                            보관상품개수
                        </label>
                        <div class="form-inline">
                        <?php
                            $checked = false;
                            if( 0 < (int)_elm( $aData, 'C_RECENT_CNT' ) ){
                                $checked = true;
                            }
                            $setParam = [
                                'name' => 'i_recent_cnt_check',
                                'id' => 'i_recent_cnt_check_Y',
                                'value' => 'Y',
                                'label' => '',
                                'checked' => $checked,
                                'extraAttributes' => [
                                    'onclick'=>'$("#i_recent_cnt").prop("disabled",false)',
                                ]
                            ];
                            echo getRadioButton($setParam);
                        ?>
                        <input type="text" class="form-control" name="i_recent_cnt" id="i_recent_cnt" style="width:10vh;text-align:right"
                            placeholder="수량 입력" value="<?php echo _elm( $aData, 'C_RECENT_CNT' )?>"/>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php
                            $checked = false;
                            if( 0 == (int)_elm( $aData, 'C_RECENT_CNT' ) ){
                                $checked = true;
                            }
                            $setParam = [
                                'name' => 'i_recent_cnt_check',
                                'id' => 'i_recent_cnt_check_N',
                                'value' => 'N',
                                'label' => '제한없음',
                                'checked' => $checked,
                                'extraAttributes' => [
                                    'onclick'=>'$("#i_recent_cnt").val("").prop("disabled",true)',
                                ]
                            ];
                            echo getRadioButton($setParam);
                        ?>
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
    </div>
    <?php echo form_close() ?>
</div>
<?php
$owensView->setFooterJs('/assets/js/setting/cart/cartSetting.js');
$script = "
";

$owensView->setFooterScript($script);
