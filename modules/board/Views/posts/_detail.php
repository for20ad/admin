<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm( $view_datas, 'aConfig', [] );
    $aData            = _elm( $view_datas, 'aData', [] );
    $aBoardId         = _elm( $view_datas, 'bo_id' );
    $aOrderInfo       = _elm( _elm( $aData, 'odrInfo' ), 'returnData', [] );
?>

<div class="row row-deck row-cards">
    <!-- 카드1 -->
    <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_modify', 'autocomplete' => 'off']); ?>
    <input type="hidden" name="i_idx" value="<?php echo _elm( $aData, 'P_IDX' )?>">
    <input type="hidden" name="i_content">
    <input type="hidden" name="i_answer" value="">
    <input type="hidden" name="bo_id" value="<?php echo $aBoardId?>">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="input-group required">
                    <label class="label body2-c">
                        공지
                        <span>*</span>
                    </label>
                    <?php
                        $checked = false;
                        if( 'Y' == _elm( $aData, 'P_NOTI' ) ){
                            $checked = true;
                        }
                        $setParam = [
                            'name' => 'i_is_notice',
                            'id' => 'i_is_notice_Y',
                            'value' => 'Y',
                            'label' => '공지사항',
                            'checked' => $checked,
                            'extraAttributes' => [
                                'aria-label' => 'Single checkbox One',
                                'class'=>'check-item',
                            ]
                        ];
                        echo getCheckBox( $setParam );
                    ?>
                    <?php
                        $checked = false;
                        if( 'Y' == _elm( $aData, 'P_STAY' ) ){
                            $checked = true;
                        }
                        $setParam = [
                            'name' => 'i_is_stay',
                            'id' => 'i_is_stay',
                            'value' => 'Y',
                            'label' => '상단고정',
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

                    <input type="text" class="form-control" placeholder="제목을 입력하세요." name="i_title" id="i_title" value="<?php echo _elm( $aData, 'P_TITLE' )?>"  data-required='제목을 입력하세요.' data-max-length="30" style="border-top-right-radius:0px; border-bottom-right-radius: 0px"/>
                    <span class="wordCount input-group-text" style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        <?php echo mb_strlen(  _elm( $aData, 'P_TITLE' ) )?>/30
                    </span>
                </div>
                <?php
                if (!empty($aOrderInfo)):
                ?>
                <div class="input-group required">
                    <label class="label body2-c">
                        문의 상품(주문)
                    </label>
                </div>
                <?php
                    foreach ($aOrderInfo as $aKey => $list):
                ?>
                <div class="input-group required" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">

                    <!-- 왼쪽 영역: 주문 정보 -->
                    <div style="flex: 1; text-align: left; display: flex; flex-direction: column; justify-content: center;">
                        주문번호 : <?php echo _elm($list, 'orderNum'); ?><br>
                        주문일시 : <?php echo _elm($list, 'orderAt'); ?>
                    </div>

                    <!-- 오른쪽 영역: 상품 정보 -->
                    <div style="flex: 3;">
                        <?php
                        if (!empty(_elm($list, 'orderProduct'))):
                            $orderProducts = _elm($list, 'orderProduct');
                            $totalProducts = count($orderProducts);
                            foreach ($orderProducts as $pKey => $goods):
                        ?>
                        <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                            <img src="<?php echo base_url() . _elm(_elm(_elm(_elm($goods, 'images'), 250), 0), 'I_IMG_PATH'); ?>" style="width:45px; height:auto; display:block;">
                            <span style="font-size: 14px; line-height: 1.2;"><?php echo _elm($goods, 'pGoodsNm') . ' - ' . _elm($goods, 'pOptionName'); ?></span>
                        </div>
                        <?php if ($pKey < $totalProducts - 1): ?>
                        <div style="margin: 2px 0;"></div> <!-- 간격 줄이기 -->
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>


                </div>
                <hr style="margin: 10px 0;">
                <?php
                    endforeach;
                endif;
                ?>
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
                        상태
                        <span>*</span>
                    </label>
                    <?php

                        $options = [''=>'전체', '1'=>'노출', '2'=>'비노출','3'=>'삭제','9'=>'삭제'];
                        $extras   = ['id' => 'i_status', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                        $selected = '1';
                        echo getSelectBox('i_status', $options, $selected, $extras);
                    ?>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        첨부파일
                        <?php
                            echo getIconAnchor([
                                'txt' => '',
                                'icon' => 'plus',
                                'buttonClass' => '',
                                'buttonStyle' => '',
                                'width' => '24',
                                'height' => '24',
                                'stroke' => '#616876',
                                'extra' => [
                                    'onclick' => 'addRows();',
                                ]
                            ]);
                        ?>
                    </label>
                    <div id="fileWrap" style="display: flex; flex-direction: column; gap: 0.5rem; width: 80%;">

                        <?php if (!empty(_elm($aData, 'files'))): ?>
                            <?php foreach (_elm($aData, 'files') as $fKey => $file): ?>
                                <?php
                                $fileName = _elm($file, 'F_NAME');
                                $filePath = _elm($file, 'F_PATH');
                                ?>
                                <div class="filesTop file-input-group" id="file-group-<?php echo $fKey; ?>" style="display: flex; align-items: center; width: 100%; gap: 10px;">

                                    <!-- 왼쪽 영역 (8) -->
                                    <div style="flex: 8; display: flex; align-items: center; height: 40px;">
                                        <?php if (!empty($fileName)): ?>
                                            <!-- 파일 이름 표시 -->
                                            <div class="file-display" style="width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                <a href="<?php echo base_url() . htmlspecialchars($filePath); ?>" target="_blank"
                                                style="text-decoration: none; width: 100%; text-overflow: ellipsis; white-space: nowrap;">
                                                    <?php echo htmlspecialchars($fileName); ?>
                                                </a>
                                            </div>
                                            <!-- 숨겨진 파일 입력 -->
                                            <div class="file-input" style="display: none; width: 100%;">
                                                <input type="file" class="form-control" name="files[]" style="width: 100%; height: 40px; box-sizing: border-box;">
                                            </div>
                                        <?php else: ?>
                                            <!-- 파일 입력 -->
                                            <div class="file-input" style="width: 100%;">
                                                <input type="file" class="form-control" name="files[]" style="width: 100%; height: 40px; box-sizing: border-box;">
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- 오른쪽 영역 (2) -->
                                    <div style="flex: 2; display: flex; justify-content: flex-end; align-items: center; height: 40px;">
                                        <?php if (!empty($fileName)): ?>
                                            <!-- 수정 및 삭제 버튼 -->
                                            <span class="edit-delete-buttons" style="display: flex; gap: 8px;">
                                                <span onclick="toggleFileInput('<?php echo $fKey; ?>', true)" style="cursor: pointer;">
                                                    <?php echo getIconAnchor([
                                                        'txt' => '',
                                                        'icon' => 'edit',
                                                        'width' => '24',
                                                        'height' => '24',
                                                        'stroke' => '#616876'
                                                    ]); ?>
                                                </span>
                                                <span onclick="deleteFileConform('<?php echo $fKey?>','<?php echo _elm( $file, 'F_IDX' ); ?>')" style="cursor: pointer;">
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

                                        <!-- 취소 버튼 -->
                                        <span class="cancel-button" style="display: none; cursor: pointer;" onclick="toggleFileInput('<?php echo $fKey; ?>', false);">
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
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>

                </div>
                <div style="display:<?php echo $aBoardId != 'QNA' ? 'none' : '' ?>">
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
                            답변상태
                            <span>*</span>
                        </label>
                        <?php
                            $options = [ '' => '선택', 'RECEIVED'=>'문의 접수', 'PREPARING'=>'답변 준비중', 'COMPLETED'=> '답변 완료',];
                            $extras   = ['id' => 'i_answer_status', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                            $selected = _elm( $aData, 'P_ANSWER_STATUS' );
                            echo getSelectBox('i_answer_status', $options, $selected, $extras);
                        ?>
                    </div>

                    <?php
                    if( _elm( $aData, 'P_ANSWER_STATUS' ) == 'COMPLETED' ){
                    ?>
                    <div class="input-group required">
                        <label class="label body2-c">
                            완료일
                            <span>*</span>
                        </label>
                        <?php echo _elm( $aData, 'P_ANSWER_AT' )?> <br>
                        <?php echo  _elm( $aData, 'MB_USERNAME' ). '( '._elm( $aData, 'MB_USERID' ).' )'; ?>
                    </div>
                    <?php
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>
    <?php echo form_close() ?>
    <!-- 카드2 -->
    <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_sub_lists', 'autocomplete' => 'off']); ?>
    <input type="hidden" name="s_idx" value="<?php echo _elm( $aData, 'P_IDX' )?>">
    <input type="hidden" name="page" value="1">
    <input type="hidden" name="s_bo_id" value="<?php echo $aBoardId?>">
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
                        <div class="d-flex align-items-center justify-content-between">
                            <!-- 왼쪽 텍스트 -->
                            <p class="body1-c mb-0" style="margin: 0;">
                                댓글
                            </p>
                            <!-- 댓글 등록 버튼 -->
                            <a href="javascript:void(0);" onclick="showCommentBox()" class="btn btn-primary btn-sm"
                            style="font-size: 12px; padding: 5px 10px;margin-left: 40px">
                                댓글 등록
                            </a>
                        </div>
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
            <div class="commentTop card-body">
                <div id="comments">

                </div>
                <div class="pagination-wrapper" id="pagination">

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


<script>
    var fKey = <?php echo count(_elm($aData, 'files')) ?: 0; ?>;

    function addRows(){

        if( fKey > 4 ){
            box_alert( '첨부파일 최대 개수를 초과했습니다.', 'i' );
            return false;
        }
        var html = `
        <div class="filesTop file-input-group" id="file-group-${fKey}" style="margin-bottom: 0.35rem; display: flex; align-items: center; width: 100%;">
            <!-- 8:2 비율 고정 레이아웃 -->
            <div style="display: flex; width: 100%; gap: 10px;">

                <!-- 왼쪽 영역 (8) - 파일 이름 또는 input -->
                <div style="flex: 8; text-align: left;">
                    <div class="file-input" style="display: flex; align-items: center;">
                        <input type="file" class="form-control" name="files[]" style="width: 100%; box-sizing: border-box;">
                    </div>
                </div>

                <!-- 오른쪽 영역 (2) - 수정, 삭제, 취소 아이콘 -->
                <div style="flex: 2; display: flex; justify-content: flex-end; align-items: center; gap: 8px;">
                    <?php
                        echo getIconAnchor([
                            'txt' => '',
                            'icon' => 'delete',
                            'buttonClass' => '',
                            'buttonStyle' => 'display: inline-flex; align-items: center; justify-content: flex-end;',
                            'width' => '24',
                            'height' => '24',
                            'stroke' => '#616876',
                            'extra' => [
                                'onclick' => 'deleteRows(this);',
                            ]
                        ]);
                    ?>
                </div>
            </div>


        </div>
        `;
        fKey ++ ;
        $("#fileWrap").append(html);
    }
    function deleteRows( obj ){

        obj.closest('.filesTop').remove();
        fKey --;
    }
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

        }
    }

    function deleteFileConform(index, fidx) {
        var obj = { 'index': index, 'file_idx': fidx }
        box_confirm( '파일을 삭제하시겠습니까?', 'q', '', deleteFile, obj );

    }
    function deleteFile( obj )
    {
        $.ajax({
            url: '/apis/board/deletePostsFile',
            type: 'post',
            data: 'file_idx='+obj.file_idx,
            processData: false,
            cache: false,
            beforeSend: function() { },
            success: function(response) {
                submitSuccess(response);

                if (response.status == 'false')
                {
                    var error_message = '';
                    error_message = error_lists.join('<br />');
                    if (error_message != '') {
                        box_alert(error_message, 'e');
                    }

                    return false;
                }
                $('#file-group-' + obj.index ).remove();


            },
            error: function(jqXHR, textStatus, errorThrown) {
                submitError(jqXHR.status, errorThrown);
                console.log(textStatus);

                return false;
            },
            complete: function() { }
        });
    }

    function cancelDelete(index) {
        // Hide input row and show file display row
        var fileDisplay = document.getElementById('file-display-' + index);
        var fileInputRow = document.getElementById('file-input-row-' + index);

        if (fileDisplay) {
            fileDisplay.style.display = 'flex';
        }
        if (fileInputRow) {
            fileInputRow.style.display = 'none';
        }

        // Remove hidden input if cancel is clicked
        var hiddenInput = document.querySelector('input[name="delete_files[]"][value="' + index + '"]');
        if (hiddenInput) {
            hiddenInput.remove();
        }
    }

    var contents_editor_value = <?php echo json_encode(htmlspecialchars_decode(_elm($aData, 'P_CONTENT'))); ?>;
    var answer_editor_value= <?php echo json_encode(htmlspecialchars_decode(_elm($aData, 'P_ANSWER'))); ?>;



    getPostsComments();
    subPagination.initPagingNumFunc(getPostsComments);
    subPagination.initPagingSelectFunc(getPostsComments);


    function showCommentBox() {
        // 이미 댓글 입력창이 있는지 확인
        if ($('#comment-input-area').length > 0) {
            alert('이미 댓글 입력 창이 열려 있습니다.');
            return;
        }

        // 댓글 입력 박스 추가
        const commentInputBox = `
            <div id="comment-input-area" style="margin-top: 20px;">
                <textarea id="new-comment" placeholder="댓글을 입력하세요..." style="width: 100%; height: 80px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;"></textarea>
                <div style="margin-top: 10px; text-align: right;">
                    <button type="button" onclick="submitComment()"
                        style="background: #28a745; color: white; border: none; border-radius: 5px; padding: 5px 10px; font-size: 12px; cursor: pointer;">
                        등록
                    </button>
                    <button type="button" onclick="cancelCommentBox()"
                        style="background: #dc3545; color: white; border: none; border-radius: 5px; padding: 5px 10px; font-size: 12px; cursor: pointer; margin-left: 10px;">
                        취소
                    </button>
                </div>
            </div>
        `;

        // 입력 박스 추가
        $('.commentTop').prepend(commentInputBox);
    }

    function cancelCommentBox() {
        // 댓글 입력 창 제거
        $('#comment-input-area').remove();
    }

    function submitComment() {
        const comment = $('#new-comment').val().trim();
        const postsIdx = <?php echo _elm( $aData, 'P_IDX' )?>;

        if (!comment) {
            alert('댓글 내용을 입력해주세요.');
            return;
        }

        $.ajax({
            url: '/apis/board/addPostsReply',
            type: 'post',
            data: 'board_id=<?php echo $aBoardId?>&posts_idx='+postsIdx+'&c_idx=0&comment='+comment,
            processData: false,
            cache: false,
            beforeSend: function() { },
            success: function(response) {
                submitSuccess(response);

                if (response.status == 'false')
                {
                    var error_message = '';
                    error_message = error_lists.join('<br />');
                    if (error_message != '') {
                        box_alert(error_message, 'e');
                    }

                    return false;
                }
                cancelCommentBox();
                getPostsComments();


            },
            error: function(jqXHR, textStatus, errorThrown) {
                submitError(jqXHR.status, errorThrown);
                console.log(textStatus);

                return false;
            },
            complete: function() { }
        });
    }

</script>