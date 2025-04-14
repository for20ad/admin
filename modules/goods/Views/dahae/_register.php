<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];


?>

<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register', 'autocomplete' => 'off']); ?>

<div class="row row-deck row-cards">
    <!-- 카드1 -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="input-group required">
                    <label class="label body2-c">
                        옵션 그룹명
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" placeholder="옵션 그룹명을 입력하세요." name="i_d_option_g_name" id="i_d_option_g_name" data-max-length="30" style="border-top-right-radius:0px; border-bottom-right-radius: 0px" value=""/>
                    <span class="wordCount input-group-text" style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        0/30
                    </span>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        옵션명
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" placeholder="옵션명을 입력하세요." name="i_d_option_name" id="i_d_option_name" style="border-top-right-radius:0px; border-bottom-right-radius: 0px" value="" />
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        매칭값
                    </label>
                    <input type="text" class="form-control" placeholder="옵션명을 입력하세요." name="i_d_result" id="i_d_result" style="border-top-right-radius:0px; border-bottom-right-radius: 0px" value="" />
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

<script>

</script>
<?php echo form_close() ?>
