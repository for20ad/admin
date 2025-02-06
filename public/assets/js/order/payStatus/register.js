function orderStatusChangeConfirm(){
    event.preventDefault();
    var frm = $('#frm_modify');

    if (frm.find('input[name="o_i_idx[]"]:checked').length === 0) {
        box_alert('주문상품을 선택해주세요.', 'i');
        return false;
    }
    if (frm.find('[name="i_status"]').val() == '') {
        box_alert('상태를 선택해주세요.', 'i');
        return false;
    }

    box_confirm('상태변경 하시겠습니까?', 'q', '', orderStatusChange);
}
function orderStatusChange(){
    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_modify').find('input, button, select');
    var isSubmit = true;


    var isSubmit = true;
    var frm = $('#frm_modify');
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

    // 폼 전송 로직 추가


    $.ajax({
        url: '/apis/order/orderStatusChange',
        method: 'POST',
        data: frm.serialize(),
        processData: false,
        cache: false,
        beforeSend: function () {
            inputs.prop('disabled', true);
            $('#preloader').show();
        },
        complete: function() { },
        success: function(response)
        {
            $(".btn-close").trigger("click");
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

            openLayer( frm.find( '[name=o_idx]' ).val(), 'dataModal' );
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