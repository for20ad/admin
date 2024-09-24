function frmRegiserConfirm( event ){
    event.preventDefault();
    box_confirm('등록 하시겠습니까?', 'q', '', frmRegiser);
}
function frmRegiser(){
    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_register').find('input, button, select');
    var isSubmit = true;

    if ($.trim($('#i_title').val()) == '') {
        _form_error('i_title', '제목을 입력하세요.');
        isSubmit = false;
    }

    if ($.trim($('#i_limit_cnt').val()) == '') {
        _form_error('i_limit_cnt', '메인페이지 노출 수량을 입력하세요.');
        isSubmit = false;
    }

    if ($.trim($('#i_start_date').val()) == '') {
        _form_error('i_start_date', '시작일시를 입력해주세요.');
        isSubmit = false;
    }

    if ($.trim($('#i_end_date').val()) == '') {
        _form_error('i_end_date', '종료일시를 입력해주세요.');
        isSubmit = false;
    }

    if (isSubmit == false) {
        var error_message = error_lists.join('<br />');
        box_alert(error_message, 'e');

        inputs.prop('disabled', false);
        return false;
    }

    // 폼 전송 로직 추가
    var formData = new FormData($('#frm_register')[0]);
    $.ajax({
        url: '/apis/goods/timeSaleRegisterProc',
        method: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {
            inputs.prop('disabled', true);
            $('#preloader').show();
        },
        complete: function() {
        },
        success: function(response)
        {
            submitSuccess(response);
            inputs.prop('disabled', false);
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

            event.preventDefault();$(".btn-close").trigger("click");
            getSearchList();
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            submitError(jqXHR.status, errorThrown);
            inputs.prop('disabled', false);
            $('#preloader').hide();
            console.log(textStatus);
            return false;
        }
    });
}