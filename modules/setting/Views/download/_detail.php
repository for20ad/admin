<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);
    $aField           = _elm($view_datas, 'fields', []);
    $aData            = _elm($view_datas, 'aData', []);
    $fFields          = isset($aData['F_FIELDS']) ? explode('|', $aData['F_FIELDS']) : [];
?>

<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_modify', 'autocomplete' => 'off']); ?>
<input type="hidden" name="i_idx" value="<?php echo _elm( $aData, 'F_IDX' )?>">
<div class="row row-deck row-cards">
    <!-- 카드1 -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="input-group required">
                    <label class="label body2-c">
                        양식 명
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" placeholder="다운로드양식 명을 입력하세요." name="i_title" id="i_title" data-max-length="30" style="border-top-right-radius:0px; border-bottom-right-radius: 0px" value="<?php echo _elm( $aData, 'F_TITLE' )?>"/>
                    <span class="wordCount input-group-text" style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        0/30
                    </span>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        메뉴 분류
                        <span>*</span>
                    </label>
                    <?php
                    $options  = _elm($aConfig, 'forms', []);
                    $extras   = ['id' => 'i_forms', 'class' => 'form-select', 'style' => 'max-width: 174px', 'onchange'=>'fetchData()'];
                    $selected = _elm( $aData, 'F_MENU' );
                    echo getSelectBox('i_forms', $options, $selected, $extras);
                    ?>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        노출여부
                        <span>*</span>
                    </label>
                    <?php
                        $checked = false;
                        if( 'Y' == _elm( $aData, 'F_STATUS' ) ){
                            $checked = true;
                        }
                        $setParam = [
                            'name' => 'i_status',
                            'id' => 'i_status_Y',
                            'value' => 'Y',
                            'label' => '노출',
                            'checked' => $checked,
                            'extraAttributes' => [
                            ]
                        ];
                        echo getRadioButton($setParam);
                    ?>
                    <?php
                        $checked = false;
                        if( 'N' == _elm( $aData, 'F_STATUS' ) ){
                            $checked = true;
                        }
                        $setParam = [
                            'name' => 'i_status',
                            'id' => 'i_status_N',
                            'value' => 'N',
                            'label' => '미노출',
                            'checked' => $checked,
                            'extraAttributes' => [
                            ]
                        ];
                        echo getRadioButton($setParam);
                    ?>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        정렬순서
                    </label>
                    <div class="col-2">
                        <input type="text" class="form-control" style="width:3.5em" name="i_sort" value="<?php echo _elm( $aData, 'F_SORT' )?>">
                    </div>
                </div>

                <div class="input-group required">
                    <label class="label body2-c">
                        필드 선택
                        <span>*</span>
                    </label>
                    <div class="select-container">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="available-items" style="padding-top:1.2rem">사용 가능한 항목</label>
                                <select id="available-items" class="form-control multi-select-box" multiple>
                                    <?php if( !empty( $aField ) ){
                                        foreach( $aField as $vField ){
                                    ?>
                                    <option value="<?php echo _elm( $vField, 'COLUMN_NAME' )?>"><?php echo _elm( $vField, 'COLUMN_COMMENT' )?></option>
                                    <?php
                                        }
                                    }?>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-center align-items-center">
                                <button id="add-btn" onclick="event.preventDefault();addItem(event)" class="btn btn-primary mb-2">&gt;</button>
                                <button id="remove-btn" onclick="event.preventDefault();removeItem(event)" class="btn btn-danger">&lt;</button>
                            </div>
                            <div class="col-md-5">
                                <label for="selected-items" style="padding-top:1.2rem">선택된 항목</label>
                                <select id="selected-items" class="form-control multi-select-box" name="i_field" multiple></select>
                            </div>
                        </div>
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
                'onclick' => 'frmModify(event);',
            ]
        ]);
        ?>
    </div>
</div>
<?php echo form_close() ?>
<script>
var originalOrder = <?php echo json_encode(array_map(function($vField) { return _elm($vField, 'COLUMN_NAME'); }, $aField)); ?>;
var selectedFields = <?php echo json_encode($fFields); ?>;

function addItem(){
    $('#available-items option:selected').each(function() {
        $('#selected-items').append($(this).clone());
        $(this).remove();
    });
}

function removeItem(){
    $('#selected-items option:selected').each(function() {
        $('#available-items').append($(this).clone());
        $(this).remove();
    });

    sortAvailableItems();
}

function sortAvailableItems() {
    var options = $('#available-items option');
    options.sort(function(a, b) {
        return originalOrder.indexOf($(a).val()) - originalOrder.indexOf($(b).val());
    });
    $('#available-items').empty().append(options);
}

function autoSelectItems() {
    $('#available-items option').each(function() {
        if (selectedFields.includes($(this).val())) {
            $('#selected-items').append($(this).clone());
            $(this).remove();
        }
    });
}

function fetchData() {
    var formValue = $('#i_forms').val();
    if (formValue) {
        $.ajax({
            url: '/apis/setting/getFormFileds',
            type: 'POST',
            data: { form: formValue },
            dataType: 'json',
            success: function(response) {
                updateSelectBoxes(response.fields);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ', status, error);
            }
        });
    }
}

function updateSelectBoxes(fields) {
    var availableItems = $('#available-items');
    var selectedItems = $('#selected-items');

    availableItems.empty();
    selectedItems.empty();

    $.each(fields, function(index, field) {
        availableItems.append($('<option>', { value: field.COLUMN_NAME, text: field.COLUMN_COMMENT }));
    });

    originalOrder = $('#available-items option').map(function() {
        return $(this).val();
    }).get();

    sortAvailableItems();

    // 자동 선택 기능 호출
    autoSelectItems();
}

function frmModify(event) {
    event.preventDefault();

    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_modify').find('input, button, select');
    var isSubmit = true;

    if ($.trim($('#i_title').val()) == '') {
        _form_error('i_title', '양식 명을 입력하세요.');
        isSubmit = false;
    }

    // i_field 체크
    if ($('#selected-items option').length == 0) {
        _form_error('selected-items', '적어도 하나의 필드를 선택해야 합니다.');
        isSubmit = false;
    }

    if (isSubmit == false) {
        var error_message = error_lists.join('<br />');
        box_alert(error_message, 'e');

        inputs.prop('disabled', false);
        return false;
    }

     // 선택된 필드들을 숨겨진 인풋 필드로 추가
    var selectedFields = $('#selected-items option').map(function() {
        return $(this).val();
    }).get();

    // 기존의 숨겨진 인풋 필드 제거
    $('#frm_modify').find('input[name="i_field[]"]').remove();

    // 새로운 숨겨진 인풋 필드 추가
    selectedFields.forEach(function(field) {
        $('#frm_modify').append('<input type="hidden" name="i_field[]" value="' + field + '">');
    });

    // 폼 전송 로직 추가
    var formData = $('#frm_modify').serialize();
    $.ajax({
        url: '/apis/setting/saveForm',
        method: 'POST',
        data: formData,
        dataType: 'json',
        beforeSend: function () {
            inputs.prop('disabled', true);
        },
        complete: function() { },
        success: function(response)
        {
            submitSuccess(response);

            if (response.status != 200)
            {
                var error_message = '';
                error_message = response.errors.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            $(".btn-close").trigger("click");
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            return false;
        }
    });
}

$(document).ready(function() {
    autoSelectItems();

    $('#available-items').on('dblclick', 'option', function() {
        addItem();
    });

    $('#selected-items').on('dblclick', 'option', function() {
        removeItem();
    });

});
</script>
