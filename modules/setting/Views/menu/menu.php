<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }
    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];

    $aConfig          = _elm($pageDatas, 'config', []);
    $aMenu            = _elm($pageDatas, 'menu', []);
    $aMenuTreeLists   = _elm($pageDatas, 'menu_tree_lists', []);
    $aMemberGroup    = _elm($pageDatas, 'member_group', []);
?>
<!-- 콘텐츠 시작 -->
<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_menu_write', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
<div class="container-fluid" style="margin-bottom: 32px">
    <div class="card-title">
        <h3 class="h3-c">메뉴관리</h3>
        <p class="body2-c" style="color: #616876"></p>
        <!-- <div>일반 alert
        <button onclick="box_alert('success','s')">success</button>
        <button onclick="box_alert('error','e')">error</button>
        <button onclick="box_alert('warning','w')">warning</button>
        <button onclick="box_alert('info','i')">info</button>
        <button onclick="box_alert('question','q')">question</button>
        </div>&nbsp;&nbsp;&nbsp;&nbsp;
        <div>auto close
        <button onclick="box_alert_autoclose('success','s')">success</button>
        <button onclick="box_alert_autoclose('error','e')">error</button>
        <button onclick="box_alert_autoclose('warning','w')">warning</button>
        <button onclick="box_alert_autoclose('info','i')">info</button>
        <button onclick="box_alert_autoclose('question','q')">question</button>
        </div>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <div>close & focus
        <button onclick="box_alert_focus('success','#i_group_id','s')">success</button>
        <button onclick="box_alert_focus('error','#i_group_id','e')">error</button>
        <button onclick="box_alert_focus('warning','#i_group_id','w')">warning</button>
        <button onclick="box_alert_focus('info','#i_group_id','i')">info</button>
        <button onclick="box_alert_focus('question','#i_group_id','q')">question</button>
        </div>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <div> confirm
        <button onclick="box_confirm('success','s')">success</button>
        <button onclick="box_confirm('error','e')">error</button>
        <button onclick="box_confirm('warning','w')">warning</button>
        <button onclick="box_confirm('info','i')">info</button>
        <button onclick="box_confirm('question','q')">question</button>
        </div> -->
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter table-bordered mb-0 text-center nowrap">

            <colgroup>
                <col style="width:10%;">
                <col style="width:10%;">
                <col style="width:15%;">
                <col style="">
                <col style="width:6%;">
                <col style="width:20%;">
                <col style="width:6%;">
                <col style="width:4%;">
            </colgroup>
            <thead class="thead-light">
            <tr>
                <th>상위메뉴</th>
                <th>메뉴그룹아이디</th>
                <th>메뉴명<span class="text-danger">*</span></th>
                <th>링크<span class="text-danger">*</span></th>
                <th>링크타겟</th>
                <th>노출관리자그룹</th>
                <th>노출상태</th>
                <th>노출순서</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <?php
                    $options  = [0 => '최상위메뉴'] + $aMenu;
                    $extras   = ['id' => 'i_parent_idx', 'class' => 'form-control size_xl', 'onchange'=>'findSort()'];
                    $selected = 0;
                    echo form_dropdown('i_parent_idx', $options, $selected, $extras);
                    ?>
                </td>
                <td><input type="text" class="form-control size_xl" id="i_group_id" name="i_group_id"></td>
                <td><input type="text" class="form-control size_xl" id="i_name" name="i_name" required></td>
                <td><input type="text" class="form-control size_xl" id="i_link" name="i_link" required></td>
                <td>
                    <?php
                    $options  = _elm($aConfig, 'target', []);
                    $extras   = ['id' => 'i_link_target', 'class' => 'form-control size_xl'];
                    $selected = '_self';
                    echo form_dropdown('i_link_target', $options, $selected, $extras);
                    ?>
                </td>
                <td>
                    <?php
                    $options  = $aMemberGroup;
                    $extras   = ['i_display_member_group', 'class' => 'select2 form-control size_lg', 'multiple' => 'multiple'];
                    $selected = '';
                    echo form_dropdown('i_display_member_group[]', $options, $selected, $extras);
                    ?>
                </td>
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

<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_menu_lists', 'onSubmit' => 'modifyMenuConfirm(); return false;', 'autocomplete' => 'off']); ?>
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


    <div class="table-responsive">
        <table class="table table-vcenter table-bordered table-striped mb-0 text-center nowrap ">
            <colgroup>
                <col style="width:3%;">
                <col style="width:3%;">
                <col style="width:8%;">
                <col style="width:10%;">
                <col style="">
                <col style="width:6%;">
                <col style="width:25%;">
                <col style="width:6%;">
                <col style="width:6%;">
                <col style="width:4%;">
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
                <th>메뉴그룹아이디</th>
                <th>메뉴명<span class="text-danger">*</span></th>
                <th>링크<span class="text-danger">*</span></th>
                <th>링크타겟</th>
                <th>노출관리자그룹</th>
                <th>노출상태</th>
                <th>노출순서</th>
                <th>-</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (empty($aMenuTreeLists) === false)
            {
                foreach ($aMenuTreeLists as $kIDX => $vMENU)
                {
                    $menu_idx = _elm($vMENU, 'MENU_IDX', 0);
            ?>
            <tr class="table-primary">
                <td>
                    <div class="checkbox checkbox-single">
                        <?php
                        $setParam = [
                            'name' => 'i_menu_idx[]',
                            'id' => 'i_menu_idx_'.$menu_idx,
                            'value' =>  $menu_idx,
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
                <td><?php echo $menu_idx; ?></td>
                <td><input type="text" class="form-control size_xl" id="i_group_id_<?php echo $menu_idx; ?>" name="i_group_id[<?php echo $menu_idx; ?>]" value="<?php echo _elm($vMENU, 'MENU_GROUP_ID', ''); ?>"></td>
                <td><input type="text" class="form-control size_xl" id="i_name_<?php echo $menu_idx; ?>" name="i_name[<?php echo $menu_idx; ?>]" value="<?php echo _elm($vMENU, 'MENU_NAME', ''); ?>" required></td>
                <td><input type="text" class="form-control size_xl" id="i_link_<?php echo $menu_idx; ?>" name="i_link[<?php echo $menu_idx; ?>]" value="<?php echo _elm($vMENU, 'MENU_LINK', ''); ?>" required></td>
                <td>
                    <?php
                    $options  = _elm($aConfig, 'target', []);
                    $extras   = ['id' => 'i_target_' . $menu_idx, 'class' => 'form-control size_xl'];
                    $selected = _elm($vMENU, 'MENU_LINK_TARGET', '');
                    echo form_dropdown('i_target[' .$menu_idx . ']', $options, $selected, $extras);
                    ?>
                </td>
                <td>
                    <?php
                    $aMemberGroups = explode(',', _elm($vMENU, 'MENU_DISPLAY_MEMBER_GROUP', ''));

                    $options  = $aMemberGroup;
                    $extras   = ['id' => 'i_display_member_group_' . _elm($vMENU, 'MENU_IDX', ''), 'class' => 'select2 form-control size_lg', 'multiple' => 'multiple'];
                    $selected = $aMemberGroups;
                    echo form_dropdown('i_display_member_group[' .$menu_idx . '][]', $options, $selected, $extras);
                    ?>
                </td>
                <td>
                    <?php
                    $options  = _elm($aConfig, 'status', []);
                    $extras   = ['id' => 'i_status_' . $menu_idx, 'class' => 'form-control size_xl'];
                    $selected = _elm($vMENU, 'MENU_STATUS', 0);
                    echo form_dropdown('i_status[' .$menu_idx . ']', $options, $selected, $extras);
                    ?>
                </td>
                <td>
                    <?php
                    $options  = _elm($aConfig, 'sort', []);
                    $extras   = ['id' => 'i_sort_' . $menu_idx, 'class' => 'form-control size_xl'];
                    $selected = _elm($vMENU, 'MENU_SORT', 1);
                    echo form_dropdown('i_sort[' .$menu_idx . ']', $options, $selected, $extras);
                    ?>
                </td>
                <td>
                    <a href="javascript:;"onclick="deleteMenuConfirm('<?php echo $menu_idx; ?>');">
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
                    if (empty($vMENU['CHILD']) === false)
                    {
                        foreach (_elm($vMENU, 'CHILD', []) as $kIDX_CHILD => $vMENU_CHILD)
                        {
                            $menu_idx = _elm($vMENU_CHILD, 'MENU_IDX', 0);
                ?>
                <tr>
                    <td>
                        <div class="checkbox checkbox-single">
                            <?php
                            $setParam = [
                                'name' => 'i_menu_idx[]',
                                'id' => 'i_menu_idx_'.$menu_idx,
                                'value' =>  $menu_idx,
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
                        <?php echo $menu_idx; ?>
                    </td>
                    <td>
                        <input type="text" class="form-control size_xl" id="i_group_id_<?php echo $menu_idx; ?>" name="i_group_id[<?php echo $menu_idx; ?>]" value="<?php echo _elm($vMENU_CHILD, 'MENU_GROUP_ID', ''); ?>">
                    </td>
                    <td><input type="text" class="form-control size_xl" id="i_name_<?php echo $menu_idx; ?>" name="i_name[<?php echo $menu_idx; ?>]" value="<?php echo _elm($vMENU_CHILD, 'MENU_NAME', ''); ?>" required></td>
                    <td><input type="text" class="form-control size_xl" id="i_link_<?php echo $menu_idx; ?>" name="i_link[<?php echo $menu_idx; ?>]" value="<?php echo _elm($vMENU_CHILD, 'MENU_LINK', ''); ?>" required></td>
                    <td>
                        <?php
                        $options  = _elm($aConfig, 'target', []);
                        $extras   = ['id' => 'i_target_' . $menu_idx, 'class' => 'form-control size_xl'];
                        $selected = _elm($vMENU_CHILD, 'MENU_LINK_TARGET', '');
                        echo form_dropdown('i_target[' .$menu_idx . ']', $options, $selected, $extras);
                        ?>
                    </td>
                    <td>
                        <?php
                        $aMemberGroups = explode(',', _elm($vMENU_CHILD, 'MENU_DISPLAY_MEMBER_GROUP', ''));

                        $options  = $aMemberGroup;
                        $extras   = ['id' => 'i_display_member_group_' . _elm($vMENU_CHILD, 'MENU_IDX', ''), 'class' => 'select2 form-control size_lg', 'multiple' => 'multiple'];
                        $selected = $aMemberGroups;
                        echo form_dropdown('i_display_member_group[' .$menu_idx . '][]', $options, $selected, $extras);
                        ?>
                    </td>
                    <td>
                        <?php
                        $options  = _elm($aConfig, 'status', []);
                        $extras   = ['id' => 'i_status_' . $menu_idx, 'class' => 'form-control size_xl'];
                        $selected = _elm($vMENU_CHILD, 'MENU_STATUS', 0);
                        echo form_dropdown('i_status[' .$menu_idx . ']', $options, $selected, $extras);
                        ?>
                    </td>
                    <td>
                        <?php
                        $options  = _elm($aConfig, 'sort', []);
                        $extras   = ['id' => 'i_sort_' . $menu_idx, 'class' => 'form-control size_xl'];
                        $selected = _elm($vMENU_CHILD, 'MENU_SORT', 1);
                        echo form_dropdown('i_sort[' .$menu_idx . ']', $options, $selected, $extras);
                        ?>
                    </td>
                    <td>
                        <a href="javascript:;"onclick="deleteMenuConfirm('<?php echo $menu_idx; ?>');">
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
                        if (empty($vMENU_CHILD['CHILD']) === false)
                        {
                            foreach (_elm($vMENU_CHILD, 'CHILD', []) as $kIDX_GRAND_CHILD => $vMENU_GRAND_CHILD)
                            {
                                $menu_idx = _elm($vMENU_GRAND_CHILD, 'MENU_IDX', 0);
                    ?>
                    <tr>
                        <td>
                            <div class="checkbox checkbox-single">
                            <?php
                            $setParam = [
                                'name' => 'i_menu_idx[]',
                                'id' => 'i_menu_idx_'.$menu_idx,
                                'value' =>  $menu_idx,
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
                            <?php echo $menu_idx; ?>
                        </td>
                        <td>

                            <input type="text" class="form-control size_sm wid_auto" id="i_group_id_<?php echo $menu_idx; ?>" name="i_group_id[<?php echo $menu_idx; ?>]" value="<?php echo _elm($vMENU_GRAND_CHILD, 'MENU_GROUP_ID', ''); ?>">
                        </td>
                        <td><input type="text" class="form-control size_xl" id="i_name_<?php echo $menu_idx; ?>" name="i_name[<?php echo $menu_idx; ?>]" value="<?php echo _elm($vMENU_GRAND_CHILD, 'MENU_NAME', ''); ?>" required></td>
                        <td><input type="text" class="form-control size_xl" id="i_link_<?php echo $menu_idx; ?>" name="i_link[<?php echo $menu_idx; ?>]" value="<?php echo _elm($vMENU_GRAND_CHILD, 'MENU_LINK', ''); ?>" required></td>
                        <td>
                            <?php
                            $options  = _elm($aConfig, 'target', []);
                            $extras   = ['id' => 'i_target_' . $menu_idx, 'class' => 'form-control size_xl'];
                            $selected = _elm($vMENU_GRAND_CHILD, 'MENU_LINK_TARGET', '');
                            echo form_dropdown('i_target[' .$menu_idx . ']', $options, $selected, $extras);
                            ?>
                        </td>
                        <td>
                            <?php
                            $aMemberGroups = explode(',', _elm($vMENU_GRAND_CHILD, 'MENU_DISPLAY_MEMBER_GROUP', ''));

                            $options  = $aMemberGroup;
                            $extras   = ['id' => 'i_display_member_group_' . _elm($vMENU_GRAND_CHILD, 'MENU_IDX', ''), 'class' => 'select2 form-control size_lg', 'multiple' => 'multiple'];
                            $selected = $aMemberGroups;
                            echo form_dropdown('i_display_member_group[' .$menu_idx . '][]', $options, $selected, $extras);
                            ?>
                        </td>
                        <td>
                            <?php
                            $options  = _elm($aConfig, 'status', []);
                            $extras   = ['id' => 'i_status_' . $menu_idx, 'class' => 'form-control size_xl'];
                            $selected = _elm($vMENU_GRAND_CHILD, 'MENU_STATUS', 0);
                            echo form_dropdown('i_status[' .$menu_idx . ']', $options, $selected, $extras);
                            ?>
                        </td>
                        <td>
                            <?php
                            $options  = _elm($aConfig, 'sort', []);
                            $extras   = ['id' => 'i_sort_' . $menu_idx, 'class' => 'form-control size_xl'];
                            $selected = _elm($vMENU_GRAND_CHILD, 'MENU_SORT', 1);
                            echo form_dropdown('i_sort[' .$menu_idx . ']', $options, $selected, $extras);
                            ?>
                        </td>
                        <td>
                            <a href="javascript:;"onclick="deleteMenuConfirm('<?php echo $menu_idx; ?>');">
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
var menuData = <?php echo json_encode($aMenuTreeLists)?>;
</script>
<?php
$owensView->setFooterJs('/assets/js/setting/menu.js');
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
