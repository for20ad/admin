function registerMileageConfirm(){
    if( $('#frm_register [name=i_mb_idx]').val() == '' ){
        box_alert( '회원 검색 후 이용해주세요.', 'i' );
    }
    box_confirm('포인트 내역을 저장하시겠습니까?', 'q', '', function(){
        registerMileageHistory(event); // event 객체 전달
    });
}

function setRegistMbidxConfirm( $idx ){
    box_confirm('선택하시겠습니까?', 'q', '', setRegistMbidx , $idx);
}
function setRegistMbidx( mb_idx )
{
    $('#frm_register [name=i_mb_idx]').val(mb_idx);
    box_alert_autoclose( '선택되었습니다.','s' );
}

function registerMileageHistory(event){
    event.preventDefault(); // 이벤트 객체를 사용하여 기본 동작 방지
    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_register').find('input, textarea, button');
    var isSubmit = true;

    var frm = $('#frm_register');

    if ($.trim($('#i_mb_idx').val()) == '')
    {
        _form_error('i_mb_idx', '회원 검색 후 이용해주세요.');
        isSubmit = false;
    }


    if ($.trim($('#i_mileage').val()) == '')
    {
        _form_error('i_mileage', '지급할 포인트를 입력하세요.');
        isSubmit = false;
    }

    if ($.trim($('#i_reason_cd').val()) == '')
    {
        _form_error('i_reason_cd', '지급 유형을 선택해주세요.');
        isSubmit = false;
    }
    if ($.trim($('#i_reason_msg').val()) == '')
    {
        _form_error('i_reason_msg', '지급 사유를 입력하세요.');
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
        url: "/apis/membership/mileageHistoryRegister",
        method: "POST",
        data: frm.serialize(),
        dataType: "json",
        cache: false,
        beforeSend: function() {
            inputs.prop('disabled', true);
            setTimeout(function() { inputs.prop('disabled', false); }, 3000);
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
                    alert(error_message);
                }
                return false;
            }
            $(".btn-close").trigger("click")
            getSearchList();
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);

            inputs.prop('disabled', false);
            return false;
        }
    });
}
