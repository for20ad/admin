
$(document).on( 'click', '#loginButton', function(e){
    e.preventDefault();
    loginAuth();
});



function loginAuth(){
    if( $('[name=i_member_id]').val() == '' ){
        box_alert('아이디를 입력하세요.', 'i');
        return false;
    }

    if( $('[name=i_member_password]').val() == '' ){
        box_alert('비밀번호를 입력하세요.', 'i');
        return false;
    }

    var frm = $("#frm_login");

    $.ajax({
        url: "/api/login/loginAuth",
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
                var error_message = '';
                error_message = response.errors.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            $('#loginFrm').empty();
            $('#loginFrm').html(response.otp_page);
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


