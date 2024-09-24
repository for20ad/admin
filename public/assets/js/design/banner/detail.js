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
        _form_error('i_title', '배너 제목을 입력하세요.');
        isSubmit = false;
    }
    if( $('#i_view_gbn').val() == '' ){
        _form_error('i_view_gbn', '노출 분류를 선택하세요.');
        isSubmit = false;
    }
    if( $('#i_locate').val() == '' ){
        _form_error('i_locate', '노출 위치를 선택하세요.');
        isSubmit = false;
    }
    if( $('#i_period_start_date_time').val() == '' ){
        _form_error('i_period_start_date_time', '노출 시작일시를 입력하세요.');
        isSubmit = false;
    }
    if( $('#i_period_end_date_time').val() == '' ){
        _form_error('i_period_end_date_time', '노출 종료일시를 입력하세요.');
        isSubmit = false;
    }
    if( $('#i_img').val() == '' && imgDeleteFlg == true ){
        _form_error('i_img', '배너이미지를 선택하세요.');
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
        url: '/apis/design/bannerDetailProc',
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
            console.log(textStatus);
            return false;
        }
    });
}

function deleteBannerImgConfirm(i_idx){
    box_confirm('배너 이미지를 삭제하시겠습니까?', 'q', '', deleteBannerImg, i_idx);
}

function deleteBannerImg( i_idx ){
    $.ajax({
        url: '/apis/design/deleteBannerImg',
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