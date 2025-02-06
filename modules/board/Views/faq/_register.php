<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);
    $aCate            = _elm($view_datas, 'aCate', []);
?>





<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register', 'autocomplete' => 'off']); ?>
<input type="hidden" name="i_content">
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
                        $selected = '';
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
                        $selected = '1';
                        echo getSelectBox('i_status', $options, $selected, $extras);
                    ?>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        첨부파일
                    </label>
                    <div  style="display: flex;flex-direction: column;">
                    <?php for ($i = 1; $i <= 2; $i++){ ?>
                        <input type="file" class="form-control" name="i_file_<?php echo $i; ?>" id="i_file_<?php echo $i; ?>" style="display: block; width: 100%; margin-bottom: 0.35rem !important;">
                    <?php } ?>
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
var contents_editor_value = '';
var answer_editor_value = '';
</script>