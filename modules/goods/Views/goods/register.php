<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }
    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];

    $aConfig          = _elm($pageDatas, 'aConfig', []);

    $aLists           = _elm($pageDatas, 'aDatas', []);

    $aGetData         = _elm( $pageDatas, 'getData', [] );


    $aMemberGrade     = _elm( $pageDatas, 'aMemberGrade', [] );

    $aColorConfig     = _elm( $pageDatas, 'aColorConfig', [] );
    $aDefaultTxtConfig= _elm( $pageDatas, 'aDefaultTxtConfig', [] );
    $aGoodsCondition  = _elm( $pageDatas, 'aGoodsCondition', [] );
    $aGoodsProductType= _elm( $pageDatas, 'aGoodsProductType', [] );
    $aGoodsSellType   = _elm( $pageDatas, 'aGoodsSellType', [] );
    $aData            = _elm( $pageDatas, 'aData', [] );

?>
<link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css">
<script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>

<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register', 'autocomplete' => 'off']); ?>

<input type="hidden" name="i_description">
<input type="hidden" name="i_content_pc">
<input type="hidden" name="i_content_mobile">
<input type="hidden" name="i_relation_goods_idxs">
<input type="hidden" name="i_add_goods_idxs">


<!-- 본문 -->
<div class="container-fluid">
    <!-- 카트 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">상품등록</h3>
    </div>

    <div class="d-flex gap-3">

        <!-- 좌측 영역 -->
        <div class="col-md-10">
            <!-- 첫번째 행 -->
            <div class="row row-deck row-cards">
                <!-- 이미지 카드 -->
                <div class="col-md">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        이미지
                                    </p>
                                </div>
                                <!-- 추가 버튼 -->
                                <div>
                                    <button class="btn-sm btn-white" id="image-upload-button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20"
                                            viewBox="0 0 21 20" fill="none" style="margin-right: 4px">
                                            <path d="M10.25 4.16669V15.8334" stroke="#1D273B" stroke-width="1.25"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M4.41663 10H16.0833" stroke="#1D273B" stroke-width="1.25"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        이미지 추가
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body" id="upload-container"
                            style="position: relative; width: 100%; height: 200px; background-image: url('/dist/img/file_upload_bg.svg'); background-repeat: no-repeat; background-position: center; background-size: 20%; overflow-y: auto;">
                            <p id="upload-text"
                                style="position: absolute; top: 70%; left: 50%; transform: translate(-50%, -50%); margin: 0; color: #616876; text-align: center;">
                                이미지를 여기로 드래그<br>750 x 750px / JPG 권장
                            </p>
                            <div id="preview-zone" style="margin-top: 20px; display: flex; flex-wrap: wrap;"></div>
                            <input type="file" id="file-input" style="display: none;" multiple>
                        </div>
                    </div>
                </div>

                <!-- 요약설명 카드 -->
                <div class="col-md">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        요약설명
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="input-group required">
                                <label class="label body2-c">
                                    상품명
                                    <span>*</span>
                                </label>
                                <input type="text" class="form-control" name="i_goods_name" data-max-length="100"
                                    style="border-top-right-radius:0px; border-bottom-right-radius: 0px; width: 1%" data-required='상품명을 입력하세요' value=""/>
                                <span class="wordCount input-group-text"
                                    style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                    0/100
                                </span>
                            </div>

                            <div class="input-group required">
                                <label class="label body2-c">
                                    상품명(영문)
                                    <span>*</span>
                                </label>
                                <input type="text" class="form-control" name="i_goods_name_eng" data-max-length="100"
                                    style="border-top-right-radius:0px; border-bottom-right-radius: 0px; width: 1%" data-required='상품명(영문)을 입력하세요.' value="" />
                                <span class="wordCount input-group-text"
                                    style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                    0/100
                                </span>
                            </div>

                            <div class="input-group-bottom-text ms-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path
                                        d="M10 17.5C14.1421 17.5 17.5 14.1421 17.5 10C17.5 5.85786 14.1421 2.5 10 2.5C5.85786 2.5 2.5 5.85786 2.5 10C2.5 14.1421 5.85786 17.5 10 17.5Z"
                                        fill="#616876" />
                                    <path d="M10 6.66667H10.0083" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.1665 10H9.99984V13.3333H10.8332" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                요약 설명. 상품 상세정보 상단에 출력됩니다. 외부광고 서비스(페이스북/크리테오 등)와 연동시 필수
                            </div>
                            <!-- 요약 설명 에디터 -->
                            <div id="description_editor"> </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 두번째 행 -->
            <div class="row row-deck row-cards" style="margin-top:24px">
                <!-- 기본설정 카드 -->
                <div class="col-md">
                    <div class="card" style="height:fit-content">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        기본설정
                                    </p>
                                </div>

                                <!-- 아코디언 토글 버튼 -->
                                <label class="form-selectgroup-item">
                                    <span class="form-selectgroup-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8"
                                            fill="none">
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
                                    상품상세설명
                                    <span>*</span>
                                </label>
                            </div>
                            <div class="required" style="padding-bottom:1.2rem">
                                <div style="text-align: right;padding-bottom:10px">
                                <?php
                                    echo getIconButton([
                                        'txt' => '확대',
                                        'icon' => 'search',
                                        'buttonClass' => 'btn btn-white',
                                        'buttonStyle' => 'width:80px; height: 36px',
                                        'width' => '21',
                                        'height' => '20',
                                        'stroke' => 'black',
                                        'extra' => [
                                            'type' => 'button',
                                            'onclick' => 'resizeEditor(contents_editor)',
                                        ]
                                    ]);
                                ?>

                                </div>
                                <div id="editor-container">
                                    <div id="contents_editor"></div>
                                </div>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    모바일 상세설명
                                    <span>*</span>
                                </label>
                                <?php
                                    $setParam = [
                                        'name' => 'i_mobile_content_same_chk',
                                        'id' => 'i_mobile_content_same_chk_Y',
                                        'value' => 'Y',
                                        'label' => 'PC상세설명과 동일',
                                        'checked' => 'checked',
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#m_content\').hide()',
                                            'data-required' => '모바일 설명이 PC와 동일한지 선택하세요.',

                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                                <?php
                                    $setParam = [
                                        'name' => 'i_mobile_content_same_chk',
                                        'id' => 'i_mobile_content_same_chk_N',
                                        'value' => 'N',
                                        'label' => '모바일 상세설명',
                                        'checked' => '',
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#m_content\').show()',
                                            'data-required' => '모바일 설명이 PC와 동일한지 선택하세요.',
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                            </div>
                            <div class="required" id="m_content" style="padding-bottom:1.2rem;display:none;">
                                <div style="text-align: right;padding-bottom:10px">
                                    <?php
                                        echo getIconButton([
                                            'txt' => '확대',
                                            'icon' => 'search',
                                            'buttonClass' => 'btn btn-white',
                                            'buttonStyle' => 'width:80px; height: 36px',
                                            'width' => '21',
                                            'height' => '20',
                                            'stroke' => 'black',
                                            'extra' => [
                                                'type' => 'button',
                                                'onclick' => 'resizeEditor(m_contents_editor)',
                                            ]
                                        ]);
                                    ?>
                                </div>
                                <div id="editor-container">
                                    <div id="m_contents_editor"></div>
                                </div>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    자체상품코드
                                </label>
                                <input type="text" class="form-control" name="i_goods_local_code" data-max-length="100"
                                    style="border-top-right-radius:0px; border-bottom-right-radius: 0px; width: 1%"  value=""/>
                                <span class="wordCount input-group-text"
                                    style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                    0/100
                                </span>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    구매 적립포인트
                                </label>
                                <input type="text" class="form-control" name="i_goods_add_point" data-max-length="100" numberwithcomma
                                    style="max-width:150px" value=""/>

                            </div>

                            <div class="input-group">
                                <label class="label body2-c">
                                    펄핏 사이즈측정
                                </label>
                                <?php
                                    $setParam = [
                                        'name' => 'i_perfit_use',
                                        'id' => 'i_perfit_use_N',
                                        'value' => 'N',
                                        'label' => '사용안함',
                                        'checked' => 'checked',
                                        'extraAttributes' => [
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                                <?php
                                    $setParam = [
                                        'name' => 'i_perfit_use',
                                        'id' => 'i_perfit_use_Y',
                                        'value' => 'Y',
                                        'label' => '사용함',
                                        'checked' => '',
                                        'extraAttributes' => [
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                            </div>
                            <div class="input-group">
                                <label class="label body2-c">
                                    검색키워드
                                </label>
                                <input type="text" class="form-control" name="i_search_keyword"
                                    style="border-top-right-radius:0px; border-bottom-right-radius: 0px; width: 1%"  value="" placeholder="바지,바지바지,여자바지,반바지"/>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    상품정보제공고시
                                </label>
                                <?php
                                    echo getIconButton([
                                        'txt' => '추가',
                                        'icon' => 'add',
                                        'buttonClass' => 'btn',
                                        'buttonStyle' => 'width:80px; height: 36px',
                                        'width' => '21',
                                        'height' => '20',
                                        'stroke' => 'black',
                                        'extra' => [
                                            'type' => 'button',
                                            'onclick' => 'addRows( \'reqInfo\' );',
                                        ]
                                    ]);
                                ?>
                            </div>

                            <div id="add_info_wrap" style="width:100%;padding:2.5rem;margin-bottom:1.3rem;border-radius: 4px;border: 1px solid var(----tblr-border-color, #E6E7E9);background: var(-----tblr-light, #F8FAFC);">
                                <div class="table-responsive">
                                    <table class="table table-vcenter" id="aListsTable">
                                        <colgroup>
                                            <col style="width:30%;">
                                            <col style="*">
                                            <col style="width:5%;">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th>항목</th>
                                                <th>내용</th>
                                                <th>삭제</th>
                                            </tr>
                                        </thead>
                                        <tbody>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    판매기간
                                </label>
                                <div class="input-icon">
                                    <input type="text" class="form-control  datetimepicker"
                                        name="i_sell_period_start_at" id="i_sell_period_start_at" readonly>
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
                                </div> <span style="margin: 0.4rem">~</span>
                                <div class="input-icon">
                                    <input type="text" class="form-control  datetimepicker"
                                        name="i_sell_period_end_at" id="i_sell_period_end_at" readonly>
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

                            <div class="input-group">
                                <label class="label body2-c">
                                    판매단위
                                    <span>*</span>
                                </label>
                                <input type="text" class="form-control" name="i_sell_unit"
                                    style="border-top-right-radius:0px; border-bottom-right-radius: 0px; width: 1%" value=""/>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c"></label>
                                예시) BOX/EA
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    상품대표색상
                                </label>
                                <div class="color-options">
                                    <?php if( !empty( $aColorConfig ) ){
                                        foreach( $aColorConfig as $key => $colorData ){
                                    ?>
                                        <div class="color-option">
                                            <div class="color-tootip">
                                                <input type="radio" id="<?php echo _elm( $colorData, 'id' )?>" name="i_goods_color" value="<?php echo $key?>">
                                                <label for="<?php echo _elm( $colorData, 'id' )?>" style="background-color: <?php echo _elm( $colorData, 'color' )?>"class="label-custom" ></label>
                                                <span class="tooltiptext" style=""><?php echo _elm( $colorData, 'text' )?></span>
                                            </div>
                                        </div>
                                    <?php
                                        }
                                    }?>
                                </div>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c"></label>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 17.5C14.1421 17.5 17.5 14.1421 17.5 10C17.5 5.85786 14.1421 2.5 10 2.5C5.85786 2.5 2.5 5.85786 2.5 10C2.5 14.1421 5.85786 17.5 10 17.5Z" fill="#616876"/>
                                    <path d="M10 6.66669H10.0083" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9.16663 10H9.99996V13.3333H10.8333" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                                대표색상은 상품 검색시에 사용되며 색상 추가시 관리자에게 문의 주시면 됩니다.
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- 세번째 행 -->
            <div class="row row-deck row-cards" style="margin-top:24px">
                <!-- 판매정보 카드 -->
                <div class="col-md">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        판매정보
                                    </p>
                                </div>
                                <!-- 아코디언 토글 버튼 -->
                                <label class="form-selectgroup-item">
                                    <span class="form-selectgroup-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8"
                                            fill="none">
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
                                    소비자가
                                    <span>*</span>
                                </label>
                                <input type="text" class="form-control" name="i_sell_price" style="max-width:250px !important;" numberwithcomma data-required='소비자가를 입력하세요.' >
                                <span class="input-group-text"
                                    style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                    원
                                </span>

                                <label class="label body2-c" style="margin-left: 15%">
                                    공급가
                                    <span>*</span>
                                </label>
                                <input type="text" class="form-control" name="i_buy_price" style="max-width:250px !important;" numberwithcomma data-required='공급가를 입력하세요.' >
                                <span class="input-group-text"
                                    style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                    원
                                </span>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    판매가
                                    <span>*</span>
                                </label>
                                <input type="text" class="form-control" name="i_goods_price" style="max-width:250px !important;" numberwithcomma data-required='판매가를 입력하세요.' >
                                <span class="input-group-text"
                                    style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                    원
                                </span>

                                <label class="label body2-c" style="margin-left: 15%">
                                    마진율
                                    <span>*</span>
                                </label>
                                <input type="text" class="form-control" name="i_goods_price_rate" style="max-width:250px !important;" placeholder="마진율은 자동 계산됩니다." data-required='마진율을 입력하세요.' readonly >
                                <span class="input-group-text"
                                    style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                    %
                                </span>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    과세/면세
                                    <span>*</span>
                                </label>
                                <?php
                                    $options  = ['Y'=>'과세', 'N'=>'면세'];
                                    $extras   = ['id' => 'i_tax_type', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;','data-required' => '과세구분을 선택하세요.'];
                                    $selected = '';
                                    echo getSelectBox('i_tax_type', $options, $selected, $extras);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-deck row-cards" style="margin-top:24px">
                <!-- 판매정보 카드 -->
                <div class="col-md">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        할인 설정
                                    </p>
                                </div>
                                <!-- 아코디언 토글 버튼 -->
                                <label class="form-selectgroup-item">
                                    <span class="form-selectgroup-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8"
                                            fill="none">
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
                                    적용 가능 할인
                                    <span>*</span>
                                </label>
                                <?php
                                    $setParam = [
                                        'name' => 'i_discount_cd[]',
                                        'id' => 'i_discount_cd_coupon',
                                        'value' => 'coupon' ,
                                        'label' => '쿠폰',
                                        'checked' => false,
                                        'extraAttributes' => [
                                            'class'=>'check-item',
                                        ]
                                    ];
                                    echo getCheckBox( $setParam );
                                ?>
                                <?php
                                    $setParam = [
                                        'name' => 'i_discount_cd[]',
                                        'id' => 'i_discount_cd_point',
                                        'value' => 'point' ,
                                        'label' => '포인트',
                                        'checked' => false,
                                        'extraAttributes' => [
                                            'class'=>'check-item',
                                        ]
                                    ];
                                    echo getCheckBox( $setParam );
                                ?>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    적립금 지급 기준
                                </label>
                                <?php
                                    $options  = ['N'=>'기본 설정에 따름', 'Y'=>'개별 적립금 설정'];
                                    $extras   = ['id' => 'i_sell_point_flag', 'class' => 'form-select', 'style' => 'max-width: 250px;margin-right:0.235em;','onChange'=>'$(this).val() == \'Y\'? $(\'#point_save_wrap\').show() : $(\'#point_save_wrap\').hide()' ];
                                    $selected = '';
                                    echo getSelectBox('i_sell_point_flag', $options, $selected, $extras);
                                ?>
                            </div>
                            <div id="point_save_wrap" style="display:none;width:100%;padding:2.5rem;margin-bottom:1.3rem;border-radius: 4px;border: 1px solid var(----tblr-border-color, #E6E7E9);background: var(-----tblr-light, #F8FAFC);">
                                <div id="point_save_list">
                                    <div class="input-group required" style="max-width:100%">

                                        <?php
                                            $options  = $aMemberGrade;
                                            $extras   = ['id' => 'i_discount_mb_group[]', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:2.235em;'];
                                            $selected = '';
                                            echo getSelectBox('i_discount_mb_group[]', $options, $selected, $extras);
                                        ?>
                                        <input type="text" name="i_discount_mb_group_amt[]" class="form-control">
                                        <?php
                                            $options  = ['per' => '%' , 'won' => '원'];
                                            $extras   = ['id' => 'i_discount_mb_group_amt_unit[]', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-left:2.235em;'];
                                            $selected = '';
                                            echo getSelectBox('i_discount_mb_group_amt_unit[]', $options, $selected, $extras);
                                        ?>

                                        <div class="input-icon" style="padding-left:0.5rem">

                                            <input type="text" class="form-control  datepicker-icon"
                                                name="i_discount_start_date[]" id="i_discount_start_date[]" readonly>
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
                                        </div> <span style="margin: 0.4rem">~</span>
                                        <div class="input-icon">
                                            <input type="text" class="form-control  datepicker-icon"
                                                name="i_discount_end_date[]" id="i_discount_end_date[]" readonly>
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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin-left:3rem" onclick="$(this).closest('.input-group').remove()">
                                            <path d="M3 12C3 13.1819 3.23279 14.3522 3.68508 15.4442C4.13738 16.5361 4.80031 17.5282 5.63604 18.364C6.47177 19.1997 7.46392 19.8626 8.55585 20.3149C9.64778 20.7672 10.8181 21 12 21C13.1819 21 14.3522 20.7672 15.4442 20.3149C16.5361 19.8626 17.5282 19.1997 18.364 18.364C19.1997 17.5282 19.8626 16.5361 20.3149 15.4442C20.7672 14.3522 21 13.1819 21 12C21 10.8181 20.7672 9.64778 20.3149 8.55585C19.8626 7.46392 19.1997 6.47177 18.364 5.63604C17.5282 4.80031 16.5361 4.13738 15.4442 3.68508C14.3522 3.23279 13.1819 3 12 3C10.8181 3 9.64778 3.23279 8.55585 3.68508C7.46392 4.13738 6.47177 4.80031 5.63604 5.63604C4.80031 6.47177 4.13738 7.46392 3.68508 8.55585C3.23279 9.64778 3 10.8181 3 12Z" fill="white"/>
                                            <path d="M10 10L14 14L10 10ZM14 10L10 14L14 10Z" fill="white"/>
                                            <path d="M10 10L14 14M14 10L10 14M3 12C3 13.1819 3.23279 14.3522 3.68508 15.4442C4.13738 16.5361 4.80031 17.5282 5.63604 18.364C6.47177 19.1997 7.46392 19.8626 8.55585 20.3149C9.64778 20.7672 10.8181 21 12 21C13.1819 21 14.3522 20.7672 15.4442 20.3149C16.5361 19.8626 17.5282 19.1997 18.364 18.364C19.1997 17.5282 19.8626 16.5361 20.3149 15.4442C20.7672 14.3522 21 13.1819 21 12C21 10.8181 20.7672 9.64778 20.3149 8.55585C19.8626 7.46392 19.1997 6.47177 18.364 5.63604C17.5282 4.80031 16.5361 4.13738 15.4442 3.68508C14.3522 3.23279 13.1819 3 12 3C10.8181 3 9.64778 3.23279 8.55585 3.68508C7.46392 4.13738 6.47177 4.80031 5.63604 5.63604C4.80031 6.47177 4.13738 7.46392 3.68508 8.55585C3.23279 9.64778 3 10.8181 3 12Z" stroke="#616876" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>

                                    </div>
                                </div>
                                <div class="input-group" >
                                    <label style="padding-top:2.1rem;cursor:pointer;color:blue;" onclick="addTargetRows()"><strong>적용대상 추가</strong></label>
                                </div>
                                <div class="input-group" >
                                    <label class="label body2-c">
                                    <?php
                                        $setParam = [
                                            'name' => 'i_sale_priod_flag',
                                            'id' => 'i_sale_priod_flag',
                                            'value' => 'Y' ,
                                            'label' => '기간 일괄설정',
                                            'checked' => false,
                                            'extraAttributes' => [
                                                'class'=>'check-item',
                                            ]
                                        ];
                                        echo getCheckBox( $setParam );
                                    ?>
                                    </label>
                                </div>
                                <div class="input-group" >
                                    <div class="form-inline">
                                        <div class="input-icon">
                                            <input type="text" class="form-control datepicker-icon"
                                                name="s_start_date" readonly>
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
                                        <span style="margin:0 1.2rem 0 1.2rem">~</span>
                                        <div class="input-icon">
                                            <input type="text" class="form-control datepicker-icon"
                                                name="s_end_date" readonly>
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
                            </div>

                        </div>
                    </div>
                </div>
            </div>




            <div class="row row-deck row-cards" style="margin-top:24px">
                <!-- 판매정보 카드 -->
                <div class="col-md">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        연관상품
                                    </p>
                                </div>
                                <!-- 아코디언 토글 버튼 -->
                                <label class="form-selectgroup-item">
                                    <span class="form-selectgroup-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8"
                                            fill="none">
                                            <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="input-group required">
                                <?php
                                    $setParam = [
                                        'name' => 'i_relation_use_flag',
                                        'id' => 'i_relation_use_flag_Y',
                                        'value' => 'Y',
                                        'label' => '사용',
                                        'checked' => 'checked',
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#relationflag_wrap\').show()'
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                                <?php
                                    $setParam = [
                                        'name' => 'i_relation_use_flag',
                                        'id' => 'i_relation_use_flag_N',
                                        'value' => 'N',
                                        'label' => '미사용',
                                        'checked' => '',
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#relationflag_wrap\').hide()'
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                            </div>
                            <div id="relationflag_wrap" style="">
                                <div class="input-group required" >
                                    <?php
                                        echo getIconButton([
                                            'txt' => '상품 추가',
                                            'icon' => 'add',
                                            'buttonClass' => 'btn btn-blue',
                                            'buttonStyle' => 'width:120px; height: 36px',
                                            'width' => '21',
                                            'height' => '20',
                                            'stroke' => 'white',
                                            'extra' => [
                                                'type' => 'button',
                                                'onclick' => 'openDataLayer(\'product\');',
                                            ]
                                        ]);
                                    ?>
                                    <span style="margin:0 0.6rem 0 0.4rem;color:#ccc;font-size:25px;">|</span>

                                    <?php
                                        echo getButton([
                                            'text' => '선택삭제',
                                            'class' => 'btn btn-secondary',
                                            'style' => 'width: 80px; height: 36px',
                                            'extra' => [
                                                'onclick' => 'event.preventDefault();removeLine(\'aRelationTable\')',
                                            ]
                                        ]);
                                    ?>
                                </div>
                                <div id="relation_table_wrap" style="width:100%;padding:2.5rem;margin-bottom:1.3rem;border-radius: 4px;border: 1px solid var(----tblr-border-color, #E6E7E9);background: var(-----tblr-light, #F8FAFC);">
                                    <div class="table-responsive">
                                        <table class="table table-vcenter" id="aRelationTable">
                                            <colgroup>
                                                <col style="width:7%;">
                                                <col style="width:5%;">
                                                <col style="width:8%;">
                                                <col style="*">
                                                <col style="width:10%;">
                                                <col style="width:10%;">
                                                <col style="width:10%;">
                                                <col style="width:10%;">
                                                <col style="width:20%;">
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>
                                                    <div class="checkbox checkbox-single">
                                                        <?php
                                                        $setParam = [
                                                            'name' => '',
                                                            'id' => 'checkAllPro',
                                                            'value' => '',
                                                            'label' => '',
                                                            'checked' => false,
                                                            'extraAttributes' => [
                                                                'class'=>'checkAll',
                                                                'aria-label' => 'Single checkbox One'
                                                            ]
                                                        ];
                                                        echo getCheckBox( $setParam );
                                                        ?>
                                                    </div>
                                                    </th>
                                                    <th></th>
                                                    <th>상품명</th>
                                                    <th>분류</th>
                                                    <th>판매가</th>
                                                    <th>노출상태</th>
                                                    <th>판매상태</th>
                                                    <th>등록방식
                                                    <?php
                                                    echo getIconAnchor([
                                                        'txt' => '',
                                                        'icon' => 'help',
                                                        'width' => '16',
                                                        'height' => '16',
                                                        'stroke' => '#ccc',
                                                        'extra' => [
                                                            'type' => 'button',
                                                            'onclick' => 'showTooltip(this,\'regist_gbn\');',
                                                        ]
                                                    ]);
                                                    ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="relationSort">


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-deck row-cards" style="margin-top:24px">
                <!-- 판매정보 카드 -->
                <div class="col-md">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        추가상품
                                    </p>
                                </div>
                                <!-- 아코디언 토글 버튼 -->
                                <label class="form-selectgroup-item">
                                    <span class="form-selectgroup-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8"
                                            fill="none">
                                            <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="input-group required">
                                <?php
                                    $setParam = [
                                        'name' => 'i_add_goods_flag',
                                        'id' => 'i_add_goods_flag_Y',
                                        'value' => 'Y',
                                        'label' => '사용',
                                        'checked' => 'checked',
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#add_goods_flag_wrap\').show()'
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                                <?php
                                    $setParam = [
                                        'name' => 'i_add_goods_flag',
                                        'id' => 'i_add_goods_flag_N',
                                        'value' => 'N',
                                        'label' => '미사용',
                                        'checked' => '',
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#add_goods_flag_wrap\').hide()'
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                            </div>
                            <div id="add_goods_flag_wrap" style="">
                                <div class="input-group required" >
                                    <?php
                                        echo getIconButton([
                                            'txt' => '상품 추가',
                                            'icon' => 'add',
                                            'buttonClass' => 'btn btn-blue',
                                            'buttonStyle' => 'width:120px; height: 36px',
                                            'width' => '21',
                                            'height' => '20',
                                            'stroke' => 'white',
                                            'extra' => [
                                                'type' => 'button',
                                                'onclick' => 'openDataLayer(\'add_product\');',
                                            ]
                                        ]);
                                    ?>
                                    <span style="margin:0 0.6rem 0 0.4rem;color:#ccc;font-size:25px;">|</span>

                                    <?php
                                        echo getButton([
                                            'text' => '선택삭제',
                                            'class' => 'btn btn-secondary',
                                            'style' => 'width: 80px; height: 36px',
                                            'extra' => [
                                                'onclick' => 'event.preventDefault();removeLine(\'aAddGoodsTable\')',
                                            ]
                                        ]);
                                    ?>
                                </div>
                                <div id="relation_table_wrap" style="width:100%;padding:2.5rem;margin-bottom:1.3rem;border-radius: 4px;border: 1px solid var(----tblr-border-color, #E6E7E9);background: var(-----tblr-light, #F8FAFC);">
                                    <div class="table-responsive">
                                        <table class="table table-vcenter" id="aAddGoodsTable">
                                            <colgroup>
                                                <col style="width:7%;">
                                                <col style="width:10%;">
                                                <col style="width:10%;">
                                                <col style="*">
                                                <col style="width:15%;">
                                                <col style="width:15%;">
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>
                                                    <div class="checkbox checkbox-single">
                                                        <?php
                                                        $setParam = [
                                                            'name' => '',
                                                            'id' => 'checkAllAdd',
                                                            'value' => '',
                                                            'label' => '',
                                                            'checked' => false,
                                                            'extraAttributes' => [
                                                                'class'=>'checkAll',
                                                                'aria-label' => 'Single checkbox One'
                                                            ]
                                                        ];
                                                        echo getCheckBox( $setParam );
                                                        ?>
                                                    </div>
                                                    </th>
                                                    <th></th>
                                                    <th>상품명</th>
                                                    <th>판매가</th>
                                                    <th>판매상태</th>
                                                </tr>
                                            </thead>
                                            <tbody id="addGoodsSort">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-deck row-cards" style="margin-top:24px">
                <!-- 판매정보 카드 -->
                <div class="col-md">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        옵션/재고 설정
                                    </p>
                                </div>
                                <!-- 아코디언 토글 버튼 -->
                                <label class="form-selectgroup-item">
                                    <span class="form-selectgroup-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8"
                                            fill="none">
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
                                    사용옵션
                                    <span>*</span>
                                </label>
                                <?php
                                    $setParam = [
                                        'name' => 'i_option_use_flag',
                                        'id' => 'i_option_use_flag_N',
                                        'value' => 'N',
                                        'label' => '사용안함',
                                        'checked' => 'checked',
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#options_wrap\').hide();'
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                                <?php
                                    $setParam = [
                                        'name' => 'i_option_use_flag',
                                        'id' => 'i_option_use_flag_Y',
                                        'value' => 'Y',
                                        'label' => '사용함',
                                        'checked' => '',
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#options_wrap\').show();'
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                            </div>
                            <div id="options_wrap" style="display:none;width:100%;padding:2.5rem;margin-bottom:1.3rem;border-radius: 4px;border: 1px solid var(----tblr-border-color, #E6E7E9);background: var(-----tblr-light, #F8FAFC);">
                                <?php
                                    echo getIconButton([
                                        'txt' => '항목추가',
                                        'icon' => 'box_plus',
                                        'buttonClass' => 'btn',
                                        'buttonStyle' => 'width:120px; height: 36px',
                                        'width' => '21',
                                        'height' => '20',
                                        'stroke' => 'black',
                                        'extra' => [
                                            'type' => 'button',
                                            'onclick' => 'addRows( \'options\' );',
                                        ]
                                    ]);
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-vcenter" id="aOptionTable">
                                        <colgroup>
                                            <col style="width:20%;">
                                            <col style="*">
                                            <col style="width:10%;">
                                            <col style="width:10%;">
                                            <col style="width:10%;">
                                            <col style="width:5%;">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th>옵션명</th>
                                                <th>옵션값</th>
                                                <th>재고수량</th>
                                                <th>추가금액</th>
                                                <th>노출여부</th>
                                                <th>삭제</th>
                                            </tr>
                                        </thead>
                                        <tbody>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    판매재고
                                </label>
                                <?php
                                    $setParam = [
                                        'name' => 'i_goods_stock_flag',
                                        'id' => 'i_goods_stock_flag_N',
                                        'value' => 'N',
                                        'label' => '무한정 판매',
                                        'checked' => 'checked',
                                        'extraAttributes' => [
                                            'onclick'=>'$(\'#stock_wrap\').hide();',
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                                <?php
                                    $setParam = [
                                        'name' => 'i_goods_stock_flag',
                                        'id' => 'i_goods_stock_flag_N',
                                        'value' => 'Y',
                                        'label' => '재고수량에 따름',
                                        'checked' => '',
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#stock_wrap\').show();',
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                            </div>
                            <div class="input-group" id="stock_wrap" style="display:none">
                                <label class="label body2-c">
                                    재고수량
                                </label>
                                <input type="text" class="form-control" name="i_goods_stock" style="max-width:250px !important;" >
                                <span class="input-group-text"
                                    style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                    개
                                </span>
                                <label class="label body2-c" style="margin-left:4.7rem;">
                                    안전재고
                                </label>
                                <input type="text" class="form-control" name="i_goods_safe_stock" style="max-width:250px !important;">
                                <span class="input-group-text"
                                    style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                    개
                                </span>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c"></label>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 17.5C14.1421 17.5 17.5 14.1421 17.5 10C17.5 5.85786 14.1421 2.5 10 2.5C5.85786 2.5 2.5 5.85786 2.5 10C2.5 14.1421 5.85786 17.5 10 17.5Z" fill="#616876"></path>
                                    <path d="M10 6.66669H10.0083" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M9.16663 10H9.99996V13.3333H10.8333" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                재고수량은 옵션의 수량으로 자동계산 됩니다. 사용옵션을 사용안함선택 시 무한정판매 옵션으로 자동 선택됩니다.
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c"></label>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 17.5C14.1421 17.5 17.5 14.1421 17.5 10C17.5 5.85786 14.1421 2.5 10 2.5C5.85786 2.5 2.5 5.85786 2.5 10C2.5 14.1421 5.85786 17.5 10 17.5Z" fill="#616876"></path>
                                    <path d="M10 6.66669H10.0083" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M9.16663 10H9.99996V13.3333H10.8333" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                재고 수량체크 기준은 ‘주문기준’ 입니다
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="row row-deck row-cards" style="margin-top:24px">
                <!-- 판매정보 카드 -->
                <div class="col-md">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        텍스트 옵션 설정
                                    </p>
                                </div>
                                <!-- 아코디언 토글 버튼 -->
                                <label class="form-selectgroup-item">
                                    <span class="form-selectgroup-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8"
                                            fill="none">
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
                                    텍스트옵션
                                    <span>*</span>
                                </label>
                                <?php
                                    $setParam = [
                                        'name' => 'i_text_option_use_flag',
                                        'id' => 'i_text_option_use_flag_N',
                                        'value' => 'N',
                                        'label' => '사용안함',
                                        'checked' => 'checked',
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#text_options_wrap\').hide();'
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                                <?php
                                    $setParam = [
                                        'name' => 'i_text_option_use_flag',
                                        'id' => 'i_text_option_use_flag_Y',
                                        'value' => 'Y',
                                        'label' => '사용함',
                                        'checked' => '',
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#text_options_wrap\').show();'
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                            </div>
                            <div id="text_options_wrap" style="display:none;width:100%;padding:2.5rem;margin-bottom:1.3rem;border-radius: 4px;border: 1px solid var(----tblr-border-color, #E6E7E9);background: var(-----tblr-light, #F8FAFC);">
                                <?php
                                    echo getIconButton([
                                        'txt' => '항목추가',
                                        'icon' => 'box_plus',
                                        'buttonClass' => 'btn',
                                        'buttonStyle' => 'width:120px; height: 36px',
                                        'width' => '21',
                                        'height' => '20',
                                        'stroke' => 'black',
                                        'extra' => [
                                            'type' => 'button',
                                            'onclick' => 'addRows( \'text_options\' );',
                                        ]
                                    ]);
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-vcenter" id="aTextOptionTable">
                                        <colgroup>
                                            <col style="width:20%;">
                                            <col style="width:20%;">
                                            <col style="width:*;">
                                            <col style="width:5%;">
                                            <col style="width:5%;">

                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th>텍스트명</th>
                                                <th>옵션타입</th>
                                                <th>타입값 설정</th>
                                                <th></th>
                                                <th>삭제</th>
                                            </tr>
                                        </thead>
                                        <tbody>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-deck row-cards" style="margin-top:24px">

                <div class="col-md">
                    <div class="card">

                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        배송비 설정
                                    </p>
                                </div>

                                <label class="form-selectgroup-item">
                                    <span class="form-selectgroup-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8"
                                            fill="none">
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
                                    배송비
                                    <span>*</span>
                                </label>
                                <?php
                                    $setParam = [
                                        'name' => 'i_delivery_pay',
                                        'id' => 'i_delivery_pay',
                                        'value' => 'od',
                                        'label' => '5만원 이상 무료배송',
                                        'checked' => '',
                                        'extraAttributes' => [
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--
            <div class="row row-deck row-cards" style="margin-top:24px">

                <div class="col-md">
                    <div class="card">

                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        안내문구
                                    </p>
                                </div>

                                <label class="form-selectgroup-item">
                                    <span class="form-selectgroup-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8"
                                            fill="none">
                                            <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="card-body">
                            <?php if( empty($aDefaultTxtConfig) === false ){
                                foreach( $aDefaultTxtConfig as $key => $inData ){
                            ?>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    <?php echo _elm( $inData, 'title' )?>
                                </label>
                                <textarea class="form-control" rows='6' name="<?php echo $key?>"><?php foreach( _elm( $inData, 'text' ) as $text ){echo $text.PHP_EOL;}?></textarea>

                            </div>
                            <?php
                                }
                            }?>
                        </div>
                    </div>
                </div>
            </div>
            -->
        </div>


        <!-- 우측 영역 -->
        <div class="col-md-2">
            <!-- 분류 카드 S-->
            <div class="" style="height: fit-content">
                <!-- 묶음 시작 -->
                <div style="padding-bottom:1.1rem">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        분류
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="input-group" style="position:relative">
                                <label class="label body2-c">
                                    카테고리
                                    <?php
                                    echo getIconAnchor([
                                        'txt' => '',
                                        'icon' => 'help',
                                        'width' => '16',
                                        'height' => '16',
                                        'stroke' => '#ccc',
                                        'extra' => [
                                            'type' => 'button',
                                            'onclick' => 'showTooltip(this,\'category\');',
                                        ]
                                    ]);
                                    ?>
                                </label>
                                <input type="text" class="form-control mt-2" id="ex-dropdown"
                                    placeholder="선택" readonly onclick="dropDownLayer('category')"/>
                                <div id="dropdown-layer-category" class="dropdown-layer" style="display:none">
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="select-item" id="select-category">
                                </div>
                            </div>
                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    PC쇼핑몰 노출상태
                                </label>
                                <div class="form-inline">
                                    <?php
                                        $setParam = [
                                            'name' => 'i_is_pc_open',
                                            'id' => 'is_pc_open_Y',
                                            'value' => 'Y',
                                            'label' => '노출함',
                                            'checked' => 'checked',
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                    <?php
                                        $setParam = [
                                            'name' => 'i_is_pc_open',
                                            'id' => 'is_pc_open_N',
                                            'value' => 'N',
                                            'label' => '노출안함',
                                            'checked' => '',
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                </div>
                            </div>

                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    PC쇼핑몰 판매상태
                                </label>
                                <div class="form-inline">
                                    <?php
                                        $setParam = [
                                            'name' => 'i_is_pc_sell',
                                            'id' => 'i_is_pc_sell_Y',
                                            'value' => 'Y',
                                            'label' => '판매함',
                                            'checked' => 'checked',
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                    <?php
                                        $setParam = [
                                            'name' => 'i_is_pc_sell',
                                            'id' => 'i_is_pc_sell_N',
                                            'value' => 'N',
                                            'label' => '판매안함',
                                            'checked' => '',
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                </div>
                            </div>

                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    모바일쇼핑몰 노출상태
                                </label>
                                <div class="form-inline">
                                    <?php
                                        $setParam = [
                                            'name' => 'i_is_mobile_open',
                                            'id' => 'i_is_mobile_open_Y',
                                            'value' => 'Y',
                                            'label' => '노출함',
                                            'checked' => 'checked',
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                    <?php
                                        $setParam = [
                                            'name' => 'i_is_mobile_open',
                                            'id' => 'i_is_mobile_open_N',
                                            'value' => 'N',
                                            'label' => '노출안함',
                                            'checked' => '',
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                </div>
                            </div>

                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    모바일쇼핑몰 판매상태
                                </label>
                                <div class="form-inline">
                                    <?php
                                        $setParam = [
                                            'name' => 'i_is_mobile_sell',
                                            'id' => 'i_is_mobile_sel_Y',
                                            'value' => 'Y',
                                            'label' => '판매함',
                                            'checked' => 'checked',
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                    <?php
                                        $setParam = [
                                            'name' => 'i_is_mobile_sell',
                                            'id' => 'i_is_mobile_sell_N',
                                            'value' => 'N',
                                            'label' => '판매안함',
                                            'checked' => '',
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                </div>
                            </div>

                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    원산지
                                </label>
                                <div class="form-inline">
                                    <input type="text" class="form-control mt-2" name="i_origin_name"
                                    placeholder="원산지를 입력해주세요." />
                                </div>
                            </div>
                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    제조사
                                </label>
                                <div class="form-inline">
                                    <input type="text" class="form-control mt-2" name="i_maker_name"
                                    placeholder="원산지를 입력해주세요." />
                                </div>
                            </div>
                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    브랜드
                                </label>
                                <div class="form-inline">
                                <?php
                                    echo getButton([
                                        'text' => '브랜드 선택',
                                        'class' => 'btn',
                                        'style' => 'width: 180px; height: 46px',
                                        'extra' => [
                                            'onclick' => 'event.preventDefault();dropDownLayer(\'brand\')',
                                        ]
                                    ]);
                                ?>
                                </div>
                                <div id="dropdown-layer-brand" class="dropdown-layer" style="display:none">
                                </div>
                            </div>
                            <div class="input-group">
                                <div id="select-brand">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 묶음 종료 -->
                <!-- 묶음 시작 -->
                <div style="padding-bottom:1.1rem">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        SEO (검색엔진 최적화)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="input-group">
                                <label class="label body2-c">
                                    제목
                                    <span>*</span>
                                </label>
                                <input type="text" class="form-control mt-2" name="i_seo_title" placeholder="메타테그 제목" />
                            </div>
                            <div class="input-group">
                                <label class="label body2-c">
                                    설명
                                    <span>*</span>
                                </label>
                                <input type="text" class="form-control mt-2" name="i_seo_description" placeholder="메타태그 설명" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 묶음 종료 -->
                <!-- 묶음 시작 -->
                <div style="padding-bottom:1.1rem">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        아이콘
                                        <?php
                                        echo getIconAnchor([
                                            'txt' => '',
                                            'icon' => 'help',
                                            'width' => '16',
                                            'height' => '16',
                                            'stroke' => '#ccc',
                                            'extra' => [
                                                'type' => 'button',
                                                'onclick' => 'showTooltip(this,\'icon\');',
                                            ]
                                        ]);
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="input-group">
                                <label class="label body2-c">
                                    기간제 아이콘
                                    <span>*</span>
                                </label>
                                <div class="table-responsive" style="width:100%">
                                    <table class="table table-vcenter" id="aIconTable_P">
                                        <colgroup>
                                            <col style="width: 10% !important;">
                                            <col style="width: 90%;"> <!-- 나머지 칸에 대한 폭 설정 -->
                                        </colgroup>
                                        <thead>
                                            <th></th>
                                            <th>아이콘</th>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="input-group" style="border-top:1px dotted #ccc;margin-top:3rem;padding-top:1.3rem;">
                                <label class="label body2-c">
                                    무기한 아이콘
                                    <span>*</span>
                                </label>
                                <div class="table-responsive" style="width:100%">
                                    <table class="table table-vcenter" id="aIconTable_L">
                                        <colgroup>
                                            <col style="width: 10% !important;">
                                            <col style="width: 90%;"> <!-- 나머지 칸에 대한 폭 설정 -->
                                        </colgroup>
                                        <thead>
                                            <th></th>
                                            <th>아이콘</th>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 묶음 종료 -->
                <!-- 묶음 시작 -->
                <div style="padding-bottom:1.1rem">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        외부노출
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="input-group">
                                <?php
                                    $setParam = [
                                        'name' => 'i_out_view[]',
                                        'id' => 'i_out_view_naver',
                                        'value' => 'NAVER',
                                        'label' => '네이버 쇼핑',
                                        'checked' => '',
                                        'extraAttributes' => [
                                        ]
                                    ];
                                    echo getCheckBox($setParam);
                                ?>
                            </div>
                            <div class="input-group">
                                <?php
                                    $setParam = [
                                        'name' => 'i_out_view[]',
                                        'id' => 'i_out_view_kakao',
                                        'value' => 'KAKAO',
                                        'label' => '카카오 쇼핑하우',
                                        'checked' => '',
                                        'extraAttributes' => [
                                        ]
                                    ];
                                    echo getCheckBox($setParam);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 묶음 종료 -->
                <!-- 묶음 시작 -->
                <div style="padding-bottom:1.1rem">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        외부 연동 정보
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="input-group">
                                <label class="label body2-c">
                                    대표 이미지
                                </label>
                            </div>
                            <div class="input-group">
                                <input type="file" name="i_out_main_img" class="form-control">
                            </div>
                            <div class="card-body" id="i_out_main_img_view">

                            </div>
                            <div class="input-group">
                                <label class="label body2-c" style="width:100%" >
                                    네이버, 카카오 노출룔 상품명
                                </label>
                            </div>
                            <div class="input-group" style="margin-top:-0.7rem;">
                                <input type="text" class="form-control" name="i_out_goods_name" id="i_out_goods_name" placeholder="비워두면 상품명과 동일하게 적용">
                            </div>
                            <div class="input-group">
                                <label class="label body2-c"  style="width:100%" >
                                    네이버 쇼핑 이벤트 문구
                                </label>
                            </div>
                            <div class="input-group" style="margin-top:-0.7rem;">
                                <input type="text" class="form-control" name="i_out_event_txt" id="i_out_event_txt" placeholder="예: 10주면 10% 할인 이벤트">
                            </div>
                            <div class="input-group">
                                <label class="label body2-c"  style="width:100%" >
                                    상품 상태
                                </label>
                            </div>
                            <div class="input-group" style="margin-top:-0.7rem;">
                            <?php
                                $options  = $aGoodsCondition;
                                $extras   = ['id' => 'i_goods_condition', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:2.235em;'];
                                $selected = '';
                                echo getSelectBox('i_goods_condition', $options, $selected, $extras);
                            ?>
                            </div>
                            <?php if( !empty($aGoodsProductType) ){
                                foreach( $aGoodsProductType as $aKey => $aVal ){
                            ?>
                            <div class="input-group" style="margin-bottom:-0.8rem">
                                <?php
                                    $setParam = [
                                        'name' => 'i_is_product_type[]',
                                        'id' => 'i_is_product_type_'.$aKey,
                                        'value' => strtoupper( $aKey ),
                                        'label' => $aVal,
                                        'checked' => '',
                                        'extraAttributes' => [
                                        ]
                                    ];
                                    echo getCheckBox($setParam);
                                ?>
                            </div>
                            <?php
                                }
                            }
                            ?>
                            <div class="input-group">
                                <label class="label body2-c"  style="width:100%" >

                                </label>
                            </div>
                            <div class="input-group" style="margin-top:-0.7rem;">
                            <?php
                                $options  = $aGoodsSellType;
                                $extras   = ['id' => 'i_is_goods_seles_type', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:2.235em;'];
                                $selected = '';
                                echo getSelectBox('i_is_goods_seles_type', $options, $selected, $extras);
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 묶음 종료 -->
                <!-- 묶음 시작 -->
                <div style="padding-bottom:1.1rem">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        기타 설정
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="input-group">
                                <label class="label body2-c">
                                    최소 구매 수량
                                </label>
                            </div>
                            <div class="input-group" style="margin-top:-0.7rem;">
                                <input type="text" name="i_min_buy_count" id="i_min_buy_count" class="form-control" value="" placeholder="숫자만 입력">
                            </div>
                            <div class="input-group">
                                <label class="label body2-c"  style="width:100%" >
                                    1인 구매시 최대 수량
                                </label>
                            </div>
                            <div class="input-group" style="margin-top:-0.7rem;">
                                <input type="text" class="form-control" name="i_mem_max_buy_count" id="i_mem_max_buy_count" placeholder="숫자만 입력">
                            </div>

                            <div class="input-group">
                                <?php
                                    $setParam = [
                                        'name' => 'i_is_adult_product',
                                        'id' => 'i_is_product_type_Y',
                                        'value' => 'Y',
                                        'label' => '미성년자 구매 불가능',
                                        'checked' => '',
                                        'extraAttributes' => [
                                        ]
                                    ];
                                    echo getCheckBox($setParam);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 묶음 종료 -->
            </div>
        </div>
    </div>
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
                'onclick' => 'frmRegisterConfirm(event);',
            ]
        ]);
        ?>
    </div>


</div>
<?php echo form_close() ?>
<!-- Modal S-->
<div class="modal fade" id="dataModal" tabindex="-1" style="margin-top:3em;" aria-labelledby="dataodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;width:80vh">

        </div>
    </div>
</div>
<!-- Modal E-->
<script>
const $uploadContainer = $('#upload-container');
const $fileInput = $('#file-input');
const $previewZone = $('#preview-zone');
const $uploadText = $('#upload-text');
const $uploadButton = $('#upload-button');
let   filesArray = [];
let   productPcikList = [];
let   addProductPickList = [];

$('#relationSort').sortable({
    handle: '.relation-move-icons',
    update: function(event, ui) {
        var idsInOrder = $('#relationSort').sortable('toArray',{ attribute : 'data-idx'});

        console.log(idsInOrder);
    }
});
$('#addGoodsSort').sortable({
    handle: '.add-move-icons',
    update: function(event, ui) {
        var idsInOrder = $('#addGoodsSort').sortable('toArray',{ attribute : 'data-idx'});

        console.log(idsInOrder);
    }
});


function resizeEditor(editor) {
    const _editorHeight = editor.getHeight();
    const editorHeight = parseInt(_editorHeight, 10);

    if (editorHeight >= 250) {

        editor.setHeight((editorHeight + 100) + 'px');
        if (editorHeight > 850) {
            editor.setHeight('250px');
        }
    } else {
        console.log('Height remains unchanged');
    }
}
function removeLine( id )
{
    $('#' + id + ' tbody input[type="checkbox"]:checked').each(function() {
        var targetIdx = $(this).val(); // 체크박스의 값을 가져옵니다.

        // data-idx 속성이 targetIdx인 <tr> 요소를 찾아서 삭제합니다.
        $('#' + id + ' tbody tr[data-idx="' + targetIdx + '"]').remove();
        if ( id == 'aRelationTable' ) {
            var index = productPcikList.indexOf(targetIdx); // 해당 값의 인덱스를 찾습니다.
            if (index !== -1) {
                productPcikList.splice(index, 1); // 해당 인덱스에서 1개 요소를 제거합니다.
            }
        }else if( id == 'aAddGoodsTable' ){
            var index = addProductPickList.indexOf(targetIdx); // 해당 값의 인덱스를 찾습니다.
            if (index !== -1) {
                addProductPickList.splice(index, 1); // 해당 인덱스에서 1개 요소를 제거합니다.
            }
        }
    });
}

function frmRegisterConfirm( e )
{
    box_confirm('등록 하시겠습니까?', 'q', '', frmRegister, e);
}
function frmRegister(e){
    e.preventDefault();
    e.stopPropagation();
    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_register').find('input, button, select');

    $('#frm_register [name=i_description]').val( description_editor.getMarkdown() );
    $('#frm_register [name=i_content_pc]').val( contents_editor.getMarkdown() );
    $('#frm_register [name=i_content_mobile]').val( m_contents_editor.getMarkdown() );


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

    // if (isSubmit == false) {
    //     var error_message = error_lists.join('<br />');
    //     box_alert(error_message, 'e');

    //     inputs.prop('disabled', false); // 폼 요소를 다시 활성화
    //     return false;
    // }
    var formData = new FormData($('#frm_register')[0]);
    console.log( filesArray );
    filesArray.forEach((file, index) => {
        formData.append('i_goods_img[]', file);
    });
    inputs.prop('disabled', false); // 폼 요소를 다시 활성화
    // 폼 전송 로직 추가
    $.ajax({
        url: '/apis/goods/goodsRegisterProc',
        method: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {
            inputs.prop('disabled', true);
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
// 마진율 계산
function calculateMarginRate() {
    // 입력된 값을 가져오고 콤마를 제거
    var sellingPrice = parseFloat($('input[name="i_goods_price"]').val().replace(/,/g, '')) || 0;
    var costPrice = parseFloat($('input[name="i_buy_price"]').val().replace(/,/g, '')) || 0;

    if (sellingPrice > 0) {
        // 마진율 계산
        var marginRate = ((sellingPrice - costPrice) / sellingPrice) * 100;
        // 소수점 2자리까지 표시하고 입력 필드에 값 설정
        $('input[name="i_goods_price_rate"]').val(marginRate.toFixed(2));
    } else {
        $('input[name="i_goods_price_rate"]').val('');
    }
}

// keyup 이벤트 바인딩
$('input[name="i_goods_price"], input[name="i_buy_price"]').on('keyup', function() {
    calculateMarginRate();
});

function getCheckedItems(){
    var checkedValues = $('.check-item-pop:checked').map(function() {
        return $(this).val();
    }).get();

    return checkedValues;
}

function addProductItem( id ){
    let goods_idxs = getCheckedItems();


    $.ajax({
        url: '/apis/goods/goodsAddRows',
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
            if( id == 'product' ){
                goods_idxs.forEach(function(p_idx) {
                    productPcikList.push(p_idx);
                });

                $("#aRelationTable tbody").append( response.page_datas.lists_row );
            }else{
                goods_idxs.forEach(function(p_idx) {
                    addProductPickList.push(p_idx);
                });

                $("#aAddGoodsTable tbody").append( response.page_datas.lists_row );
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

function openDataLayer( gbn ){

    let data = '';
    let url  = '';
    switch( gbn ){
        case 'category':
            url = '/apis/goods/getPopCategoryManage';
            break;
        case 'brand':
            url = '/apis/goods/getPopBrandManage';
            break;
        case 'product':
            url = '/apis/goods/getPopProductLists';
            data='openGroup='+gbn+'&picLists='+productPcikList;
            break;
        case 'add_product':
            url = '/apis/goods/getPopProductLists';
            data='openGroup='+gbn+'&picLists='+addProductPickList;
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
var description_editor = new toastui.Editor({
    el: document.querySelector('#description_editor'),
    height: '200px',
    initialEditType: 'wysiwyg',
    previewStyle: 'vertical',
    hooks: {
        addImageBlobHook: function(blob, callback) {
            var reader = new FileReader();
            reader.onload = function() {
                var base64Image = reader.result.split(',')[1];
                $.ajax({
                    url: '/apis/design/writeImage',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        image: base64Image,
                        path: 'goods/editor'
                    }),
                    success: function(response) {
                        var imageUrl = response.url;
                        callback(imageUrl, 'alt text');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error uploading file:', textStatus, errorThrown);
                    }
                });
            };
            reader.readAsDataURL(blob);
        }
    }
});

var contents_editor = new toastui.Editor({
    el: document.querySelector('#contents_editor'),
    height: '250px',
    initialEditType: 'wysiwyg',
    previewStyle: 'vertical',
    hooks: {
        addImageBlobHook: function(blob, callback) {
            var reader = new FileReader();
            reader.onload = function() {
                var base64Image = reader.result.split(',')[1];
                $.ajax({
                    url: '/apis/design/writeImage',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        image: base64Image,
                        path: 'goods/editor'
                    }),
                    success: function(response) {
                        var imageUrl = response.url;
                        callback(imageUrl, 'alt text');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error uploading file:', textStatus, errorThrown);
                    }
                });
            };
            reader.readAsDataURL(blob);
        }
    }
});

var m_contents_editor = new toastui.Editor({
    el: document.querySelector('#m_contents_editor'),
    height: '250px',
    initialEditType: 'wysiwyg',
    previewStyle: 'vertical',
    hooks: {
        addImageBlobHook: function(blob, callback) {
            var reader = new FileReader();
            reader.onload = function() {
                var base64Image = reader.result.split(',')[1];
                $.ajax({
                    url: '/apis/design/writeImage',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        image: base64Image,
                        path: 'goods/editor'
                    }),
                    success: function(response) {
                        var imageUrl = response.url;
                        callback(imageUrl, 'alt text');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error uploading file:', textStatus, errorThrown);
                    }
                });
            };
            reader.readAsDataURL(blob);
        }
    }
});


var tooltipContent = {
    'sell_option_nomal': {
        title: '일반상품',
        left: 240,
        height: 120,
        content: `
            <p>실물 상품 및 일부 무형 상품(티켓 발급 등)을 포함한 대부분의 경우 해당됩니다.</p>
            <p><strong>주의:</strong> 티켓, 쿠폰 발행과 같이 무형 상품이지만, 구매 후 판매자의 처리(SMS 및 이메일 발송 등)가  <br>필요한 경우배송방법을 <span style="color: red;">배송없음</span>으로 지정하여 사용하세요.</p>
        `
    },
    'sell_option_member': {
        title: '회원그룹 이용권',
        left: 230,
        height: 290,
        content: `
        <p>회원그룹 이용권 구매자는 판매가가 상품에서 설정한 그룹으로 일정 기간 동안 유지됩니다.</p>
        <br>
        <p>
        <svg width="202" height="51" viewBox="0 0 202 51" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="0.5" y="3.5" width="47" height="47" rx="23.5" fill="white"/>
        <rect x="0.5" y="3.5" width="47" height="47" rx="23.5" stroke="#E6E7E9"/>
        <path d="M22.6758 21.4492V32.043H21.375V21.4492H22.6758ZM13.8984 29.1016C14.7422 29.0957 15.6973 29.0957 16.6992 29.0664V28.0938C15.5039 27.9062 14.7305 27.1738 14.7305 26.1719C14.7305 24.9766 15.7852 24.1797 17.332 24.1797C18.8906 24.1797 19.9688 24.9766 19.9688 26.1719C19.9688 27.168 19.1895 27.8945 18 28.082V29.0195C18.9316 28.9785 19.8691 28.9023 20.7539 28.7852L20.8477 29.7578C18.5156 30.1562 15.9961 30.1797 14.0859 30.1797L13.8984 29.1016ZM20.5664 22.668V23.6992H14.1211V22.668H16.6992V21.4844H18V22.668H20.5664ZM17.332 25.1758C16.5352 25.1875 15.9609 25.5508 15.9727 26.1719C15.9609 26.7812 16.5352 27.1445 17.332 27.1328C18.1641 27.1445 18.7148 26.7812 18.7148 26.1719C18.7148 25.5508 18.1641 25.1875 17.332 25.1758ZM33.0335 21.4609V29.4766H31.721V28.5508H29.6468V27.6367H31.721V21.4609H33.0335ZM33.2796 30.7773V31.832H25.51V28.7031H26.8108V30.7773H33.2796ZM24.1038 26.2188C26.0608 26.207 28.6858 26.1836 30.9358 25.9023L31.0061 26.8398C30.1331 26.9863 29.2307 27.0859 28.3343 27.1445V29.1836H27.0335V27.2148C26.0432 27.2559 25.0999 27.2617 24.2678 27.2617L24.1038 26.2188ZM27.5608 21.8477C29.1077 21.8477 30.1858 22.6094 30.1975 23.7227C30.1858 24.8594 29.1077 25.5859 27.5608 25.5859C25.9905 25.5859 24.9124 24.8594 24.9124 23.7227C24.9124 22.6094 25.9905 21.8477 27.5608 21.8477ZM27.5608 22.8086C26.7171 22.8086 26.1428 23.1484 26.1428 23.7227C26.1428 24.2969 26.7171 24.6367 27.5608 24.6367C28.3694 24.6367 28.9436 24.2969 28.9553 23.7227C28.9436 23.1484 28.3694 22.8086 27.5608 22.8086Z" fill="#616876"/>
        <path d="M77 26L72 23.1132V28.8868L77 26ZM48 26.5H72.5V25.5H48V26.5Z" fill="#616876"/>
        <path d="M61.4381 31.1016V31.7188C61.4381 32.3828 61.4381 33.1016 61.2428 34.1016H62.2506V34.7109H59.4146V37.6719H58.6646V34.7109H55.8678V34.1016H60.4928C60.7037 33.1055 60.7037 32.3711 60.7037 31.7188V31.7109H56.649V31.1016H61.4381ZM68.7572 30.6641V37.6641H68.0463V34.0156H67.1791V37.3203H66.476V30.7969H67.1791V33.4062H68.0463V30.6641H68.7572ZM65.6869 31.4531V35.8203H62.9682V31.4531H65.6869ZM63.6713 32.0391V35.2266H64.9916V32.0391H63.6713Z" fill="#616876"/>
        <rect x="77.5" y="3.5" width="47" height="47" rx="23.5" fill="white"/>
        <rect x="77.5" y="3.5" width="47" height="47" rx="23.5" stroke="#E6E7E9"/>
        <rect x="90" width="22" height="12" rx="6" fill="#616876"/>
        <path d="M99.8516 3.32031V4.11719C99.8516 5.0625 99.8516 6.03906 99.5938 7.52344L98.7109 7.44531C98.9844 6.05469 98.9844 5.03125 98.9844 4.11719V4.02344H94.9141V3.32031H99.8516ZM100.672 8.01562V8.72656H94.2344V8.01562H100.672ZM107.546 6.07812V6.76562H104.741V7.46875H103.882V6.76562H101.093V6.07812H107.546ZM102.78 7.125V7.76562H105.897V7.125H106.757V9.60938H101.921V7.125H102.78ZM102.78 8.94531H105.897V8.41406H102.78V8.94531ZM106.749 2.75781V4.5H102.772V5H106.89V5.64844H101.913V3.88281H105.882V3.41406H101.89V2.75781H106.749Z" fill="white"/>
        <path d="M99.6758 22.4492V33.043H98.375V22.4492H99.6758ZM90.8984 30.1016C91.7422 30.0957 92.6973 30.0957 93.6992 30.0664V29.0938C92.5039 28.9062 91.7305 28.1738 91.7305 27.1719C91.7305 25.9766 92.7852 25.1797 94.332 25.1797C95.8906 25.1797 96.9688 25.9766 96.9688 27.1719C96.9688 28.168 96.1895 28.8945 95 29.082V30.0195C95.9316 29.9785 96.8691 29.9023 97.7539 29.7852L97.8477 30.7578C95.5156 31.1562 92.9961 31.1797 91.0859 31.1797L90.8984 30.1016ZM97.5664 23.668V24.6992H91.1211V23.668H93.6992V22.4844H95V23.668H97.5664ZM94.332 26.1758C93.5352 26.1875 92.9609 26.5508 92.9727 27.1719C92.9609 27.7812 93.5352 28.1445 94.332 28.1328C95.1641 28.1445 95.7148 27.7812 95.7148 27.1719C95.7148 26.5508 95.1641 26.1875 94.332 26.1758ZM110.033 22.4609V30.4766H108.721V29.5508H106.647V28.6367H108.721V22.4609H110.033ZM110.28 31.7773V32.832H102.51V29.7031H103.811V31.7773H110.28ZM101.104 27.2188C103.061 27.207 105.686 27.1836 107.936 26.9023L108.006 27.8398C107.133 27.9863 106.231 28.0859 105.334 28.1445V30.1836H104.033V28.2148C103.043 28.2559 102.1 28.2617 101.268 28.2617L101.104 27.2188ZM104.561 22.8477C106.108 22.8477 107.186 23.6094 107.198 24.7227C107.186 25.8594 106.108 26.5859 104.561 26.5859C102.991 26.5859 101.912 25.8594 101.912 24.7227C101.912 23.6094 102.991 22.8477 104.561 22.8477ZM104.561 23.8086C103.717 23.8086 103.143 24.1484 103.143 24.7227C103.143 25.2969 103.717 25.6367 104.561 25.6367C105.369 25.6367 105.944 25.2969 105.955 24.7227C105.944 24.1484 105.369 23.8086 104.561 23.8086Z" fill="#616876"/>
        <path d="M154 26L149 23.1132V28.8868L154 26ZM125 26.5H149.5V25.5H125V26.5Z" fill="#616876"/>
        <path d="M136.415 31.2812V34.5469H133.141V31.2812H136.415ZM133.868 31.8828V33.9609H135.688V31.8828H133.868ZM138.368 30.6562V32.75H139.383V33.375H138.368V35.7891H137.626V30.6562H138.368ZM138.665 36.9141V37.5156H133.93V35.2969H134.672V36.9141H138.665ZM146.14 36.2031V36.8281H139.734V36.2031H141.484V34.9688H140.515V32.7422H144.624V31.7656H140.499V31.1562H145.359V33.3438H141.249V34.3594H145.523V34.9688H144.46V36.2031H146.14ZM142.203 36.2031H143.734V34.9688H142.203V36.2031Z" fill="#616876"/>
        <rect x="154.5" y="3.5" width="47" height="47" rx="23.5" fill="white"/>
        <rect x="154.5" y="3.5" width="47" height="47" rx="23.5" stroke="#E6E7E9"/>
        <path d="M176.676 21.4492V32.043H175.375V21.4492H176.676ZM167.898 29.1016C168.742 29.0957 169.697 29.0957 170.699 29.0664V28.0938C169.504 27.9062 168.73 27.1738 168.73 26.1719C168.73 24.9766 169.785 24.1797 171.332 24.1797C172.891 24.1797 173.969 24.9766 173.969 26.1719C173.969 27.168 173.189 27.8945 172 28.082V29.0195C172.932 28.9785 173.869 28.9023 174.754 28.7852L174.848 29.7578C172.516 30.1562 169.996 30.1797 168.086 30.1797L167.898 29.1016ZM174.566 22.668V23.6992H168.121V22.668H170.699V21.4844H172V22.668H174.566ZM171.332 25.1758C170.535 25.1875 169.961 25.5508 169.973 26.1719C169.961 26.7812 170.535 27.1445 171.332 27.1328C172.164 27.1445 172.715 26.7812 172.715 26.1719C172.715 25.5508 172.164 25.1875 171.332 25.1758ZM187.033 21.4609V29.4766H185.721V28.5508H183.647V27.6367H185.721V21.4609H187.033ZM187.28 30.7773V31.832H179.51V28.7031H180.811V30.7773H187.28ZM178.104 26.2188C180.061 26.207 182.686 26.1836 184.936 25.9023L185.006 26.8398C184.133 26.9863 183.231 27.0859 182.334 27.1445V29.1836H181.033V27.2148C180.043 27.2559 179.1 27.2617 178.268 27.2617L178.104 26.2188ZM181.561 21.8477C183.108 21.8477 184.186 22.6094 184.198 23.7227C184.186 24.8594 183.108 25.5859 181.561 25.5859C179.991 25.5859 178.912 24.8594 178.912 23.7227C178.912 22.6094 179.991 21.8477 181.561 21.8477ZM181.561 22.8086C180.717 22.8086 180.143 23.1484 180.143 23.7227C180.143 24.2969 180.717 24.6367 181.561 24.6367C182.369 24.6367 182.944 24.2969 182.955 23.7227C182.944 23.1484 182.369 22.8086 181.561 22.8086Z" fill="#616876"/>
        </svg>
        </p>
        <br>
        <p><strong>활용예시</strong></p>
        <br>
        <p> • 유료 커뮤니티(구매한 회원만 게시글 조회)</p>
        <p> • 온라인 결제로 회원관리</p>
        <p>이용권은 구매 시각에 관계 없이 만료일의 23시 59분에 만료됩니다.</p>
        <br>
        <p><strong>참조 : </strong> 네이버페이 정책상 네이버페이 구매 버튼이 항상 숨김처리 됩니다.</p>
        `
    },
    'regist_gbn': {
        title: '등록방식',
        left: 180,
        height: 260,
        content: `
        <p><strong>상호등록</strong></p>
        <br>
        <p> • A상품에 B상품을 연관상품으로 등록한 경우 A와 B상품 모두에</p>
        <p>연관상품으로 등록됩니다.</p>
        <p> • 상호등록된 연관상품을 삭제할 경우 A와 B 모두 삭제됩니다.</p>
        <br>
        <p><strong>일방등록</strong></p>
        <br>
        <p> • A상품에 B상품을 연관상품으로 등록한 경우 A상품에만 연관상품으로</p>
        <p>등록됩니다.</p>
        <p> • B에서는 A상품이 연관상품으로 표시되지 않습니다.</p>
        `
    },
    'category':{
        title: '카테고리',
        left : 160,
        height: 130,
        content: `
        <p>카테고리 등록 시 상위카테고리는 자동 등록되며, 등록된 카테</p>
        <p>고리에 상품이 노출됩니다. 상품 노출을 원하지 않는 카테고리는 </p>
        <p>‘삭제’버튼을 이용하여 삭제할 수 있습니다. 등록하신 카테고리들</p>
        <p>중 체크된 카테고리가 대표 카테고리로 설정됩니다.</p>
        `
    },
    'icon':{
        title: '아이콘',
        left : 150,
        height: 130,
        content: `
        <p>상품에 세일, 이벤트 등의 정보를 알려주는 아이콘을 </p>
        <p>표시할 수 있습니다.  "상품 > 상품 관리 > 상품 아이콘</p>
        <p> 관리"에 등록된 아이콘이 노출되므로, 아이콘 노출설</p>
        <p>정을 하기 이전에 아이콘이 등록되어 있어야 합니다.</p>
        `
    },
};

function addRows( gbn ){
    if( gbn == 'reqInfo' ){
        var html = `
        <tr>
            <td><input type="text" class="form-control" name="i_req_info_keys[]"></td>
            <td><input type="text" class="form-control" name="i_req_info_values[]"></td>
            <td>
            <?php
                echo getIconAnchor([
                    'txt' => '',
                    'icon' => 'delete',
                    'buttonClass' => '',
                    'buttonStyle' => '',
                    'width' => '24',
                    'height' => '24',
                    'stroke' => '#616876',
                    'extra' => [
                        'onclick' => 'deleteRows(this);',
                    ]
                ]);
            ?>
            </td>
        </tr>
        `;
        $("#aListsTable tbody").append(html);
    }else if( gbn == 'options' ){
        var html = `
        <tr>
            <td><input type="text" class="form-control option-key" name="i_option_keys[]"></td>
            <td><input type="text" class="form-control option-value" name="i_option_value[]"></td>
            <td><input type="text" class="form-control option-stock" name="i_option_stock[]"></td>
            <td><input type="text" class="form-control option-add_amt" numberwithcomma name="i_option_add_price[]"></td>
            <td>
                <?php
                    $options  = ['Y'=>'노출','N'=>'비노출'];
                    $extras   = ['id' => 'i_option_status[]', 'class' => 'form-select', 'style' => 'max-width: 80px;margin-right:2.235em;'];
                    $selected = '';
                    echo getSelectBox('i_option_status[]', $options, $selected, $extras);
                ?>
            </td>
            <td>
            <?php
                echo getIconAnchor([
                    'txt' => '',
                    'icon' => 'delete',
                    'buttonClass' => '',
                    'buttonStyle' => '',
                    'width' => '24',
                    'height' => '24',
                    'stroke' => '#616876',
                    'extra' => [
                        'onclick' => 'deleteRows(this);',
                    ]
                ]);
            ?>
            </td>
        </tr>
        `;
        $("#aOptionTable tbody").append(html);

    }else if( gbn == 'text_options' ){
        var html = `
        <tr>
            <td><input type="text" class="form-control option-key" name="i_text_option_keys[]"></td>
            <td><select name="i_text_option_type[]" class="form-select"><option value="text">text</option><option value="select">select</option><option value="radio">radio</option><option value="checkbox">checkbox</option></select></td>
            <td class="options-type-set-input"></td>
            <td class="options-type-set-buttons" style="text-align: right;vertical-align:top;"></td>
            <td>
            <?php
                echo getIconAnchor([
                    'txt' => '',
                    'icon' => 'delete',
                    'buttonClass' => '',
                    'buttonStyle' => '',
                    'width' => '24',
                    'height' => '24',
                    'stroke' => '#616876',
                    'extra' => [
                        'onclick' => 'deleteRows(this);',
                    ]
                ]);
            ?>
            </td>
        </tr>
        `;
        $("#aTextOptionTable tbody").append(html);
    }

}


$(document).on('change', 'select[name="i_text_option_type[]"]', function() {
    var $row = $(this).closest('tr');
    var rowIndex = $row.index(); // 행 인덱스
    var $optionsSetInput = $row.find('.options-type-set-input');
    var $optionsSetButtons = $row.find('.options-type-set-buttons');
    var selectedType = $(this).val();

    if (selectedType == 'text') {
        // 'text' 타입일 때는 추가된 input 및 버튼 모두 삭제
        $optionsSetInput.empty();
        $optionsSetButtons.empty();
    } else if (selectedType == 'select' || selectedType == 'radio' || selectedType == 'checkbox') {
        // 'select', 'radio', 'checkbox' 타입일 때는 input 필드와 버튼 추가
        var inputHtml = `
            <div class="option-input-group">
                <input type="text" class="form-control option-extra-input" name="i_text_option_extra[${rowIndex}][]" placeholder="옵션 추가">
            </div>
        `;
        var buttonHtml = `
            <div class="button-group">
                <svg xmlns="http://www.w3.org/2000/svg" class="btn-add-option" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm0 1a6 6 0 1 1 0 12A6 6 0 0 1 8 2z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3H4a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="btn-remove-option" width="16" height="16" fill="currentColor" class="bi bi-dash-circle" viewBox="0 0 16 16">
                    <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm0 1a6 6 0 1 1 0 12A6 6 0 1 1 8 2z"/>
                    <path d="M5 8.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </div>
        `;
        $optionsSetInput.html(inputHtml);
        $optionsSetButtons.html(buttonHtml);
    }
});

// '추가' 버튼 클릭 시 input 추가
$(document).on('click', '.btn-add-option', function() {
    var $row = $(this).closest('tr');
    var rowIndex = $row.index(); // 행 인덱스
    var $optionsSetInput = $row.find('.options-type-set-input'); // input 필드가 들어갈 div
    var inputHtml = `
        <div class="option-input-group">
            <input type="text" class="form-control option-extra-input" name="i_text_option_extra[${rowIndex}][]" placeholder="옵션 추가">
        </div>
    `;
    $optionsSetInput.append(inputHtml);
});

// '삭제' 버튼 클릭 시 마지막 input 삭제
$(document).on('click', '.btn-remove-option', function() {
    var $row = $(this).closest('tr');
    var $optionsSetInput = $row.find('.options-type-set-input'); // input 필드가 들어갈 div
    $optionsSetInput.find('.option-input-group').last().remove();
});




// Option stock 합계를 계산하는 함수
function updateGoodsStock() {
    let totalStock = 0;

    // 모든 option_stock[] 필드를 순회하며 합계 계산
    $("input[name='i_option_stock[]']").each(function() {
        let value = parseInt($(this).val(), 10);
        if (!isNaN(value)) {
            totalStock += value;
        }
    });

    // i_goods_stock 필드에 총 합계 반영
    $("input[name='i_goods_stock']").val(totalStock);
}

// option_stock[] 필드 값이 변경될 때 이벤트 핸들러 연결
$(document).on('input', "input[name='i_option_stock[]']", function() {
    updateGoodsStock();
});

// 삭제 버튼 클릭 시 행 삭제
function deleteRows(obj) {
    $(obj).closest('tr').remove();
    updateGoodsStock(); // 행 삭제 후 재고 합계 업데이트
}

function addTargetRows(){
    var html = `
    <div class="input-group required" style="max-width:100%">
        <?php
            $options  = $aMemberGrade;
            $extras   = ['id' => 'i_discount_mb_group[]', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:2.235em;'];
            $selected = '';
            echo getSelectBox('i_discount_mb_group[]', $options, $selected, $extras);
        ?>
        <input type="text" name="i_discount_mb_group_amt[]" class="form-control">
        <?php
            $options  = ['per' => '%' , 'won' => '원'];
            $extras   = ['id' => 'i_discount_mb_group_amt_unit[]', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-left:2.235em;'];
            $selected = '';
            echo getSelectBox('i_discount_mb_group_amt_unit[]', $options, $selected, $extras);
        ?>

        <div class="input-icon" style="padding-left:0.5rem">
            <input type="text" class="form-control  datepicker-icon"
                name="i_discount_start_date[]" id="i_discount_start_date[]" readonly>
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
        </div> <span style="margin: 0.4rem">~</span>
        <div class="input-icon">
            <input type="text" class="form-control datepicker-icon"
                name="i_discount_end_date[]" id="i_discount_end_date[]" readonly>
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
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin-left:3rem" onclick="$(this).closest('.input-group').remove()">
            <path d="M3 12C3 13.1819 3.23279 14.3522 3.68508 15.4442C4.13738 16.5361 4.80031 17.5282 5.63604 18.364C6.47177 19.1997 7.46392 19.8626 8.55585 20.3149C9.64778 20.7672 10.8181 21 12 21C13.1819 21 14.3522 20.7672 15.4442 20.3149C16.5361 19.8626 17.5282 19.1997 18.364 18.364C19.1997 17.5282 19.8626 16.5361 20.3149 15.4442C20.7672 14.3522 21 13.1819 21 12C21 10.8181 20.7672 9.64778 20.3149 8.55585C19.8626 7.46392 19.1997 6.47177 18.364 5.63604C17.5282 4.80031 16.5361 4.13738 15.4442 3.68508C14.3522 3.23279 13.1819 3 12 3C10.8181 3 9.64778 3.23279 8.55585 3.68508C7.46392 4.13738 6.47177 4.80031 5.63604 5.63604C4.80031 6.47177 4.13738 7.46392 3.68508 8.55585C3.23279 9.64778 3 10.8181 3 12Z" fill="white"/>
            <path d="M10 10L14 14L10 10ZM14 10L10 14L14 10Z" fill="white"/>
            <path d="M10 10L14 14M14 10L10 14M3 12C3 13.1819 3.23279 14.3522 3.68508 15.4442C4.13738 16.5361 4.80031 17.5282 5.63604 18.364C6.47177 19.1997 7.46392 19.8626 8.55585 20.3149C9.64778 20.7672 10.8181 21 12 21C13.1819 21 14.3522 20.7672 15.4442 20.3149C16.5361 19.8626 17.5282 19.1997 18.364 18.364C19.1997 17.5282 19.8626 16.5361 20.3149 15.4442C20.7672 14.3522 21 13.1819 21 12C21 10.8181 20.7672 9.64778 20.3149 8.55585C19.8626 7.46392 19.1997 6.47177 18.364 5.63604C17.5282 4.80031 16.5361 4.13738 15.4442 3.68508C14.3522 3.23279 13.1819 3 12 3C10.8181 3 9.64778 3.23279 8.55585 3.68508C7.46392 4.13738 6.47177 4.80031 5.63604 5.63604C4.80031 6.47177 4.13738 7.46392 3.68508 8.55585C3.23279 9.64778 3 10.8181 3 12Z" stroke="#616876" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    `;
    $('#point_save_list').append( html );
    flatpickr('.datepicker-icon', {
        enableTime: true,
        dateFormat: 'Y-m-d',
        time_24hr: true
    });
    sameSalePeriod();
}

flatpickr('.datetimepicker', {
    enableTime: true,
    dateFormat: 'Y-m-d H:i',
    time_24hr: true
});
flatpickr('.datepicker-icon', {
    enableTime: true,
    dateFormat: 'Y-m-d',
    time_24hr: true
});

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

// 체크박스 클릭 시 선택된 항목을 표시
$(document).on('change', '#dropdown-layer-category input[type="checkbox"]', function() {
    var selectedCategory = $("#select-category");

    var value = $(this).val();
    var id = $(this).attr('id');
    var txt= $(this).attr('data-txt');

    if ($(this).is(':checked')) {
        var listItem = `
        <div class="dropdown-list-item" data-id="${id}">
            <div class="dropdown-content">
                <div class="left-content">
                    <?php
                        $setParam = [
                            'name' => 'i_is_category_main',
                            'id' => 'i_is_category_main${id}',
                            'value' => '${value}',
                            'label' => '',
                            'checked' => 'checked',
                            'extraAttributes' => [
                            ]
                        ];
                        echo getRadioButton($setParam);
                    ?>
                </div>
                <div class="center-content">
                    <div class="text">${txt}</div>
                </div>
                <div class="right-content">
                    <svg class="tabler-icon-circle-x" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 10C1 11.1819 1.23279 12.3522 1.68508 13.4442C2.13738 14.5361 2.80031 15.5282 3.63604 16.364C4.47177 17.1997 5.46392 17.8626 6.55585 18.3149C7.64778 18.7672 8.8181 19 10 19C11.1819 19 12.3522 18.7672 13.4442 18.3149C14.5361 17.8626 15.5282 17.1997 16.364 16.364C17.1997 15.5282 17.8626 14.5361 18.3149 13.4442C18.7672 12.3522 19 11.1819 19 10C19 8.8181 18.7672 7.64778 18.3149 6.55585C17.8626 5.46392 17.1997 4.47177 16.364 3.63604C15.5282 2.80031 14.5361 2.13738 13.4442 1.68508C12.3522 1.23279 11.1819 1 10 1C8.8181 1 7.64778 1.23279 6.55585 1.68508C5.46392 2.13738 4.47177 2.80031 3.63604 3.63604C2.80031 4.47177 2.13738 5.46392 1.68508 6.55585C1.23279 7.64778 1 8.8181 1 10Z" fill="white"/>
                        <path d="M8 8L12 12L8 8ZM12 8L8 12L12 8Z" fill="white"/>
                        <path d="M8 8L12 12M12 8L8 12M1 10C1 11.1819 1.23279 12.3522 1.68508 13.4442C2.13738 14.5361 2.80031 15.5282 3.63604 16.364C4.47177 17.1997 5.46392 17.8626 6.55585 18.3149C7.64778 18.7672 8.8181 19 10 19C11.1819 19 12.3522 18.7672 13.4442 18.3149C14.5361 17.8626 15.5282 17.1997 16.364 16.364C17.1997 15.5282 17.8626 14.5361 18.3149 13.4442C18.7672 12.3522 19 11.1819 19 10C19 8.8181 18.7672 7.64778 18.3149 6.55585C17.8626 5.46392 17.1997 4.47177 16.364 3.63604C15.5282 2.80031 14.5361 2.13738 13.4442 1.68508C12.3522 1.23279 11.1819 1 10 1C8.8181 1 7.64778 1.23279 6.55585 1.68508C5.46392 2.13738 4.47177 2.80031 3.63604 3.63604C2.80031 4.47177 2.13738 5.46392 1.68508 6.55585C1.23279 7.64778 1 8.8181 1 10Z" stroke="#616876" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
        </div>
        `;

        selectedCategory.append(listItem);
    } else {
        selectedCategory.find('.dropdown-list-item[data-id="'+ id +'"]').remove();
    }
});
$(document).on('change', '#dropdown-layer-brand input[type="radio"]', function() {
    var selectedCategory = $("#select-brand");

    var value = $(this).val();
    var id = $(this).attr('id');
    var txt= $(this).attr('data-txt');

    if ($(this).is(':checked')) {
        var listItem = `
        <div class="dropdown-list-item" data-id="${id}">
            <div class="dropdown-content">
                <div class="left-content">
                    <?php
                        $setParam = [
                            'name' => 'i_is_brand_main',
                            'id' => 'i_is_brand_main${id}',
                            'value' => '${value}',
                            'label' => '',
                            'checked' => 'checked',
                            'extraAttributes' => [
                            ]
                        ];
                        echo getRadioButton($setParam);
                    ?>
                </div>
                <div class="center-content">
                    <div class="text">${txt}</div>
                </div>
                <div class="right-content">
                    <svg class="tabler-icon-circle-x" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 10C1 11.1819 1.23279 12.3522 1.68508 13.4442C2.13738 14.5361 2.80031 15.5282 3.63604 16.364C4.47177 17.1997 5.46392 17.8626 6.55585 18.3149C7.64778 18.7672 8.8181 19 10 19C11.1819 19 12.3522 18.7672 13.4442 18.3149C14.5361 17.8626 15.5282 17.1997 16.364 16.364C17.1997 15.5282 17.8626 14.5361 18.3149 13.4442C18.7672 12.3522 19 11.1819 19 10C19 8.8181 18.7672 7.64778 18.3149 6.55585C17.8626 5.46392 17.1997 4.47177 16.364 3.63604C15.5282 2.80031 14.5361 2.13738 13.4442 1.68508C12.3522 1.23279 11.1819 1 10 1C8.8181 1 7.64778 1.23279 6.55585 1.68508C5.46392 2.13738 4.47177 2.80031 3.63604 3.63604C2.80031 4.47177 2.13738 5.46392 1.68508 6.55585C1.23279 7.64778 1 8.8181 1 10Z" fill="white"/>
                        <path d="M8 8L12 12L8 8ZM12 8L8 12L12 8Z" fill="white"/>
                        <path d="M8 8L12 12M12 8L8 12M1 10C1 11.1819 1.23279 12.3522 1.68508 13.4442C2.13738 14.5361 2.80031 15.5282 3.63604 16.364C4.47177 17.1997 5.46392 17.8626 6.55585 18.3149C7.64778 18.7672 8.8181 19 10 19C11.1819 19 12.3522 18.7672 13.4442 18.3149C14.5361 17.8626 15.5282 17.1997 16.364 16.364C17.1997 15.5282 17.8626 14.5361 18.3149 13.4442C18.7672 12.3522 19 11.1819 19 10C19 8.8181 18.7672 7.64778 18.3149 6.55585C17.8626 5.46392 17.1997 4.47177 16.364 3.63604C15.5282 2.80031 14.5361 2.13738 13.4442 1.68508C12.3522 1.23279 11.1819 1 10 1C8.8181 1 7.64778 1.23279 6.55585 1.68508C5.46392 2.13738 4.47177 2.80031 3.63604 3.63604C2.80031 4.47177 2.13738 5.46392 1.68508 6.55585C1.23279 7.64778 1 8.8181 1 10Z" stroke="#616876" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
        </div>
        `;

        selectedCategory.html(listItem);
    } else {
        selectedCategory.find('.dropdown-list-item[data-id="'+ id +'"]').remove();
    }
});


// X 버튼 클릭 시 태그 제거 및 체크박스 해제
$(document).on('click', '.tabler-icon-circle-x', function() {
    var listItem = $(this).closest('.dropdown-list-item');
    var id = listItem.data('id');
    $('#'+id).prop('checked', false);
    listItem.remove();
});


/* 색상 선택radio박스*/
const colorOptions = document.querySelectorAll('input[name="color"]');
const selectedColorText = document.getElementById('selected-color');

colorOptions.forEach(option => {
    option.addEventListener('change', () => {
        selectedColorText.textContent = `You have selected: ${option.value}`;
    });
});


/*외부 파일 선택시 미리보기 넣기*/
$('[name=i_out_main_img]').on('change', function(event) {
    var file = event.target.files[0]; // 선택한 파일을 가져옵니다.

    if (file) {
        var reader = new FileReader(); // FileReader 객체를 생성합니다.

        reader.onload = function(e) {
            // 파일이 로드되면 미리보기 이미지를 생성합니다.
            var img = $('<img>').attr('src', e.target.result).css({
                'max-width': '100%', // 이미지를 부모 요소의 너비에 맞추도록 합니다.
                'height': 'auto' // 이미지의 비율을 유지하며 높이를 자동으로 조정합니다.
            });

            // 기존 이미지가 있으면 제거하고 새 이미지를 추가합니다.
            $('#i_out_main_img_view').empty().append(img);
        }

        reader.readAsDataURL(file); // 파일을 읽고, Data URL로 변환합니다.
    } else {
        // 파일이 없을 경우 미리보기를 비웁니다.
        $('#i_out_main_img_view').empty();
    }
});



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
function getBrandDropDown()
{
    $.ajax({
        url: '/apis/goods/getBrandDropDown',
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

            $('#dropdown-layer-brand').empty().html( response.page_datas.detail );
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
function getGoodsIconGroup()
{
    $.ajax({
        url: '/apis/goods/getGoodsIconGroup',
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
            $('#aIconTable_P tbody').empty();
            $('#aIconTable_L tbody').empty();
            if( response.page_datas.lists.L != undefined && response.page_datas.lists.L.length > 0 ){
                $.each(response.page_datas.lists.L, function( k, dt ){
                    $('#aIconTable_L tbody').append(`
                    <tr>
                        <td style="padding:2px 0 5px 0;">
                            <?php
                                $setParam = [
                                    'name' => 'i_icon_select[${dt.I_IDX}]',
                                    'id' => 'i_icon_select_${dt.I_IDX}',
                                    'value' => '${dt.I_IDX}',
                                    'label' => '',
                                    'checked' => '',
                                    'extraAttributes' => [

                                    ]
                                ];
                                echo getCheckBox($setParam);
                            ?>
                        </td>
                        <td style="padding:0;">
                            <div style="width:100%; text-align:center;padding-top:0.5rem" >
                                <img src='/${dt.I_IMG_PATH}'>
                            </div>
                        </td>
                    </tr>
                    `);
                });
            }
            if( response.page_datas.lists.P != undefined && response.page_datas.lists.P.length > 0 ){
                $.each(response.page_datas.lists.P, function( k, dt ){
                    $('#aIconTable_P tbody').append(`
                    <tr>
                        <td style="padding:2px 0 5px 0;">
                            <?php
                                $setParam = [
                                    'name' => 'i_icon_select[${dt.I_IDX}]',
                                    'id' => 'i_icon_select_${dt.I_IDX}',
                                    'value' => '${dt.I_IDX}',
                                    'label' => '',
                                    'checked' => '',
                                    'extraAttributes' => [
                                    ]
                                ];
                                echo getCheckBox($setParam);
                            ?>
                        </td>
                        <td style="padding:0;">
                            <div style="width:100%; text-align:center;padding-top:0.5rem" >
                                <img src='/${dt.I_IMG_PATH}'>
                                <div style="width:100%;float:left:padding:0;padding-top:0.4rem;padding-bottom:0.4rem;text-align:left">
                                    <input type='text' style="border:0;width:74px;text-align:center" class="datepicker-icon" name="i_icon_selct_start_at[${dt.I_IDX}]" value="" placeholder="시작일" >~<input type='text' style="border:0;width:74px;text-align:center" class="datepicker-icon" name="i_icon_selct_end_at[${dt.I_IDX}]" value=""  placeholder="종료일">
                                </div>
                            </div>
                        </td>
                    </tr>
                    `);
                });
            }
            flatpickr('.datepicker-icon', {
                enableTime: true,
                dateFormat: 'Y-m-d',
                time_24hr: true
            });



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
function sameSalePeriod()
{
    // i_sale_priod_flag가 체크된 상태인지 확인
    if ($('input[name="i_sale_priod_flag"]').is(':checked')) {
        // s_start_date와 s_end_date 값을 가져옴
        var startDate = $('input[name="s_start_date"]').val();
        var endDate = $('input[name="s_end_date"]').val();

        // i_discount_start_date[]와 i_discount_end_date[] 값을 설정
        $('input[name="i_discount_start_date[]"]').val(startDate);
        $('input[name="i_discount_end_date[]"]').val(endDate);
    }

}
$(document).on('click', '#checkAllPro', function(){
    var checkAllPro = document.querySelector('#checkAllPro');
    var checkItemsPro = document.querySelectorAll('.check-item-pro');

    if (checkAllPro && checkItemsPro.length > 0) {
        checkAllPro.addEventListener('change', function () {
            var isCheckedPro = this.checked;
            checkItemsPro.forEach(function (checkbox) {
                checkbox.checked = isCheckedPro;
            });
        });

        // 개별 체크박스 체크/체크 해제 시 전체 체크박스 상태 업데이트
        checkItemsPro.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                var allCheckedPro = document.querySelectorAll('.check-item-pro:checked').length === checkItemsPro.length;
                checkAllPro.checked = allCheckedPro;
            });
        });
    }
});
$(document).on('click', '#checkAllAdd', function(){
    var checkAllAdd = document.querySelector('#checkAllAdd');
    var checkItemsAdd = document.querySelectorAll('.check-item-add');

    if (checkAllAdd && checkItemsAdd.length > 0) {
        checkAllAdd.addEventListener('change', function () {
            var isCheckedAdd = this.checked;
            checkItemsAdd.forEach(function (checkbox) {
                checkbox.checked = isCheckedAdd;
            });
        });

        // 개별 체크박스 체크/체크 해제 시 전체 체크박스 상태 업데이트
        checkItemsAdd.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                var allCheckedAdd = document.querySelectorAll('.check-item-add:checked').length === checkItemsAdd.length;
                checkAllAdd.checked = allCheckedAdd;
            });
        });
    }
});

$(document).on( 'change', 'input[name="i_sale_priod_flag"]', function(){
    sameSalePeriod();
})
$(document).on( 'change', 'input[name="s_start_date"], input[name="s_end_date"]', function(){
    sameSalePeriod();
})
function _initFunc(){
    getCategoryDropDown();
    getBrandDropDown();
    getGoodsIconGroup();

}
_initFunc();
</script>




<?php

// $owensView->setFooterJs('/assets/js/goods/goods/tooltip.js');
//$owensView->setFooterJs('/assets/js/goods/goods/register.js');
$owensView->setFooterJs('/assets/js/goods/goods/imageUpload.js');


$script = "
";

$owensView->setFooterScript($script);