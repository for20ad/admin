function modifyGradeConfirm()
{
    if ($('input:checkbox[name="i_grade_idx[]"]:checked').length > 0)
    {
        box_confirm('선택된 등급을 수정하시겠습니까?', 'q', '', modifyGrade);
    }
    else
    {
        box_alert('선택된 등급이 없습니다.', 'i');
    }
}

function modifyGrade()
{
    const frm = $('#frm_grade_lists')
    $.ajax({
        url: '/apis/setting/modifyMembershipGrade',
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
function deleteGradeConfirm( g_idx ){
    box_confirm('등급을 삭제하시겠습니까?', 'q', '', deleteGrade, {'g_idx': g_idx});
}
function deleteGrade( param ){
    $.ajax({
        url: '/apis/setting/deleteMembershipGrade',
        type: 'post',
        data: 'g_idx='+param.g_idx,
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

function addMembershipGrade(){
    event.preventDefault();
    const frm = $('#frm_register')
    error_lists = [];
    $('.error_txt').html('');

    var inputs = frm.find('input, button');
    var isSubmit = true;

    if ($.trim(frm.find('#i_name').val()) == '')
    {
        _form_error('i_user_id', '등급명을 입력하세요.');
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
        url: '/apis/setting/addMembershipGrade',
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


function updateGradrSort()
{
    var s_data = new FormData();

    $("[name='i_grade_idx[]']").each(function() {
        s_data.append('sort[]', $(this).val());
    });

    $.ajax({
        url: "/apis/setting/updateGradeSort",
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
