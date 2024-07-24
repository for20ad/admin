<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }
    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];

    $aConfig          = _elm($pageDatas, 'config', []);
    $aCode            = _elm($pageDatas, 'code', []);
    $aCodeTreeLists   = _elm($pageDatas, 'code_tree_lists', []);

?>

<!-- 콘텐츠 시작 -->
<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_code_write', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
<div class="container-fluid" style="margin-bottom: 32px">
    <div class="card-title">
        <h3 class="h3-c">코드관리</h3>
        <p class="body2-c" style="color: #616876"></p>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter table-bordered mb-0 text-center nowrap">

            <colgroup>
                <col style="width:10%;">
                <col style="width:20%;">
                <col style="width:30%;">
                <col style="width:10%;">
            </colgroup>
            <thead class="thead-light">
            <tr>
                <th>상위코드</th>
                <th>코드명<span class="text-danger">*</span></th>
                <th>노출상태</th>
                <th>노출순서</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <?php
                    $options  = [0 => '최상위코드'] + $aCode;
                    $extras   = ['id' => 'i_parent_idx', 'class' => 'form-control size_xl', 'onchange'=>'findSort()'];
                    $selected = 0;
                    echo form_dropdown('i_parent_idx', $options, $selected, $extras);
                    ?>
                </td>
                <td><input type="text" class="form-control size_xl" id="i_name" name="i_name" required></td>
                <td>
                    <?php
                    $options  = _elm($aConfig, 'status', []);
                    $extras   = ['id' => 'i_status', 'class' => 'form-control size_xl'];
                    $selected = 1;
                    echo form_dropdown('i_status', $options, $selected, $extras);
                    ?>
                </td>
                <td>
                    <?php
                    $options  = _elm($aConfig, 'sort', []);
                    $extras   = ['id' => 'i_sort', 'class' => 'form-control size_xl'];
                    $selected = 1;
                    echo form_dropdown('i_sort', $options, $selected, $extras);
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div style="padding:0.5em 0.5em">
        <div id="" class="btn_list clearfix">
            <div class="float-right">
                <button type="submit" class="btn btn-success waves-effect waves-light">
                    <span class="btn-label"><i class="mdi mdi-playlist-plus "></i></span>추가
                </button>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_code_lists', 'onSubmit' => 'modifyCodeConfirm(); return false;', 'autocomplete' => 'off']); ?>
<div class="container-fluid" style="margin-bottom: 32px">

    <div class="card-title">
        <h3 class="h3-c">메뉴목록</h3>
        <p class="body2-c" style="color: #616876"></p>
    </div>
    <div style="padding:0.5em 0.5em">
        <div id="" class="btn_list clearfix">
            <div class="float-right">
                <button type="submit" class="btn btn-success waves-effect waves-light">
                    선택수정
                </button>
            </div>
        </div>
    </div>


    <div class="table-responsive-other">
        <table class="table table-vcenter table-bordered table-striped mb-0 text-center nowrap ">
            <colgroup>
                <col style="width:3%;">
                <col style="width:3%;">
                <col style="width:10%;">
                <col style="width:20%;">
                <col style="width:20%;">
                <col style="width:10%;">
                <col style="width:10%;">

            </colgroup>
            <thead class="thead-light">
            <tr>
                <th>
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
                <th>IDX</th>
                <th>코드</th>
                <th>코드명<span class="text-danger">*</span></th>
                <th>노출상태</th>
                <th>노출순서</th>
                <th>-</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (empty($aCodeTreeLists) === false)
            {
                foreach ($aCodeTreeLists as $kIDX => $vCODE)
                {
                    $code_idx = _elm($vCODE, 'C_IDX', 0);
            ?>
            <tr class="table-primary">
                <td>
                    <div class="checkbox checkbox-single">
                        <?php
                        $setParam = [
                            'name' => 'i_code_idx[]',
                            'id' => 'i_code_idx_'.$code_idx,
                            'value' =>  $code_idx,
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
                <td><?php echo $code_idx; ?></td>

                <td><input type="text" class="form-control size_xl" id="i_code_<?php echo $code_idx; ?>" name="i_code[<?php echo $code_idx; ?>]" value="<?php echo _elm($vCODE, 'C_CODE', ''); ?>" required readonly></td>
                <td><input type="text" class="form-control size_xl" id="i_name_<?php echo $code_idx; ?>" name="i_name[<?php echo $code_idx; ?>]" value="<?php echo _elm($vCODE, 'C_NAME', ''); ?>" required></td>
                <td>
                    <?php
                    $options  = _elm($aConfig, 'status', []);
                    $extras   = ['id' => 'i_status_' . $code_idx, 'class' => 'form-control size_xl'];
                    $selected = _elm($vCODE, 'C_STATUS', 0);
                    echo form_dropdown('i_status[' .$code_idx . ']', $options, $selected, $extras);
                    ?>
                </td>
                <td>
                    <?php
                    $options  = _elm($aConfig, 'sort', []);
                    $extras   = ['id' => 'i_sort_' . $code_idx, 'class' => 'form-control size_xl'];
                    $selected = _elm($vCODE, 'C_SORT', 1);
                    echo form_dropdown('i_sort[' .$code_idx . ']', $options, $selected, $extras);
                    ?>
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
                            'onclick' => 'deleteCodeConfirm("'. $code_idx.'");',
                        ]
                    ]);
                ?>

                </td>
            </tr>
                <?php
                    if (empty($vCODE['CHILD']) === false)
                    {
                        foreach (_elm($vCODE, 'CHILD', []) as $kIDX_CHILD => $vCODE_CHILD)
                        {
                            $code_idx = _elm($vCODE_CHILD, 'C_IDX', 0);
                ?>
                <tr class="table-success">
                    <td>
                        <div class="checkbox checkbox-single">
                            <?php
                            $setParam = [
                                'name' => 'i_code_idx[]',
                                'id' => 'i_code_idx_'.$code_idx,
                                'value' =>  $code_idx,
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
                        <div class="d-flex justify-content-start">
                            <i class="mdi bi-arrow-return-right"></i>&nbsp;
                        </div>
                        <?php echo $code_idx; ?>
                    </td>
                    <td><input type="text" class="form-control size_xl" id="i_code_<?php echo $code_idx; ?>" name="i_code[<?php echo $code_idx; ?>]" value="<?php echo _elm($vCODE_CHILD, 'C_CODE', ''); ?>" required readonly></td>
                    <td><input type="text" class="form-control size_xl" id="i_name_<?php echo $code_idx; ?>" name="i_name[<?php echo $code_idx; ?>]" value="<?php echo _elm($vCODE_CHILD, 'C_NAME', ''); ?>" required></td>
                    <td>
                        <?php
                        $options  = _elm($aConfig, 'status', []);
                        $extras   = ['id' => 'i_status_' . $code_idx, 'class' => 'form-control size_xl'];
                        $selected = _elm($vCODE_CHILD, 'C_STATUS', 0);
                        echo form_dropdown('i_status[' .$code_idx . ']', $options, $selected, $extras);
                        ?>
                    </td>
                    <td>
                        <?php
                        $options  = _elm($aConfig, 'sort', []);
                        $extras   = ['id' => 'i_sort_' . $code_idx, 'class' => 'form-control size_xl'];
                        $selected = _elm($vCODE_CHILD, 'C_SORT', 1);
                        echo form_dropdown('i_sort[' .$code_idx . ']', $options, $selected, $extras);
                        ?>
                    </td>
                    <td>
                        <a href="javascript:;"onclick="deleteCodeConfirm('<?php echo $code_idx; ?>');">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 7H20" stroke="#616876" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 11V17" stroke="#616876" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 11V17" stroke="#616876" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5 7L6 19C6 19.5304 6.21071 20.0391 6.58579 20.4142C6.96086 20.7893 7.46957 21 8 21H16C16.5304 21 17.0391 20.7893 17.4142 20.4142C17.7893 20.0391 18 19.5304 18 19L19 7" stroke="#616876" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 7V4C9 3.73478 9.10536 3.48043 9.29289 3.29289C9.48043 3.10536 9.73478 3 10 3H14C14.2652 3 14.5196 3.10536 14.7071 3.29289C14.8946 3.48043 15 3.73478 15 4V7" stroke="#616876" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                    <?php
                        if (empty($vCODE_CHILD['CHILD']) === false)
                        {
                            foreach (_elm($vCODE_CHILD, 'CHILD', []) as $kIDX_GRAND_CHILD => $vCODE_GRAND_CHILD)
                            {
                                $code_idx = _elm($vCODE_GRAND_CHILD, 'C_IDX', 0);
                    ?>
                    <tr>
                        <td>
                            <div class="checkbox checkbox-single">
                            <?php
                            $setParam = [
                                'name' => 'i_code_idx[]',
                                'id' => 'i_code_idx_'.$code_idx,
                                'value' =>  $code_idx,
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
                            <div class="d-flex justify-content-start">
                                <i class="mdi bi-arrow-return-right"></i>&nbsp;
                                <i class="mdi bi-arrow-return-right"></i>
                            </div>
                            <?php echo $code_idx; ?>
                        </td>
                        <td><input type="text" class="form-control size_xl" id="i_code_<?php echo $code_idx; ?>" name="i_code[<?php echo $code_idx; ?>]" value="<?php echo _elm($vCODE_GRAND_CHILD, 'C_CODE', ''); ?>" required readonly></td>
                        <td><input type="text" class="form-control size_xl" id="i_name_<?php echo $code_idx; ?>" name="i_name[<?php echo $code_idx; ?>]" value="<?php echo _elm($vCODE_GRAND_CHILD, 'C_NAME', ''); ?>" required></td>
                        <td>
                            <?php
                            $options  = _elm($aConfig, 'status', []);
                            $extras   = ['id' => 'i_status_' . $code_idx, 'class' => 'form-control size_xl'];
                            $selected = _elm($vCODE_GRAND_CHILD, 'C_STATUS', 0);
                            echo form_dropdown('i_status[' .$code_idx . ']', $options, $selected, $extras);
                            ?>
                        </td>
                        <td>
                            <?php
                            $options  = _elm($aConfig, 'sort', []);
                            $extras   = ['id' => 'i_sort_' . $code_idx, 'class' => 'form-control size_xl'];
                            $selected = _elm($vCODE_GRAND_CHILD, 'C_SORT', 1);
                            echo form_dropdown('i_sort[' .$code_idx . ']', $options, $selected, $extras);
                            ?>
                        </td>
                        <td>
                            <a href="javascript:;"onclick="deleteCodeConfirm('<?php echo $code_idx; ?>');">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 7H20" stroke="#616876" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10 11V17" stroke="#616876" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14 11V17" stroke="#616876" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M5 7L6 19C6 19.5304 6.21071 20.0391 6.58579 20.4142C6.96086 20.7893 7.46957 21 8 21H16C16.5304 21 17.0391 20.7893 17.4142 20.4142C17.7893 20.0391 18 19.5304 18 19L19 7" stroke="#616876" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 7V4C9 3.73478 9.10536 3.48043 9.29289 3.29289C9.48043 3.10536 9.73478 3 10 3H14C14.2652 3 14.5196 3.10536 14.7071 3.29289C14.8946 3.48043 15 3.73478 15 4V7" stroke="#616876" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    <?php
                            }
                        }
                    ?>
                <?php
                        }
                    }
                ?>

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
                <button type="submit" class="btn btn-success waves-effect waves-light">
                    선택수정
                </button>
            </div>
        </div>
    </div>
</div>
<!-- 콘텐츠 끝 -->

<?php echo form_close(); ?>
<script>
var codeData = <?php echo json_encode($aCodeTreeLists)?>;
</script>
<?php
$owensView->setFooterJs('/assets/js/setting/code/code.js');
$owensView->setHeaderCss('/plugins/select2/select2.css');
$owensView->setFooterJs('/plugins/select2/select2.js');
$script = "
$(document).ready(function () {
    $('.select2').select2({
        language: 'ko',
        placeholder: '',
        allowClear: true,
    });
});
";

$owensView->setFooterScript($script);
