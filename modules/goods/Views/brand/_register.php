<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);

    $brandCode        = _elm($view_datas, 'brandCode', []);
    $parentInfo       = _elm($view_datas, 'parentInfo', []);


?>


<div style="flex: 1">

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
        <div class="accordion-card"
            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                        fill="none">
                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                    </svg>
                    <p class="body1-c ms-2 mt-1">
                        브랜드 정보
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
                    상위 브랜드
                    <span>*</span>
                </label>
                <?php echo _elm( $parentInfo, 'C_BRAND_NAME' )?>
            </div>
            <div class="input-group">
                <label class="label body2-c">
                    등록 상품수
                    <span>*</span>
                </label>
                <?php echo _elm( $parentInfo, 'C_BRAND_NAME' )?> 개가 등록되어 있습니다. <span style="font-color:##206BC4">( 하위 브랜드 등록상품 포함 )</span>
            </div>

            <div class="input-group required">
                <label class="label body2-c">
                    브랜드 코드
                    <span>*</span>
                </label>
                <input type="text" class="form-control " name="i_brand_code" id="i_brand_code" value="<?php echo $brandCode ?>" readonly placeholder="브랜드 코드 고정"
                    />
            </div>

            <div class="input-group required">
                <label class="label body2-c">
                    브랜드 명
                    <span>*</span>
                </label>
                <input type="text" class="form-control" name="i_brand_name" id="i_brand_name" />
            </div>
            <div class="input-group required">
                <label class="label body2-c">
                    브랜드명(영문)
                    <span>*</span>
                </label>
                <input type="text" class="form-control" name="i_brand_name_eng" id="i_brand_name_eng" />
            </div>

            <!-- <div class="input-group required">
                <label class="label body2-c">
                        PC브랜드<br>이미지 등록
                    <span>*</span>
                </label>
                <input type="file" class="form-control" name="i_brand_pc_img">
            </div> -->
            <div class="input-group required">
                <label class="label body2-c">
                        모바일브랜드<br>이미지 등록
                    <span>*</span>
                </label>
                <input type="file" class="form-control" name="i_brand_mobile_img">
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
                    PC 노출여부
                </label>
                <div>
                    <?php
                        $checked = true;

                        $setParam = [
                            'name' => 'i_status_pc',
                            'id' => 'i_status_pc_Y',
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
                            'name' => 'i_status_pc',
                            'id' => 'i_status_pc_N',
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
            <div class="input-group" style="margin-bottom:0">
                <label class="label body2-c ">
                    모바일 노출여부
                </label>
                <div>
                    <?php
                        $checked = true;

                        $setParam = [
                            'name' => 'i_status_mobile',
                            'id' => 'i_status_mobile_Y',
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
                            'name' => 'i_status_mobile',
                            'id' => 'i_status_mobile_N',
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
        </div>
    </div>
    <div class="card col-12" style="margin-top:1.3rem;">
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
                        브랜드 개별 SEO 태그 설정
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
                    타이틀
                </label>
                <input type="text" class="form-control" name="i_meta_title" id="i_meta_title" />
            </div>
            <div class="input-group">
                <label class="label body2-c">
                    메타태그 작성자
                </label>
                <input type="text" class="form-control" name="i_meta_author" id="i_meta_author" />
            </div>
            <div class="input-group">
                <label class="label body2-c">
                    메타태그 설명
                </label>
                <input type="text" class="form-control" name="i_meta_description" id="i_meta_description" />
            </div>
            <div class="input-group">
                <label class="label body2-c">
                    메타태그 키워드
                </label>
                <input type="text" class="form-control" name="i_meta_keyword" id="i_meta_keyword" />
            </div>
        </div>
    </div>

    <?php echo form_close()?>
    <!-- 버튼들 -->
    <div style="text-align: center; margin-top:5.25rem; margin-bottom:9rem">
        <?php
        if(strpos(_elm($_SERVER, 'HTTP_REFERER'), 'goodsRegister') === false){
            echo getButton([
                'text' => '취소',
                'class' => 'btn',
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

