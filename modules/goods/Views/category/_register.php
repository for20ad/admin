<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);

    $cateCode         = _elm($view_datas, 'cateCode', []);
    $parentInfo       = _elm($view_datas, 'parentInfo', []);


?>


<div style="flex: 1; height: 80vh; overflow: auto;">

    <?php
if(strpos(_elm($_SERVER, 'HTTP_REFERER'), 'goodsRegister') === false){
    echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register', 'autocomplete' => 'off']);
}else{
    echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_pop_register', 'autocomplete' => 'off']);
}
?>
    <input type="hidden" name="i_parent_idx" value="<?php echo _elm( $parentInfo, 'C_PARENT_IDX' )?>">
    <div class="card col-12">
        <!-- 카드 타이틀 -->
        <div class="accordion-card" style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4" fill="none">
                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                    </svg>
                    <p class="body1-c ms-2 mt-1">
                        카테고리 정보
                    </p>
                </div>
                <!-- 아코디언 토글 버튼 -->
                <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
                    <span class="form-selectgroup-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8" fill="none">
                            <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                </label>
            </div>
        </div>

        <div class="card-body">
            <div class="input-group">
                <label class="label body2-c">
                    상위 카테고리
                    <span>*</span>
                </label>
                <?php echo _elm( $parentInfo, 'C_CATE_NAME' )?>
            </div>

            <div class="input-group required">
                <label class="label body2-c">
                    카테고리 코드
                    <span>*</span>
                </label>
                <input type="text" class="form-control" name="i_cate_code" id="i_cate_code"
                    value="<?php echo $cateCode ?>" readonly placeholder="카테고리 코드 고정" />
            </div>

            <div class="input-group required">
                <label class="label body2-c">
                    카테고리 명
                    <span>*</span>
                </label>
                <input type="text" class="form-control" name="i_cate_name" id="i_cate_name" />
            </div>

            <div class="input-group required">
                <label class="label body2-c">
                    진열방법
                    <span>*</span>
                </label>
                <?php
                    $options = ['reg_desc'=>'최신등록순', 'reg_asc'=>'오래된순',];
                    $extras   = ['id' => 'i_ordering_cd', 'class' => 'form-select', 'style' => 'max-width: 110px;margin-right:0.235em;'];
                    $selected = 'reg_desc';
                    echo getSelectBox('i_ordering_cd', $options, $selected, $extras);
                ?>
            </div>

            <div class="input-group" style="margin-bottom:0">
                <label class="label body2-c ">
                    노출여부
                </label>
                <div>
                    <?php
                        $checked = true;

                        $setParam = [
                            'name' => 'i_status',
                            'id' => 'i_status_Y',
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

                        $setParam = [
                            'name' => 'i_status',
                            'id' => 'i_status_N',
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
        </div>
        <!-- 버튼들 -->

    </div>
    <div class="card col-12" style="margin-top:1.3rem;">
        <!-- 카드 타이틀 -->
        <div class="accordion-card" style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4" fill="none">
                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                    </svg>
                    <p class="body1-c ms-2 mt-1">
                        리뷰 지문 설정
                    </p>
                </div>
                <!-- 아코디언 토글 버튼 -->
                <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
                    <span class="form-selectgroup-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8" fill="none">
                            <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                </label>
            </div>
        </div>

        <div class="card-body">
            <div class="input-group">
                <label class="label body2-c">
                <?php
                echo getIconButton([
                    'txt' => '지문추가',
                    'icon' => 'add',
                    'buttonClass' => 'btn-sm btn-white',
                    'buttonStyle' => '',
                    'width' => '21',
                    'height' => '20',
                    'stroke' => '#1D273B',
                    'extra' => [
                        'type' => 'button',
                        'onclick' => 'addGroup();',
                    ]
                ]);
                ?>
                </label>
            </div>
            <div class="container" id="inputContainer" style="margin-top:2.2rem">

            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top:3.25rem; margin-bottom:3rem">
        <div class="form-inline" style="padding-left:30%">
        <?php
        if(strpos(_elm($_SERVER, 'HTTP_REFERER'), 'goodsRegister') === false){
            echo getButton([
                'text' => '취소',
                'class' => 'btn btn-sm btn-white',
                'style' => 'width: 90px; height: 46px',
                'extra' => [
                    'onclick' => 'event.preventDefault();openLayer("",0)',
                ]
            ]);
        }

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

    <?php echo form_close()?>


<script>
    groupCount = 0;
    groups = [];
    columnCounts = {};
    // 묶음 추가 함수 (지문과 값 묶음)
    $('#inputContainer').sortable({
        handle: '.move-icons',  // 드래그 핸들 설정
        items: '.sortable-item',  // 드래그할 수 있는 요소
        placeholder: 'sortable-placeholder',  // 드래그 중 표시될 공간
        update: function(event, ui) {
            // 정렬 후 처리할 작업 (필요에 따라 추가 가능)
            updateGroupLabels();
        }
    });


</script>