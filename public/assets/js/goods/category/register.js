$(document).on( 'keyup', '#frm_register #i_cate_name', function(e){
    if( e.keyCode == 13 ){
        frmRegiserConfirm(e);
    }
} );

function frmRegiserConfirm( event ){
    event.preventDefault();
    box_confirm('등록 하시겠습니까?', 'q', '', frmRegiser);
}
function frmRegiser(){
    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_register').find('input, button, select');
    var isSubmit = true;

    if ($.trim($('#i_cate_name').val()) == '') {
        _form_error('i_name', '커태고리명을 입력하세요.');
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
        url: '/apis/goods/categoryRegisterProc',
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
        complete: function() { },
        success: function(response)
        {
            submitSuccess(response);
            $('#preloader').hide();
            inputs.prop('disabled', false);
            if (response.status != 200)
            {
                var error_message = '';
                error_message = response.errors.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            getSearchList();
            openLayer('',0);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            inputs.prop('disabled', false);
            console.log(textStatus);
            return false;
        }
    });
}