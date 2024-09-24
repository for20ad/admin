<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);
    $aData            = _elm($view_datas, 'aData', []);

?>
<style>
#editor {
    width: 100%; /* 원하는 너비 값 설정 */
    margin: 0 auto; /* 가운데 정렬 */
}
</style>
<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_modify', 'autocomplete' => 'off']); ?>
<input type="hidden" name="i_idx" value="<?php echo _elm( $aData, 'P_IDX' )?>">
<input type="hidden" name="i_content">
<div class="row row-deck row-cards">
    <!-- 카드1 -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="input-group required">
                    <label class="label body2-c">
                        노출분류
                        <span>*</span>
                    </label>
                    <?php
                        $options  = [''=>'전체'];
                        $options += _elm( $aConfig, 'viewGbn' );
                        $extras   = ['id' => 'i_view_gbn', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                        $selected = _elm( $aData, 'P_VIEW_GBN' );
                        echo getSelectBox('i_view_gbn', $options, $selected, $extras);
                    ?>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        노출여부
                        <span>*</span>
                    </label>
                    <?php
                        $options  = _elm( $aConfig, 'status' );
                        $extras   = ['id' => 'i_status', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                        $selected = _elm( $aData, 'P_STATUS' );
                        echo getSelectBox('i_status', $options, $selected, $extras);
                    ?>
                </div>

                <div class="input-group required">
                    <label class="label body2-c">
                        노출기간(시작)
                    </label>
                    <div class="input-icon">
                        <input type="text" class="form-control  datetimepicker"
                            name="i_period_start_date_time" id="i_period_start_date_time" readonly value="<?php echo _elm( $aData, 'P_PERIOD_START_AT' )?>">
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
                <div class="input-group required">
                    <label class="label body2-c">
                        노출기간(종료)
                    </label>
                    <div class="input-icon">
                        <input type="text" class="form-control  datetimepicker"
                            name="i_period_end_date_time" id="i_period_end_date_time" readonly value="<?php echo _elm( $aData, 'P_PERIOD_END_AT' )?>">
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
                            팝업정보
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
                    <label class="label body2-c">
                        팝업제목
                    </label>
                    <input type="text" class="form-control" placeholder="제목을 입력하세요." name="i_title" id="i_title" data-max-length="30"  value="<?php echo _elm( $aData, 'P_TITLE' )?>"/>
                    <span class="wordCount input-group-text" style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        <?php echo mb_strlen( _elm( $aData, 'P_TITLE' ) )?>/30
                    </span>
                </div>
                <div class="input-group">
                    <label class="label body2-c">
                        팝업내용
                    </label>
                    <div id="editor" ></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 카드3 -->
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
                            팝업표시
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
                    <label class="label body2-c">
                        이동링크 주소
                    </label>
                    <input type="text" class="form-control" placeholder="이동링크 주소를 입력하세요." name="i_link_url" id="i_link_url" value="<?php echo _elm( $aData, 'P_LINK_URL' )?>"/>
                </div>
                <div class="input-group">
                    <label class="label body2-c">
                        표시위치
                    </label>

                    <?php
                        $options  = [''=>'전체'];
                        $options += _elm( $aConfig, 'viewLoc' );
                        $extras   = ['id' => 'i_locate', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                        $selected = _elm( $aData, 'P_LOCATE' );
                        echo getSelectBox('i_locate', $options, $selected, $extras);
                    ?>
                </div>
                <div class="input-group">
                    <label class="label body2-c">
                        팝업크기(가로)
                    </label>
                    <input type="text" class="form-control" placeholder="가로 사이즈를 입력하세요." name="i_width" id="i_width" value="<?php echo (int)_elm( $aData, 'P_WIDTH' )?>"/>
                    <span class="wordCount input-group-text" style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        px
                    </span>
                </div>
                <div class="input-group">
                    <label class="label body2-c">
                        팝업크기(세로)
                    </label>
                    <input type="text" class="form-control" placeholder="세로 사이즈를 입력하세요." name="i_height" id="i_height" value="<?php echo (int)_elm( $aData, 'P_HEIGHT' )?>"/>
                    <span class="wordCount input-group-text" style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        px
                    </span>
                </div>
                <div class="input-group">
                    <label class="label body2-c">
                        창닫기
                    </label>
                    <?php
                    $checked = false;
                    if( 'Y' === _elm( $aData, 'P_CLOSE_YN' ) ){
                        $checked = true;
                    }
                    $setParam = [
                        'name' => 'i_close_yn',
                        'id' => 'i_close_yn',
                        'value' =>  'Y',
                        'label' => '오늘하루 열지 않음',
                        'checked' => $checked,
                        'extraAttributes' => [
                            'aria-label' => 'Single checkbox One',
                            'class'=>'check-item',
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
    flatpickr('.datetimepicker', {
    enableTime: true,
    dateFormat: 'Y-m-d H:i',
    time_24hr: true
});


// Initialize Toast UI Editor
var editor = new toastui.Editor({
    el: document.querySelector('#editor'),
    height: '300px',
    initialEditType: 'markdown',
    previewStyle: 'vertical',
    hooks: {
        addImageBlobHook: function (blob, callback) {
            var reader = new FileReader();
            reader.onload = function() {
                var base64Image = reader.result.split(',')[1];
                $.ajax({
                    url: '/apis/design/writeImage', // 파일을 업로드할 서버 측 엔드포인트
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ image: base64Image, path:'popup/editor' }),
                    success: function (response) {
                        // 서버에서 반환한 이미지 URL을 사용하여 에디터에 이미지를 추가
                        var imageUrl = response.url;
                        callback(imageUrl, 'alt text');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Error uploading file:', textStatus, errorThrown);
                    }
                });
            };
            reader.readAsDataURL(blob);
        }
    }
});

// 값 세팅
var initialContent = <?php echo json_encode(_elm( $aData, 'P_CONTENT' ))?>;
editor.setMarkdown(initialContent);
</script>
