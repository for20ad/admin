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
                            <col style="width:10%;">
                            <col style="width:5%;">
                            <col style="width:30%;">
                            <col style="*">
                            <col style="width:5%;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>이동</th>
                                <th>순번</th>
                                <th>항목</th>
                                <th>내용</th>
                                <th>삭제</th>
                            </tr>
                        </thead>
                        <tbody id="tableSort">
                        <?php
                        if( empty( _elm( $aData, 'detail' ) ) === false ){
                            foreach( _elm( $aData, 'detail' ) as $key => $subData ){
                        ?>
                            <tr onmouseover="$(this).find('.group-move-icons').show()" onmouseout="$(this).find('.group-move-icons').hide()">
                                <td>
                                    <div class="group-move-icons" style="display:none;cursor:pointer" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32" fill="none">
                                            <g clip-path="url(#clip0_492_18580)">
                                                <path d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z" fill="#616876"/>
                                                <path d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z" fill="#616876"/>
                                                <path d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z" fill="#616876"/>
                                                <path d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z" fill="#616876"/>
                                                <path d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z" fill="#616876"/>
                                                <path d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z" fill="#616876"/>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_492_18580">
                                                <rect width="32" height="32" fill="white"/>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </div>
                                </td>
                                <td><span class="numbering"><?php echo $key + 1?></span></td>
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
    $('#tableSort').sortable({
        handle: '.group-move-icons',
        update: function(event, ui) {
            var idsInOrder = $('#tableSort').sortable('toArray',{ attribute : 'data-idx'});

            console.log(idsInOrder);
            // 재정렬된 순서대로 .numbering 요소를 업데이트
            $('#tableSort tr').each(function(index) {
                $(this).find('.numbering').text(index + 1);
            });
        }
    });
</script>
