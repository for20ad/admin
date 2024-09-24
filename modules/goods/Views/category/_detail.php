<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);

    $aData            = _elm($view_datas, 'aData', []);
    $parentInfo       = _elm($view_datas, 'parentInfo', []);


?>


<div style="flex: 1">
    <?php
if(strpos(_elm($_SERVER, 'HTTP_REFERER'), 'goodsRegister') === false){
    echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_modify', 'autocomplete' => 'off']);
}else{
    echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_pop_modify', 'autocomplete' => 'off']);
}
?>
    <input type="hidden" name="i_cate_idx" value="<?php echo _elm( $aData, 'C_IDX' )?>">
    <input type="hidden" name="i_parent_idx" value="<?php echo _elm( $aData, 'C_PARENT_IDX' )?>">
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
                    value="<?php echo _elm( $aData, 'C_CATE_CODE' ) ?>" readonly placeholder="카테고리 코드 고정" />
            </div>

            <div class="input-group required">
                <label class="label body2-c">
                    카테고리 명
                    <span>*</span>
                </label>
                <input type="text" class="form-control" name="i_cate_name" id="i_cate_name"
                    value="<?php echo _elm( $aData, 'C_CATE_NAME' ) ?>" />
            </div>

            <div class="input-group required">
                <label class="label body2-c">
                    진열방법
                    <span>*</span>
                </label>
                <?php
                    $options = ['reg_desc'=>'최신등록순', 'reg_asc'=>'오래된순',];
                    $extras   = ['id' => 'i_ordering_cd', 'class' => 'form-select', 'style' => 'max-width: 110px;margin-right:0.235em;'];
                    $selected = _elm( $aData, 'C_ORDERING_CD' );
                    echo getSelectBox('i_ordering_cd', $options, $selected, $extras);
                ?>
            </div>

            <div class="input-group" style="margin-bottom:0">
                <label class="label body2-c ">
                    노출여부
                </label>
                <div>
                    <?php
                        $checked = false;
                        if( 'Y' == _elm( $aData, 'C_STATUS' ) ){
                            $checked = true;
                        }
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
                        if( 'N' == _elm( $aData, 'C_STATUS' ) ){
                            $checked = true;
                        }
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
                <?php
                if( empty( _elm( $aData, 'questions' ) ) === false ){
                    foreach( _elm( $aData, 'questions' ) as $qKey => $question ){
                ?>
                <div class="form-wrap sortable-item" id="group-wrap-<?php echo $qKey?>">
                    <div class="form-group" id="group-<?php echo $qKey?>">
                        <div style="flex: 1;">
                            <label class="group-label move">
                            <div class="move-icons ui-sortable-handle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 32 32" fill="none">
                                    <g clip-path="url(#clip0_492_18580)">
                                        <path d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z" fill="#616876"></path>
                                        <path d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z" fill="#616876"></path>
                                        <path d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z" fill="#616876"></path>
                                        <path d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z" fill="#616876"></path>
                                        <path d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z" fill="#616876"></path>
                                        <path d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z" fill="#616876"></path>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_492_18580">
                                            <rect width="32" height="32" fill="white"></rect>
                                        </clipPath>
                                    </defs>
                                </svg>
                                지문 <?php echo $qKey?>

                            </div>


                            </label>

                            <input type="hidden" name="questions[<?php echo $qKey?>][q_idx]" value="<?php echo _elm($question, 'q_idx') ?>">
                            <input type="text" class="form-control question-input" name="questions[<?php echo $qKey?>][question]" value="<?php echo _elm( $question, 'question' )?>" placeholder="지문을 입력하세요">
                        </div>
                        <div style="flex: 0 0 auto;">
                            <button type="button" class="btn-sm btn-red" style="margin-top:1.2rem;padding: 5px 10px;font-size: 12px;border:0;" onclick="deleteGroup(<?php echo $qKey?>)">지문 삭제</button>
                        </div>
                    </div>
                    <?php
                    if( empty( _elm( $question, 'values' ) ) === false ){
                    ?>

                    <div class="form-group value-group" id="value-container-<?php echo $qKey?>">
                        <div class="value-row" id="value-row-<?php echo $qKey?>">
                            <?php
                            foreach( _elm( $question, 'values' ) as $eKey => $example ){
                            ?>
                            <div class="row" id="row-<?php echo $qKey?>-<?php echo $eKey?>">
                                <div style="flex: 1.6;">
                                    <label>보기 <?php echo $eKey?></label>
                                    <input type="hidden" name="questions[<?php echo $qKey?>][values][<?php echo $eKey?>][e_idx]" value="<?php echo _elm($example, 'e_idx') ?>">
                                    <input type="text" class="form-control" name="questions[<?php echo $qKey?>][values][<?php echo $eKey?>][description]" value="<?php echo _elm( $example, 'description' )?>" placeholder="값 설명 입력">
                                </div>
                                <div style="flex: 0.4;">
                                    <label>&nbsp;</label>
                                    <select class="selectbox form-select" style="max-width:100px" name="questions[<?php echo $qKey?>][values][<?php echo $eKey?>][value]">
                                        <option value='1' <?php echo _elm( $example, 'value' ) == 1 ? 'selected' : ''?>>1</option>
                                        <option value='2' <?php echo _elm( $example, 'value' ) == 2 ? 'selected' : ''?>>2</option>
                                        <option value='3' <?php echo _elm( $example, 'value' ) == 3 ? 'selected' : ''?>>3</option>
                                        <option value='4' <?php echo _elm( $example, 'value' ) == 4 ? 'selected' : ''?>>4</option>
                                        <option value='5' <?php echo _elm( $example, 'value' ) == 5 ? 'selected' : ''?>>5</option>
                                    </select>
                                </div>
                            </div>
                            <?php
                            }
                            ?>

                        </div>
                        <div style="flex: 0 0 auto;">
                            <button type="button" class="btn-sm btn-white" style="margin-top:1.2rem;font-size: 12px;" onclick="addRow(<?php echo $qKey?>)">보기 추가</button>
                            <button type="button" class="btn-sm btn-red" style="font-size: 12px;" onclick="deleteLastRow(<?php echo $qKey?>)">보기 삭제</button>
                        </div>
                    </div>

                    <?php
                    }
                    ?>
                </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top:3.25rem; margin-bottom:3rem">
        <div class="form-inline" style="padding-left:30%">
        <!-- 버튼들 -->

            <?php
            $btnSize = '180';
            if(strpos(_elm($_SERVER, 'HTTP_REFERER'), 'goodsRegister') === false){
                echo getButton([
                    'text' => '취소',
                    'class' => 'btn',
                    'style' => 'width: 90px; height: 46px',
                    'extra' => [
                        'onclick' => 'event.preventDefault();openLayer("",0)',
                    ]
                ]);
            }else{
                $btnSize = '110';
            }
            ?>
            <?php
            echo getButton([
                'text' => '삭제',
                'class' => 'btn btn-secondary',
                'style' => 'width: '.$btnSize.'px; height: 46px',
                'extra' => [
                    'onclick' => 'event.preventDefault();deleteCategoryConfirm( "'._elm( $aData, 'C_IDX' ).'" )',
                ]
            ]);
            ?>

            <?php
            echo getIconButton([
                'txt' => '저장',
                'icon' => 'success',
                'buttonClass' => 'btn btn-success',
                'buttonStyle' => 'width:'.$btnSize.'px; height: 46px',
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

    <?php echo form_close()?>
<script>

    $('#inputContainer').sortable({
        handle: '.move-icons',  // 드래그 핸들 설정
        items: '.sortable-item',  // 드래그할 수 있는 요소
        placeholder: 'sortable-placeholder',  // 드래그 중 표시될 공간
        update: function(event, ui) {
            // 정렬 후 처리할 작업 (필요에 따라 추가 가능)
            updateGroupLabels();
        }
    });


    groupCount = <?php echo empty( _elm( $aData, 'questions') ) === false ? count( _elm( $aData, 'questions') ) + 1 : 0 ; ?>

    // 페이지 로드 시 기존 데이터를 groups 배열에 추가
    <?php if (!empty(_elm($aData, 'questions'))) { ?>
        groups = [];
        columnCounts = {};
        <?php foreach (_elm($aData, 'questions') as $qKey => $question) { ?>
            groups.push(<?php echo $qKey; ?>);
            columnCounts[<?php echo $qKey; ?>] = <?php echo empty( _elm($question, 'values') ) === false ? count(_elm($question, 'values')): 0; ?>;
        <?php } ?>
    <?php
    }else{
    ?>
    groups = [];
    columnCounts = {};
    <?php
    } ?>



</script>