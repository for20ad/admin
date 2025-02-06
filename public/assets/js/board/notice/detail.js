function frmModifyConfirm( event ){
    event.preventDefault();
    box_confirm('수정 하시겠습니까?', 'q', '', frmModify);
}
function frmModify(){
    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_modify').find('input, button, select');
    var isSubmit = true;

    $('#frm_modify [name=i_content]').val( contents_editor.getMarkdown() );

    var isSubmit = true;

    $('#frm_modify').find('[data-required]').each(function() {
        var $input = $(this);
        var value = $.trim($input.val());
        var errorMessage = $input.data('required');

        if (value === '') {
            _form_error($input.attr('id'), errorMessage);
            error_lists.push(errorMessage);
            isSubmit = false;
        }
    });
    const selectedValues = $('input[name="s_visible"]:checked')
    .map(function() { return this.value; })
    .get()
    .join(',');
    if (isSubmit == false) {
        var error_message = error_lists.join('<br />');
        box_alert(error_message, 'e');

        inputs.prop('disabled', false); // 폼 요소를 다시 활성화
        return false;
    }

    // 폼 전송 로직 추가
    var formData = new FormData($('#frm_modify')[0]);
    formData.append('i_visible', selectedValues);

    $.ajax({
        url: '/apis/board/noticeDetailProc',
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

