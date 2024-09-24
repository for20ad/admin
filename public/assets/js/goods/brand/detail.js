$(document).on( 'keyup', '#frm_modify #i_cate_name', function(e){
    e.preventDefault();
    if( e.keyCode == 13 ){
        frmModifyConfirm(e);
    }
} );

function frmModifyConfirm( event ){
    event.preventDefault();
    box_confirm('수정 하시겠습니까?', 'q', '', frmModify);
}
function frmModify(){
    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_modify').find('input, button, select');
    var isSubmit = true;

    if ($.trim($('#i_brand_name').val()) == '') {
        _form_error('i_brand_name', '브랜드명을 입력하세요.');
        isSubmit = false;
    }
    if ($.trim($('#i_brand_name_eng').val()) == '') {
        _form_error('i_brand_name_eng', '브랜드 영문명을 입력하세요.');
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
        url: '/apis/goods/brandDetailProc',
        method: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {
            inputs.prop('disabled', true);
            setTimeout(function () { inputs.prop('disabled', false); }, 3000);
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

            getSearchList();
            openLayer( $('#frm_modify').find('[name=i_brand_idx]').val(), $('#frm_modify').find('[name=i_parent_idx]').val() );
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