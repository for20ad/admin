
$(document).on( 'click', '#loginButton', function(e){
    loginAuth();
    e.preventDefault();
});



function loginAuth(){
    if( $('[name=i_member_id]').val() == '' ){
        alert('아이디를 입력하세요.');
        return false;
    }

    if( $('[name=i_member_password]').val() == '' ){
        alert('비밀번호를 입력하세요.');
        return false;
    }

    var frm = $("#frm_login");

    $.ajax({
        url: "/api/login/loginAuth",
        method: "POST",
        data: frm.serialize(),
        dataType: "json",
        cache: false,
        beforeSend: function() { },
        complete: function() { },
        success: function(response)
        {
            submitSuccess(response);

            if (response.status != 200)
            {
                var error_message = '';
                error_message = response.errors.join('<br />');
                if (error_message != '') {
                    alert(error_message);
                }
                return false;
            }

            $('#loginFrm').empty();
            $('#loginFrm').html(response.otp_page);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            return false;
        }
    });
}
$(function(){
    $(document).on( 'keypress' , '[name=i_member_id]' , function(e){
        if( e.keyCode == '13' ){
            $('[name=i_member_password]').focus();
        }
    });
    $(document).on( 'keypress' , '[name=i_member_password]' , function(e){
        if( e.keyCode == '13' ){
            loginAuth();
        }
    });
});


