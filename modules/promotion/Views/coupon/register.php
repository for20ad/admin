<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($pageDatas, 'aConfig', []);
    $aMemeberGroup    = _elm($pageDatas, 'member_group', []);
    $aMemberGrade    =_elm($pageDatas, 'aMemberGrade', []);
?>
<div class="container-fluid">
    <!-- 카트 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">쿠폰 등록</h3>


    </div>
    <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
    <input type="hidden" name="i_pick_items">
    <input type="hidden" name="i_except_product_idxs">
    <div class="row row-deck row-cards">
        <!-- 카드 -->
        <div class="col-12">
            <div class="card">
                <!-- 카드 타이틀 -->
                <div class="card accordion-card"
                    style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                                viewBox="0 0 4 4" fill="none">
                                <circle cx="2" cy="2" r="2" fill="#206BC4" />
                            </svg>
                            <p class="body1-c ms-2 mt-1">
                                기본설정
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

                <div class="card-body">
                    <div class="input-group required">
                        <label class="label body2-c ">
                            쿠폰명
                            <span>*</span>
                        </label>
                        <input type="text" class="form-control" name="i_coupon_name" id="i_coupon_name" data-max-length="20" data-required='쿠폰명을 입력하세요.'
                            style="border-top-right-radius:0px; border-bottom-right-radius: 0px" value=""/>
                        <span class="wordCount input-group-text"
                            style="border-top-left-radius:0px; border-bottom-left-radi us: 0px">
                            0/20
                        </span>

                    </div>

                    <div class="input-group required">
                        <label class="label body2-c">
                            쿠폰설명
                            <span>*</span>
                        </label>
                        <input type="text" class="form-control" name="i_coupon_discription" id="i_coupon_discription" data-max-length="20" data-required='쿠폰설명을 입력하세요.'
                            style="border-top-right-radius:0px; border-bottom-right-radius: 0px" value=""/>
                        <span class="wordCount input-group-text"
                            style="border-top-left-radius:0px; border-bottom-left-radi us: 0px">
                            0/20
                        </span>

                    </div>

                    <div class="input-group">
                        <label class="label body2-c">
                            쿠폰유형
                            <?php
                                echo getIconAnchor([
                                    'txt' => '',
                                    'icon' => 'help',
                                    'width' => '16',
                                    'height' => '16',
                                    'stroke' => '#ccc',
                                    'extra' => [
                                        'type' => 'button',
                                        'onclick' => 'showTooltipPosition(this,\'cpn_gbn\', \'left\');',
                                    ]
                                ]);
                            ?>
                            <span>*</span>
                        </label>
                        <div class="form-inline">
                        <?php
                            $checked = true;

                            $setParam = [
                                'name' => 'i_coupon_gbn',
                                'id' => 'i_coupon_gbn_P',
                                'value' => 'P',
                                'label' => '지정발행',
                                'checked' => $checked,
                                'extraAttributes' => [
                                    'onclick'=>'showHideForm($(this).val())',
                                ]
                            ];
                            echo getRadioButton($setParam);
                        ?>
                        <?php
                            $checked = false;

                            $setParam = [
                                'name' => 'i_coupon_gbn',
                                'id' => 'i_coupon_gbn_D',
                                'value' => 'D',
                                'label' => '고객다운로드',
                                'checked' => $checked,
                                'extraAttributes' => [
                                    'onclick'=>'showHideForm($(this).val())',
                                ]
                            ];
                            echo getRadioButton($setParam);
                        ?>
                        <?php
                            $checked = false;

                            $setParam = [
                                'name' => 'i_coupon_gbn',
                                'id' => 'i_coupon_gbn_A',
                                'value' => 'A',
                                'label' => '자동발행',
                                'checked' => $checked,
                                'extraAttributes' => [
                                    'onclick'=>'showHideForm($(this).val())',
                                ]
                            ];
                            echo getRadioButton($setParam);
                        ?>
                        </div>
                    </div>


                    <div id="group-P">
                        <div class="input-group">
                            <label class="label body2-c">
                                발급대상
                                <?php
                                    echo getIconAnchor([
                                        'txt' => '',
                                        'icon' => 'help',
                                        'width' => '16',
                                        'height' => '16',
                                        'stroke' => '#ccc',
                                        'extra' => [
                                            'type' => 'button',
                                            'onclick' => 'showTooltipPosition(this,\'cpn_target\', \'left\');',
                                        ]
                                    ]);
                                ?>
                                <span>*</span>
                            </label>
                            <div class="form-inline">
                                <?php
                                    $options  = ['all'=>'전체회원', 'grade'=>'지정등급'];
                                    $extras   = ['id' => 'i_target', 'class' => 'form-select', 'style' => 'width:174px;max-width: 174px', 'onchange'=>'$(this).val() == \'grade\' ? $(\'#group-P #i_mb_grade\').show(): $(\'#group-P #i_mb_grade\').hide()'];
                                    $selected = '';
                                    echo form_dropdown('i_target', $options, $selected, $extras);
                                ?>
                                &nbsp;&nbsp;&nbsp;
                                <?php
                                    $options  = $aMemberGrade;
                                    $extras   = ['id' => 'i_mb_grade', 'class' => 'form-select', 'style' => 'max-width: 174px;display:none',];
                                    $selected = '';
                                    echo form_dropdown('i_mb_grade', $options, $selected, $extras);
                                ?>
                            </div>
                        </div>

                    </div>

                    <div id="group-D" style="display:none;">
                        <div class="input-group">
                            <label class="label body2-c">
                                발급대상
                                <?php
                                    echo getIconAnchor([
                                        'txt' => '',
                                        'icon' => 'help',
                                        'width' => '16',
                                        'height' => '16',
                                        'stroke' => '#ccc',
                                        'extra' => [
                                            'type' => 'button',
                                            'onclick' => 'showTooltipPosition(this,\'cpn_target\', \'left\');',
                                        ]
                                    ]);
                                ?>
                                <span>*</span>
                            </label>
                            <div class="form-inline">
                                <?php
                                    $options  = ['all'=>'전체회원', 'grade'=>'지정등급'];
                                    $extras   = ['id' => 'i_target', 'class' => 'form-select', 'style' => 'width: 174px;max-width: 174px', 'disabled'=>'true','onchange'=>'$(this).val() == \'grade\' ? $(\'#group-D #i_mb_grade\').show(): $(\'#group-D #i_mb_grade\').hide()'];
                                    $selected = '';
                                    echo form_dropdown('i_target', $options, $selected, $extras);
                                ?>
                                &nbsp;&nbsp;&nbsp;
                                <?php
                                    $options  = $aMemberGrade;
                                    $extras   = ['id' => 'i_mb_grade', 'class' => 'form-select', 'style' => 'max-width: 174px;display:none', 'disabled'=>'true',];
                                    $selected = '';
                                    echo form_dropdown('i_mb_grade', $options, $selected, $extras);
                                ?>
                            </div>
                        </div>
                        <div class="input-group">
                            <label class="label body2-c">
                            발행수량
                            <?php
                                echo getIconAnchor([
                                    'txt' => '',
                                    'icon' => 'help',
                                    'width' => '16',
                                    'height' => '16',
                                    'stroke' => '#ccc',
                                    'extra' => [
                                        'type' => 'button',
                                        'onclick' => 'showTooltipPosition(this,\'issue_cnt\', \'left\');',
                                    ]
                                ]);
                            ?>
                            <span>*</span>
                            </label>
                            <div class="form-inline">
                                <input type="text" class="form-control" name="i_issue_cnt" disabled=true> 개
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php
                                    $setParam = [
                                        'name' => 'i_issue_no_limit',
                                        'id' => 'i_issue_no_limit_Y',
                                        'value' => 'Y',
                                        'label' => '수량제한없음',
                                        'checked' => false,
                                        'extraAttributes' => [
                                            'aria-label' => 'Single checkbox One',
                                            'class'=>'check-item',
                                            'disabled'=>'true',
                                        ]
                                    ];
                                    echo getCheckBox( $setParam );
                                ?>
                            </div>
                        </div>
                    </div>

                    <div id="group-A" style="display:none">
                        <div class="input-group">
                            <label class="label body2-c">
                                발급대상
                                <?php
                                    echo getIconAnchor([
                                        'txt' => '',
                                        'icon' => 'help',
                                        'width' => '16',
                                        'height' => '16',
                                        'stroke' => '#ccc',
                                        'extra' => [
                                            'type' => 'button',
                                            'onclick' => 'showTooltipPosition(this,\'cpn_target\', \'left\');',
                                        ]
                                    ]);
                                ?>
                                <span>*</span>
                            </label>
                            <div class="form-inline">
                                <?php
                                    $options  = ['welcome'=>'첫 회원가입', 'f_purchase'=>'첫구매', 'birth'=>'생일'];
                                    $extras   = ['id' => 'i_target', 'class' => 'form-select', 'style' => 'width:174px;max-width: 174px','disabled'=>'true',];
                                    $selected = '';
                                    echo form_dropdown('i_target', $options, $selected, $extras);
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="label body2-c">
                            발행알림
                            <?php
                                echo getIconAnchor([
                                    'txt' => '',
                                    'icon' => 'help',
                                    'width' => '16',
                                    'height' => '16',
                                    'stroke' => '#ccc',
                                    'extra' => [
                                        'type' => 'button',
                                        'onclick' => 'showTooltipPosition(this,\'noti\', \'left\');',
                                    ]
                                ]);
                            ?>
                            <span>*</span>
                        </label>
                        <?php
                            $options  = ['down'=>'발행 시 알림', 'none'=>'알림안함'];
                            $extras   = ['id' => 'i_noti', 'class' => 'form-select', 'style' => 'max-width: 174px',];
                            $selected = '';
                            echo form_dropdown('i_noti', $options, $selected, $extras);
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <!-- 카드 타이틀 -->
                <div class="card accordion-card"
                    style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                                viewBox="0 0 4 4" fill="none">
                                <circle cx="2" cy="2" r="2" fill="#206BC4" />
                            </svg>
                            <p class="body1-c ms-2 mt-1">
                                사용정보
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

                <div class="card-body">
                    <div class="input-group required">
                        <label class="label body2-c">
                            사용혜택
                            <span>*</span>
                            <?php
                                echo getIconAnchor([
                                    'txt' => '',
                                    'icon' => 'help',
                                    'width' => '16',
                                    'height' => '16',
                                    'stroke' => '#ccc',
                                    'extra' => [
                                        'type' => 'button',
                                        'onclick' => 'showTooltipPosition(this,\'benefit\', \'left\');',
                                    ]
                                ]);
                            ?>

                        </label>
                        <div class="form-inline">
                            <?php
                                $options  = ['price'=>'금액할인', 'delivery'=>'배송비할인'];
                                $extras   = ['id' => 'i_benefit_gbn', 'class' => 'form-select', 'style' => 'width: 200px',];
                                $selected = '';
                                echo form_dropdown('i_benefit_gbn', $options, $selected, $extras);
                            ?>
                            &nbsp;&nbsp;&nbsp;
                            <input type="text" class="form-control" name="i_benefit" style="text-align:right;max-width:90px" numberwithcomma value=""  data-required='금액 또는 퍼센트를 입력하세요.'>
                            <?php
                                $options  = ['won'=>'원', 'per'=>'%'];
                                $extras   = ['id' => 'i_benefit_unit', 'class' => 'form-select', 'style' => 'max-width: 90px',];
                                $selected = '';
                                echo form_dropdown('i_benefit_unit', $options, $selected, $extras);
                            ?>
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="label body2-c">
                            최소 주문금액
                            <?php
                                echo getIconAnchor([
                                    'txt' => '',
                                    'icon' => 'help',
                                    'width' => '16',
                                    'height' => '16',
                                    'stroke' => '#ccc',
                                    'extra' => [
                                        'type' => 'button',
                                        'onclick' => 'showTooltipPosition(this,\'min_price\', \'left\');',
                                    ]
                                ]);
                            ?>
                            <span>*</span>
                        </label>
                        <div class="form-inline">
                            <input type="text" class="form-control" name="i_min_price" style="text-align:right" numberwithcomma value="" placeholder="0"> 원
                        </div>
                    </div>
                    <div id="selectScope">
                        <div class="input-group">
                            <label class="label body2-c">
                                쿠폰 적용 범위
                                <?php
                                    echo getIconAnchor([
                                        'txt' => '',
                                        'icon' => 'help',
                                        'width' => '16',
                                        'height' => '16',
                                        'stroke' => '#ccc',
                                        'extra' => [
                                            'type' => 'button',
                                            'onclick' => 'showTooltipPosition(this,\'scope\', \'left\');',
                                        ]
                                    ]);
                                ?>
                                <span>*</span>
                            </label>
                            <?php
                                $options  = ['all'=>'모든상품', 'category'=>'지정 카테고리', 'brand'=>'지정 브랜드', 'product'=>'지정 상품'];
                                $extras   = ['id' => 'i_scope_gbn', 'class' => 'form-select', 'style' => 'max-width: 174px','onchange'=>'showHideScope( $(this).val() )'];
                                $selected = '';
                                echo form_dropdown('i_scope_gbn', $options, $selected, $extras);
                            ?>
                        </div>

                        <div id="scope_all">
                            <div class="input-group">
                                <label class="label body2-c">
                                    사용 제외 상품
                                    <?php
                                        echo getIconAnchor([
                                            'txt' => '',
                                            'icon' => 'help',
                                            'width' => '16',
                                            'height' => '16',
                                            'stroke' => '#ccc',
                                            'extra' => [
                                                'type' => 'button',
                                                'onclick' => 'showTooltipPosition(this,\'excp_product\', \'left\');',
                                            ]
                                        ]);
                                    ?>
                                    <span>*</span>
                                </label>
                                <div class="form-inline">
                                    <?php
                                        echo getButton([
                                            'text' => '적용 제외 상품 선택',
                                            'class' => 'btn btn-secondary',
                                            'style' => 'width: 180px; height: 36px',
                                            'extra' => [
                                                'onclick' => 'openDataLayer( \'excp_product\' )',
                                            ]
                                        ]);
                                    ?>
                                </div>
                            </div>
                            <div class="input-group" style="max-width:90%">
                                <div id="exceptProductTxt" class="text-box-container"></div>
                            </div>
                        </div>
                        <div id="scope_category" style="display:none">
                            <div class="input-group">
                                <label class="label body2-c">
                                    카테고리 지정
                                    <span>*</span>
                                </label>
                                <div class="form-inline">
                                    <?php
                                        echo getButton([
                                            'text' => '카테고리 선택',
                                            'class' => 'btn btn-secondary',
                                            'style' => 'width: 180px; height: 36px',
                                            'extra' => [
                                                'onclick' => 'dropDownLayer( \'category\' )',
                                            ]
                                        ]);
                                    ?>
                                    <div style="position:relative; width:320px;">
                                        <div id="dropdown-layer-category" style="display:none;max-width:280px;max-height:480px;overflow-y:auto;position:absolute;top:-400px;left:30px;" class="dropdown-layer" style="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="select-item" id="select-category">
                                </div>
                            </div>
                        </div>
                        <div id="scope_brand" style="display:none">
                            <div class="input-group">
                                <label class="label body2-c">
                                    브랜드 지정
                                </label>
                                <div class="form-inline">
                                    <?php
                                        echo getButton([
                                            'text' => '브랜드 선택',
                                            'class' => 'btn btn-secondary',
                                            'style' => 'width: 180px; height: 36px',
                                            'extra' => [
                                                'onclick' => 'dropDownLayer( \'brand\' )',
                                            ]
                                        ]);
                                    ?>
                                    <div style="position:relative; width:460px;">
                                        <div id="dropdown-layer-brand" style="display:none;max-width:440px;position:absolute;top:-450px;left:30px" class="dropdown-layer" style="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="select-item" id="select-brand">
                                </div>
                            </div>
                        </div>
                        <div id="scope_product" style="display:none">
                            <div class="input-group">
                                <label class="label body2-c">
                                    상품 지정
                                    <span>*</span>
                                </label>
                                <div class="form-inline">
                                    <?php
                                        echo getButton([
                                            'text' => '상품 선택',
                                            'class' => 'btn btn-secondary',
                                            'style' => 'width: 180px; height: 36px',
                                            'extra' => [
                                                'onclick' => 'openDataLayer( \'add_product\' )',
                                            ]
                                        ]);
                                    ?>
                                    <?php
                                        echo getButton([
                                            'text' => '선택 상품 보기',
                                            'class' => 'btn',
                                            'style' => 'width: 180px; height: 36px;display:none',
                                            'extra' => [
                                                'onclick' => 'openDataLayer( \'product\' )',
                                                'id'      => 'addPickProductBtn',
                                            ]
                                        ]);
                                    ?>
                                </div>
                            </div>
                            <div class="input-group" style="max-width:90%">
                                <div id="addProductTxt" class="text-box-container"></div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="label body2-c">
                            사용기간
                        </label>
                        <div class="form-inline">
                            <div id="date_type_A" class="form-inline">
                                <div class="input-icon">
                                    <input type="text" class="form-control datepicker-icon"
                                        name="i_period_start_date" data-org_value='' readonly>
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
                                        name="i_period_end_date" data-org_value='' readonly>
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
                            <div id="date_type_B" style="display:none;" class="form-inline">
                                발행일로 부터 &nbsp;&nbsp;&nbsp;
                                <?php
                                    $options  = ['' => '선택', '1 month'=>'1개월', '3 month'=>'3개월', '6 month'=>'6개월', '12 month'=>'1년'];
                                    $extras   = [
                                        'id' => 'i_expire_months', 'class' => 'form-select', 'style' => 'width:174px;max-width: 174px',
                                        'onchange'=>'$(this).val() != \'\' ? $(\'[name=i_period_limit]\').prop(\'checked\', false) :  $(\'[name=i_period_limit]\').prop(\'checked\', true);'
                                    ];
                                    $selected = '';
                                    echo form_dropdown('i_expire_months', $options, $selected, $extras);
                                ?>
                            </div>

                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
                                $checked = true;
                                $setParam = [
                                    'name' => 'i_period_limit',
                                    'id' => 'i_period_limit_N',
                                    'value' => 'N',
                                    'label' => '기간제한없음',
                                    'checked' => $checked ,
                                    'extraAttributes' => [
                                        'aria-label' => 'Single checkbox One',
                                        'class'=>'check-item',
                                        'onclick' => 'changeLimitCheck( $(this) ); if($(this).is(\':checked\') ) $(\'[name=i_expire_months]\').val(\'\') ',
                                    ]
                                ];
                                echo getCheckBox( $setParam );
                            ?>
                        </div>
                    </div>
                    <!-- <div class="input-group" id="dupCpnArea">
                        <label class="label body2-c">
                            사용옵션
                        </label>
                        <?php
                            $setParam = [
                                'name' => 'i_duplicate_use',
                                'id' => 'i_duplicate_use_Y',
                                'value' => 'Y',
                                'label' => '다른 쿠폰과 중복사용가능',
                                'checked' => false,
                                'extraAttributes' => [
                                    'aria-label' => 'Single checkbox One',
                                    'class'=>'check-item',
                                ]
                            ];
                            echo getCheckBox( $setParam );
                        ?>
                    </div> -->
                    <div class="input-group" id="superCpnArea">
                        <label class="label body2-c">
                            슈퍼쿠폰
                        </label>
                        <?php
                            $setParam = [
                                'name' => 'i_is_super_flag',
                                'id' => 'i_is_super_flag_Y',
                                'value' => 'Y',
                                'label' => '슈퍼쿠폰으로 사용',
                                'checked' => false,
                                'extraAttributes' => [
                                    'aria-label' => 'Single checkbox One',
                                    'class'=>'check-item',
                                    'onclick'=>'changeSuperCpn( $(this) )',
                                ]
                            ];
                            echo getCheckBox( $setParam );
                        ?>
                    </div>

                </div>
            </div>
        </div>
        <!-- 버튼 -->
        <div style="text-align: center; margin-top: 52px">
            <?php
            echo getIconButton([
                'txt' => '목록',
                'icon' => 'list',
                'buttonClass' => 'btn',
                'buttonStyle' => 'width: 180px; height: 46px',
                'width' => '21',
                'height' => '20',
                'extra' => [
                    'onclick' => 'location.href="'._link_url('/promotion/coupon/cpnLists').'"',
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
                    'onclick' => 'frmRegisterConfirm()'
                ]
            ]);
            ?>

        </div>
    </div>
    <?php echo form_close() ?>
</div>
<div class="modal fade" id="dataModal" tabindex="-1" style="margin-top:3em;" aria-labelledby="dataodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;width:80vh">

        </div>
    </div>
</div>
<script>

let   addProductPickList = [];
let   exceptProductPickList = [];
let   preloadedCategories = [];
let   preloadedBrands = [];

$(document).on('keyup', '[name=i_benefit]', function() {
    var unit = $('[name=i_benefit_unit]').val(); // i_benefit_unit 값 확인

    if (unit === 'per') {
        // 정수 2자리만 입력 가능
        var value = $(this).val().replace(/[^0-9]/g, ''); // 숫자 이외 제거
        if (value.length > 2) {
            value = value.substring(0, 2); // 정수 2자리까지만 유지
        }
        $(this).val(value); // 다시 입력창에 값 설정
    }
});

function changeSuperCpn( obj )
{
    if( obj.is(':checked') ){
        $('#selectScope').hide();
    }else{
        $('#selectScope').show();
    }
}

function changeLimitCheck( obj )
{
    if( obj.is(':checked') ){
        $('[name=i_period_start_date]').val('');
        $('[name=i_period_end_date]').val('');
    }else{
        $('[name=i_period_start_date]').val($('[name=i_period_start_date]').data('org_value'));
        $('[name=i_period_end_date]').val($('[name=i_period_end_date]').data('org_value'));
    }

}

function frmRegisterConfirm()
{
    box_confirm('등록 하시겠습니까?', 'q', '', frmRegister);
}

function frmRegister(){
    event.preventDefault();
    event.stopPropagation();
    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_register').find('input, button, select');


    var isSubmit = true;

    $('#frm_register').find('[data-required]').each(function() {
        var $input = $(this);
        var value = $.trim($input.val());
        var errorMessage = $input.data('required');

        if (value === '') {
            _form_error($input.attr('id'), errorMessage);
            error_lists.push(errorMessage);
            isSubmit = false;
        }
    });
    var scope_gbn = $('#frm_register [name=i_scope_gbn]').val();
    if( scope_gbn == 'all' ){
        $('[name=i_except_product_idxs]').val(exceptProductPickList);
    }else if( scope_gbn == 'category' ){
        $('[name=i_pick_items]').val(preloadedCategories);
    }else if( scope_gbn == 'brand' ){
        $('[name=i_pick_items]').val(preloadedBrands);
    }else if( scope_gbn == 'product' ){
        $('[name=i_pick_items]').val(addProductPickList);
    }

    var formData = new FormData($('#frm_register')[0]);

    inputs.prop('disabled', false); // 폼 요소를 다시 활성화
    // 폼 전송 로직 추가
    $.ajax({
        url: '/apis/promotion/couponRegisterProc',
        method: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {
            //inputs.prop('disabled', true);
            $('#preloader').show();
        },
        complete: function() {inputs.prop('disabled', false); },
        success: function(response)
        {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status != 200)
            {
                var error_message = '';
                error_message = response.errors.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            inputs.prop('disabled', false);
            $('#preloader').hide();
            return false;
        }
    });

}
function showHideForm( $gbn ){
    if( $gbn == 'P' ){
        $('#group-P').show().find('input,select,checkbox').prop('disabled', false);
        $('#group-D').hide().find('input,select,checkbox').prop('disabled', true);
        $('#group-A').hide().find('input,select,checkbox').prop('disabled', true);
        $('#date_type_A').show().find('input,select,checkbox').prop('disabled', false);
        $('#date_type_B').hide().find('input,select,checkbox').prop('disabled', true);
        $('[name=i_period_limit]').prop( 'checked', true );
        $('#superCpnArea').show();
        //$('#dupCpnArea').show();
    }else if( $gbn == 'D' ){
        $('#group-P').hide().find('input,select,checkbox').prop('disabled', true);
        $('#group-D').show().find('input,select,checkbox').prop('disabled', false);
        $('#group-A').hide().find('input,select,checkbox').prop('disabled', true);
        $('#date_type_A').show().find('input,select,checkbox').prop('disabled', false);
        $('#date_type_B').hide().find('input,select,checkbox').prop('disabled', true);
        $('[name=i_period_limit]').prop( 'checked', true );
        $('#superCpnArea').hide();
        //$('#dupCpnArea').hide();
    }else if( $gbn == 'A' ){
        $('#group-P').hide().find('input,select,checkbox').prop('disabled', true);
        $('#group-D').hide().find('input,select,checkbox').prop('disabled', true);
        $('#group-A').show().find('input,select,checkbox').prop('disabled', false);
        $('#date_type_A').hide().find('input,select,checkbox').prop('disabled', true);
        $('#date_type_B').show().find('input,select,checkbox').prop('disabled', false);
        $('[name=i_period_limit]').prop( 'checked', false );
        $('#superCpnArea').hide();
        //$('#dupCpnArea').show();

    }
}

function showHideScope( $gbn ){
    if( $gbn == 'all' ){
        $('#scope_all').show().find('input,select,checkbox').prop('disabled', false);
        $('#scope_category').hide().find('input,select,checkbox').prop('disabled', true);
        $('#scope_brand').hide().find('input,select,checkbox').prop('disabled', true);
        $('#scope_product').hide().find('input,select,checkbox').prop('disabled', true);
    }else if( $gbn == 'category' ){
        $('#scope_all').hide().find('input,select,checkbox').prop('disabled', true);
        $('#scope_category').show().find('input,select,checkbox').prop('disabled', false);
        $('#scope_brand').hide().find('input,select,checkbox').prop('disabled', true);
        $('#scope_product').hide().find('input,select,checkbox').prop('disabled', true);
    }else if( $gbn == 'brand' ){
        $('#scope_all').hide().find('input,select,checkbox').prop('disabled', true);
        $('#scope_category').hide().find('input,select,checkbox').prop('disabled', true);
        $('#scope_brand').show().find('input,select,checkbox').prop('disabled', false);
        $('#scope_product').hide().find('input,select,checkbox').prop('disabled', true);
    }else if( $gbn == 'product' ){
        $('#scope_all').hide().find('input,select,checkbox').prop('disabled', true);
        $('#scope_category').hide().find('input,select,checkbox').prop('disabled', true);
        $('#scope_brand').hide().find('input,select,checkbox').prop('disabled', true);
        $('#scope_product').show().find('input,select,checkbox').prop('disabled', false);
    }
}
/* 드롭다운 */
function dropDownLayer(layerId) {

    var dropdownLayer = $("#dropdown-layer-" + layerId);

    if (dropdownLayer.css("display") === "none") {
        dropdownLayer.css("display", "block");
    } else {
        dropdownLayer.css("display", "none");
    }

    // 클릭된 요소 외부를 클릭하면 레이어를 닫도록 설정
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.input-group').length) {
            dropdownLayer.hide();
        }
    });
}


var tooltipContent = {
    'cpn_target': {
        title: '발급대상',
        left: 20,
        height: 160,
        top:-200 ,
        content: `
        <p>쿠폰 발행 대상을 지정합니다.</p>
        <p><strong><h4>고객다운로드</h4></strong></p>
        <p> • 전체회원: 모든 회원에게 발행합니다.</p>
        <p> • 특정 회원그룹에게 발행합니다. 회원그룹은 회원목록에서 관리할 수 있습니다.</p>
        <p> • 고객 행동 관리 그룹: 고객 행동 관리에서 생성한 그룹을 지정합니다</p>
        <p><strong><h4>자동발행</h4></strong></p>
        <p> • 첫 회원가입: 신규 가입 회원에게 쿠폰을 발행합니다. 같은 계정으로</p>
        <p> 재가입 하거나 동일 본인 인증 데이터로 재가입 시 쿠폰이 발행되지 않습니다.</p>
        <p> • 첫 주문완료: 회원의 첫 주문 배송완료일의 23:00에 지급 됩니다. </p>
        <p>부분취소 등으로 인하여 부분 배송완료된 주문이 있는 경우에도 발행됩니다. </p>
        <p>단, 동일인(동일 계정, 이메일, 본인인증)이 재주문하거나 구매완료전 전체취소시</p>
        <p>쿠폰이 발행되지 않습니다. 첫 주문완료 후 반품/교환 신청 후 다시 쿠폰 발급을</p>
        <p>막으려면 주문 > 환경설정에서 반품/교환 가능 기간을 무제한이 아닌</p>
        <p>특정 기간으로 사용을 권장합니다.</p>
        <p>신규 주문 시스템에서는 구매확정일자에 쿠폰이 자동 발행됩니다.</p>
        <p> • 생일 : 생일을 맞은 회원이 있을 때 쿠폰을 방행합니다. </p>
        <p>생년월일 기준으로 판단합니다. 생일 정보 변경 후 동일한 쿠폰이</p>
        <p>재발행되는 것을 막기 위해 1년에 한번만 발행 됩니다. </p>
        <p>생일 쿠폰을 만든 다음날부터 발행이 시작됩니다. </p>
        `
    },
    'cpn_gbn': {
        title: '쿠폰유형',
        left: 20,
        height: 150,
        top:-155,
        content: `
        <p>쿠폰 발행 방식을 선택합니다.</p>
        <p><strong><h4>지정 발행</h4></strong></p>
        <p> • 발행대상을 지정하여 일괄 발행하는 쿠폰입니다. 그룹 발행시 쿠폰 생성시점의</p>
        <p>회원을 대상으로 발행됩니다. 발행 후 설정하신 사용기간에 맞춰 고객이</p>
        <p> 마이페이지에서 쿠폰을 확인할 수 있습니다. 발급대상 회원수에 따라 최대</p>
        <p>30분까지 발급 시간이 소요될 수 있습니다.</p>
        <p><strong><h4>고객다운로드</h4></strong></p>
        <p> • 쿠폰을 발행하면 고객이 마이페이지를 통해 받을 수 있습니다.</p>
        <p><strong><h4>자동발행</h4></strong></p>
        <p> • 회원가입, 첫 주문완료 등 특정 조건을 만족하면 쿠폰이 자동 발행됩니다.</p>
        <p>고객이 마이페이지에서 쿠폰을 확인할 수 있고, 결제 페이지에서 쿠폰을</p>
        <p> 사용할 수 있습니다.</p>
        `
    },
    'noti':{
        title: '발행알림',
        left : 20,
        height: 150,
        top:-40,
        content: `
        <p>발행대상에게 웹과 앱 푸시 알림(앱 사용 시에만) 을 발송합니다.</p>
        <p><strong>고객 다운로드</strong>: 다운로드 받는 즉시 알림을 받습니다.</p>
        <p><strong>자동발행</strong>: 자동으로 발행된 발행일에 알림을 받습니다.</p>
        `
    },
    'benefit': {
        title: '쿠폰유형',
        left: 20,
        height: 150,
        top:-70,
        content: `
        <p>쿠폰 사용 시 어떤 혜택을 줄지 지정합니다.</p>
        <p><strong>금액할인</strong>: 주문금액에 적용할 할인 금액 또는 할인율을 지정합니다.</p>
        <p>할인율(%)은 결제금액에 비례하여 금액이 책정됩니다.</p>
        <p><strong>배송비 무료</strong>: 주문서의 모든 배송비를 무료로 적용합니다. 주문서당</p>
        <p>1개의 배송비 무료 쿠폰만 적용할 수 있습니다. 반품/교환 배송비는 </p>
        <p>면제되지 않습니다.</p>
        `
    },
    'min_price': {
        title: '최소 주문금액',
        left: 20,
        height: 150,
        top:-40,
        content: `
        <p>쿠폰 사용을 위해 필요한 최소 주문금액을 지정합니다. 주문금액은 주문하려는</p>
        <p>상품금액의 합계를 의미하며, 즉시/기간할인이 적용되어있는 경우 할인이 적용된</p>
        <p>상품금액으로 계산합니다.</p>
        `
    },
    'scope': {
        title: '쿠폰 적용 범위',
        left: 20,
        height: 150,
        top:-80,
        content: `
        <p>쿠폰을 사용할 수 있는 사애품의 조건을 지정합니다.</p>
        <p> • 모든 상품: 모든 상품과 카테고리에 쿠폰을 적용할 수 있습니다.</p>
        <p> • 지정 카테고리: 특정 카테고리에 포함된 상품을 구매할 때만 쿠폰을 </p>
        <p>적용할 수있습니다.</p>
        <p> • 지정 브랜드: 특정 브랜드에 포함된 상품을 구매할 때만 쿠폰을 </p>
        <p>적용할 수있습니다.</p>
        <p> • 지정 상품: 특정 상품을 구매 시에만 쿠폰을 적용할 수 있습니다.</p>
        `
    },
    'excp_product': {
        title: '사용 제외 상품',
        left: 20,
        height: 150,
        top:-60,
        content: `
        <p>쿠폰을 사용할 수 없는 상품을 지정합니다.</p>
        <p>사용 제외 상품은 주문에 포함되더라도 쿠폰할인에서 제외합니다.</p>
        <p>단, 배송비 무료 쿠폰의 경우 주문서의 모든 배송비에 적용하기</p>
        <p>때문에 사용가능 상품이 주문에 1개라도 포함되어 있으면 배송비</p>
        <p>전체가 할인됩니다.</p>
        `
    },
    'add_category': {
        title: '사용 제외 상품',
        left: 20,
        height: 150,
        top:-60,
        content: `
        <p>쿠폰을 사용할 수 없는 상품을 지정합니다.</p>
        <p>사용 제외 상품은 주문에 포함되더라도 쿠폰할인에서 제외합니다.</p>
        <p>단, 배송비 무료 쿠폰의 경우 주문서의 모든 배송비에 적용하기</p>
        <p>때문에 사용가능 상품이 주문에 1개라도 포함되어 있으면 배송비</p>
        <p>전체가 할인됩니다.</p>
        `
    },


};


function openDataLayer( gbn ){

    let data = '';
    let url  = '';
    switch( gbn ){
        case 'category':
            url = '/apis/promotion/getPopCategoryManage';
            break;
        case 'brand':
            url = '/apis/promotion/getPopBrandManage';
            break;
        case 'product':
            url = '/apis/promotion/getPopProductLists';
            data='openGroup='+gbn+'&xPickLists='+addProductPickList;
            break;
        case 'add_product':
            url = '/apis/promotion/getPopProductLists';
            data='openGroup='+gbn+'&picLists='+addProductPickList;
            break;
        case 'excp_product':
            url = '/apis/promotion/getPopProductLists';
            data='openGroup='+gbn+'&picLists='+exceptProductPickList;
            break;
    }
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        processData: false,
        cache: false,
        beforeSend: function() {
            $('#preloader').show();
        },
        success: function(response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status == 'false')
            {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }

                return false;
            }
            $('#dataModal .modal-content').empty().html( response.page_datas.lists_row );
            $('.dropdown-layer').hide();
            //var modal = new bootstrap.Modal(document.getElementById(id));
            var modalElement = document.getElementById('dataModal');
            var modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static', // 마스크 클릭해도 닫히지 않게 설정
                keyboard: true     // esc 키로 닫히지 않게 설정
            });

            modal.show();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            return false;
        },
        complete: function() { }
    });       // 모달 열기

}
function getCheckedItems(){
    var checkedValues = $('.check-item-pop:checked').map(function() {
        return $(this).val();
    }).get();

    return checkedValues;
}

function addProductItem( id ){
    let goods_idxs = getCheckedItems();

    $.ajax({
        url: '/apis/promotion/goodsAddRows',
        type: 'post',
        data: 'goodsIdxs='+goods_idxs+'&targetId='+id,
        processData: false,
        cache: false,
        beforeSend: function() {
            $('#preloader').show();
        },
        success: function(response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status == 'false')
            {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }

                return false;
            }
            if( id == 'excp_product' ){
                goods_idxs.forEach(function(p_idx) {
                    exceptProductPickList.push(p_idx);
                });

                $("#exceptProductTxt").append( response.page_datas.lists_row );
                console.log( "exceptProductPickList::"+exceptProductPickList );
            }else{
                goods_idxs.forEach(function(p_idx) {
                    addProductPickList.push(p_idx);
                });
                if( addProductPickList.length > 0 ){
                    $("#addPickProductBtn").text('선택 상품 보기( '+addProductPickList.length+' )').show();
                }
                $("#addProductTxt").append( response.page_datas.lists_row );
                console.log( "addProductPickList::"+addProductPickList );
            }

            setTimeout(() => {
                getSearchList();
            }, 200);

        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            return false;
        },
        complete: function() { }
    });

}

function deleteProductItem( $productIdx, gbn = 'single' )
{

    var index = addProductPickList.indexOf($productIdx); // 해당 값의 인덱스를 찾습니다.
    if (index !== -1) {
        addProductPickList.splice(index, 1);
        if( addProductPickList.length < 1 ){
            $('#addPickProductBtn').text('선택 상품 보기').hide();
        }else{
            $('#addPickProductBtn').text('선택 상품 보기( '+addProductPickList.length+' )')
        }
    }
    if( gbn == 'single' ){
        getSearchList();
    }

}
function deleteProductCheckAll()
{
    if( $('#dataModal #listsTable input[type="checkbox"]:checked').length < 1 ){
        box_alert( '삭제할 아이템을 선택해주세요.' );
        return false;
    }
    $('#dataModal #listsTable input[type="checkbox"]:checked').each(function(){
        deleteProductItem( $(this).val(), 'multi' ) ;
        console.log( $(this).val() );
    });
    getSearchList();
}
function deleteProductTmpLists( $obj, $id, $productIdx )
{
    if( $id == 'excp_product' ){
        var index = exceptProductPickList.indexOf($productIdx); // 해당 값의 인덱스를 찾습니다.
        if (index !== -1) {
            exceptProductPickList.splice(index, 1); // 해당 인덱스에서 1개 요소를 제거합니다.
            $obj.closest('.text-box').remove();
            console.log( "exceptProductPickList::"+exceptProductPickList );
        }
    }else if( $id == 'category' ){
        var index = preloadedCategories.indexOf($productIdx); // 해당 값의 인덱스를 찾습니다.
        if (index !== -1) {
            preloadedCategories.splice(index, 1); // 해당 인덱스에서 1개 요소를 제거합니다.
            $obj.closest('.text-box').remove();
            console.log( "preloadedCategories::"+preloadedCategories );
        }
    }else if( $id == 'brand' ){
        var index = preloadedBrands.indexOf($productIdx); // 해당 값의 인덱스를 찾습니다.
        if (index !== -1) {
            preloadedBrands.splice(index, 1); // 해당 인덱스에서 1개 요소를 제거합니다.
            $obj.closest('.text-box').remove();
            console.log( "preloadedBrands::"+preloadedBrands );
        }
    }
}
// 카테고리 로드
function getCategoryDropDown()
{
    $.ajax({
        url: '/apis/goods/getCategoryDropDown',
        method: 'POST',
        data: '',
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
        },
        complete: function() { },
        success: function(response)
        {
            //submitSuccess(response);
            $('#preloader').hide();
            if (response.status != 200)
            {
                var error_message = '';
                error_message = response.errors.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            $('#dropdown-layer-category').empty().html( response.page_datas.detail );
            // 기존 세팅된 카테고리들 체크되도록 함.
            setCheckedCategories( preloadedCategories );
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            console.log(textStatus);
            return false;
        }
    });
}

// 체크박스를 상태를 설정하는 함수
function setCheckedCategories(checkedCategories) {
    // checkedCategories는 서버에서 받아온 체크되어야 할 카테고리 ID 리스트라고 가정
    if (Array.isArray(checkedCategories)) {
        checkedCategories.forEach(function(categoryId) {
            // 각 카테고리 ID에 해당하는 체크박스를 체크
            $('input[name="i_cate_idx[]"][value="' + categoryId + '"]').prop('checked', true);
        });
    }
}
function getBrandDropDown()
{
    $.ajax({
        url: '/apis/goods/getBrandDropDown',
        method: 'POST',
        data: 'viewPage=promotion',
        dataType: 'json',
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
        },
        complete: function() { },
        success: function(response)
        {
            //submitSuccess(response);
            $('#preloader').hide();
            if (response.status != 200)
            {
                var error_message = '';
                error_message = response.errors.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            $('#dropdown-layer-brand').empty().html( response.page_datas.detail );
            setCheckedBrands(preloadedBrands);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            return false;
        }
    });
}
function setCheckedBrands(checkedBrands) {
    // checkedBrands 서버에서 받아온 체크되어야 할 카테고리 ID 리스트라고 가정
    if (Array.isArray(checkedBrands)) {
        checkedBrands.forEach(function(brandId) {
            // 각 브랜드 ID에 해당하는 체크박스를 체크
            $('input[name="i_brand_idx[]"][value="' + brandId + '"]').prop('checked', true);
        });
    }
}

$(document).on('change', '#dropdown-layer-category input[type="checkbox"]', function() {
    var selectedCategory = $("#select-category");

    var value = $(this).val();
    var id = $(this).attr('id');
    var txt= $(this).attr('data-txt');

    if ($(this).is(':checked')) {
        var listItem = `
        <div class="text-box" data-id="${id}" >
            <span class="text-content">${txt}</span>
            <span class="delete-box-button" onclick="deleteProductTmpLists( $(this), 'category', '${value}' )">
                <svg class="tabler-icon-circle-x" width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 10C1 11.1819 1.23279 12.3522 1.68508 13.4442C2.13738 14.5361 2.80031 15.5282 3.63604 16.364C4.47177 17.1997 5.46392 17.8626 6.55585 18.3149C7.64778 18.7672 8.8181 19 10 19C11.1819 19 12.3522 18.7672 13.4442 18.3149C14.5361 17.8626 15.5282 17.1997 16.364 16.364C17.1997 15.5282 17.8626 14.5361 18.3149 13.4442C18.7672 12.3522 19 11.1819 19 10C19 8.8181 18.7672 7.64778 18.3149 6.55585C17.8626 5.46392 17.1997 4.47177 16.364 3.63604C15.5282 2.80031 14.5361 2.13738 13.4442 1.68508C12.3522 1.23279 11.1819 1 10 1C8.8181 1 7.64778 1.23279 6.55585 1.68508C5.46392 2.13738 4.47177 2.80031 3.63604 3.63604C2.80031 4.47177 2.13738 5.46392 1.68508 6.55585C1.23279 7.64778 1 8.8181 1 10Z" fill="white"/>
                    <path d="M8 8L12 12L8 8ZM12 8L8 12L12 8Z" fill="white"/>
                    <path d="M8 8L12 12M12 8L8 12M1 10C1 11.1819 1.23279 12.3522 1.68508 13.4442C2.13738 14.5361 2.80031 15.5282 3.63604 16.364C4.47177 17.1997 5.46392 17.8626 6.55585 18.3149C7.64778 18.7672 8.8181 19 10 19C11.1819 19 12.3522 18.7672 13.4442 18.3149C14.5361 17.8626 15.5282 17.1997 16.364 16.364C17.1997 15.5282 17.8626 14.5361 18.3149 13.4442C18.7672 12.3522 19 11.1819 19 10C19 8.8181 18.7672 7.64778 18.3149 6.55585C17.8626 5.46392 17.1997 4.47177 16.364 3.63604C15.5282 2.80031 14.5361 2.13738 13.4442 1.68508C12.3522 1.23279 11.1819 1 10 1C8.8181 1 7.64778 1.23279 6.55585 1.68508C5.46392 2.13738 4.47177 2.80031 3.63604 3.63604C2.80031 4.47177 2.13738 5.46392 1.68508 6.55585C1.23279 7.64778 1 8.8181 1 10Z" stroke="#616876" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </div>
        `;

        selectedCategory.append(listItem);
        preloadedCategories.push(value);
    } else {
        selectedCategory.find('.text-box[data-id="'+ id +'"]').remove();
    }
});
$(document).on('change', '#dropdown-layer-brand input[type="checkbox"]', function() {
    var selectedBrand = $("#select-brand");

    var value = $(this).val();
    var id = $(this).attr('id');
    var txt= $(this).attr('data-txt');

    if ($(this).is(':checked')) {
        var listItem = `
        <div class="text-box" data-id="${value}" >
            <span class="text-content">${txt}</span>
            <span class="delete-box-button" onclick="deleteProductTmpLists( $(this), 'brand', '${value}' )">
                <svg class="tabler-icon-circle-x" width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 10C1 11.1819 1.23279 12.3522 1.68508 13.4442C2.13738 14.5361 2.80031 15.5282 3.63604 16.364C4.47177 17.1997 5.46392 17.8626 6.55585 18.3149C7.64778 18.7672 8.8181 19 10 19C11.1819 19 12.3522 18.7672 13.4442 18.3149C14.5361 17.8626 15.5282 17.1997 16.364 16.364C17.1997 15.5282 17.8626 14.5361 18.3149 13.4442C18.7672 12.3522 19 11.1819 19 10C19 8.8181 18.7672 7.64778 18.3149 6.55585C17.8626 5.46392 17.1997 4.47177 16.364 3.63604C15.5282 2.80031 14.5361 2.13738 13.4442 1.68508C12.3522 1.23279 11.1819 1 10 1C8.8181 1 7.64778 1.23279 6.55585 1.68508C5.46392 2.13738 4.47177 2.80031 3.63604 3.63604C2.80031 4.47177 2.13738 5.46392 1.68508 6.55585C1.23279 7.64778 1 8.8181 1 10Z" fill="white"/>
                    <path d="M8 8L12 12L8 8ZM12 8L8 12L12 8Z" fill="white"/>
                    <path d="M8 8L12 12M12 8L8 12M1 10C1 11.1819 1.23279 12.3522 1.68508 13.4442C2.13738 14.5361 2.80031 15.5282 3.63604 16.364C4.47177 17.1997 5.46392 17.8626 6.55585 18.3149C7.64778 18.7672 8.8181 19 10 19C11.1819 19 12.3522 18.7672 13.4442 18.3149C14.5361 17.8626 15.5282 17.1997 16.364 16.364C17.1997 15.5282 17.8626 14.5361 18.3149 13.4442C18.7672 12.3522 19 11.1819 19 10C19 8.8181 18.7672 7.64778 18.3149 6.55585C17.8626 5.46392 17.1997 4.47177 16.364 3.63604C15.5282 2.80031 14.5361 2.13738 13.4442 1.68508C12.3522 1.23279 11.1819 1 10 1C8.8181 1 7.64778 1.23279 6.55585 1.68508C5.46392 2.13738 4.47177 2.80031 3.63604 3.63604C2.80031 4.47177 2.13738 5.46392 1.68508 6.55585C1.23279 7.64778 1 8.8181 1 10Z" stroke="#616876" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </div>
        `;

        selectedBrand.append(listItem);
        preloadedBrands.push( value );
    } else {
        selectedCategory.find('.text-box[data-id="'+ id +'"]').remove();
    }
});
function _initFunc(){
    getCategoryDropDown();
    getBrandDropDown();
}
_initFunc();
</script>
<?php
$owensView->setFooterJs('/assets/js/setting/member/register.js');

$owensView->setHeaderCss('/plugins/select2/select2.css');
$owensView->setFooterJs('/plugins/select2/select2.js');

$script = "
$(document).ready(function () {
    $('.select2').select2({
        language: 'ko',
        placeholder: '',
        allowClear: true,
    });
});
";

$owensView->setFooterScript($script);
