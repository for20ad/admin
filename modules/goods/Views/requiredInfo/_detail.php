<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);
    $aData            = _elm($view_datas, 'aData', []);

?>

<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_modify', 'autocomplete' => 'off']); ?>
<input type="hidden" name="i_idx" value="<?php echo _elm( $aData, 'R_IDX' )?>">

<div class="row row-deck row-cards">
    <!-- 카드1 -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="input-group required">
                    <label class="label body2-c">
                        필수정보명
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" placeholder="필수정보명을 입력하세요." name="i_name" id="i_name" data-max-length="100" value="<?php echo _elm( $aData, 'R_TITLE' )?>" style="border-top-right-radius:0px; border-bottom-right-radius: 0px" value=""/>
                    <span class="wordCount input-group-text" style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        <?php echo mb_strlen( _elm( $aData, 'R_TITLE' ) )?>/100
                    </span>
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
                            상품정보
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
                <div class="input-group" style="padding-bottom:1.8rem">
                    <label class="label body2-c" style="">
                    <?php
                    echo getIconButton([
                        'txt' => '항목 추가',
                        'icon' => 'box_plus',
                        'buttonClass' => 'btn',
                        'buttonStyle' => 'width:150px; height: 46px;',
                        'width' => '21',
                        'height' => '20',
                        'stroke' => 'black',
                        'extra' => [
                            'type' => 'button',
                            'onclick' => 'addRows(event);',
                        ]
                    ]);
                    ?>
                    </label>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter" id="aListsTable">
                        <colgroup>
                            <col style="width:30%;">
                            <col style="*">
                            <col style="width:5%;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>항목</th>
                                <th>내용</th>
                                <th>삭제</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if( empty( _elm( $aData, 'detail' ) ) === false ){
                            foreach( _elm( $aData, 'detail' ) as $key => $subData ){
                        ?>
                            <tr>
                                <td><input type="text" class="form-control" name="keys[]" value="<?php echo _elm( $subData, 'D_KEY' ) ?>"></td>
                                <td><input type="text" class="form-control" name="values[]" value="<?php echo _elm( $subData, 'D_VALUE' ) ?>"></td>
                                <td>
                                <?php
                                    echo getIconAnchor([
                                        'txt' => '',
                                        'icon' => 'delete',
                                        'buttonClass' => '',
                                        'buttonStyle' => '',
                                        'width' => '24',
                                        'height' => '24',
                                        'stroke' => '#616876',
                                        'extra' => [
                                            'onclick' => 'deleteRoes(this);',
                                        ]
                                    ]);
                                ?>
                                </td>
                            </tr>
                        <?php
                            }
                        }?>
                        </tbody>
                    </table>
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
</div>    <!-- 버튼 -->

<?php echo form_close() ?>
<script>
</script>
