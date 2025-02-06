<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);
?>

<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register', 'autocomplete' => 'off']); ?>
<div class="row row-deck row-cards">
    <!-- 카드1 -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="input-group required">
                    <label class="label body2-c">
                        게시판 명
                        <span>*</span>
                    </label>

                    <input type="text" class="form-control" placeholder="제목을 입력하세요." name="i_title" id="i_title" value="" data-max-length="30" style="border-top-right-radius:0px; border-bottom-right-radius: 0px"/>
                    <span class="wordCount input-group-text" style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        0/30
                    </span>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        게시판 ID
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" placeholder="제목을 입력하세요." name="i_id" id="i_id" value="" data-max-length="30" style="border-top-right-radius:0px; border-bottom-right-radius: 0px"/>
                    <span class="wordCount input-group-text" style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        0/30
                    </span>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        게시판 그룹
                        <span>*</span>
                    </label>
                    <?php
                        $options  = _elm( $aConfig, 'brdGrp' );
                        $extras   = ['id' => 'i_group', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                        $selected = '';
                        echo getSelectBox('i_group', $options, $selected, $extras);
                    ?>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        게시판 아이콘
                    </label>
                    <input type="file" class="form-control" name="i_img" id="i_img">
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        회원전용
                    </label>
                    <?php
                        $setParam = [
                            'name' => 'i_is_free',
                            'id' => 'i_is_free',
                            'value' => 'N',
                            'label' => '회원전용',
                            'checked' => false,
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
                        조회수 사용
                    </label>
                    <?php
                        $setParam = [
                            'name' => 'i_hit_use',
                            'id' => 'i_hit_use',
                            'value' => 'Y',
                            'label' => '사용',
                            'checked' => false,
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
                        비밀글 사용
                    </label>
                    <?php
                        $setParam = [
                            'name' => 'i_secret_use',
                            'id' => 'i_secret_use',
                            'value' => 'Y',
                            'label' => '사용',
                            'checked' => false,
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
                        댓글 사용
                    </label>
                    <?php
                        $setParam = [
                            'name' => 'i_comment_use',
                            'id' => 'i_comment_use',
                            'value' => 'Y',
                            'label' => '사용',
                            'checked' => false,
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
                        노출여부
                        <span>*</span>
                    </label>
                    <?php
                        $options  = _elm( $aConfig, 'status' );
                        $extras   = ['id' => 'i_status', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                        $selected = '';
                        echo getSelectBox('i_status', $options, $selected, $extras);
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
                'onclick' => 'frmRegiserConfirm(event);',
            ]
        ]);
        ?>
    </div>
</div>

<?php echo form_close() ?>
