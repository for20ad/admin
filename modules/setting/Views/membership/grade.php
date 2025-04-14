<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($pageDatas, 'aConfig', []);
    $aData            = _elm($pageDatas, 'gradeDatas', [] );
?>

<div class="container-fluid">
    <!-- 카트 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">회원등급</h3>
    </div>

    <div class="row row-deck row-cards">
        <!-- 카드1 -->
        <div class="col-12">
            <div class="card">
            <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
                <div class="card accordion-card"
                    style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                                viewBox="0 0 4 4" fill="none">
                                <circle cx="2" cy="2" r="2" fill="#206BC4" />
                            </svg>
                            <p class="body1-c ms-2 mt-1">
                                회원등급등록
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
                <div class="card-body" style="margin-top:-2vh">
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <tbody>
                                <colgroup>
                                    <col style="width:10%;">
                                    <col style="width:20%;">
                                    <col style="width:15%;">
                                    <col style="width:15%;">
                                    <col style="width:15%;">
                                </colgroup>
                                <tr>
                                    <th style="padding-left:2em">회원등급</th>
                                    <th style="padding-left:2em">등급아이콘</th>
                                    <th style="padding-left:2em">구매 할인율</th>
                                    <th style="padding-left:2em">포인트 적립율</th>
                                    <th style="padding-left:2em">배송비 무료</th>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-inline">
                                            <input type="text" class="form-control" name="i_name" id="i_name">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-inline">
                                            <input type="file" class="form-control" name="i_icon" id="i_icon">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-inline">
                                            <input type="text" class="form-control" styel="width:90px" name="i_dc_rate" style="max-width:100%;margin:0px !important;">
                                            <span class="wordCount input-group-text"
                                                style="border-top-left-radius:0px; border-bottom-left-radius: 0px;margin-left:0px !important">
                                                %
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-inline">
                                            <input type="text" class="form-control" styel="width:90px" name="i_save_rate" style="max-width:100%;margin:0px !important;">
                                            <span class="wordCount input-group-text"
                                                style="border-top-left-radius:0px; border-bottom-left-radius: 0px;margin-left:0px !important">
                                                %
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-inline">
                                            <?php
                                            $setParam = [
                                                'name' => 'i_delivery_free',
                                                'id' => 'i_delivery_free',
                                                'value' => 'Y',
                                                'label' => '',
                                                'checked' => '',
                                                'extraAttributes' => []
                                            ];

                                            echo getCheckBox($setParam);
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                        </table>
                    </div>

                    <div style="display:flex;justify-content: center;text-align: center; margin-top: 15px">
                        <?php
                        echo getIconButton([
                            'txt' => '추가',
                            'icon' => 'add',
                            'buttonClass' => 'btn-lg btn-sky',
                            'buttonStyle' => '',
                            'width' => '21',
                            'height' => '20',
                            'stroke' => 'white',
                            'extra' => [
                                'onclick' => 'addMembershipGrade();',
                            ]
                        ]);
                        ?>
                    </div>
                </div>
                <?php echo form_close();?>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
            <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_grade_lists',  'onSubmit' => 'modifyGradeConfirm(); return false;', 'autocomplete' => 'off']); ?>
                <div class="card accordion-card"
                    style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                                viewBox="0 0 4 4" fill="none">
                                <circle cx="2" cy="2" r="2" fill="#206BC4" />
                            </svg>
                            <p class="body1-c ms-2 mt-1">
                                회원등급목록
                            </p>
                            <div style="margin-left:3vh;float:left;">드레그하여 순서를 변경할 수 있습니다.</div>

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
                <div class="card-body" >
                    <div style="padding:0.5em 0.5em">
                        <div id="" class="btn_list clearfix">
                            <div class="float-right">
                                <button type="submit" class="btn btn-success waves-effect waves-light">
                                    선택수정
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <colgroup>
                                <col styel="width:5%;">
                                <col style="width:10%;">
                                <col style="width:*%;">
                                <col style="width:15%;">
                                <col style="width:10%;">
                                <col style="width:15%;">
                                <col style="width:10%;">
                            </colgroup>
                            <thead class="thead-light">
                            <tr>
                                <th style="padding-left:2em">
                                    <div class="checkbox checkbox-single">
                                        <?php
                                        $setParam = [
                                            'name' => '',
                                            'id' => 'checkAll',
                                            'value' => '',
                                            'label' => '',
                                            'checked' => false,
                                            'extraAttributes' => [
                                                'class'=>'checkAll',
                                                'aria-label' => 'Single checkbox One'
                                            ]
                                        ];
                                        echo getCheckBox( $setParam );
                                        ?>
                                    </div>
                                </th>
                                <th style="padding-left:2em">회원등급</th>
                                <th style="padding-left:2em">등급아이콘</th>
                                <th style="padding-left:2em">구매 할인율</th>
                                <th style="padding-left:2em">포인트 적립율</th>
                                <th style="padding-left:2em">배송비 무료</th>
                                <th style="padding-left:2em">삭제</th>
                            </tr>
                            </thead>
                            <tbody id="sortable">
                            <?php
                            if( empty( $aData ) === false ){
                                foreach( $aData as $key => $lists ){
                            ?>
                            <tr>
                                <td>
                                    <div class="checkbox checkbox-single">
                                        <?php
                                        $setParam = [
                                            'name' => 'i_grade_idx[]',
                                            'id' => 'i_grade_idx_'._elm( $lists, 'G_IDX' ),
                                            'value' =>  _elm( $lists, 'G_IDX' ),
                                            'label' => '',
                                            'checked' => false,
                                            'extraAttributes' => [
                                                'aria-label' => 'Single checkbox One',
                                                'class'=>'check-item',
                                            ]
                                        ];
                                        echo getCheckBox( $setParam );
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" name="i_name[<?php echo _elm( $lists, 'G_IDX' )?>]" id="i_name_<?php echo _elm( $lists, 'G_IDX' )?>" value="<?php echo _elm( $lists, 'G_NAME' )?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-inline">
                                        <input type="file" class="form-control" name="i_icon[<?php echo _elm( $lists, 'G_IDX' )?>]" id="i_icon" >
                                        <?php
                                        if( !empty(_elm( $lists, 'G_ICON_NAME' ) ) ){
                                        ?>
                                        <span class="image-container">
                                            <img src="<?php echo base_url()._elm( $lists, 'G_ICON_PATH' )?>"  class="icon-image">
                                            <svg class="delete-button" xmlns="http://www.w3.org/2000/svg" onclick="deleteIconConfirm( '<?php echo _elm( $lists, 'G_IDX' )?>' )" viewBox="0 0 24 24" width="24" height="24" fill="red">
                                                <path d="M18 6L6 18M6 6l12 12" stroke="white" />
                                            </svg>
                                        </span>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </td>
                                 <td>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" style="max-width:100%;margin:0px !important;" name="i_dc_rate[<?php echo _elm( $lists, 'G_IDX' )?>]" value="<?php echo _elm( $lists, 'G_DC_RATE' )?>" >
                                        <span class="wordCount input-group-text"
                                            style="border-top-left-radius:0px; border-bottom-left-radius: 0px;margin:0;">
                                            %
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" style="max-width:100%;margin:0px !important;" name="i_save_rate[<?php echo _elm( $lists, 'G_IDX' )?>]" value="<?php echo _elm( $lists, 'G_SAVE_RATE' )?>" >
                                        <span class="wordCount input-group-text"
                                            style="border-top-left-radius:0px; border-bottom-left-radius: 0px;margin:0;">
                                            %
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-inline">
                                        <?php
                                        $checked = false;
                                        if( _elm( $lists, 'G_DELIVERY_FREE' ) == 'Y' ){
                                            $checked = true;
                                        }
                                        $setParam = [
                                            'name' => 'i_delivery_free['.$lists['G_IDX'].']',
                                            'id' => 'i_delivery_free',
                                            'value' => 'Y',
                                            'label' => '',
                                            'checked' => $checked,
                                            'extraAttributes' => []
                                        ];

                                        echo getCheckBox($setParam);
                                        ?>
                                    </div>
                                </td>
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
                                            'onclick' => 'deleteGradeConfirm("'. _elm( $lists, 'G_IDX' ).'");',
                                        ]
                                    ]);
                                ?>
                                </td>
                            </tr>
                            <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding:0.5em 0.5em">
                        <div id="" class="btn_list clearfix">
                            <div class="float-right">
                                <button type="submit" class="btn btn-success ">
                                    선택수정
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close();?>
            </div>
        </div>

    </div>
</div>
<script>

</script>

<?php
$owensView->setFooterJs('/assets/js/setting/membership/grade.js');

$script = "
    $('#sortable').sortable({
        update: function(event, ui) {
            var idsInOrder = $('#sortable').sortable('toArray',{ attribute : 'data-idx'});
            updateGradrSort(idsInOrder);
            console.log(idsInOrder);
        }
    });
";

$owensView->setFooterScript($script);
