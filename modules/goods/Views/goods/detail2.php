<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }
    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];

    $aConfig          = _elm($pageDatas, 'aConfig', []);

    $aGetData         = _elm( $pageDatas, 'getData', [] );
    $aIconsData       = _elm( $pageDatas, 'iconsData' );

    $aMemberGrade     = _elm( $pageDatas, 'aMemberGrade', [] );

    $aColorConfig     = _elm( $pageDatas, 'aColorConfig', [] );
    $aDefaultTxtConfig= _elm( $pageDatas, 'aDefaultTxtConfig', [] );
    $aGoodsCondition  = _elm( $pageDatas, 'aGoodsCondition', [] );
    $aGoodsProductType= _elm( $pageDatas, 'aGoodsProductType', [] );
    $aGoodsSellType   = _elm( $pageDatas, 'aGoodsSellType', [] );
    $aDatas            = _elm( $pageDatas, 'aDatas', [] );

    // echo "<pre>";
    // print_R($aColorConfig);
    // echo "</pre>";

?>
<link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
 <!-- Toast UI Editor JS -->
<link
rel="stylesheet"
href="https://uicdn.toast.com/tui-color-picker/latest/tui-color-picker.min.css"
/>
<link
rel="stylesheet"
href="https://uicdn.toast.com/editor-plugin-color-syntax/latest/toastui-editor-plugin-color-syntax.min.css"
/>

<script src="https://uicdn.toast.com/tui-color-picker/latest/tui-color-picker.min.js"></script>

<!-- Editor's Plugin -->
<script src="https://uicdn.toast.com/editor-plugin-color-syntax/latest/toastui-editor-plugin-color-syntax.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.0/purify.min.js"></script>

<!-- 토스트 UI 에디터 코어 -->
<script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css" />

<!-- 토스트 UI 에디터 플러그인, 컬러피커 -->
<link rel="stylesheet" href="https://uicdn.toast.com/tui-color-picker/latest/tui-color-picker.css" />
<script src="https://uicdn.toast.com/tui-color-picker/latest/tui-color-picker.min.js"></script>

<link rel="stylesheet" href="https://uicdn.toast.com/editor-plugin-color-syntax/latest/toastui-editor-plugin-color-syntax.min.css" />
<script src="https://uicdn.toast.com/editor-plugin-color-syntax/latest/toastui-editor-plugin-color-syntax.min.js"></script>

<!-- 토스트 UI 차트 -->
<link rel="stylesheet" href="https://uicdn.toast.com/chart/latest/toastui-chart.css">
<script src="https://uicdn.toast.com/chart/latest/toastui-chart.js"></script>
<!-- 토스트 UI 차트와 토스트 UI 에디터를 연결  -->
<script src="https://uicdn.toast.com/editor-plugin-chart/latest/toastui-editor-plugin-chart.min.js"></script>

<!-- 토스트 UI 에디터 플러그인, 코드 신텍스 하이라이터 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism.min.css">
<link rel="stylesheet" href="https://uicdn.toast.com/editor-plugin-code-syntax-highlight/latest/toastui-editor-plugin-code-syntax-highlight.min.css">
<script src="https://uicdn.toast.com/editor-plugin-code-syntax-highlight/latest/toastui-editor-plugin-code-syntax-highlight-all.min.js"></script>

<!-- 토스트 UI 에디터 플러그인, 테이블 셀 병합 -->
<script src="https://uicdn.toast.com/editor-plugin-table-merged-cell/latest/toastui-editor-plugin-table-merged-cell.min.js"></script>

<!-- 토스트 UI 에디터 플러그인, katex -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.13.13/katex.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.13.13/katex.min.css">

<!-- 토스트 UI 에디터 플러그인, UML -->
<script src="https://uicdn.toast.com/editor-plugin-uml/latest/toastui-editor-plugin-uml.min.js"></script>

<!-- docpurify -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.8/purify.min.js"></script>
<!-- 토스트 UI 에디터 의존성 끝 -->
<link href="/plugins/select2/select2.css" rel="stylesheet" />
<script src="/plugins/select2/select2.js"></script>

<style>
    /* 토스트에디터-유튜브 플러그인 시작 */
.toast-ui-youtube-plugin-wrap{
  max-width:500px;
  margin-left:auto;
  margin-right:auto;
  position:relative;
}

.toast-ui-youtube-plugin-wrap::before{
  content:"";
  display:block;
  padding-top:calc(100% / 16 * 9);
}

.toast-ui-youtube-plugin-wrap > iframe{
  position:absolute;
  top:0;
  left:0;
  width:100%;
  height:100%;
}
.toastui-editor-toolbar-icons.youtube-icon::before {
    content: '🎥'; /* 유튜브 아이콘 대체 */
    font-size: 14px;
    margin-right: 4px;
}
.select2-container--default .select2-selection--single {
    height: auto;
    padding: 0.275rem 0.75rem;
    border: var(--tblr-border-width) solid var(--tblr-border-color);
    border-radius: 0.25rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100%;
    right: 10px;
}

/* Select2 드롭다운 옵션 스타일 */
.select2-container .select2-dropdown {
    border-radius: 0.25rem;
    border: 1px solid #ced4da;
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}

.select2-results__option {
    padding: 0.175rem 0.35rem;
}
/* 꺽쇠 아이콘 제거 */
.select2-container--default .select2-selection--single .select2-selection__arrow {
    display: none !important;  /* 화살표 아이콘 영역 자체를 숨김 */
}
.select2-results__option[aria-selected=true] {
    background-color: #f8f9fa;
    color: #495057;
}
.form-select2 {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem; /* padding을 조정해 아이콘 위치를 고려하지 않음 */
    -moz-padding-start: calc(0.75rem - 3px);
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #fff;
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: none !important; /* 기본 화살표 제거 */

}
</style>
<style>
.toastui-editor-toolbar-item-wrapper{
    height: 32px;
    line-height: 32px;
    margin: 1px 5px;
}
</style>
<style>
.group-move-icons {
    position: absolute;
    top: 60%; /* 부모 요소의 정중앙에 배치 */
    left: 50%;
    transform: translate(-50%, -60%); /* 가운데 정렬 + 위로 살짝 이동 */
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
}

td:hover .group-move-icons {
    visibility: visible;
    opacity: 1;
    transform: translate(-50%, -80%); /* hover 시 살짝 더 위로 이동 */
}
</style>
<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_modify', 'autocomplete' => 'off', 'enctype'=>"multipart/form-data" ]); ?>
<input type="hidden" name="i_goods_idx" value="<?php echo _elm( $aDatas, 'G_IDX' )?>">
<input type="hidden" name="i_description">
<input type="hidden" name="i_content_pc">
<input type="hidden" name="i_content_mobile">
<input type="hidden" name="i_relation_goods_idxs">
<input type="hidden" name="i_add_goods_idxs">



<!-- 본문 -->
<div class="container-fluid">
    <!-- 카트 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">상품 수정</h3>
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
                'onclick' => 'frmModifyConfirm(event);',
            ]
        ]);
        ?>
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
                            style="position: relative; width: 100%; height: 200px; background-image: <?php echo empty( _elm( $aDatas, 'IMGS_INFO' ) ) == true ? "url('/dist/img/file_upload_bg.svg')": "none" ?>; background-repeat: no-repeat; background-position: center; background-size: 20%; overflow-y: auto;">
                            <p id="upload-text"
                                style="position: absolute; top: 70%; left: 50%; transform: translate(-50%, -50%); margin: 0; color: #616876; text-align: center;">
                                <?php echo empty( _elm( $aDatas, 'IMGS_INFO' ) ) ? '이미지를 여기로 드래그<br>750 x 750px / JPG 권장' : ''?>
                            </p>
                            <div id="preview-zone" style="margin-top: 20px; display: flex; flex-wrap: wrap;">

                            </div>
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
                                    style="border-top-right-radius:0px; border-bottom-right-radius: 0px; width: 1%" data-required='상품명을 입력하세요' value="<?php echo _elm( $aDatas, 'G_NAME' )?>"/>
                                <span class="wordCount input-group-text"
                                    style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                    <?php echo mb_strlen( _elm( $aDatas, 'G_NAME' ) )?>/100
                                </span>
                            </div>

                            <div class="input-group required">
                                <label class="label body2-c">
                                    상품명(영문)
                                    <span>*</span>
                                </label>
                                <input type="text" class="form-control" name="i_goods_name_eng" data-max-length="100"
                                    style="border-top-right-radius:0px; border-bottom-right-radius: 0px; width: 1%" data-required='상품명(영문)을 입력하세요.' value="<?php echo _elm( $aDatas, 'G_NAME_ENG' )?>" />
                                <span class="wordCount input-group-text"
                                    style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                    <?php echo mb_strlen( _elm( $aDatas, 'G_NAME_ENG' ) )?>/100
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
                                <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
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
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_CONTETN_IS_SAME_FLAG' ) == 'Y' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_mobile_content_same_chk',
                                        'id' => 'i_mobile_content_same_chk_Y',
                                        'value' => 'Y',
                                        'label' => 'PC상세설명과 동일',
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#m_content\').hide()',
                                            'data-required' => '모바일 설명이 PC와 동일한지 선택하세요.',

                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                                <?php
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_CONTETN_IS_SAME_FLAG' ) == 'N' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_mobile_content_same_chk',
                                        'id' => 'i_mobile_content_same_chk_N',
                                        'value' => 'N',
                                        'label' => '모바일 상세설명',
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#m_content\').show()',
                                            'data-required' => '모바일 설명이 PC와 동일한지 선택하세요.',
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                            </div>
                            <div class="required" id="m_content" style="padding-bottom:1.2rem;<?php echo  _elm( $aDatas, 'G_CONTETN_IS_SAME_FLAG' ) == 'Y' ? 'display:none;' : '';?>">
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
                                    style="border-top-right-radius:0px; border-bottom-right-radius: 0px; width: 1%"  value="<?php echo _elm( $aDatas, 'G_LOCAL_PRID' )?>"/>
                                <span class="wordCount input-group-text"
                                    style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                    <?php echo mb_strlen( _elm( $aDatas, 'G_LOCAL_PRID' ) )?>/100
                                </span>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    구매 적립포인트
                                </label>
                                <input type="text" class="form-control" name="i_goods_add_point" data-max-length="100" numberwithcomma
                                    style="max-width:150px" value="<?php echo _elm( $aDatas, 'G_ADD_POINT' )?>"/>

                            </div>
                            <div class="input-group">
                                <label class="label body2-c">
                                    펄핏 사이즈측정
                                </label>
                                <?php
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_IS_PERFIT_FLAG' ) == 'N' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_perfit_use',
                                        'id' => 'i_perfit_use_N',
                                        'value' => 'N',
                                        'label' => '사용안함',
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                                <?php
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_IS_PERFIT_FLAG' ) == 'Y' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_perfit_use',
                                        'id' => 'i_perfit_use_Y',
                                        'value' => 'Y',
                                        'label' => '사용함',
                                        'checked' => $checked,
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
                                    style="border-top-right-radius:0px; border-bottom-right-radius: 0px; width: 1%"  value="<?php echo _elm( $aDatas, 'G_SEARCH_KEYWORD' )?>" placeholder="바지,바지바지,여자바지,반바지"/>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    상품정보제공고시
                                </label>
                                <?php
                                    echo getIconButton([
                                        'txt' => '항목 불러오기',
                                        'icon' => 'add',
                                        'buttonClass' => 'btn',
                                        'buttonStyle' => 'width:130px; height: 36px',
                                        'width' => '21',
                                        'height' => '20',
                                        'stroke' => 'black',
                                        'extra' => [
                                            'type' => 'button',
                                            'onclick' => 'loadAddInfo();',
                                        ]
                                    ]);
                                ?>
                                <?php
                                    echo getIconButton([
                                        'txt' => '항목추가',
                                        'icon' => 'box_plus',
                                        'buttonClass' => 'btn',
                                        'buttonStyle' => 'width:130px; height: 36px',
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
                                        <?php
                                        if( !empty( _elm( $aDatas, 'REQ_INFO' ) ) ){
                                            foreach( _elm( $aDatas, 'REQ_INFO' ) as $rKey => $reqInfo ){
                                        ?>
                                            <tr>
                                                <td><input type="text" class="form-control" name="i_req_info_keys[]" value="<?php echo _elm( $reqInfo, 'I_KEY' )?>"></td>
                                                <td><input type="text" class="form-control" name="i_req_info_values[]" value="<?php echo _elm( $reqInfo, 'I_VALUE' )?>"></td>
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
                                        <?php
                                            }
                                        }
                                        ?>
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
                                        name="i_sell_period_start_at" id="i_sell_period_start_at" value="<?php echo _elm( $aDatas, 'G_SELL_PERIOD_START_AT' )!= '0000-00-00 00:00:00' ? date('Y-m-d H:i:s', strtotime( _elm( $aDatas, 'G_SELL_PERIOD_START_AT' ) ) ):''?>" readonly>
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
                                        name="i_sell_period_end_at" id="i_sell_period_end_at" value="<?php echo _elm( $aDatas, 'G_SELL_PERIOD_END_AT' )!= '0000-00-00 00:00:00' ? date('Y-m-d H:i:s', strtotime( _elm( $aDatas, 'G_SELL_PERIOD_END_AT' ) ) ): ''?>" readonly>
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
                                    style="border-top-right-radius:0px; border-bottom-right-radius: 0px; width: 1%" placeholder="EA" value="<?php echo _elm( $aDatas, 'G_SELL_UNIT' )?>"/>
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
                                                <input type="radio" id="<?php echo _elm( $colorData, 'id' )?>" name="i_goods_color" value="<?php echo $key?>" <?php echo _elm( _elm( $aColorConfig, _elm( $aDatas, 'G_COLOR' ) ) , 'color') == _elm( $colorData, 'color' ) ? 'checked' : ''?>>
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
                                <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
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
                                <input type="text" class="form-control" name="i_sell_price" style="max-width:250px !important;" value="<?php echo number_format( _elm( $aDatas, 'G_SELL_PRICE' ) )?>" numberwithcomma data-required='소비자가를 입력하세요.' >
                                <span class="input-group-text"
                                    style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                    원
                                </span>

                                <label class="label body2-c" style="margin-left: 15%">
                                    공급가
                                    <span>*</span>
                                </label>
                                <input type="text" class="form-control" name="i_buy_price" style="max-width:250px !important;" value="<?php echo number_format( _elm( $aDatas, 'G_BUY_PRICE' ) )?>" numberwithcomma data-required='공급가를 입력하세요.' >
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
                                <input type="text" class="form-control" name="i_goods_price" style="max-width:250px !important;" value="<?php echo number_format( _elm( $aDatas, 'G_PRICE' ) )?>" numberwithcomma data-required='판매가를 입력하세요.' >
                                <span class="input-group-text"
                                    style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                    원
                                </span>

                                <label class="label body2-c" style="margin-left: 15%">
                                    마진율
                                    <span>*</span>
                                </label>
                                <input type="text" class="form-control" name="i_goods_price_rate" style="max-width:250px !important;" placeholder="마진율은 자동 계산됩니다." value="<?php echo number_format( _elm( $aDatas, 'G_PRICE_RATE' ), 2 )?>" data-required='마진율을 입력하세요.' readonly >
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
                                    $selected = _elm( $aDatas, 'G_TAX_TYPE' );
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
                                <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
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

                                    $checked = false;
                                    if( strpos( _elm( $aDatas, 'G_DISCOUNT_CD' ), 'coupon' ) !== false )
                                        $checked = true;

                                    $setParam = [
                                        'name' => 'i_discount_cd[]',
                                        'id' => 'i_discount_cd_coupon',
                                        'value' => 'coupon' ,
                                        'label' => '쿠폰',
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                            'class'=>'check-item',
                                        ]
                                    ];
                                    echo getCheckBox( $setParam );
                                ?>
                                <?php
                                    $checked = false;
                                    if( strpos( _elm( $aDatas, 'G_DISCOUNT_CD' ), 'point' ) !== false )
                                        $checked = true;

                                    $setParam = [
                                        'name' => 'i_discount_cd[]',
                                        'id' => 'i_discount_cd_point',
                                        'value' => 'point' ,
                                        'label' => '포인트',
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                            'class'=>'check-item',
                                        ]
                                    ];
                                    echo getCheckBox( $setParam );
                                ?>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    등급 할인 적용
                                    <span>*</span>
                                </label>
                                <?php

                                    $checked = false;
                                    if( _elm( $aDatas, 'G_GRADE_DISCOUNT_FLAG' )== 'Y' )
                                        $checked = true;

                                    $setParam = [
                                        'name' => 'i_grade_discount_flag',
                                        'id' => 'i_grade_discount_flag',
                                        'value' => 'Y' ,
                                        'label' => '회원 등급에 따라 적용 가능',
                                        'checked' => $checked,
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
                                    $options  = ['N'=>'기본 설정에 따름', 'Y'=>'개별 적립 설정'];
                                    $extras   = ['id' => 'i_sell_point_flag', 'class' => 'form-select', 'style' => 'max-width: 250px;margin-right:0.235em;','onChange'=>'$(this).val() == \'Y\'? $(\'#point_save_wrap\').show() : $(\'#point_save_wrap\').hide()' ];
                                    $selected = _elm( $aDatas, 'G_SELL_POINT_FLAG' );
                                    echo getSelectBox('i_sell_point_flag', $options, $selected, $extras);
                                ?>
                            </div>
                            <div id="point_save_wrap" style="<?php echo _elm( $aDatas, 'G_SELL_POINT_FLAG' ) == 'N'? 'display:none;': '' ?>width:100%;padding:2.5rem;margin-bottom:1.3rem;border-radius: 4px;border: 1px solid var(----tblr-border-color, #E6E7E9);background: var(-----tblr-light, #F8FAFC);">
                                <div id="point_save_list">
                                    <?php
                                    if( empty( _elm( $aDatas, 'SEL_POINT_GROUP' ) ) === false ){
                                        foreach( _elm( $aDatas, 'SEL_POINT_GROUP' ) as $sKey => $pGroup ){
                                    ?>
                                        <div class="input-group required" style="max-width:100%">

                                            <?php
                                                $options  = $aMemberGrade;
                                                $extras   = ['id' => 'i_discount_mb_group[]', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:2.235em;'];
                                                $selected = _elm( $pGroup, 'D_MB_GROUP_IDX' );
                                                echo getSelectBox('i_discount_mb_group[]', $options, $selected, $extras);
                                            ?>
                                            <input type="text" name="i_discount_mb_group_amt[]" class="form-control" value="<?php echo number_format( _elm( $pGroup, 'D_MB_GROUP_DC_AMT' ) )?>">
                                            <?php
                                                $options  = ['per' => '%' , 'won' => '원'];
                                                $extras   = ['id' => 'i_discount_mb_group_amt_unit[]', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-left:2.235em;'];
                                                $selected = _elm( $pGroup, 'D_MB_GOURP_SV_UNIT' );
                                                echo getSelectBox('i_discount_mb_group_amt_unit[]', $options, $selected, $extras);
                                            ?>

                                            <div class="input-icon" style="padding-left:0.5rem">

                                                <input type="text" class="form-control  datepicker-icon"
                                                    name="i_discount_start_date[]" id="i_discount_start_date[]" value="<?php echo _elm( $pGroup, 'D_DC_PERIOD_START_AT' ) != '0000-00-00 00:00:00'? date('Y-m-d H:i:s', strtotime( _elm( $pGroup, 'D_DC_PERIOD_START_AT' ) ) ) : '' ?>" readonly>
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
                                                    name="i_discount_end_date[]" id="i_discount_end_date[]" value="<?php echo _elm( $pGroup, 'D_DC_PERIOD_END_AT' ) != '0000-00-00 00:00:00'? date('Y-m-d H:i:s', strtotime( _elm( $pGroup, 'D_DC_PERIOD_END_AT' ) ) ) : '' ?>" readonly>
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
                                    <?php
                                        }
                                    }
                                    ?>


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
                                        그룹상품

                                    </p>
                                </div>
                                <!-- 아코디언 토글 버튼 -->
                                <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
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
                                        'name' => 'i_group_use_flag',
                                        'id' => 'i_group_use_flag_Y',
                                        'value' => 'Y',
                                        'label' => '사용',
                                        'checked' => 'checked',
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#groupflag_wrap\').show()'
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                                <?php
                                    $setParam = [
                                        'name' => 'i_group_use_flag',
                                        'id' => 'i_group_use_flag_N',
                                        'value' => 'N',
                                        'label' => '미사용',
                                        'checked' => '',
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#groupflag_wrap\').hide()'
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                            </div>
                            <div id="groupflag_wrap" style="">
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
                                                'onclick' => 'openDataLayer(\'group_product\');',
                                            ]
                                        ]);
                                    ?>
                                    <span style="margin:0 0.6rem 0 0.4rem;color:#ccc;font-size:25px;">|</span>
                                    <?php
                                        echo getButton([
                                            'text' => '복제',
                                            'class' => 'btn btn-success',
                                            'style' => 'width: 80px; height: 36px',
                                            'extra' => [
                                                'onclick' => 'event.preventDefault();copyGoodsConfirm(\''._elm( $aDatas, 'G_IDX' ).'\')',
                                            ]
                                        ]);
                                    ?>
                                    <?php
                                        echo getButton([
                                            'text' => '선택삭제',
                                            'class' => 'btn btn-secondary',
                                            'style' => 'width: 80px; height: 36px',
                                            'extra' => [
                                                'onclick' => 'event.preventDefault();removeLine(\'aGroupGoodsTable\')',
                                            ]
                                        ]);
                                    ?>
                                </div>
                                <div id="relation_table_wrap" style="width:100%;padding:2.5rem;margin-bottom:1.3rem;border-radius: 4px;border: 1px solid var(----tblr-border-color, #E6E7E9);background: var(-----tblr-light, #F8FAFC);">
                                    <div class="table-responsive">
                                        <table class="table table-vcenter" id="aGroupGoodsTable">
                                            <colgroup>
                                                <col style="width:6%;">
                                                <col style="width:5%;">
                                                <col style="width:8%;">
                                                <col style="*">
                                                <col style="width:13%;">
                                                <col style="width:13%;">
                                                <col style="width:10%;">
                                                <col style="width:5%;">
                                                <col style="width:5%;">
                                                <col style="width:5%;">
                                                <col style="width:10%;">

                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th>&nbsp;</th>
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
                                                    <th>색상</th>
                                                    <th>색상(origin)</th>
                                                    <th>판매가</th>
                                                    <th>노출상태<br>
                                                        PC/MOBILE
                                                    </th>
                                                    <th>판매상태<br>
                                                        PC/MOBILE
                                                    </th>
                                                    <th>재고</th>
                                                    <th>등록일/수정일</th>
                                                </tr>
                                            </thead>
                                            <tbody id="groupSort">
                                            <?php
                                                $goodsGroupIdxs = [];
                                                if( !empty( _elm( $aDatas, 'G_GROUP_LISTS' ) ) ){

                                                    foreach( _elm( $aDatas, 'G_GROUP_LISTS' ) as $gKey => $gLists ){
                                                        $goodsGroupIdxs[] = _elm( $gLists, 'G_IDX' );
                                            ?>
                                            <tr data-idx="<?php echo _elm( $gLists , 'G_IDX' )?>" onmouseover="$(this).find('.group-move-icons').show()" onmouseout="$(this).find('.group-move-icons').hide()">
                                                <input type="hidden" name="i_group_goods_idxs[]" value="<?php echo _elm( $gLists , 'G_IDX' )?>">
                                                <td style="position:relative">
                                                    <div class="group-move-icons" style="display:none;cursor:pointer" >
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32" fill="none">
                                                            <g clip-path="url(#clip0_492_18580)">
                                                                <path d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z" fill="#616876"/>
                                                                <path d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z" fill="#616876"/>
                                                                <path d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z" fill="#616876"/>
                                                                <path d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z" fill="#616876"/>
                                                                <path d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z" fill="#616876"/>
                                                                <path d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z" fill="#616876"/>
                                                            </g>
                                                            <defs>
                                                                <clipPath id="clip0_492_18580">
                                                                <rect width="32" height="32" fill="white"/>
                                                                </clipPath>
                                                            </defs>
                                                        </svg>
                                                    </div>
                                                </td>
                                                <td class="body2-c nowrap moving">
                                                    <?php
                                                    if( _elm( $gLists, 'G_IDX' ) == _elm( $aDatas, 'G_IDX' ) ){
                                                    ?>
                                                        <span style="border:1px solid #ccc;border-radius:10px;padding:0.32rem">현재상품</span>
                                                    <?php
                                                    }else{
                                                    ?>
                                                    <div class="checkbox checkbox-single">
                                                        <?php
                                                            $setParam = [
                                                                'name' => 'i_group_goods_idxs[]',
                                                                'id' => 'i_group_goods_idxs_'._elm( $gLists, 'G_IDX'),
                                                                'value' =>  _elm( $gLists, 'G_IDX'),
                                                                'label' => '',
                                                                'checked' => false,
                                                                'extraAttributes' => [
                                                                    'aria-label' => 'Single checkbox One',
                                                                    'class'=>'check-item-pro',
                                                                ]
                                                            ];
                                                            echo getCheckBox( $setParam );
                                                        ?>
                                                    </div>
                                                    <?php
                                                    }?>

                                                </td>
                                                <td class="body2-c nowrap"><img src="/<?php echo _elm( $gLists, 'I_IMG_PATH' )?>" width="80" class="icon-image"></td>
                                                <td class="body2-c nowrap">
                                                    <?php
                                                    if( _elm( $gLists, 'G_IDX' ) == _elm( $aDatas, 'G_IDX' ) ){
                                                    ?>
                                                    <?php echo  _elm( $gLists , 'G_NAME' )?>
                                                    <?php
                                                    }else{
                                                    ?>
                                                    <a href="javascript:window.open('<?php echo _link_url('/goods/goodsDetail/'._elm( $gLists , 'G_IDX' ) )?>', '_blank');" ><?php echo  _elm( $gLists , 'G_NAME' )?></a>
                                                    <?php
                                                    }
                                                    ?>
                                                    </td>
                                                <td class="body2-c nowrap">

                                                <?php if( !empty( $aColorConfig ) ){
                                                    $colorData = _elm($aColorConfig, _elm( $gLists, 'G_COLOR' ) );
                                                ?>
                                                    <span>
                                                        <span style="border:1px solid #ccc;background-color:<?php echo _elm( $colorData, 'color' )?>width: 18px; height: 18px; display: inline-block; margin-right: 10px; border-radius: 3px; vertical-align: middle;"></span>
                                                        <span style="vertical-align: middle;"><?php echo _elm( $colorData, 'text' )?></span>
                                                    </span>
                                                <?php
                                                }
                                                ?>

                                                </td>
                                                <td class="body2-c nowrap"><input type="text" name="i_real_color" class="form-control" value="<?php echo _elm( $gLists, 'G_REAL_COLOR' )?>" data-g-idx="<?php echo _elm( $gLists, 'G_IDX' )?>"> </td>
                                                <td class="body2-c nowrap"><?php echo number_format( _elm( $gLists, 'G_PRICE' ) )?></td>
                                                <td class="body2-c nowrap">
                                                    <?php echo _elm( $gLists, 'G_PC_OPEN_FLAG' ) == 'Y' ? '노출' : '미노출' ?><br>
                                                    <?php echo _elm( $gLists, 'G_MOBILE_OPEN_FLAG' ) == 'Y' ? '노출' : '미노출' ?>
                                                </td>
                                                <td class="body2-c nowrap">
                                                    <?php echo _elm( $gLists, 'G_PC_SELL_FLAG' ) == 'Y' ? '판매중' : '판매중단' ?><br>
                                                    <?php echo _elm( $gLists, 'G_MOBILE_SELL_FLAG' ) == 'Y' ? '판매중' : '판매중단' ?><br>
                                                </td>
                                                <td class="body2-c nowrap">
                                                    <?php echo _elm( $gLists, 'G_STOCK_CNT' )?>
                                                </td>
                                                <td class="body2-c nowrap">
                                                    <?php echo empty( _elm( $gLists , 'G_CREATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $gLists , 'G_CREATE_AT' ) ) ) : '-' ?><br>
                                                    <?php echo empty( _elm( $gLists , 'G_UPDATE_AT' ) ) == false ? date( 'Y-m-d' , strtotime( _elm( $gLists , 'G_UPDATE_AT' ) ) ) : '-' ?>
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
                                <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
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
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_RELATION_GOODS_FLAG' ) == 'Y' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_relation_use_flag',
                                        'id' => 'i_relation_use_flag_Y',
                                        'value' => 'Y',
                                        'label' => '사용',
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#relationflag_wrap\').show()'
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                                <?php
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_RELATION_GOODS_FLAG' ) == 'N' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_relation_use_flag',
                                        'id' => 'i_relation_use_flag_N',
                                        'value' => 'N',
                                        'label' => '미사용',
                                        'checked' => $checked,
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
                                                    <th>&nbsp;</th>
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
                                            <?php
                                                $productIdxs = [];
                                                if( empty( _elm( $aDatas, 'RELATION_LISTS' ) ) === false ){
                                                    foreach( _elm( $aDatas, 'RELATION_LISTS' ) as $rKey => $rLists ){
                                                        $productIdxs[] = _elm( $rLists , 'G_IDX' );
                                            ?>
                                                <tr data-idx="<?php echo _elm( $rLists , 'G_IDX' )?>" onmouseover="$(this).find('.relation-move-icons').show()" onmouseout="$(this).find('.relation-move-icons').hide()">
                                                    <input type="hidden" name="i_relation_goods_idxs[]" value="<?php echo _elm( $rLists , 'G_IDX' )?>">
                                                    <td>
                                                        <div class="relation-move-icons" style="display:none;cursor:pointer" >
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32" fill="none">
                                                                <g clip-path="url(#clip0_492_18580)">
                                                                    <path d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z" fill="#616876"/>
                                                                    <path d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z" fill="#616876"/>
                                                                    <path d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z" fill="#616876"/>
                                                                    <path d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z" fill="#616876"/>
                                                                    <path d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z" fill="#616876"/>
                                                                    <path d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z" fill="#616876"/>
                                                                </g>
                                                                <defs>
                                                                    <clipPath id="clip0_492_18580">
                                                                    <rect width="32" height="32" fill="white"/>
                                                                    </clipPath>
                                                                </defs>
                                                            </svg>
                                                        </div>
                                                    </td>
                                                    <td class="body2-c nowrap">
                                                        <div class="checkbox checkbox-single">
                                                        <?php
                                                            $setParam = [
                                                                'name' => 'i_relation_goods_idxs[]',
                                                                'id' => 'i_relation_goods_idxs_'._elm( $rLists, 'G_IDX'),
                                                                'value' =>  _elm( $rLists, 'G_IDX'),
                                                                'label' => '',
                                                                'checked' => false,
                                                                'extraAttributes' => [
                                                                    'aria-label' => 'Single checkbox One',
                                                                    'class'=>'check-item-pro',
                                                                ]
                                                            ];
                                                            echo getCheckBox( $setParam );
                                                        ?>
                                                        </div>
                                                    </td>
                                                    <td class="body2-c nowrap"><img src="/<?php echo _elm( $rLists, 'I_IMG_PATH' )?>" width="80" loading="lazy"></td>
                                                    <td class="body2-c nowrap"><a href='<?php echo _link_url('/goods/goodsDetail/'._elm( $rLists , 'G_IDX' ) )?>' target='_blank'><?php echo  _elm( $rLists , 'G_NAME' )?></a></td>
                                                    <td class="body2-c nowrap"><?php echo _elm( $rLists, 'G_CATEGORY_MAIN' )?></td>
                                                    <td class="body2-c nowrap"><?php echo number_format( _elm( $rLists, 'G_PRICE' ) )?></td>
                                                    <td class="body2-c nowrap"><?php echo _elm( $rLists, 'G_PC_OPEN_FLAG' ) == 'Y' ? '노출' : '미노출' ?></td>
                                                    <td class="body2-c nowrap"><?php echo _elm( $rLists, 'G_PC_SELL_FLAG' ) == 'Y' ? '판매중' : '판매중단' ?></td>
                                                    <td class="body2-c nowrap">
                                                        <?php
                                                            $options  = ['nomal'=>'일방등록', 'dup'=>'상호등록'];
                                                            $extras   = ['id' => 'i_relation_goods_add_gbn[]', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                                                            $selected =  _elm( _elm( _elm( $aDatas, 'RELATION_INFO' ), _elm( $rLists , 'G_IDX' ) ), 'g_add_gbn' ) ;
                                                            echo getSelectBox('i_relation_goods_add_gbn[]', $options, $selected, $extras);
                                                        ?>
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
                                <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
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
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_ADD_GOODS_FLAG' ) == 'Y' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_add_goods_flag',
                                        'id' => 'i_add_goods_flag_Y',
                                        'value' => 'Y',
                                        'label' => '사용',
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#add_goods_flag_wrap\').show()'
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                                <?php
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_ADD_GOODS_FLAG' ) == 'N' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_add_goods_flag',
                                        'id' => 'i_add_goods_flag_N',
                                        'value' => 'N',
                                        'label' => '미사용',
                                        'checked' => $checked,
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
                                            <?php
                                                $addProductIdxs = [];
                                                if( empty( _elm( $aDatas, 'ADD_GOODS_LISTS' ) ) === false ){
                                                    foreach( _elm( $aDatas, 'ADD_GOODS_LISTS' ) as $adKey => $adLists ){
                                                        $addProductIdxs[] = _elm( $adLists, 'G_IDX' );
                                            ?>
                                                <tr data-idx="<?php echo _elm( $adLists , 'G_IDX' )?>" onmouseover="$(this).find('.add-move-icons').show()" onmouseout="$(this).find('.add-move-icons').hide()">
                                                    <input type="hidden" name="i_add_goods_idxs[]" value="<?php echo _elm( $adLists , 'G_IDX' )?>">
                                                    <td>
                                                        <div class="add-move-icons" style="display:none;cursor:pointer" >
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32" fill="none">
                                                                <g clip-path="url(#clip0_492_18580)">
                                                                    <path d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z" fill="#616876"/>
                                                                    <path d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z" fill="#616876"/>
                                                                    <path d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z" fill="#616876"/>
                                                                    <path d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z" fill="#616876"/>
                                                                    <path d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z" fill="#616876"/>
                                                                    <path d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z" fill="#616876"/>
                                                                </g>
                                                                <defs>
                                                                    <clipPath id="clip0_492_18580">
                                                                    <rect width="32" height="32" fill="white"/>
                                                                    </clipPath>
                                                                </defs>
                                                            </svg>
                                                        </div>
                                                    </td>
                                                    <td class="body2-c nowrap">
                                                        <div class="checkbox checkbox-single">
                                                        <?php
                                                            $setParam = [
                                                                'name' => 'i_add_goods_idxs[]',
                                                                'id' => 'i_add_goods_idxs_'._elm( $adLists, 'G_IDX'),
                                                                'value' =>  _elm( $adLists, 'G_IDX'),
                                                                'label' => '',
                                                                'checked' => false,
                                                                'extraAttributes' => [
                                                                    'aria-label' => 'Single checkbox One',
                                                                    'class'=>'check-item-add',
                                                                ]
                                                            ];
                                                            echo getCheckBox( $setParam );
                                                        ?>
                                                        </div>

                                                    </td>
                                                    <td class="body2-c nowrap"><img src="/<?php echo _elm( $adLists, 'I_IMG_PATH' )?>" width="80" loading="lazy"></td>
                                                    <td class="body2-c nowrap"><a href='<?php echo _link_url('/goods/goodsDetail/'._elm( $adLists , 'G_IDX' ) )?>' target='_blank'><?php echo  _elm( $adLists , 'G_NAME' )?></a></td>
                                                    <td class="body2-c nowrap"><?php echo number_format( _elm( $adLists, 'G_PRICE' ) )?></td>
                                                    <td class="body2-c nowrap"><?php echo _elm( $adLists, 'G_PC_SELL_FLAG' ) == 'Y' ? '판매중' : '판매중단' ?></td>
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
                                <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
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
                            <div class="input-group">
                                <label class="label body2-c">
                                    다해코드
                                </label>
                                <?php
                                    echo '<a href="/dahae/getProductHeaders/'._elm( $aDatas, 'G_DAHAE_P_CODE' ).'" target="_blank">'._elm( $aDatas, 'G_DAHAE_P_CODE' ).'</a>';
                                ?>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    사용옵션
                                    <span>*</span>
                                </label>
                                <?php
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_OPTION_USE_FLAG' ) == 'N' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_option_use_flag',
                                        'id' => 'i_option_use_flag_N',
                                        'value' => 'N',
                                        'label' => '사용안함',
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#options_wrap\').hide();',
                                            'disabled' => 'true',
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                                <?php
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_OPTION_USE_FLAG' ) == 'Y' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_option_use_flag',
                                        'id' => 'i_option_use_flag_Y',
                                        'value' => 'Y',
                                        'label' => '사용함',
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#options_wrap\').show();'
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    재입고 알림
                                </label>
                                <?php
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_IS_RESTOCK_ALIM_FLAG' ) == 'Y' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_is_restock_alim_flag',
                                        'id' => 'i_is_restock_alim_flag_Y',
                                        'value' => 'Y',
                                        'label' => '사용',
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                        ]
                                    ];
                                    echo getCheckBox($setParam);
                                ?>
                            </div>
                            <div id="options_group_wrap" style="width:100%;padding:2.5rem;margin-bottom:1.3rem;border-radius: 4px;border: 1px solid var(----tblr-border-color, #E6E7E9);background: var(-----tblr-light, #F8FAFC);">
                                <?php
                                    echo getIconButton([
                                        'txt' => '그룹추가',
                                        'icon' => 'box_plus',
                                        'buttonClass' => 'btn',
                                        'buttonStyle' => 'width:120px; height: 36px',
                                        'width' => '21',
                                        'height' => '20',
                                        'stroke' => 'black',
                                        'extra' => [
                                            'type' => 'button',
                                            'onclick' => 'addRows( \'options_group\' );',
                                        ]
                                    ]);
                                ?>

                                <div class="table-responsive">
                                    <table class="table table-vcenter" id="aOptionGroupTable">
                                        <colgroup>
                                            <col style="width:15%;">
                                            <col style="*">
                                            <col style="width:15%;">

                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th>옵션명</th>
                                                <th>옵션값</th>
                                                <th>삭제</th>
                                            </tr>
                                        </thead>
                                        <tbody id="aTbody">
                                            <?php
                                                if( empty( _elm( $aDatas, 'G_OPTION_INFO' ) ) === false ){

                                                    $option_group_info = json_decode( _elm( $aDatas, 'G_OPTION_INFO' ) ,true);
                                                    $optionGroupIndex = 0;

                                                    foreach( $option_group_info as  $goKey => $ogInfo ){
                                            ?>
                                            <tr class="options-group" data-group-index="<?php echo $optionGroupIndex?>">
                                                <td><input type="text" class="form-control group-key-input" name="i_option_group_keys[]" data-old-value="<?php echo $goKey?>" value="<?php echo $goKey?>"></td>
                                                <td>
                                                    <div class="tag-input-container">
                                                        <?php
                                                        foreach( $ogInfo as $ogData ){
                                                        ?>
                                                        <div class="tag" data-value="<?php echo _elm( $ogData, 'value' )?>">
                                                            <span class="tag-text"><?php echo _elm( $ogData, 'value' )?></span>
                                                            <span class="remove-tag">×</span>
                                                        </div>
                                                        <?php
                                                        }
                                                        ?>
                                                        <input type="text" class="tag-input form-control option-group-value" name="i_option_group_value[${optionGroupIndex}][]">
                                                    </div>
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
                                                            'onclick' => 'deleteOptionGroup(this);',
                                                        ]
                                                    ]);
                                                ?>
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
                            <div id="options_wrap" style="<?php echo  _elm( $aDatas, 'G_OPTION_USE_FLAG' ) == 'N' ? 'display:none;' : '' ?>width:100%;padding:2.5rem;margin-bottom:1.3rem;border-radius: 4px;border: 1px solid var(----tblr-border-color, #E6E7E9);background: var(-----tblr-light, #F8FAFC);">
                                <?php
                                    /*
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
                                    */
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-vcenter" id="aOptionTable">
                                        <div style="float:right;width:5.5rem;" class="input-group required">
                                        <?php
                                            $checked = false;
                                            if( _elm( $aDatas, 'G_OPTION_COMBINATION_FLAG' ) == 'Y' ){
                                                $checked = true;
                                            }
                                            $setParam = [
                                                'name' => 'i_option_combination_flag',
                                                'id' => 'i_option_combination_flag_Y',
                                                'value' => 'Y',
                                                'label' => '조합형',
                                                'checked' => $checked,
                                                'extraAttributes' => [
                                                    'aria-label' => 'Single checkbox One',
                                                    'class'=>'check-item',
                                                    'onclick'=>'addOptionRowsConfirm(tagData);'
                                                ]
                                            ];
                                            echo getCheckBox( $setParam );
                                        ?>
                                        </div>
                                        <?php
                                        if( _elm( $aDatas, 'G_OPTION_COMBINATION_FLAG' ) != 'Y' ){
                                        ?>
                                        <colgroup>
                                            <col style="*">
                                            <col style="width:15%;">
                                            <col style="width:15%;">
                                            <col style="width:10%;">
                                            <col style="width:10%;">
                                            <col style="width:10%;">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th>옵션명</th>
                                                <th>옵션값</th>
                                                <th>다해스팩</th>
                                                <th>다해바코드</th>
                                                <th>재고수량</th>
                                                <th>추가금액</th>
                                                <th>노출여부</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        }else{

                                            $maxOptionCount = 0;

                                            // G_OPTION_INFO를 파싱하여 최대 옵션값 개수 구하기
                                            if (!empty(_elm($aDatas, 'G_OPTION_INFO'))) {
                                                $option_group_info = json_decode(_elm($aDatas, 'G_OPTION_INFO'), true);
                                                $maxOptionCount = count( $option_group_info );
                                                $gOptionName   = [];
                                                foreach ($option_group_info as $goKey => $ogInfo) {
                                                    $optionCount = count($ogInfo);
                                                    $gOptionName[] =  $goKey;
                                                }
                                            }
                                            ?>
                                            <!-- 동적으로 생성된 colgroup -->
                                            <colgroup>
                                                <?php for ($i = 1; $i <= $maxOptionCount; $i++) { ?>
                                                    <col style="width:15%;"> <!-- 옵션값 컬럼 -->
                                                <?php } ?>
                                                <col style="width:15%;"> <!-- 다해스팩 -->
                                                <col style="width:15%;"> <!-- 다해바코드 -->
                                                <col style="width:10%;"> <!-- 재고수량 -->
                                                <col style="width:10%;"> <!-- 추가금액 -->
                                                <col style="width:10%;"> <!-- 노출여부 -->
                                            </colgroup>

                                            <!-- 동적으로 생성된 thead -->
                                            <thead>
                                                <tr>
                                                    <?php foreach($gOptionName as $gOname) { ?>
                                                        <th><?php echo $gOname; ?></th>
                                                    <?php } ?>
                                                    <th>다해스팩</th>
                                                    <th>다해바코드</th>
                                                    <th>재고수량</th>
                                                    <th>추가금액</th>
                                                    <th>노출여부</th>
                                                </tr>
                                            </thead>
                                        <?php
                                        }
                                        ?>


                                        <tbody>
                                        <?php
                                        if( empty( _elm( $aDatas, 'GOODS_OPTION_LISTS' ) ) === false){
                                            if( _elm( $aDatas, 'G_OPTION_COMBINATION_FLAG' ) != 'Y' ){
                                                foreach( _elm( $aDatas, 'GOODS_OPTION_LISTS' ) as $oKey => $oLists ){
                                        ?>
                                            <tr>
                                                <input type="hidden" name="i_option_idx[]" value="<?php echo _elm( $oLists, 'O_IDX' );?>">
                                                <td><input type="text" class="form-control option-key" name="i_option_keys[]" value="<?php echo _elm( $oLists, 'O_KEYS' )?>" readonly></td>
                                                <td><input type="text" class="form-control option-value" name="i_option_value[]" value="<?php echo _elm( $oLists, 'O_VALUES' )?>" readonly></td>
                                                <td><input type="text" class="form-control option-spec" name="i_option_spec[]" value="<?php echo _elm( $oLists, 'O_DAH_SPEC' ) ?>"></td>
                                                <td><input type="text" class="form-control option-barcode" name="i_option_barcode[]" value="<?php echo _elm( $oLists, 'O_PRD_BARCODE' ) ?>"></td>
                                                <td><input type="text" class="form-control option-stock" name="i_option_stock[]" value="<?php echo _elm( $oLists, 'O_STOCK' )?>"></td>
                                                <td><input type="text" class="form-control option-add_amt" numberwithcomma name="i_option_add_price[]" value="<?php echo number_format( _elm( $oLists, 'O_ADD_PRICE' ) )?>"></td>
                                                <td>
                                                    <?php
                                                        $options  = ['Y'=>'노출','N'=>'비노출'];
                                                        $extras   = ['id' => 'i_option_status[]', 'class' => 'form-select', 'style' => 'max-width: 80px;margin-right:2.235em;'];
                                                        $selected = _elm( $oLists, 'O_VIEW_STATUS' );
                                                        echo getSelectBox('i_option_status[]', $options, $selected, $extras);
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php
                                                }
                                            }else{
                                                foreach( _elm( $aDatas, 'GOODS_OPTION_LISTS' ) as $oKey => $oLists ){
                                                    $oValues = [];
                                                    foreach ($oLists as $key => $value) {
                                                        if (strpos($key, 'O_VALUES') === 0) {  // O_VALUES, O_VALUES2, O_VALUES3 등
                                                            if( empty( $value ) === false  ){
                                                                $oValues[] = $value;
                                                            }

                                                        }
                                                    }


                                        ?>
                                            <tr>
                                                <input type="hidden" name="i_option_idx[]" value="<?php echo _elm( $oLists, 'O_IDX' );?>">
                                                <!-- 동적으로 생성된 옵션값들 -->
                                                <?php
                                                $n = 0;
                                                foreach ($oValues as $value) { ?>
                                                    <td><input type="text" class="form-control option-value" name="i_option_value<?php echo $n == 0 ? '' : $n;?>[]" value="<?php echo htmlspecialchars($value); ?>" readonly></td>
                                                <?php
                                                $n++;
                                                }
                                                ?>
                                                <td><input type="text" class="form-control option-spec" name="i_option_spec[]" value="<?php echo _elm( $oLists, 'O_DAH_SPEC' ) ?>"></td>
                                                <td><input type="text" class="form-control option-barcode" name="i_option_barcode[]" value="<?php echo _elm( $oLists, 'O_PRD_BARCODE' ) ?>"></td>
                                                <td><input type="text" class="form-control option-stock" name="i_option_stock[]" value="<?php echo _elm( $oLists, 'O_STOCK' )?>"></td>
                                                <td><input type="text" class="form-control option-add_amt" numberwithcomma name="i_option_add_price[]" value="<?php echo number_format( _elm( $oLists, 'O_ADD_PRICE' ) )?>"></td>
                                                <td>
                                                    <?php
                                                        $options  = ['Y'=>'노출','N'=>'비노출'];
                                                        $extras   = ['id' => 'i_option_status[]', 'class' => 'form-select', 'style' => 'max-width: 80px;margin-right:2.235em;'];
                                                        $selected = _elm( $oLists, 'O_VIEW_STATUS' );
                                                        echo getSelectBox('i_option_status[]', $options, $selected, $extras);
                                                    ?>
                                                </td>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="input-group required">
                                <label class="label body2-c">
                                    판매재고 <?php  _elm( $aDatas, 'G_STOCK_FLAG' ) ?>
                                </label>
                                <?php
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_STOCK_FLAG' ) == 'N' ){
                                        $checked = true;
                                    }
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
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_STOCK_FLAG' ) == 'Y' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_goods_stock_flag',
                                        'id' => 'i_goods_stock_flag_N',
                                        'value' => 'Y',
                                        'label' => '재고수량에 따름',
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#stock_wrap\').show();',
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                            </div>
                            <div class="input-group" id="stock_wrap" style="<?php echo _elm( $aDatas, 'G_STOCK_FLAG' ) == 'N'? 'display:none' : '' ?>">
                                <label class="label body2-c">
                                    재고수량
                                </label>
                                <input type="text" class="form-control" name="i_goods_stock" style="max-width:250px !important;" value="<?php echo _elm( $aDatas, 'G_STOCK_CNT' )?>" >
                                <span class="input-group-text"
                                    style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                    개
                                </span>
                                <label class="label body2-c" style="margin-left:4.7rem;">
                                    안전재고
                                </label>
                                <input type="text" class="form-control" name="i_goods_safe_stock" style="max-width:250px !important;" value="<?php echo _elm( $aDatas, 'G_SAFETY_STOCK' )?>">
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
                                <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
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
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_TEXT_OPTION_USE_FLAG' ) == 'N' ){
                                        $checked = true;
                                    }

                                    $setParam = [
                                        'name' => 'i_text_option_use_flag',
                                        'id' => 'i_text_option_use_flag_N',
                                        'value' => 'N',
                                        'label' => '사용안함',
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#text_options_wrap\').hide();'
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                                <?php
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_TEXT_OPTION_USE_FLAG' ) == 'Y' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_text_option_use_flag',
                                        'id' => 'i_text_option_use_flag_Y',
                                        'value' => 'Y',
                                        'label' => '사용함',
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                            'onclick' => '$(\'#text_options_wrap\').show();'
                                        ]
                                    ];
                                    echo getRadioButton($setParam);
                                ?>
                            </div>
                            <div id="text_options_wrap" style="<?php echo  _elm( $aDatas, 'G_TEXT_OPTION_USE_FLAG' ) == 'N' ? 'display:none;' : '' ?>width:100%;padding:2.5rem;margin-bottom:1.3rem;border-radius: 4px;border: 1px solid var(----tblr-border-color, #E6E7E9);background: var(-----tblr-light, #F8FAFC);">
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
                                            <?php
                                            if( empty( _elm( $aDatas, 'G_TEXT_OPTION' ) ) === false ){
                                                $tOptions = json_decode( _elm( $aDatas, 'G_TEXT_OPTION' ), true );

                                                foreach( $tOptions as $tKey => $tOption ){
                                            ?>
                                            <tr>
                                                <td><input type="text" class="form-control option-key" name="i_text_option_keys[]" value="<?php echo _elm( $tOption, 'title' )?>"></td>
                                                <td>
                                                    <?php
                                                        $options  = ['text'=>'text', 'select'=>'select', 'radio'=>'radio', 'checkbox'=>'checkbox' ];
                                                        $extras   = ['class' => 'form-select' ];
                                                        $selected = _elm( $tOption, 'type' );
                                                        echo getSelectBox('i_text_option_type[]', $options, $selected, $extras);
                                                    ?>
                                                <td class="options-type-set-input">
                                                    <?php
                                                        if( empty( _elm( $tOption, 'extras' ) ) === false ){
                                                            foreach( _elm( $tOption, 'extras' ) as $eKey => $extra ){
                                                    ?>
                                                    <div class="option-input-group">
                                                        <input type="text" class="form-control option-extra-input" name="i_text_option_extra[<?php echo $tKey?>][]" value="<?php echo $extra?>" placeholder="옵션 추가">
                                                    </div>
                                                    <?php
                                                            }
                                                        }
                                                    ?>
                                                </td>
                                                <td class="options-type-set-buttons" style="text-align: right;vertical-align:top;">
                                                    <?php
                                                        if( _elm( $tOption, 'type' ) != 'text' ){
                                                    ?>
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
                                                    <?php
                                                        }
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

                                <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
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
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_DELIVERY_PAY_CD' ) == 'od' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_delivery_pay',
                                        'id' => 'i_delivery_pay',
                                        'value' => 'od',
                                        'label' => '5만원 이상 무료배송',
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
                                <?php
                                if( empty( _elm( $aDatas, 'GOODS_CATEGOTY_LISTS' ) ) === false ){
                                    foreach( _elm( $aDatas, 'GOODS_CATEGOTY_LISTS' ) as $cKey => $cLists ){
                                ?>
                                    <div class="dropdown-list-item" data-id="cate_<?php echo _elm( $cLists, 'C_IDX' )?>">
                                        <div class="dropdown-content">
                                            <div class="left-content">
                                                <?php
                                                    $checked = false;
                                                    if( _elm( $cLists, 'IS_MAIN' ) == 'Y' ){
                                                        $checked = true;
                                                    }
                                                    $setParam = [
                                                        'name' => 'i_is_category_main',
                                                        'id' => 'i_is_category_maincate_'._elm( $cLists, 'C_IDX' ),
                                                        'value' => _elm( $cLists, 'C_IDX' ),
                                                        'label' => '',
                                                        'checked' => $checked,
                                                        'extraAttributes' => [
                                                        ]
                                                    ];
                                                    echo getRadioButton($setParam);
                                                ?>
                                            </div>
                                            <div class="center-content">
                                                <div class="text"><?php echo _elm( $cLists, 'C_FULL_NAME' )?></div>
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
                                <?php
                                    }
                                }
                                ?>
                                </div>
                            </div>
                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    PC쇼핑몰 노출상태
                                </label>
                                <div class="form-inline">
                                    <?php
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_PC_OPEN_FLAG' ) == 'Y'){
                                            $checked = true;
                                        }

                                        $setParam = [
                                            'name' => 'i_is_pc_open',
                                            'id' => 'is_pc_open_Y',
                                            'value' => 'Y',
                                            'label' => '노출함',
                                            'checked' => $checked,
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                    <?php
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_PC_OPEN_FLAG' ) == 'N'){
                                            $checked = true;
                                        }
                                        $setParam = [
                                            'name' => 'i_is_pc_open',
                                            'id' => 'is_pc_open_N',
                                            'value' => 'N',
                                            'label' => '노출안함',
                                            'checked' => $checked,
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
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_PC_SELL_FLAG' ) == 'Y'){
                                            $checked = true;
                                        }
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
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_PC_SELL_FLAG' ) == 'N'){
                                            $checked = true;
                                        }
                                        $setParam = [
                                            'name' => 'i_is_pc_sell',
                                            'id' => 'i_is_pc_sell_N',
                                            'value' => 'N',
                                            'label' => '판매안함',
                                            'checked' => $checked,
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
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_MOBILE_OPEN_FLAG' ) == 'Y'){
                                            $checked = true;
                                        }
                                        $setParam = [
                                            'name' => 'i_is_mobile_open',
                                            'id' => 'i_is_mobile_open_Y',
                                            'value' => 'Y',
                                            'label' => '노출함',
                                            'checked' => $checked,
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                    <?php
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_MOBILE_OPEN_FLAG' ) == 'N'){
                                            $checked = true;
                                        }
                                        $setParam = [
                                            'name' => 'i_is_mobile_open',
                                            'id' => 'i_is_mobile_open_N',
                                            'value' => 'N',
                                            'label' => '노출안함',
                                            'checked' => $checked,
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
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_MOBILE_SELL_FLAG' ) == 'Y'){
                                            $checked = true;
                                        }
                                        $setParam = [
                                            'name' => 'i_is_mobile_sell',
                                            'id' => 'i_is_mobile_sel_Y',
                                            'value' => 'Y',
                                            'label' => '판매함',
                                            'checked' => $checked,
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                    <?php
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_MOBILE_SELL_FLAG' ) == 'N'){
                                            $checked = true;
                                        }
                                        $setParam = [
                                            'name' => 'i_is_mobile_sell',
                                            'id' => 'i_is_mobile_sell_N',
                                            'value' => 'N',
                                            'label' => '판매안함',
                                            'checked' => $checked,
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
                                    <input type="text" class="form-control mt-2" name="i_origin_name" value="<?php echo _elm( $aDatas, 'G_ORIGIN_NAME' )?>"
                                    placeholder="원산지를 입력해주세요." />
                                </div>
                            </div>
                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    제조사
                                </label>
                                <div class="form-inline">
                                    <input type="text" class="form-control mt-2" name="i_maker_name" value="<?php echo _elm( $aDatas, 'G_MAKER_NAME' )?>"
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
                                    <?php
                                    if( empty( _elm( $aDatas, 'G_BRAND_IDX' ) ) === false ){

                                    ?>
                                    <div class="dropdown-list-item" data-id="brand_<?php echo _elm( $aDatas, 'G_BRAND_IDX' )?>">
                                        <div class="dropdown-content">
                                            <div class="left-content">
                                                <?php
                                                    $setParam = [
                                                        'name' => 'i_is_brand_main',
                                                        'id' => 'i_is_brand_mainbrand_'._elm( $aDatas, 'G_BRAND_IDX' ),
                                                        'value' => _elm( $aDatas, 'G_BRAND_IDX' ),
                                                        'label' => '',
                                                        'checked' => 'checked',
                                                        'extraAttributes' => [
                                                        ]
                                                    ];
                                                    echo getRadioButton($setParam);
                                                ?>
                                            </div>
                                            <div class="center-content">
                                                <div class="text"><?php echo _elm( $aDatas, 'G_BRAND_NAME' )?></div>
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
                                    <?php

                                    }
                                    ?>
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
                                <input type="text" class="form-control mt-2" name="i_seo_title" placeholder="메타테그 제목" value="<?php echo _elm( $aDatas, 'G_SEO_TITLE' )?>" />
                            </div>
                            <div class="input-group">
                                <label class="label body2-c">
                                    설명
                                    <span>*</span>
                                </label>
                                <input type="text" class="form-control mt-2" name="i_seo_description" placeholder="메타태그 설명" value="<?php echo _elm( $aDatas, 'G_SEO_DESCRIPTION' )?>"/>
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
                                        <?php

                                        $iconSelectArr = [];

                                        if( empty( _elm( $aDatas, 'ICONS_LISTS' ) ) === false ){

                                            foreach( _elm( $aDatas, 'ICONS_LISTS' ) as $iKey => $iconData ){
                                                $iconSelectArr[_elm( $iconData, 'I_ICONS_IDX' )]['I_ICONS_PERIOD_START_AT'] = _elm( $iconData, 'I_ICONS_PERIOD_START_AT' );
                                                $iconSelectArr[_elm( $iconData, 'I_ICONS_IDX' )]['I_ICONS_PERIOD_END_AT'] = _elm( $iconData, 'I_ICONS_PERIOD_END_AT' );
                                            }
                                        }
                                        if( empty( _elm( $aIconsData , 'P' ) ) === false ){
                                            foreach( _elm( $aIconsData , 'P' ) as $iKey => $icons ){
                                                $iIdx = _elm($icons, 'I_IDX');  // 'I_IDX' 값을 변수에 저장
                                                $startPeriod = isset($iconSelectArr[$iIdx]) ? _elm($iconSelectArr[$iIdx], 'I_ICONS_PERIOD_START_AT') : '';  // 기본값 설정
                                                $endPeriod = isset($iconSelectArr[$iIdx]) ? _elm($iconSelectArr[$iIdx], 'I_ICONS_PERIOD_END_AT') : '';  // 기본값 설정
                                        ?>
                                            <tr>
                                                <td style="padding:2px 0 5px 0;">
                                                    <?php
                                                        $checked = false;
                                                        if( empty( _elm( $iconSelectArr, _elm( $icons, 'I_IDX' ) ) ) === false ){
                                                            $checked = true;
                                                        }
                                                        $setParam = [
                                                            'name' => 'i_icon_select[]',
                                                            'id' => 'i_icon_select_'._elm( $icons, 'I_IDX' ),
                                                            'value' => _elm( $icons, 'I_IDX' ),
                                                            'label' => '',
                                                            'checked' => $checked,
                                                            'extraAttributes' => [

                                                            ]
                                                        ];
                                                        echo getCheckBox($setParam);
                                                    ?>
                                                </td>
                                                <td style="padding:0;">

                                                    <div style="width:100%; text-align:center;padding-top:0.5rem" >
                                                        <img src='/<?php echo _elm( $icons, 'I_IMG_PATH' )?>' loading="lazy">
                                                        <div style="width:100%;float:left:padding:0;padding-top:0.4rem;padding-bottom:0.4rem;text-align:left">
                                                            <input type='text' style="border:0;width:74px;text-align:center" class="datepicker-icon" name="i_icon_selct_start_at[<?php echo _elm( $icons, 'I_IDX' )?>]" value="<?php echo $startPeriod?>" placeholder="시작일" >~<input type='text' style="border:0;width:74px;text-align:center" class="datepicker-icon" name="i_icon_selct_end_at[<?php echo _elm( $icons, 'I_IDX' )?>]" value="<?php echo $endPeriod?>"  placeholder="종료일">
                                                        </div>
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
                                        <?php
                                        if( empty( _elm( $aIconsData , 'L' ) ) === false ){
                                            foreach( _elm( $aIconsData , 'L' ) as $iKey => $icons ){
                                                $iIdx = _elm($icons, 'I_IDX');  // 'I_IDX' 값을 변수에 저장
                                        ?>
                                            <tr>
                                                <td style="padding:2px 0 5px 0;">
                                                    <?php
                                                        $checked = false;
                                                        if( empty( _elm( $iconSelectArr, _elm( $icons, 'I_IDX' ) ) ) === false ){
                                                            $checked = true;
                                                        }
                                                        $setParam = [
                                                            'name' => 'i_icon_select["'._elm( $icons, 'I_IDX' ).'"]',
                                                            'id' => 'i_icon_select_'._elm( $icons, 'I_IDX' ),
                                                            'value' => _elm( $icons, 'I_IDX' ),
                                                            'label' => '',
                                                            'checked' => $checked,
                                                            'extraAttributes' => [

                                                            ]
                                                        ];
                                                        echo getCheckBox($setParam);
                                                    ?>
                                                </td>
                                                <td style="padding:0;">
                                                    <div style="width:100%; text-align:center;" >
                                                        <img src='/<?php echo _elm( $icons, 'I_IMG_PATH' )?>' loading="lazy">
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
                                    $checked = $checked = false;
                                    if( strpos(_elm( $aDatas, 'G_OUT_VIEW' ), 'NAVER' ) !== false ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_out_view[]',
                                        'id' => 'i_out_view_naver',
                                        'value' => 'NAVER',
                                        'label' => '네이버 쇼핑',
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                        ]
                                    ];
                                    echo getCheckBox($setParam);
                                ?>
                            </div>
                            <div class="input-group">
                                <?php
                                    $checked = $checked = false;
                                    if( strpos(_elm( $aDatas, 'G_OUT_VIEW' ), 'KAKAO' ) !== false ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_out_view[]',
                                        'id' => 'i_out_view_kakao',
                                        'value' => 'KAKAO',
                                        'label' => '카카오 쇼핑하우',
                                        'checked' => $checked,
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
                                <?php
                                if( empty( _elm( $aDatas, 'G_OUT_MAIN_IMG_PATH' ) ) === false ){
                                ?>
                                <img src="/<?php echo _elm( $aDatas, 'G_OUT_MAIN_IMG_PATH' )?>" style="max-width: 100%; height: auto;" loading="lazy">
                                <?php
                                }
                                ?>
                            </div>
                            <div class="input-group">
                                <label class="label body2-c" style="width:100%" >
                                    네이버, 카카오 노출룔 상품명
                                </label>
                            </div>
                            <div class="input-group" style="margin-top:-0.7rem;">
                                <input type="text" class="form-control" name="i_out_goods_name" id="i_out_goods_name" placeholder="비워두면 상품명과 동일하게 적용" value="<?php echo _elm( $aDatas, 'G_OUT_GOODS_NAME' )?>">
                            </div>
                            <div class="input-group">
                                <label class="label body2-c"  style="width:100%" >
                                    네이버 쇼핑 이벤트 문구
                                </label>
                            </div>
                            <div class="input-group" style="margin-top:-0.7rem;">
                                <input type="text" class="form-control" name="i_out_event_txt" id="i_out_event_txt" placeholder="예: 10주면 10% 할인 이벤트" value="<?php echo _elm( $aDatas, 'G_OUT_EVENT_TXT' )?>">
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
                                $selected = _elm( $aDatas, 'G_GOODS_CONDITION' );
                                echo getSelectBox('i_goods_condition', $options, $selected, $extras);
                            ?>
                            </div>
                            <?php if( !empty($aGoodsProductType) ){
                                foreach( $aGoodsProductType as $aKey => $aVal ){
                            ?>
                            <div class="input-group" style="margin-bottom:-0.8rem">
                                <?php
                                    $checked = false;
                                    if( strpos(  _elm( $aDatas, 'G_GOODS_PRODUCT_TYPE' ), strtoupper( $aKey ) ) !== false){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_is_product_type[]',
                                        'id' => 'i_is_product_type_'.$aKey,
                                        'value' => strtoupper( $aKey ),
                                        'label' => $aVal,
                                        'checked' => $checked,
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
                                $selected = _elm( $aDatas, 'G_IS_SALES_TYPE' );
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
                                <input type="text" name="i_min_buy_count" id="i_min_buy_count" class="form-control" value="<?php echo _elm( $aDatas, 'G_MIN_BUY_COUNT' )?>" placeholder="숫자만 입력">
                            </div>
                            <div class="input-group">
                                <label class="label body2-c"  style="width:100%" >
                                    1인 구매시 최대 수량
                                </label>
                            </div>
                            <div class="input-group" style="margin-top:-0.7rem;">
                                <input type="text" class="form-control" name="i_mem_max_buy_count" id="i_mem_max_buy_count" value="<?php echo _elm( $aDatas, 'G_MEM_MAX_BUY_COUNT' )?>" placeholder="숫자만 입력">
                            </div>

                            <div class="input-group">
                                <?php
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_IS_ADULT_PRODUCT' ) == 'Y' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_is_adult_product',
                                        'id' => 'i_is_product_type_Y',
                                        'value' => 'Y',
                                        'label' => '미성년자 구매 불가능',
                                        'checked' => $checked,
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
                'onclick' => 'frmModifyConfirm(event);',
            ]
        ]);
        ?>
    </div>


</div>
<input type="file" name="i_goods_img[]" id="i_goods_img" multiple style="display: none;">

<?php echo form_close() ?>
<!-- Modal S-->
<div class="modal fade" id="dataModal" tabindex="-1" style="margin-top:3em;" aria-labelledby="dataodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;width:80vh">

        </div>
    </div>
</div>
<!-- Modal E-->
<!-- info Modal(사이즈 때문에 나눔) S-->
<div class="modal fade" id="infoModal" tabindex="-1" style="margin-top:3em;" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;width:70vh">

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
let filesArray = <?php
if (!empty(_elm($aDatas, 'IMGS_INFO'))) {
    $initialImages = [];
    foreach (_elm($aDatas, 'IMGS_INFO') as $aKey => $images) {
        $initialImages[] = [
            'name' => _elm($images, 'I_IMG_NAME'),  // 이미지 파일명
            'size' => _elm($images, 'I_IMG_SIZE'),  // 이미지 파일 크기
            'type' => _elm($images, 'I_IMG_MIME_TYPE'), // MIME 타입
            'path' => '/' . _elm($images, 'I_IMG_PATH'),  // 이미지 경로
               // 이미지 ID
        ];
    }
    echo json_encode($initialImages);  // PHP 배열을 JSON으로 변환
} else {
    echo '[]';  // 데이터가 없을 경우 빈 배열 전달
}
?>;
let groupProductLists = <?php echo json_encode( $goodsGroupIdxs ); ?>;
let productPcikList = <?php echo json_encode( $productIdxs ); ?>;
let addProductPickList = <?php echo json_encode( $addProductIdxs );?>;
var optionGroupIndex = <?php echo $optionGroupIndex?? 0 ?>;
let tagDataString = <?php
if (!empty(_elm($aDatas, 'G_OPTION_INFO'))) {
    echo _elm($aDatas, 'G_OPTION_INFO');
} else {
    echo "'{}'";
}
?>;
console.log( "tagDataString::", tagDataString );
let tagData = {};
try {
    tagData = tagDataString;
    console.log("초기화된 tagData:", tagData);
} catch (e) {
    //console.error("JSON 파싱 에러:", e);
}

function loadAddInfo(){
    let data = '';
    let url  = '/apis/goods/getPopRequiredLists';
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
            $('#infoModal .modal-content').empty().html( response.page_datas.lists_row );
            $('.dropdown-layer').hide();
            //var modal = new bootstrap.Modal(document.getElementById(id));
            var modalElement = document.getElementById('infoModal');
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
    });       // 모달
}


$('#groupSort').sortable({
    handle: '.group-move-icons',
    update: function(event, ui) {
        var idsInOrder = $('#groupSort').sortable('toArray',{ attribute : 'data-idx'});

        console.log(idsInOrder);
    }
});
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


// 서버에서 받아온 데이터로 Blob 객체 생성
async function convertToBlob(fileData) {
    try {
        const response = await fetch(fileData.path);
        const blob = await response.blob();
        return new File([blob], fileData.name, { type: fileData.type });
    } catch (error) {
        console.error('Error converting to Blob:', error);
        return null; // 에러 발생 시 null 반환
    }
}



async function initializeFilesArray() {
    const dataTransfer = new DataTransfer();

    for (let i = 0; i < filesArray.length; i++) {
        const fileData = filesArray[i];
        const file = await convertToBlob(fileData);

        if (file) {
            dataTransfer.items.add(file); // 변환된 File 객체를 추가
        } else {
            console.error(`Failed to convert fileData to File:`, fileData);
        }
    }

    // 파일들을 input[type="file"] 필드에 추가
    document.getElementById('i_goods_img').files = dataTransfer.files;

    renderPreview();  // Blob으로 변환된 데이터를 렌더링
}

// 서버에서 받아온 데이터 초기화
initializeFilesArray();

/* 이미지 드롭다운 재정의 */
$('#image-upload-button').on('click', function(e) {
    e.preventDefault();
    $fileInput.click(); // 숨겨진 파일 입력 요소 트리거
});

// 드래그한 파일이 드랍존으로 들어왔을 때
$uploadContainer.on('dragover', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $uploadContainer.addClass('dragover');
});

// 드래그한 파일이 드랍존을 떠났을 때
$uploadContainer.on('dragleave', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $uploadContainer.removeClass('dragover');
});

// 파일이 드랍되었을 때
$uploadContainer.on('drop', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $uploadContainer.removeClass('dragover');

    const files = Array.from(e.originalEvent.dataTransfer.files);
    addFiles(files);
});

// 파일 선택 input에서 파일이 선택되었을 때
$fileInput.on('change', function(e) {
    const files = Array.from(e.target.files);
    addFiles(files);
    $fileInput.val(''); // 파일 선택 후 input을 초기화하여 동일한 파일을 다시 선택할 수 있도록 함
});

function addFiles(files) {
    files.forEach(file => {
        // 중복되지 않도록 검사: name과 size로 중복 체크
        if (!filesArray.some(f => f.name === file.name && f.size === file.size)) {
            // 새 파일 객체를 추가
            filesArray.push({
                name: file.name,
                size: file.size,
                type: file.type,
                file: file, // 실제 File 객체
                path: URL.createObjectURL(file) // 미리보기용 URL 생성
            });
        }
    });
    initializeFilesArray();
    console.log('Updated filesArray:', filesArray);  // 파일 추가 후 배열 확인
    renderPreview();  // 미리보기 렌더링
    updateUI();  // UI 업데이트
}

function renderPreview() {
    $previewZone.empty(); // 기존 미리보기 초기화

    filesArray.forEach((fileData, index) => {
        const $imgContainer = $('<div>').addClass('preview-container').css({
            'position': 'relative',
            'margin': '5px',
            'width': '150px',
            'height': '180px',
            'cursor': 'move'
        }).attr('data-index', index);

        if (fileData.file instanceof Blob) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const $img = $('<img>').attr('src', e.target.result).css({
                    'width': '100%',
                    'height': '100%',
                    'object-fit': 'cover'
                });
                $imgContainer.append($img);
            };
            reader.readAsDataURL(fileData.file);
        } else {
            const $img = $('<img>').attr('src', fileData.path).css({
                'width': '100%',
                'height': '100%',
                'object-fit': 'cover'
            });
            $imgContainer.append($img);
        }

        const $deleteButton = $('<button>').addClass('delete-button').html(
        `
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#clip0_286_1517)">
        <path d="M12.5 3.5L3.5 12.5" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M12.5 12.5L3.5 3.5" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
        </g>
        <defs>
        <clipPath id="clip0_286_1517">
        <rect width="16" height="16" fill="white"/>
        </clipPath>
        </defs>
        </svg>
        `
        ).css({
            'position': 'absolute',
            'top': '5px',
            'right': '5px',
            'background-color': '#616876',
            'display': 'none'
        });

        const $orderLabel = $('<div>').addClass('order-label').text(index + 1).css({
            'position': 'absolute',
            'top': '5px',
            'left': '5px',
            'background-color': 'rgba(0, 0, 0, 0.6)',
            'color': 'white',
            'padding': '2px 5px',
            'border-radius': '3px',
            'font-size': '12px',
            'font-weight': 'bold',
        });

        $deleteButton.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const index = $(this).parent().data('index');
            const imgIdx = filesArray[index].idx;
            removeFile(index, imgIdx);
        });

        $imgContainer.append($deleteButton).append($orderLabel);
        $previewZone.append($imgContainer);

        $imgContainer.hover(
            function() {
                $deleteButton.show();
            },
            function() {
                $deleteButton.hide();
            }
        );
    });

    if (filesArray.length > 0) {
        $uploadButton.show();
    } else {
        $uploadButton.hide();
    }

    // jQuery UI의 sortable 적용: 드래그 앤 드롭으로 순서 변경 가능
    $previewZone.sortable({
        update: function() {
            const newFilesArray = [];
            $previewZone.children('.preview-container').each(function(index) {
                const oldIndex = $(this).data('index');
                newFilesArray.push(filesArray[oldIndex]);
                $(this).data('index', index); // 새로운 인덱스 업데이트
                $(this).find('.order-label').text(index + 1); // 라벨 업데이트
            });
            filesArray = newFilesArray;
            console.log("Updated filesArray:", filesArray);
        }
    });

    $previewZone.disableSelection();
}

function updateOrderLabels() {
    $previewZone.children('.preview-container').each((index, container) => {
        $(container).find('.order-label').text(index + 1); // 순서 라벨 업데이트
    });
}


// UI 업데이트 함수
function updateUI() {
    if (filesArray.length > 0) {
        $uploadText.hide();
        $uploadContainer.css('background-image', 'none');
    } else {
        $uploadText.show();
        $uploadContainer.css('background-image', 'url("/dist/img/file_upload_bg.svg")');
    }
}

// 순서 라벨 업데이트 함수
function updateOrderLabels() {
    $previewZone.children('.preview-container').each(function(index) {
        //$(this).find('.order-label').text(`Order: ${index + 1}`);
    });
}


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
    var idxs = [];

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
        }else if( id == 'aGroupGoodsTable' ){
            var idx = groupProductLists.indexOf(targetIdx); // 해당 값의 인덱스를 찾습니다.
            if (index !== -1) {
                groupProductLists.splice(index, 1); // 해당 인덱스에서 1개 요소를 제거합니다.


            }
            idxs.push( targetIdx );
        }

    });
    if( id == 'aGroupGoodsTable' ){
        deleteToCopyGoodsConfirm(idxs);
    }
}

function deleteToCopyGoodsConfirm( idxs ){
    box_confirm('해당 상품의 데이터를 모두 삭제하시겠습니까?<br>데이터 및 이미지등 모든 상품정보가 삭제됩니다.', 'q', '', deleteToCopyGoods, idxs);
}

function deleteToCopyGoods( idxs ){

    $.ajax({
        url: '/apis/goods/deleteToCopyGoods',
        type: 'post',
        data: 'goodsIdxs='+idxs,
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


function copyGoodsConfirm( goods_idx ){
    box_confirm('상품을 복사하시겠습니까?<br>현재 성품과 동일한 상품이 추가되며<br> 옵션 및 재고도 동일하게 세팅됩니다.', 'q', '', copyGoods, goods_idx);
}

function copyGoods( goods_idx ){
    $.ajax({
        url: '/apis/goods/copyGoods',
        type: 'post',
        data: 'goodsIdx='+goods_idx,
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
            allReloadGroupGoods( goods_idx );
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

// 전체 그룹상품 리로드
function allReloadGroupGoods( goods_idx )
{

    $.ajax({
        url: '/apis/goods/allReloadGroupGoods',
        method: 'POST',
        data: 'i_goods_idx='+goods_idx,
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
            $("#aGroupGoodsTable tbody").empty().html( response.page_datas.lists_row );
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            return false;
        }
    });
}

// 새 파일을 추가할 때 파일 리스트에 추가
async function addFilesToInput(filesArray) {
    var dataTransfer = new DataTransfer();

    // filesArray의 각 항목을 File 객체로 변환
    for (let fileData of filesArray) {
        const file = await convertToBlob(fileData);  // 비동기적으로 파일을 변환
        if (file) {
            dataTransfer.items.add(file);  // File 객체를 DataTransfer에 추가
        } else {
            console.error('Failed to convert:', fileData);
        }
    }

    // 기존 파일 입력 필드에 파일 리스트를 추가
    document.getElementById('i_goods_img').files = dataTransfer.files;
}
function frmModifyConfirm( e )
{
    box_confirm('수정 하시겠습니까?', 'q', '', frmModify, e);

}
function getEditorHTMLWithStyle(editor) {
    // 에디터의 HTML 내용 가져오기
    const htmlContent = editor.getHTML();

    // 에디터 요소 직접 접근
    const editorElement = editor.options.el.querySelector('.toastui-editor-contents');

    // 스타일 정보 추출 (없을 경우 빈 문자열)
    const style = editorElement ? editorElement.getAttribute('style') : '';

    // HTML에 스타일 정보 추가
    return `<div class="ProseMirror toastui-editor-contents" style="${style}">${htmlContent}</div>`;
}


async function frmModify(e) {
    e.preventDefault();
    e.stopPropagation();
    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_modify').find('input, button, select');

    // 마크다운 에디터 값 설정
    $('#frm_modify [name=i_description]').val( description_editor.getMarkdown() );
    $('#frm_modify [name=i_content_pc]').val( contents_editor.getMarkdown() );
    $('#frm_modify [name=i_content_mobile]').val( m_contents_editor.getMarkdown() );

    var isSubmit = true;

    // 필수 필드 확인
    $('#frm_modify').find('[data-required]').each(function() {
        var $input = $(this);
        var value = $.trim($input.val());
        var errorMessage = $input.data('required');

        if (value === '') {
            _form_error($input.attr('id'), errorMessage);
            error_lists.push(errorMessage);
            isSubmit = false;
        }
    });

    if (!isSubmit) {
        var error_message = error_lists.join('<br />');
        box_alert(error_message, 'e');
        inputs.prop('disabled', false); // 폼 요소를 다시 활성화

        return false;
    }

    // 먼저 파일을 input 필드에 추가 (비동기 작업이므로 await 사용)
    // 순서 변경된 파일 목록을 가져오기
    // const orderedFiles = [];
    // $('#preview-zone img').each(function() {
    //     const fileIndex = $(this).data('file-index');
    //     if (filesArray[fileIndex]) {
    //         orderedFiles.push(filesArray[fileIndex]);
    //     }
    // });


    // 파일이 추가된 후에 FormData 생성
    var formData = new FormData($('#frm_modify')[0]);
    // filesArray.forEach(file => {
    //     formData.append('i_goods_img[]', file);
    // });
    //filesArray.forEach((fileData, index) => {
    var nFile = 0;
    filesArray.forEach(file => {
        formData.append(`img_info[${nFile}][filename]`, file.name);
        formData.append(`img_info[${nFile}][order]`, nFile); // 각 파일의 순서 정보를 추가
        formData.append(`i_goods_img[${nFile}]`, file); // 실제 파일 데이터
        nFile ++;
    });
    console.log( '저장:::',tagData );
    if (typeof tagData !== 'undefined') {
        formData.append('i_tag_data', JSON.stringify(tagData));
    } else {
        console.warn("tagData is undefined or empty.");
    }

    // 그룹상품 데이터 세팅
    $('#aGroupGoodsTable tbody tr').each( function(){
        formData.append('i_goods_group_idxs[]', $(this).data('idx'));
    } );




    $.ajax({
        url: '/apis/goods/goodsDetailProc2',
        method: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {
            // inputs.prop('disabled', true);
            $('#preloader').show();
        },
        complete: function() {
            inputs.prop('disabled', false);
        },
        success: function(response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status != 200) {
                var error_message = response.errors.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }
            if (window.opener) {
                // 부모 창의 특정 함수 호출 (부모 창에서 정의된 함수)
                window.opener.reloadGroupGoods();
            } else {
                console.log("Parent window not accessible");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
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
            }else if( id == 'add_product' ){
                goods_idxs.forEach(function(p_idx) {
                    addProductPickList.push(p_idx);
                });

                $("#aAddGoodsTable tbody").append( response.page_datas.lists_row );

            }else if( id == 'group_product' ){
                goods_idxs.forEach(function(p_idx) {
                    groupProductLists.push(p_idx);
                });

                $("#aGroupGoodsTable tbody").append( response.page_datas.lists_row );
            }


            setTimeout(() => {
                getSearchList();
            }, 200);

        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            console.log(textStatus);

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
        case 'group_product':
            url = '/apis/goods/getPopProductLists';
            data='openGroup='+gbn+'&picLists='+groupProductLists;
            console.log( 'groupProductLists' );
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
function createIconButton(iconClass, tooltip) {
    const button = document.createElement('button');
    button.innerHTML = `<i class="${iconClass}"></i>`;
    button.type= "button";
    button.title = tooltip;
    button.style.marginRight = '4px';
    button.style.cursor = 'pointer';
    return button;
}

var description_editor_value = <?php echo json_encode( htmlspecialchars_decode( _elm( $aDatas, 'G_SHORT_DESCRIPTION' ) ) )?>;
var contents_editor_value = <?php echo json_encode( htmlspecialchars_decode( _elm( $aDatas, 'G_CONTENT_PC' ) ) )?>;
var m_contents_editor_value = <?php echo json_encode( htmlspecialchars_decode(  _elm( $aDatas, 'G_CONTENT_MOBILE' ) ) )?>;
var description_editor, contents_editor, m_contents_editor;
//document.addEventListener('DOMContentLoaded', function() {
    // WYSIWYG 모드에서 선택된 블록에 CSS를 적용하여 정렬 처리
    function applyAlignment(editor, alignType) {
        if (editor.isWysiwygMode()) {
            const wysiwygEditor = editor.getCurrentModeEditor().$editorContainerEl;
            const selection = window.getSelection();
            const range = selection.getRangeAt(0);  // 현재 선택된 범위 가져오기

            if (range && range.commonAncestorContainer) {
                const selectedBlock = range.commonAncestorContainer.closest('p, div, h1, h2, h3, h4, h5, h6');
                if (selectedBlock) {
                    selectedBlock.style.textAlign = alignType;  // 정렬 스타일 적용
                    console.log(`${alignType} 정렬 적용됨`);
                } else {
                    alert('선택된 블록이 없습니다.');
                }
            } else {
                alert('선택된 범위가 없습니다.');
            }
        } else {
            alert('WYSIWYG 모드에서만 사용할 수 있습니다.');
        }
    }




    /* 토스트 UI 관련 자바스크립트 시작 */
        function getUriParams(uri) {
            uri = uri.trim();
            uri = uri.replaceAll('&amp;', '&');
            if (uri.indexOf('#') !== -1) {
                let pos = uri.indexOf('#');
                uri = uri.substr(0, pos);
            }

            let params = {};

            uri.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (str, key, value) { params[key] = value; });
            return params;
        }

        function codepenPlugin() {
            const toHTMLRenderers = {
                codepen(node) {
                    const html = renderCodepen(node.literal);

                    return [
                        { type: 'openTag', tagName: 'div', outerNewLine: true },
                        { type: 'html', content: html },
                        { type: 'closeTag', tagName: 'div', outerNewLine: true }
                    ];
                }
            }

            function renderCodepen(uri) {
                let uriParams = getUriParams(uri);

                let height = 400;

                let preview = '';

                if (uriParams.height) {
                    height = uriParams.height;
                }

                let width = '100%';

                if (uriParams.width) {
                    width = uriParams.width;
                }

                if (!isNaN(width)) {
                    width += 'px';
                }

                let iframeUri = uri;

                if (iframeUri.indexOf('#') !== -1) {
                    let pos = iframeUri.indexOf('#');
                    iframeUri = iframeUri.substr(0, pos);
                }

                return '<iframe height="' + height + '" style="width: ' + width + ';" scrolling="no" title="" src="' + iframeUri + '" frameborder="no" allowtransparency="true" allowfullscreen="true"></iframe>';
            }

            return { toHTMLRenderers }
        }
        // 유튜브 플러그인 끝

        // repl 플러그인 시작
        function replPlugin() {
            const toHTMLRenderers = {
                repl(node) {
                    const html = renderRepl(node.literal);

                    return [
                        { type: 'openTag', tagName: 'div', outerNewLine: true },
                        { type: 'html', content: html },
                        { type: 'closeTag', tagName: 'div', outerNewLine: true }
                    ];
                }
            }

            function renderRepl(uri) {
                var uriParams = getUriParams(uri);

                var height = 400;

                if (uriParams.height) {
                    height = uriParams.height;
                }

                return '<iframe frameborder="0" width="100%" height="' + height + 'px" src="' + uri + '"></iframe>';
            }

            return { toHTMLRenderers }
        }

        function youtubePlugin() {
            const toHTMLRenderers = {
                youtube(editor) {
                const html = renderYoutube(editor.literal);

                return [
                    { type: 'openTag', tagName: 'div', outerNewLine: true },
                    { type: 'html', content: html },
                    { type: 'closeTag', tagName: 'div', outerNewLine: true }
                ];
                }
            };

            function renderYoutube(uri) {
                uri = uri.replace('https://www.youtube.com/watch?v=', '');
                uri = uri.replace('https://youtu.be/', '');

                console.log( "111::"+uri );
                const youtubeId = uri.split('?')[0];

                return `
                <div style="max-width:500px; margin-left:auto; margin-right:auto;" class="ratio-16/9 relative">
                    <iframe class="absolute top-0 left-0 w-full" width="100%" height="100%"
                            src="https://www.youtube.com/embed/${youtubeId}"
                            frameborder="0"
                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
                `;
            }

            return { toHTMLRenderers };
            }



        // katex 플러그인
        function katexPlugin() {
            const toHTMLRenderers = {
                katex(node) {
                    let html = katex.renderToString(node.literal, {
                        throwOnError: false
                    });

                    return [
                        { type: 'openTag', tagName: 'div', outerNewLine: true },
                        { type: 'html', content: html },
                        { type: 'closeTag', tagName: 'div', outerNewLine: true }
                    ];
                },
            }

            return { toHTMLRenderers }
        }

        const ToastEditor__chartOptions = {
            minWidth: 100,
            maxWidth: 600,
            minHeight: 100,
            maxHeight: 300
        };


    // 에디터 초기화 함수
    function initializeEditor(editorId, editorHeight, initialValue) {


        var editor = new toastui.Editor({
            el: document.querySelector(editorId),
            height: editorHeight+'px',
            initialEditType: 'wysiwyg',
            previewStyle: 'vertical',
            initialValue: initialValue, // 초기 값을 바로 적용
            toolbarItems: [
                ['heading', 'bold', 'italic', 'strike',],
                ['hr', 'quote'],
                ['ul', 'ol', 'task'],
                ['table', 'link'],
                ['image'],
                ['indent', 'outdent'],
                ['scrollSync'],

            ],
            //plugins: , // YouTube 플러그인 추가[],
            plugins: [
                [toastui.Editor.plugin.codeSyntaxHighlight, { highlighter: Prism }],
                toastui.Editor.plugin.colorSyntax,
                toastui.Editor.plugin.tableMergedCell,
                toastui.Editor.plugin.uml,
                katexPlugin,
                youtubePlugin,
                codepenPlugin,
                replPlugin
            ],
            customHTMLSanitizer: html => {
                return DOMPurify.sanitize(html, { ADD_TAGS: ["iframe"], ADD_ATTR: ['width', 'height', 'allow', 'allowfullscreen', 'frameborder', 'scrolling', 'style', 'title', 'loading', 'allowtransparency'] }) || ''
            },
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



        return editor;
    }

    // description_editor 초기화
    description_editor = initializeEditor('#description_editor', '250', description_editor_value);

    // m_contents_editor 초기화
    m_contents_editor = initializeEditor('#m_contents_editor', '800', m_contents_editor_value);

    // contents_editor 초기화
    contents_editor = initializeEditor('#contents_editor', '800', contents_editor_value);

    console.log('All editors initialized');
//});


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
            <td><input type="text" class="form-control option-spec" name="i_option_spec[]"></td>
            <td><input type="text" class="form-control option-barcode" name="i_option_barcode[]"></td>
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

    }else if( gbn == 'options_group' ){

        var html = `
        <tr class="options-group" data-group-index="${optionGroupIndex}">
            <td><input type="text" class="form-control group-key-input" id="error_i_option_group_keys[]" name="i_option_group_keys[]"></td>
            <td><div class="tag-input-container"><input type="text" class="tag-input form-control option-group-value" name="i_option_group_value[${optionGroupIndex}][]"></div></td>
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
                        'onclick' => 'deleteOptionGroup(this);',
                    ]
                ]);
            ?>
            </td>
        </tr>
        `;
        $("#aOptionGroupTable #aTbody").append(html);
        optionGroupIndex++;
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

/// Enter 키 입력 감지
$(document).on('keydown', '.option-group-value', function(event) {
    if (event.keyCode === 13) {  // Enter key
        event.preventDefault();
        handleInput($(this));
    }
});

//그룹상품 리얼컬러 입력
$(document).on('input', '[name=i_real_color]', function () {
    if( $(this).val() != '' ){
        var g_idx = $(this).data('g-idx');
        var colorText = $(this).val();
        console.log( "g_idx", g_idx );
        console.log( "colorText", colorText );

        $.ajax({
            url: '/apis/goods/changeGoodsRealColor',
            method: 'POST',
            data: 'i_goods_idx='+g_idx+'&i_colorText='+colorText,
            processData: false,
            cache: false,
            beforeSend: function () {
                //$('#preloader').show();
            },
            complete: function() { },
            success: function(response)
            {
                //submitSuccess(response);
                //$('#preloader').hide();
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
                //submitError(jqXHR.status, errorThrown);
                console.log(textStatus);
                $('#preloader').hide();
                return false;
            }
        });
    }
});


// 포커스 아웃(blur) 감지
$(document).on('blur', '.option-group-value', function() {
    handleInput($(this));
});
// i_option_group_keys[] 값 변경 감지
$(document).on('input', '.group-key-input', function () {
    var $input = $(this);
    var oldGroupKey = $input.data('old-value') || ''; // 이전 값 저장
    var newGroupKey = $input.val().trim();

    // 값이 변경되었을 경우에만 실행
    if (oldGroupKey !== newGroupKey && newGroupKey !== "") {
        if (tagData.hasOwnProperty(oldGroupKey)) {
            tagData = renameKeyPreserveOrder(tagData, oldGroupKey, newGroupKey); // 키값 변경 및 순서 유지
        }

        console.log("Updated tagData::", tagData);

        // 새로운 값을 old-value로 저장
        $input.data('old-value', newGroupKey);

        // 새로운 값을 old-value로 저장
        applyCombinationHeaders( tagData );
    }
});

function renameKeyPreserveOrder(obj, oldKey, newKey) {
    let newObj = {};

    Object.entries(obj).forEach(([key, value]) => {
        if (key === oldKey) {
            newObj[newKey] = value; // 키값 변경
        } else {
            newObj[key] = value; // 기존 키 유지
        }
    });

    return newObj;
}


// 공통 로직 처리 함수
function handleInput($input) {
    var inputValue = $input.val().trim();
    if (inputValue === "") return;  // 빈 값 무시

    // i_option_group_keys의 값 가져오기
    var groupKey = $input.closest('tr').find('.group-key-input').val().trim();

    console.log("groupKey:", groupKey); // 디버깅: groupKey 값 확인
    if (groupKey === "") {
        box_alert("옵션 그룹 키를 입력하세요.", 'i');
        return;
    }

    // tagData가 비어있을 경우 초기화
    if (!tagData || typeof tagData !== "object") {
        tagData = {};
        console.log("tagData 초기화됨:", tagData); // 디버깅 로그
    }

    // groupKey가 tagData에 없으면 초기화
    if (!tagData[groupKey]) {
        tagData[groupKey] = [];
        console.log(`tagData[${groupKey}] 초기화됨`); // 디버깅 로그
    }

    // 데이터 추가 (중복 방지)
    if (!tagData[groupKey].some(item => item.value === inputValue)) {
        tagData[groupKey].push({ value: inputValue });
    } else {
        console.log(`"${inputValue}" 값이 이미 존재함`);
    }

    console.log("Updated tagData::", tagData); // 최종 업데이트된 tagData 확인

    // 태그 박스 생성
    var tagHtml = `
        <div class="tag" data-value="${inputValue}">
            <span class="tag-text">${inputValue}</span>
            <span class="remove-tag">×</span>
        </div>
    `;

    // 태그 박스를 input 앞에 추가
    $input.before(tagHtml);

    // input 값 초기화
    $input.val('');
    addOptionRows(tagData);
}

// 태그 삭제 버튼 클릭 이벤트
$(document).on('click', '.remove-tag', function() {
    var tagDiv = $(this).parent('.tag');
    var tagValue = tagDiv.find('.tag-text').text().trim();  // 수정된 부분
    var groupKey = $(this).closest('tr').find('.group-key-input').val().trim();

    // tagData에서 해당 값 삭제
    if (tagData[groupKey]) {
        tagData[groupKey] = tagData[groupKey].filter(item => item.value !== tagValue);

        // 그룹이 비어있으면 삭제
        if (tagData[groupKey].length === 0) {
            delete tagData[groupKey];
        }
    }

    console.log("tagData삭제::", tagData);
    // 태그 박스 삭제
    if( $('#aOptionTable tbody tr').length > 0 ){
        if( confirm( '기존 데이터가 변경 됩니다. 진행하시겠습니까?' ) ){
            addOptionRows(tagData);
            tagDiv.remove();
        }
    }else{
        tagDiv.remove();
    }


});



function addOptionRowsConfirm(){
    if( $('#aOptionTable tbody tr').length > 0 ){
        box_confirm('기존 데이터가 변경 됩니다. 진행하시겠습니까?', 'q', '', addOptionRows, tagData);
    }else{
        addOptionRows();
    }
}
let existingCombinations = [];  // 기존에 추가된 조합을 저장


function addOptionRows(tagsObject) {
    $("#aOptionTable tbody").empty();  // 기존 행 초기화

    // 각 그룹 키에 대해 테이블 생성
    if( $('[name=i_option_combination_flag]').is(':checked') === true ){
        applyCombinationHeaders( tagData );


         // 옵션 그룹을 배열로 변환
        let optionGroups = Object.values(tagsObject).map(group => group.map(item => item.value));

        // 조합 생성
        let allCombinations = generateCombinations(optionGroups);

        // 테이블 초기화
        $("#aOptionTable tbody").empty();

        // 조합을 테이블에 추가
        allCombinations.forEach(combination => {
            let html = '<tr>';

            // 옵션 조합
            combination.forEach((opt, i) => {
                let inputName = `i_option_value${i > 0 ? i + 1 : ''}`;
                html += `<td><input type="text" class="form-control" name="${inputName}[]" value="${opt}" readonly></td>`;
            });

            // 추가 필드 및 삭제 버튼
            html += `
                <td><input type="text" class="form-control option-spec" name="i_option_spec[]"></td>
                <td><input type="text" class="form-control option-barcode" name="i_option_barcode[]"></td>
                <td><input type="text" class="form-control option-stock" name="i_option_stock[]"></td>
                <td><input type="text" class="form-control option-add_amt" numberwithcomma name="i_option_add_price[]"></td>
                <td>
                    <select name="i_option_status[]" class="form-select" style="max-width: 80px; margin-right: 2.235em;">
                        <option value="Y">노출</option>
                        <option value="N">비노출</option>
                    </select>
                </td>

            </tr>`;

            $("#aOptionTable tbody").append(html);
        });
    }else{
        resetTableHeaders();
        Object.keys(tagsObject).forEach(function(groupKey) {
            tagsObject[groupKey].forEach(function(tag) {
                var html = `
                    <tr>
                        <td><input type="text" class="form-control option-key" name="i_option_keys[]" value="${groupKey}" readonly></td>
                        <td><input type="text" class="form-control option-value" name="i_option_value[]" value="${tag.value}" readonly></td>
                        <td><input type="text" class="form-control option-spec" name="i_option_spec[]"></td>
                        <td><input type="text" class="form-control option-barcode" name="i_option_barcode[]"></td>
                        <td><input type="text" class="form-control option-stock" name="i_option_stock[]"></td>
                        <td><input type="text" class="form-control option-add_amt" numberwithcomma name="i_option_add_price[]"></td>
                        <td>
                            <select name="i_option_status[]" class="form-select" style="max-width: 80px; margin-right: 2.235em;">
                                <option value="Y">노출</option>
                                <option value="N">비노출</option>
                            </select>
                        </td>

                    </tr>
                `;
                $("#aOptionTable tbody").append(html);
            });
        });
    }
    updateGoodsStock();
}
function arraysEqual(a, b) {
    if (a.length !== b.length) return false;
    return a.every((val, idx) => val === b[idx]);
}
// function addOptionRows(tagsObject) {
//     $("#aOptionTable tbody").empty();  // 기존 행 초기화

//     // 각 그룹 키에 대해 테이블 생성
//     if( $('[name=i_option_combination_flag]').is(':checked') === true ){
//         applyCombinationHeaders( tagData );


//          // 옵션 그룹을 배열로 변환
//         let optionGroups = Object.values(tagsObject).map(group => group.map(item => item.value));

//         // 조합 생성
//         let allCombinations = generateCombinations(optionGroups);

//         // 테이블 초기화
//         $("#aOptionTable tbody").empty();

//         // 조합을 테이블에 추가
//         allCombinations.forEach(combination => {
//             let html = '<tr>';

//             // 옵션 조합
//             combination.forEach((opt, i) => {
//                 let inputName = `i_option_values${i > 0 ? i + 1 : ''}`;
//                 html += `<td><input type="text" class="form-control" name="${inputName}[]" value="${opt}" readonly></td>`;
//             });

//             // 추가 필드 및 삭제 버튼
//             html += `
//                 <td><input type="text" class="form-control option-spec" name="i_option_spec[]"></td>
//                 <td><input type="text" class="form-control option-barcode" name="i_option_barcode[]"></td>
//                 <td><input type="text" class="form-control option-stock" name="i_option_stock[]"></td>
//                 <td><input type="text" class="form-control option-add_amt" numberwithcomma name="i_option_add_price[]"></td>
//                 <td>
//                     <select name="i_option_group_status[]" class="form-select" style="max-width: 80px; margin-right: 2.235em;">
//                         <option value="Y">노출</option>
//                         <option value="N">비노출</option>
//                     </select>
//                 </td>

//             </tr>`;

//             $("#aOptionTable tbody").append(html);
//         });
//     }else{
//         resetTableHeaders();
//         Object.keys(tagsObject).forEach(function(groupKey) {
//             tagsObject[groupKey].forEach(function(tag) {
//                 var html = `
//                     <tr>
//                         <td><input type="text" class="form-control option-key" name="i_option_keys[]" value="${groupKey}" readonly></td>
//                         <td><input type="text" class="form-control option-value" name="i_option_value[]" value="${tag.value}" readonly></td>
//                         <td><input type="text" class="form-control option-spec" name="i_option_spec[]"></td>
//                         <td><input type="text" class="form-control option-barcode" name="i_option_barcode[]"></td>
//                         <td><input type="text" class="form-control option-stock" name="i_option_stock[]"></td>
//                         <td><input type="text" class="form-control option-add_amt" numberwithcomma name="i_option_add_price[]"></td>
//                         <td>
//                             <select name="i_option_group_status[]" class="form-select" style="max-width: 80px; margin-right: 2.235em;">
//                                 <option value="Y">노출</option>
//                                 <option value="N">비노출</option>
//                             </select>
//                         </td>

//                     </tr>
//                 `;
//                 $("#aOptionTable tbody").append(html);
//             });
//         });
//     }

// }
// 옵션 그룹 삭제 및 인덱스 재정렬
function deleteOptionGroup(el) {

    var groupKey = $(el).closest('tr').find('.group-key-input').val().trim();
    // tagData에서 해당 값 삭제
    if (tagData[groupKey]) {
        // 그룹이 비어있으면 삭제
        delete tagData[groupKey];
    }

    console.log("tagData그룹삭제::", tagData);
    $(el).closest('tr').remove();
    addOptionRows( tagData );
    updateGoodsStock();
}

/**
 * 조합형 선택 시 테이블 헤더와 컬럼을 조합 수에 맞춰 조정
 */
function applyCombinationHeaders(tagData) {
    let combinationKeys = Object.keys(tagData);
    let theadHtml = '<tr>';
    let colgroupHtml = '';

    // 옵션명 헤더 추가
    combinationKeys.forEach((key, index) => {
        theadHtml += `<th>${key}</th>`;
        colgroupHtml += '<col style="*">';
    });

    // 추가 고정 헤더들
    theadHtml += `
        <th>다해스팩</th>
        <th>다해바코드</th>
        <th>재고수량</th>
        <th>추가금액</th>
        <th>노출여부</th>
    `;

    // 추가 고정 컬럼들
    colgroupHtml += `
        <col style="width:15%;">
        <col style="width:15%;">
        <col style="width:10%;">
        <col style="width:10%;">
        <col style="width:10%;">
    `;

    theadHtml += '</tr>';

    // 테이블에 적용
    $("#aOptionTable colgroup").html(colgroupHtml);
    $("#aOptionTable thead").html(theadHtml);
}

/**
 * 기본 테이블 헤더와 컬럼으로 리셋
 */

function resetTableHeaders() {
    const defaultColgroup = `
        <col style="*">
        <col style="width:15%;">
        <col style="width:15%;">
        <col style="width:10%;">
        <col style="width:10%;">
        <col style="width:10%;">
    `;

    const defaultThead = `
        <tr>
            <th>옵션명</th>
            <th>옵션값</th>
            <th>다해스팩</th>
            <th>다해바코드</th>
            <th>재고수량</th>
            <th>추가금액</th>
            <th>노출여부</th>
        </tr>
    `;

    $("#aOptionTable colgroup").html(defaultColgroup);
    $("#aOptionTable thead").html(defaultThead);
}

// //
// function addFrmRows(el) {
//     var $currentRow = $(el).closest('tr');
//     var groupIndex = $currentRow.closest('tr[data-group-index]').data('group-index');

//     var newRow = `
//     <tr>
//         <td><input type="text" class="form-control option-group-value" name="i_option_group_value[${groupIndex}][]"></td>
//         <td>
//             <select name="i_option_group_status[${groupIndex}][]" class="form-select" style="max-width: 80px; margin-right: 2.235em;">
//                 <option value="Y">노출</option>
//                 <option value="N">비노출</option>
//             </select>
//         </td>
//         <td>
//             <?php
//                 echo getIconAnchor([
//                     'txt' => '',
//                     'icon' => 'delete',
//                     'buttonClass' => '',
//                     'buttonStyle' => '',
//                     'width' => '24',
//                     'height' => '24',
//                     'stroke' => '#616876',
//                     'extra' => [
//                         'onclick' => 'deleteFrmRows(this);',
//                     ]
//                 ]);
//             ?>
//         </td>
//     </tr>
//     `;

//     $currentRow.closest('tbody').append(newRow);

// } 삭제 함수
// function deleteFrmRows(el) {
//     var $currentRow = $(el).closest('tr');
//     var $table = $currentRow.closest('table');

//     // 첫 번째 td가 있는지 확인
//     var $firstTd = $currentRow.find('td:first-child');
//     if ($firstTd.length) {
//         var rowspan = parseInt($firstTd.attr('rowspan') || 1, 10);
//         if (rowspan > 1) {
//             // rowspan 감소
//             $firstTd.attr('rowspan', rowspan - 1);

//             // 다음 행에 첫 번째 td 이동
//             var $nextRow = $currentRow.next('tr');
//             if ($nextRow.length) {
//                 $nextRow.prepend($firstTd);
//             }
//         }
//     }

//     // 현재 행 삭제
//     $currentRow.remove();
// }



// 옵션 행 삭제 버튼
$(document).on('click', '.delete-option-row', function() {
    $(this).closest('tr').remove();
});



// function adjustOptionTableHeaders() {
//     const $optionTable = $("#aOptionTable");
//     const $theadRow = $optionTable.find("thead tr");
//     const $colgroup = $optionTable.find("colgroup");

//     // 기존 옵션명 제거
//     $theadRow.find("th.option-header").remove();
//     $colgroup.find("col.option-col").remove();

//     // 옵션명 및 colgroup에 새로운 항목 추가

//     ("#aOptionGroupTable #aTbody .options-group").each(function (i) {
//         const optionName = `옵션명${i > 0 ? i + 1 : ''}`;

//         // thead에 새로운 th 추가
//         $(`<th class="option-header">${optionName}</th>`).insertBefore($theadRow.find("th").eq(optionCount));

//         // colgroup에 새로운 col 추가
//         $(`<col class="option-col" style="width:15%;">`).insertBefore($colgroup.find("col").eq(optionCount));
//     });
// }
/**
 * 재귀적으로 모든 조합을 생성하는 함수
 * @param {Array} arrays - 배열의 배열 (옵션 그룹들)
 * @returns {Array} - 가능한 모든 조합
 */
function generateCombinations(arrays) {
    if (arrays.length === 0) return [[]];

    let result = [];
    let rest = generateCombinations(arrays.slice(1));

    arrays[0].forEach(item => {
        rest.forEach(r => {
            result.push([item, ...r]);
        });
    });

    return result;
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

// 그룹상품 리로드
function reloadGroupGoods()
{
    var goodsIdxs = [];
    $("#aGroupGoodsTable tbody tr").each(function(){
        goodsIdxs.push( $(this).data('idx') );
    });
    $.ajax({
        url: '/apis/goods/reloadGroupGoods',
        method: 'POST',
        data: 'i_goods_idx='+$('[name=i_goods_idx]').val()+'&order='+goodsIdxs,
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
            $("#aGroupGoodsTable tbody").empty().html( response.page_datas.lists_row );
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            return false;
        }
    });
}


var preloadedCategories = <?php echo json_encode(array_column(_elm($aDatas, 'GOODS_CATEGOTY_LISTS'), 'C_IDX')); ?>;

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
            console.log(textStatus);
            $('#preloader').hide();
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
// 파일 삭제 함수 (인덱스 기반)

function removeFile(index, imgIdx = 0 ) {
    if( imgIdx == 0 ){
        filesArray.splice(index, 1); // 해당 인덱스의 파일 제거
        initializeFilesArray();
        renderPreview(); // 미리보기 갱신
        updateUI(); // UI 업데이트
    }else{
        initializeFilesArray();
        filesArray.splice(index, 1); // 해당 인덱스의 파일 제거
        renderPreview(); // 미리보기 갱신
        updateUI(); // UI 업데이트
    }

}

// 셀렉트 박스 클릭 시 옵션 목록 열기/닫기
$('.select-box').on('click', function() {
    $(this).find('.options').toggle();
});

// 옵션 선택 시 동작
$('.option').on('click', function() {
    var selectedValue = $(this).data('value');
    var selectedText = $(this).html();

    // 선택된 옵션을 상단에 표시
    $('.selected-option').html(selectedText);

    // 옵션 목록 닫기
    $(this).closest('.options').hide();

    // 선택된 값을 콘솔에 출력 (필요에 따라 추가 작업 가능)
    console.log('Selected value:', selectedValue);
});

// 셀렉트 박스 외부 클릭 시 옵션 목록 닫기
$(document).on('click', function(e) {
    if (!$(e.target).closest('.select-box').length) {
        $('.options').hide();
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


}
_initFunc();







</script>




<?php

$owensView->setFooterJs('/assets/js/goods/goods/tooltip.js');
//$owensView->setFooterJs('/assets/js/goods/goods/register.js');



$script = "
";

$owensView->setFooterScript($script);