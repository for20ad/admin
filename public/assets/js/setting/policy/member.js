$(function(){

    $('#frm_register').on('submit', function(event) {
        event.preventDefault();

        error_lists = [];
        $('.error_txt').html('');

        var inputs = $(this).find('input, button, textarea');
        var isSubmit = true;

        if ($.trim($('#i_accept:checked').val()) == '')
        {
            _form_error('i_accept', '회원가입설정을 선택하세요.');
            isSubmit = false;
        }

        if ($.trim($('#i_grade:checked').val()) == '')
        {
            _form_error('i_grade', '회원등급 사용 여부를 선택하세요.');
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
            url: '/apis/setting/policyMemberSet',
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
    });
});