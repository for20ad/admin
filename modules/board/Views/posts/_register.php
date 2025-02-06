<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);
    $aBoardId         = _elm( $view_datas, 'bo_id' );
?>


<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register', 'autocomplete' => 'off']); ?>
<input type="hidden" name="i_content">
<input type="hidden" name="bo_id" value="<?php echo $aBoardId?>">
<div class="row row-deck row-cards">
    <!-- 카드1 -->
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

                    <input type="text" class="form-control" placeholder="제목을 입력하세요." name="i_title" id="i_title" value=""  data-required='제목을 입력하세요.' data-max-length="30" style="border-top-right-radius:0px; border-bottom-right-radius: 0px"/>
                    <span class="wordCount input-group-text" style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        0/30
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

                    </div>


                </div>
                <div style="display:<?php echo $aBoardId !== '' ? 'none' : '' ?>">
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

                            $options = [ 'READY'=>'문의 등록', 'RECEIVED'=>'문의 접수', 'PREPARING'=>'답변 준비중', 'COMPLETED'=> '답변 완료',];
                            $extras   = ['id' => 'i_answer_status', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                            $selected = '1';
                            echo getSelectBox('i_answer_status', $options, $selected, $extras);
                        ?>
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
                'onclick' => 'frmRegiserConfirm(event);',
            ]
        ]);
        ?>
    </div>
</div>

<?php echo form_close() ?>
<script>
    var fKey = 0;
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

var contents_editor_value = '';
var answer_editor_value = '';
</script>