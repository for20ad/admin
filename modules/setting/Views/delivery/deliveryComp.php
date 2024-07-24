<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($pageDatas, 'aConfig', []);
    $aData            = _elm($pageDatas, 'gData', [] );
?>

<div class="container-fluid">
    <!-- 카드 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">배송업체</h3>
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
                                업체 추가
                            </p>
                        </div>
                        <!-- 아코디언 토글 버튼 -->
                        <label class="form-selectgroup-item" onclick="toggleForm($(this))">
                            <input type="radio" name="icons" value="home" class="form-selectgroup-input" checked />
                            <span class="form-selectgroup-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8" fill="none">
                                    <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
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
                                    <col style="width:25%;">
                                    <col style="width:40%;">
                                    <col style="width:20%;">
                                    <col style="width:15%;">
                                </colgroup>

                                <tr>
                                    <th style="padding-left:2em">업체명</th>
                                    <th style="padding-left:2em">추적URL</th>
                                    <th style="padding-left:2em">특이사항</th>
                                    <th style="padding-left:2em">고정업체</th>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-inline">
                                            <input type="text" class="form-control" name="i_company_name" id="i_company_name">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-inline">
                                            <input type="text" class="form-control" name="i_trace_url" id="i_trace_url">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-inline">
                                            <input type="text" class="form-control" name="i_company_ext">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-inline">
                                            <?php
                                            $setParam = [
                                                'name' => 'i_fix_yn',
                                                'id' => 'i_fix_yn',
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
                                'onclick' => 'addDeliveryCompany();',
                            ]
                        ]);
                        ?>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
            <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_lists', 'onSubmit' => 'modifyDeliveryCompConfirm(); return false;', 'autocomplete' => 'off']); ?>
                <div class="card accordion-card"
                    style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4" fill="none">
                                <circle cx="2" cy="2" r="2" fill="#206BC4" />
                            </svg>
                            <p class="body1-c ms-2 mt-1">
                                배송업체목록
                            </p>
                            <div style="margin-left:3vh;">드레그하여 순서를 변경할 수 있습니다.</div>
                        </div>
                        <!-- 아코디언 토글 버튼 -->
                        <label class="form-selectgroup-item" onclick="toggleForm($(this))">
                            <input type="radio" name="icons" value="home" class="form-selectgroup-input" checked />
                            <span class="form-selectgroup-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8" fill="none">
                                    <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="card-body">
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
                                <col style="width:5%;">
                                <col style="width:15%;">
                                <col>
                                <col style="width:15%;">
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
                                                'class' => 'checkAll',
                                                'aria-label' => 'Single checkbox One'
                                            ]
                                        ];
                                        echo getCheckBox($setParam);
                                        ?>
                                    </div>
                                </th>
                                <th>업체명</th>
                                <th>추적URL</th>
                                <th>특이사항</th>
                                <th>고정업체</th>
                                <th>삭제</th>
                            </tr>
                            </thead>
                            <tbody id="sortable">
                            <?php
                            if (empty($aData) === false) {
                                foreach ($aData as $key => $lists) {
                            ?>
                            <tr>
                                <td>
                                    <div class="checkbox checkbox-single">
                                        <?php
                                        $setParam = [
                                            'name' => 'i_idx[]',
                                            'id' => 'i_idx_' . _elm($lists, 'D_IDX'),
                                            'value' => _elm($lists, 'D_IDX'),
                                            'label' => '',
                                            'checked' => false,
                                            'extraAttributes' => [
                                                'aria-label' => 'Single checkbox One',
                                                'class' => 'check-item',
                                            ]
                                        ];
                                        echo getCheckBox($setParam);
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" name="i_company_name[<?php echo _elm($lists, 'D_IDX') ?>]" id="i_company_name_<?php echo _elm($lists, 'D_IDX') ?>" value="<?php echo _elm($lists, 'D_COMPANY_NAME') ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" name="i_trace_url[<?php echo _elm($lists, 'D_IDX') ?>]" id="i_trace_url_<?php echo _elm($lists, 'D_IDX') ?>" value="<?php echo _elm( $lists, 'D_TRACE_URL' )?>" >
                                    </div>
                                </td>
                                <td>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" name="i_company_ext[<?php echo _elm($lists, 'D_IDX') ?>]" value="<?php echo _elm($lists, 'D_COMPANY_EXT') ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-inline">
                                        <?php
                                        $checked = false;
                                        if (_elm($lists, 'D_FIX_YN') == 'Y') {
                                            $checked = true;
                                        }
                                        $setParam = [
                                            'name' => 'i_fix_yn[' . $lists['D_IDX'] . ']',
                                            'id' => 'i_fix_yn_' . $lists['D_IDX'],
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
                                    <a href="javascript:;" onclick="deletedeliveryCompConfirm('<?php echo _elm($lists, 'D_IDX') ?>');">
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
                            </tbody>
                        </table>
                    </div>
                    <div style="padding:0.5em 0.5em">
                        <div id="" class="btn_list clearfix">
                            <div class="float-right">
                                <button type="submit" class="btn btn-success">
                                    선택수정
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
</div>

<script>
function addDeliveryCompany(){
    event.preventDefault();
    const frm = $('#frm_register');
    let error_lists = [];
    $('.error_txt').html('');

    var inputs = frm.find('input, button');
    var isSubmit = true;

    if ($.trim(frm.find('#i_company_name').val()) == '')
    {
        _form_error('i_company_name', '업체명을 입력하세요.');
        isSubmit = false;
    }

    if ($.trim(frm.find('#i_trace_url').val()) == '')
    {
        _form_error('i_trace_url', '추적url을 입력하세요.');
        isSubmit = false;
    }

    if (isSubmit == false)
    {
        var error_message = '';
        error_message = error_lists.join('<br />');
        box_alert(error_message, 'e');

        inputs.prop('disabled', false);
        return false;
    }

    $.ajax({
        url: '/apis/setting/addDeliveryCompany',
        method: 'POST',
        data: new FormData(frm[0]),
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function()
        {
            inputs.prop('disabled', true);
            setTimeout(function() { inputs.prop('disabled', false); }, 3000);
        },
        success: function(response)
        {
            submitSuccess(response);

            inputs.prop('disabled', false);

            if (response.status == 'false')
            {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'i');
                }

                return false;
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);

            inputs.prop('disabled', false);
            return false;
        },
        complete: function() { }
    });
}


function modifyDeliveryCompConfirm()
{
    if ($('input:checkbox[name="i_idx[]"]:checked').length > 0)
    {
        box_confirm('선택된 업채룰 수정하시겠습니까?', 'q', '', modifyDeliveryComp);
    }
    else
    {
        box_alert('선택된 업체가 없습니다.', 'i');
    }
}

function modifyDeliveryComp()
{
    const frm = $('#frm_lists')
    $.ajax({
        url: '/apis/setting/modifyDeliveryComp',
        type: 'post',
        data: new FormData( frm[0] ),
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function() { },
        success: function(response) {
            submitSuccess(response);

            if (response.status == 'false')
            {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'i');
                }

                return false;
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);

            return false;
        },
        complete: function() { }
    });
}
function deletedeliveryCompConfirm( g_idx ){
    box_confirm('등급을 삭제하시겠습니까?', 'q', '', deletedeliveryComp, {'d_idx': g_idx});
}
function deletedeliveryComp( param ){
    $.ajax({
        url: '/apis/setting/deletedeliveryComp',
        type: 'post',
        data: 'd_idx='+param.d_idx,
        dataType: 'json',
        processData: false,
        cache: false,
        beforeSend: function() { },
        success: function(response) {
            submitSuccess(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);

            return false;
        },
        complete: function() { }
    });
}

function updateDeliveryCompanySort()
{
    var s_data = new FormData();

    $("[name='i_idx[]']").each(function() {
        s_data.append('sort[]', $(this).val());
    });

    $.ajax({
        url: "/apis/setting/updateDeliveryCompanySort",
        type: 'post',
        data: s_data,
        dataType: 'json',
        processData:false,
        contentType: false,
        cache:false,
        success: function(response) {
            submitSuccess(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus);
            return submitError(jqXHR.status, errorThrown);
        }
    });
}

</script>

<?php
$owensView->setFooterJs('/assets/js/setting/delivery/deliveryComp.js');

$script = "
    $('#sortable').sortable({
        update: function(event, ui) {
            var idsInOrder = $('#sortable').sortable('toArray',{ attribute : 'data-idx'});
            updateDeliveryCompanySort(idsInOrder);
            console.log(idsInOrder);
        }
    });
";

$owensView->setFooterScript($script);
?>
