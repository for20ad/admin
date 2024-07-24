
function findSort() {
    const parentIdx = parseInt($('#frm_code_write #i_parent_idx').val());
    let maxSort = 0;

    function findMaxSort(codeArray, parentIdx) {
        $.each(codeArray, function(index, code) {
            if (parseInt(code.C_PARENT_IDX) === parentIdx) {
                if (parseInt(code.C_SORT) > maxSort) {
                    maxSort = parseInt(code.C_SORT);
                }
            }

            if (code.CHILD && code.CHILD.length > 0) {
                findMaxSort(code.CHILD, parentIdx);
            }
        });
    }

    findMaxSort(codeData, parentIdx);

    // Set the found maximum value + 1 to i_sort input
    $('#frm_code_write #i_sort').val(maxSort + 1);
}

findSort()

// -----------------------------------------------------------------------------
// 메뉴
// -----------------------------------------------------------------------------
function modifyCodeConfirm()
{
    if ($('input:checkbox[name="i_code_idx[]"]:checked').length > 0)
    {
        box_confirm('선택된 코드를 수정하시겠습니까?', 'q', '', modifyCode);
    }
    else
    {
        box_alert('선택된 코드가 없습니다.', 'i');
    }
}

function modifyCode()
{
    $.ajax({
        url: '/apis/setting/modifyCode',
        type: 'post',
        data: $('#frm_code_lists').serialize(),
        dataType: 'json',
        processData: false,
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

function deleteCodeConfirm(code_idx)
{

    box_confirm('코드를 삭제하시겠습니까?', 'q', '', deleteCode, {'code_idx': code_idx});
}

function deleteCode(param)
{
    $('#code_idx').val(param.code_idx);

    $.ajax({
        url: '/apis/setting/deleteCode',
        type: 'post',
        data: 'code_idx='+param.code_idx,
        processData: false,
        cache: false,
        beforeSend: function() { },
        success: function(response) {
            submitSuccess(response);

            if (response.status == 'false')
            {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
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

$(document).ready(function () {
    $('#frm_code_write').on('submit', function(event) {
        event.preventDefault();

        error_lists = [];
        $('.error_txt').html('');

        var inputs = $(this).find('input, button');
        var isSubmit = true;

        if ($.trim($('#i_name').val()) == '')
        {
            _form_error('i_name', '메뉴명을 입력하세요.');
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
            url: '/apis/setting/writeCode',
            method: 'POST',
            data: new FormData(this),
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
                        box_alert(error_message, 'e');
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
    });
});