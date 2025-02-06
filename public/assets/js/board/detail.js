function frmModifyConfirm( event ){
    event.preventDefault();
    box_confirm('수정 하시겠습니까?', 'q', '', frmModify);
}
function frmModify(){
    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_modify').find('input, button, select');
    var isSubmit = true;

    if ($.trim($('#i_title').val()) == '') {
        _form_error('i_title', '게시판 제목을 입력하세요.');
        isSubmit = false;
    }
    if ($.trim($('#i_id').val()) == '') {
        _form_error('i_title', '게시판 ID를 입력하세요.');
        isSubmit = false;
    }


    if (isSubmit == false) {
        var error_message = error_lists.join('<br />');
        box_alert(error_message, 'e');

        inputs.prop('disabled', false);
        return false;
    }

    // 폼 전송 로직 추가
    var formData = new FormData($('#frm_modify')[0]);
    $.ajax({
        url: '/apis/board/boardDetailProc',
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

            event.preventDefault();$(".btn-close").trigger("click");
            getSearchList();
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

function deleteboardIconConfirm(i_idx){
    box_confirm('아이콘을 삭제하시겠습니까?', 'q', '', deleteboardIcon, i_idx);
}

function deleteboardIcon( i_idx ){
    $.ajax({
        url: '/apis/board/deleteboardIcon',
        type: 'post',
        data: 'i_idx='+i_idx,
        dataType: 'json',
        cache: false,
        beforeSend: function() {
            $('#preloader').show();
        },
        success: function(response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status == 'false')
            {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'i');
                }
                return false;
            }
            $('.image-container').empty();
            imgDeleteFlg = true;
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            return false;
        },
        complete: function() { }
    });
}