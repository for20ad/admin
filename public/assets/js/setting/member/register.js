let idChk = false;
let passwdChk = false;

function dupCheckId( $obj ){
    if( $obj.val().length < 4 ){
        box_alert( '4자 이상의 아이디를 입력해주세요.' );
        return false;
    }
    if($obj.val().length > 16){
        box_alert( '16자 이하의 아이디를 입력해주세요.' );
        return false;
    }
    var frm = $("#frm_register");

    $.ajax({
        url: "/apis/setting/duplicateId",
        method: "POST",
        data: frm.serialize(),
        dataType: "json",
        cache: false,
        beforeSend: function() {
            $('#preloader').show();
        },
        complete: function() { },
        success: function(response)
        {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status != 200)
            {
                $('#frm_register [name=i_user_id]').addClass('error');
                var error_message = '';
                error_message = response.errors.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }
            $('#frm_register [name=i_user_id]').removeClass('error');
            idChk = true;

        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            console.log(textStatus);
            return false;
        }
    });

}

function sameValueCheck( name, $obj ){
    const targetValue = $('input[name="' + name + '"]').val();
    const currentValue = $obj.val();

    if (targetValue !== currentValue) {
        box_alert('비밀번호가 동일하지 않습니다.', 'i');
    }else{
        passwdChk = true;
    }
}
$(function(){

    $('#frm_register').on('submit', function(event) {
        event.preventDefault();

        error_lists = [];
        $('.error_txt').html('');

        var inputs = $(this).find('input, button');
        var isSubmit = true;

        if ($.trim($('#i_user_id').val()) == '')
        {
            _form_error('i_user_id', '아이디를 입력하세요.');
            isSubmit = false;
        }

        if ($.trim($('#i_password').val()) == '')
        {
            _form_error('i_password', '비밀번호를 입력하세요.');
            isSubmit = false;
        }

        if ($.trim($('#i_password_check').val()) == '')
        {
            _form_error('i_password_check', '비밀번호 확인을 입력하세요.');
            isSubmit = false;
        }

        if ($.trim($('#i_user_name').val()) == '')
        {
            _form_error('i_user_name', '관리자명을 입력하세요.');
            isSubmit = false;
        }

        if ($.trim($('#i_mobile_num').val()) == '')
        {
            _form_error('i_link', '휴대폰번호를 입력하세요.');
            isSubmit = false;
        }
        if( idChk === false ){
            _form_error('i_user_id', '아이디체크를 진행하세요.');
            isSubmit = false;
        }

        if( passwdChk === false ){
            _form_error('i_password_check', '패스워드가 서로 다릅니다. 다시 입력하세요.');
            isSubmit = false;
        }

        if (isSubmit == false)
        {
            var error_message = '';
            error_message = error_lists.join('<br />');
            box_alert(error_message, 'i');

            inputs.prop('disabled', false);
            return false;
        }

        $.ajax({
            url: '/apis/setting/memberRegister',
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