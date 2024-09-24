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
        _form_error('i_title', '팝업 제목을 입력하세요.');
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

    const markdownContent = editor.getMarkdown(); // Markdown 모드에서 에디터의 내용을 가져옴e getHTML() for HTML content

    if ($.trim(markdownContent) == '') {
        _form_error('i_content', '팝업 내용을 입력하세요.');
        isSubmit = false;
    }

    if (!markdownContent.trim()) {
        _form_error('i_content', '팝업 내용을 입력하세요.');
        isSubmit = false;

    }

    if (markdownContent.trim().length < 20) {
        _form_error('i_content', '내용은 최소 20자 이상 입력해야 합니다.');
        isSubmit = false;
    }

    if (isSubmit == false) {
        var error_message = error_lists.join('<br />');
        box_alert(error_message, 'e');

        inputs.prop('disabled', false);
        return false;
    }

    $('#frm_modify [name=i_content]').val( markdownContent );



    // 폼 전송 로직 추가
    var formData = new FormData($('#frm_modify')[0]);
    $.ajax({
        url: '/apis/design/popupDetailProc',
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