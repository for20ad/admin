<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);
    $aData            = _elm($view_datas, 'aData', []);

    $aCate            = _elm($view_datas, 'aCate', []);
?>

<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_modify', 'autocomplete' => 'off']); ?>
<input type="hidden" name="i_idx" value="<?php echo _elm( $aData, 'F_IDX' )?>">
<input type="hidden" name="i_content" value="">
<input type="hidden" name="i_answer">
<div class="row row-deck row-cards">
    <!-- 카드1 -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="input-group required">
                    <label class="label body2-c">
                        분류
                        <span>*</span>
                    </label>
                    <?php
                        $options  = $aCate;
                        $extras   = ['id' => 'i_cate', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                        $selected = _elm( $aData, 'F_CATE' );
                        echo getSelectBox('i_cate', $options, $selected, $extras);
                    ?>


                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        베스트
                        <span>*</span>
                    </label>
                    <?php
                        $checked = false;
                        if( 'Y' == _elm( $aData, 'F_IS_BEST' ) ){
                            $checked = true;
                        }
                        $setParam = [
                            'name' => 'i_is_best',
                            'id' => 'i_is_best_Y',
                            'value' => 'Y',
                            'label' => '베스트',
                            'checked' => $checked,
                            'extraAttributes' => [
                                'aria-label' => 'Single checkbox One',
                                'class'=>'check-item',
                            ]
                        ];
                        echo getCheckBox( $setParam );
                    ?>
                </div>

                <div class="input-group required">
                    <label class="label body2-c">
                        제목
                        <span>*</span>
                    </label>

                    <input type="text" class="form-control" placeholder="제목을 입력하세요." name="i_title" id="i_title" value="<?php echo _elm( $aData, 'F_TITLE' )?>"  data-required='제목을 입력하세요.' data-max-length="30" style="border-top-right-radius:0px; border-bottom-right-radius: 0px"/>
                    <span class="wordCount input-group-text" style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        <?php echo mb_strlen( _elm( $aData, 'F_TITLE' ) )?>/30
                    </span>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        내용
                        <span>*</span>
                    </label>
                    <div id="editor-container">
                        <div id="contents_editor"></div>
                    </div>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        답변
                        <span>*</span>
                    </label>
                    <div id="editor-container">
                        <div id="answer_editor"></div>
                    </div>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        상태
                        <span>*</span>
                    </label>
                    <?php
                        $options = ['Y'=>'노출', 'N'=>'비노출','R'=>'승인대기', 'D'=>'삭제'];
                        $extras   = ['id' => 'i_status', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                        $selected = _elm( $aData, 'N_STATUS' );
                        echo getSelectBox('i_status', $options, $selected, $extras);
                    ?>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        첨부파일
                    </label>
                    <div  style="display: flex;flex-direction: column;">

                        <?php for ($i = 1; $i <= 2; $i++): ?>
                            <?php
                            $fileName = _elm($aData, 'F_FILES_' . $i . '_NAME');
                            $filePath = _elm($aData, 'F_FILES_' . $i . '_PATH');
                            ?>

                            <div class="file-input-group" id="file-group-<?php echo $i; ?>" style="margin-bottom: 0.35rem; display: flex; align-items: center; width: 100%;">

                                <!-- 8:2 비율 고정 레이아웃 -->
                                <div style="display: flex; width: 100%; gap: 10px;">

                                    <!-- 왼쪽 영역 (8) - 파일 이름 또는 input -->
                                    <div style="flex: 8; text-align: left;">
                                        <?php if (!empty($fileName)): ?>
                                            <span class="file-display" style="display: flex; align-items: center; height: 38px;  padding: 0.375rem 0.75rem; ">
                                                <a href="<?php echo base_url().$filePath ?>" target="_blank"><?php echo htmlspecialchars($fileName); ?></a>
                                            </span>
                                            <div class="file-input" style="display: none; align-items: center;">
                                                <input type="file" class="form-control" name="i_file_<?php echo $i; ?>" id="i_file_<?php echo $i; ?>" style="width: 100%; box-sizing: border-box;">
                                            </div>
                                        <?php else: ?>
                                            <div class="file-input" style="display: flex; align-items: center;">
                                                <input type="file" class="form-control" name="i_file_<?php echo $i; ?>" id="i_file_<?php echo $i; ?>" style="width: 100%; box-sizing: border-box;">
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- 오른쪽 영역 (2) - 수정, 삭제, 취소 아이콘 -->
                                    <div style="flex: 2; display: flex; justify-content: flex-end; align-items: center; gap: 8px;">
                                        <?php if (!empty($fileName)): ?>
                                            <span class="edit-delete-buttons">
                                                <span onclick="toggleFileInput('<?php echo $i; ?>', true)" style="cursor: pointer;">
                                                    <?php echo getIconAnchor([
                                                        'txt' => '',
                                                        'icon' => 'edit',
                                                        'width' => '24',
                                                        'height' => '24',
                                                        'stroke' => '#616876'
                                                    ]); ?>
                                                </span>
                                                <span onclick="deleteFile('<?php echo $i; ?>')" style="cursor: pointer;">
                                                    <?php echo getIconAnchor([
                                                        'txt' => '',
                                                        'icon' => 'delete',
                                                        'width' => '24',
                                                        'height' => '24',
                                                        'stroke' => '#616876'
                                                    ]); ?>
                                                </span>
                                            </span>
                                        <?php endif; ?>

                                        <!-- 취소 버튼 (수정 또는 삭제 상태일 때만 표시) -->
                                        <span class="cancel-button" style="display: none; cursor: pointer;" onclick="toggleFileInput('<?php echo $i; ?>', false);">
                                            <?php echo getIconAnchor([
                                                'txt' => '',
                                                'icon' => 'reset',
                                                'width' => '24',
                                                'height' => '24',
                                                'stroke' => '#616876'
                                            ]); ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- 삭제 요청 히든 필드 -->
                                <input type="hidden" name="delete_file_<?php echo $i; ?>" id="delete_file_<?php echo $i; ?>" value="0">
                            </div>
                        <?php endfor; ?>

                    </div>
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
                'type' => 'button',
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

        // 초기값 설정
    var contents_editor_value = <?php echo json_encode(htmlspecialchars_decode(_elm($aData, 'F_CONTENT'))); ?>;
    var answer_editor_value = <?php echo json_encode(htmlspecialchars_decode(_elm($aData, 'F_ANSWER'))); ?>;


    function toggleFileInput(index, showInput) {
        var fileDisplay = document.querySelector('#file-group-' + index + ' .file-display');
        var fileInput = document.querySelector('#file-group-' + index + ' .file-input');
        var editDeleteButtons = document.querySelector('#file-group-' + index + ' .edit-delete-buttons');
        var cancelButton = document.querySelector('#file-group-' + index + ' .cancel-button');

        if (showInput) {
            if (fileDisplay) fileDisplay.style.display = 'none';
            if (fileInput) fileInput.style.display = 'flex';
            if (editDeleteButtons) editDeleteButtons.style.display = 'none';
            if (cancelButton) cancelButton.style.display = 'inline-flex';
        } else {
            if (fileDisplay) fileDisplay.style.display = 'flex';
            if (fileInput) fileInput.style.display = 'none';
            if (editDeleteButtons) editDeleteButtons.style.display = 'flex';
            if (cancelButton) cancelButton.style.display = 'none';
            document.getElementById('delete_file_' + index).value = "0";
        }
    }

    function deleteFile(index) {
        var fileDisplay = document.querySelector('#file-group-' + index + ' .file-display');
        var fileInput = document.querySelector('#file-group-' + index + ' .file-input');
        var editDeleteButtons = document.querySelector('#file-group-' + index + ' .edit-delete-buttons');
        var cancelButton = document.querySelector('#file-group-' + index + ' .cancel-button');

        document.getElementById('delete_file_' + index).value = "1";
        if (fileDisplay) fileDisplay.style.display = 'none';
        if (fileInput) fileInput.style.display = 'flex';
        if (editDeleteButtons) editDeleteButtons.style.display = 'none';
        if (cancelButton) cancelButton.style.display = 'inline-flex';
    }


</script>

