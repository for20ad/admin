<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($pageDatas, 'aConfig', []);
    $aData            = _elm($pageDatas, 'aData', [] );

?>

<div class="container-fluid">
    <!-- 카트 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">이용약관</h3>
    </div>
    <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
    <div class="row row-deck row-cards">
        <!-- 카드1 -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="input-group required">
                        <label class="label body2-c">
                            이용약관
                        </label>
                        <textarea class="form-control" style="height:160px" name="i_agreement_terms"><?php echo _elm( $aData, 'T_AGREEMENT_TERMS' )?></textarea>
                    </div>

                    <div class="input-group required">
                        <label class="label body2-c">
                            개인정보처리방침
                        </label>
                        <textarea class="form-control" style="height:160px" name="i_agreement_privacy"><?php echo _elm( $aData, 'T_AGREEMENT_PRIVACY' )?></textarea>
                    </div>

                    <div class="input-group required">
                        <label class="label body2-c">
                            마케팅정보수신동의
                        </label>
                        <textarea class="form-control" style="height:160px" name="i_agreement_marketing"><?php echo _elm( $aData, 'T_AGREEMENT_MARKETING' )?></textarea>
                    </div>

                    <div class="input-group required">
                        <label class="label body2-c">
                            제 3자 제공동의
                        </label>
                        <textarea class="form-control" style="height:160px" name="i_agreement_third_party"><?php echo _elm( $aData, 'T_AGREEMENT_THIRD_PARTY' )?></textarea>
                    </div>
                    <div class="input-group required">
                        <label class="label body2-c">
                            정기과금 이용약관
                        </label>
                        <textarea class="form-control" style="height:160px" name="i_agreement_recurring"><?php echo _elm( $aData, 'T_AGREEMENT_RECURRING' )?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- 버튼 -->
        <div style="text-align: center; margin-top: 52px">

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
                    'type' => 'submit'
                ]
            ]);
            ?>

        </div>
    </div>
    <?php echo form_close() ?>
</div>
<?php
$owensView->setFooterJs('/assets/js/setting/mall/terms.js');

$script = "
";

$owensView->setFooterScript($script);
