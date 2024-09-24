
function dupCheckId( $obj ){
    if( $obj.val() == orgUseID ){
        idChk = true
        return true;
    }
    if( $obj.val().length < 4 ){
        box_alert( '4자 이상의 아이디를 입력해주세요.', 'i' );
        return false;
    }
    if($obj.val().length > 16){
        box_alert( '16자 이하의 아이디를 입력해주세요.', 'i' );
        return false;
    }
    var frm = $("#frm_modify");

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
                $('#frm_modify [name=i_user_id]').addClass('error');
                var error_message = '';
                error_message = response.errors.join('<br />');
                if (error_message != '') {
                    box_alert(error_message,'e');
                }
                return false;
            }
            $('#frm_modify [name=i_user_id]').removeClass('error');
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
function deleteMember(){
    event.preventDefault();
    box_confirm( '정말 삭제하시겠습니까?', 'w', '', deleteMemberProc);
}

function deleteMemberProc(){
    var frm = $("#frm_modify");

    $.ajax({
        url: "/apis/setting/memberDelete",
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
                    alert(error_message);
                }
                return false;
            }

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
        box_alert('비밀번호가 동일하지 않습니다.', 'e');
    }else{
        passwdChk = true;
    }
}
function modfiyConfirm(){
    box_confirm('회원정보를 수정하시겠습니까?', 'q', '', function(){
        modfiyAdminMember(event); // event 객체 전달
    });
}

function modfiyAdminMember(event){
    event.preventDefault(); // 이벤트 객체를 사용하여 기본 동작 방지
    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_modify').find('input, button');
    var isSubmit = true;

    var frm = $('#frm_modify');

    if ($.trim($('#i_user_id').val()) == '')
    {
        _form_error('i_user_id', '아이디를 입력하세요.');
        isSubmit = false;
    }
    if(passwdChk == false){
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
        box_alert(error_message, 'e');

        inputs.prop('disabled', false);
        return false;
    }

    $.ajax({
        url: "/apis/setting/memberModify",
        method: "POST",
        data: frm.serialize(),
        dataType: "json",
        cache: false,
        beforeSend: function() {
            inputs.prop('disabled', true);
            setTimeout(function() { inputs.prop('disabled', false); }, 3000);
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
                    alert(error_message);
                }
                return false;
            }

        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            inputs.prop('disabled', false);
            return false;
        }
    });
}
