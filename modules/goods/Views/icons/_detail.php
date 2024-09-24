<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);
    $aData            = _elm($view_datas, 'aData', []);

?>

<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_modify', 'autocomplete' => 'off']); ?>
<input type="hidden" name="i_idx" value="<?php echo _elm( $aData, 'I_IDX' )?>">
<div class="row row-deck row-cards">
    <!-- 카드1 -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="input-group required">
                    <label class="label body2-c">
                        아이콘 이름
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" placeholder="아이콘 이름을 입력하세요." name="i_name" id="i_name" data-max-length="30" style="border-top-right-radius:0px; border-bottom-right-radius: 0px" value="<?php echo _elm( $aData, 'I_NAME' )?>"/>
                    <span class="wordCount input-group-text" style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        <?php echo mb_strlen( _elm( $aData, 'I_NAME' ) )?>/30
                    </span>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        아이콘 코드
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" placeholder="자동생성됩니다." name="i_code" id="i_code" style="border-top-right-radius:0px; border-bottom-right-radius: 0px" value="<?php echo _elm( $aData, 'I_CODE' )?>" readonly/>
                </div>

                <div class="input-group required">
                    <label class="label body2-c">
                        기간제한 상태
                    </label>
                    <?php
                        $checked = false;
                        if( 'L' == _elm( $aData, 'I_GBN' ) ){
                            $checked = true;
                        }
                        $setParam = [
                            'name' => 'i_gbn',
                            'id' => 'i_gbn_L',
                            'value' => 'L',
                            'label' => '무제한용',
                            'checked' => $checked,
                            'extraAttributes' => [
                            ]
                        ];
                        echo getRadioButton($setParam);
                    ?>
                    <?php
                        $checked = false;
                        if( 'P' == _elm( $aData, 'I_GBN' ) ){
                            $checked = true;
                        }
                        $setParam = [
                            'name' => 'i_gbn',
                            'id' => 'i_gbn_P',
                            'value' => 'P',
                            'label' => '기간제한용',
                            'checked' => $checked,
                            'extraAttributes' => [
                            ]
                        ];
                        echo getRadioButton($setParam);
                    ?>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        사용상태
                    </label>
                    <?php

                        $checked = false;
                        if( 'Y' == _elm( $aData, 'I_STATUS' ) ){
                            $checked = true;
                        }
                        $setParam = [
                            'name' => 'i_status',
                            'id' => 'i_status_Y',
                            'value' => 'Y',
                            'label' => '사용함',
                            'checked' => $checked,
                            'extraAttributes' => [
                            ]
                        ];
                        echo getRadioButton($setParam);
                    ?>
                    <?php
                        $checked = false;
                        if( 'N' == _elm( $aData, 'I_STATUS' ) ){
                            $checked = true;
                        }
                        $setParam = [
                            'name' => 'i_status',
                            'id' => 'i_status_N',
                            'value' => 'N',
                            'label' => '사용안함',
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
    <!-- 카드2 -->
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
                            아이콘 이미지 설정
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
                <div class="input-group">
                    <label class="label body2-c" style="max-width:10rem;text-align:center;padding:0.4rem 0.9rem; border:1px solid #e6e7e9;border-radius:4px; height:2.2rem;margin-right:1.3rem;">
                        <img src="/<?php echo _elm( $aData, 'I_IMG_PATH' )?>">
                    </label>
                    <input type="file" class="form-control" name="i_icon" id="i_icon"/>
                </div>

                <div class="input-group-bottom-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                        viewBox="0 0 6 6" fill="none">
                        <circle cx="3" cy="3" r="3" fill="#616876" />
                    </svg>
                    아이콘 이미지 사이즈는 작게 해서 올려주세요. 해당 이미지 크기 그대로 출력이 됩니다.
                </div>



            </div>
        </div>
    </div>
    <!-- 버튼 -->
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
<?php echo form_close() ?>
<script>
</script>
