<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);

    $cateCode         = _elm($view_datas, 'cateCode', []);
    $parentInfo       = _elm($view_datas, 'parentInfo', []);
    $keywords         = _elm($view_datas, 'keywords', []);



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
            <div class="input-group required">
                <label class="label body2-c">
                    배너<br>이미지 등록
                </label>
                <span style="padding:5px 5px">
                    <?php
                        echo getIconButton([
                            'txt' => '파일 추가',
                            'icon' => 'box_plus',
                            'buttonClass' => 'btn',
                            'buttonStyle' => 'width:130px; height: 36px',
                            'width' => '21',
                            'height' => '20',
                            'stroke' => 'black',
                            'extra' => [
                                'type' => 'button',
                                'onclick' => 'addRows();',
                            ]
                        ]);
                    ?>
                </span>

            </div>
            <div id="fileArea">

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
                <label class="label body2-c">
                <div style="position: relative; display: inline-block; margin-bottom: 10px;">
                    <?php
                    echo getIconButton([
                        'txt' => '키워드관리',
                        'icon' => 'search',
                        'buttonClass' => 'btn-sm btn-white',
                        'buttonStyle' => '',
                        'width' => '21',
                        'height' => '20',
                        'stroke' => '#1D273B',
                        'extra' => [
                            'type' => 'button',
                            'onclick' => 'toggleLayer()',
                        ]
                    ]);
                    ?>
                    </label>
                    <div id="keyword-layer" style="position: absolute; top: 100%; left: 0; background: #fff; border: 1px solid #ddd; padding: 10px; width: 250px; z-index: 1000;display:none;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>키워드 관리</span>
                        </div>
                        <div id="keyword-list" style="margin-top: 10px; max-height: 150px; overflow-y: auto;">
                            <!-- 키워드 목록은 JavaScript로 추가 -->
                        </div>
                        <button type="button" style="margin-top: 10px; background: #007bff; color: white; border: none; padding: 5px 10px; font-size: 12px; cursor: pointer;" onclick="addKeyword(event)">키워드 추가</button>

                    </div>
                </div>
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
    var keywords = <?php echo json_encode($keywords); ?>;

    // 키워드 목록 추가
    keywords.forEach(keyword => {
        const option = `<option value="${keyword.K_IDX}">${keyword.K_NAME}</option>`;
        $(`#keyword-select`).append(option);

        const keywordDiv = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;" id="keyword-row-${keyword.K_IDX}">
                <span>${keyword.K_NAME}</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" onclick="deleteKeyword(event, '${keyword.K_IDX}')">
                    <path d="M2.5 10C2.5 10.9849 2.69399 11.9602 3.0709 12.8701C3.44781 13.7801 4.00026 14.6069 4.6967 15.3033C5.39314 15.9997 6.21993 16.5522 7.12987 16.9291C8.03982 17.306 9.01509 17.5 10 17.5C10.9849 17.5 11.9602 17.306 12.8701 16.9291C13.7801 16.5522 14.6069 15.9997 15.3033 15.3033C15.9997 14.6069 16.5522 13.7801 16.9291 12.8701C17.306 11.9602 17.5 10.9849 17.5 10C17.5 9.01509 17.306 8.03982 16.9291 7.12987C16.5522 6.21993 15.9997 5.39314 15.3033 4.6967C14.6069 4.00026 13.7801 3.44781 12.8701 3.0709C11.9602 2.69399 10.9849 2.5 10 2.5C9.01509 2.5 8.03982 2.69399 7.12987 3.0709C6.21993 3.44781 5.39314 4.00026 4.6967 4.6967C4.00026 5.39314 3.44781 6.21993 3.0709 7.12987C2.69399 8.03982 2.5 9.01509 2.5 10Z" fill="white"/>
                    <path d="M8.33333 8.33333L11.6667 11.6667L8.33333 8.33333ZM11.6667 8.33333L8.33333 11.6667L11.6667 8.33333Z" fill="white"/>
                    <path d="M8.33333 8.33333L11.6667 11.6667M11.6667 8.33333L8.33333 11.6667M2.5 10C2.5 10.9849 2.69399 11.9602 3.0709 12.8701C3.44781 13.7801 4.00026 14.6069 4.6967 15.3033C5.39314 15.9997 6.21993 16.5522 7.12987 16.9291C8.03982 17.306 9.01509 17.5 10 17.5C10.9849 17.5 11.9602 17.306 12.8701 16.9291C13.7801 16.5522 14.6069 15.9997 15.3033 15.3033C15.9997 14.6069 16.5522 13.7801 16.9291 12.8701C17.306 11.9602 17.5 10.9849 17.5 10C17.5 9.01509 17.306 8.03982 16.9291 7.12987C16.5522 6.21993 15.9997 5.39314 15.3033 4.6967C14.6069 4.00026 13.7801 3.44781 12.8701 3.0709C11.9602 2.69399 10.9849 2.5 10 2.5C9.01509 2.5 8.03982 2.69399 7.12987 3.0709C6.21993 3.44781 5.39314 4.00026 4.6967 4.6967C4.00026 5.39314 3.44781 6.21993 3.0709 7.12987C2.69399 8.03982 2.5 9.01509 2.5 10Z" stroke="#616876" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        `;
        $(`#keyword-list`).append(keywordDiv);
    });

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