function orderStatusChangeConfirm( location = '' ){
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

function orderStatusChangeListsConfirm( status ){
    event.preventDefault();
    var idxs = [];
    var s_ordStatus = $('#s_ordStatus').val().split(',');

    if(s_ordStatus.indexOf('ExchangeRequest') !== -1) {
        if( $("#requestOrdersTable tbody").find('input[name="o_i_idx[]"]:checked').length === 0 ){
            box_alert( '상태변경할 데이터를 선택해주세요.', 'e' );
            return false;
        }
        $("#requestOrdersTable tbody").find('input[name="o_i_idx[]"]:checked').each(function(){
            idxs.push( $(this).val() );
        });
    }else if(s_ordStatus.indexOf('ExchangeApproved') !== -1) {
        if( $("#handleOrdersTable tbody").find('input[name="o_i_idx[]"]:checked').length === 0 ){
            box_alert( '상태변경할 데이터를 선택해주세요.', 'e' );
            return false;
        }
        $("#handleOrdersTable tbody").find('input[name="o_i_idx[]"]:checked').each(function(){
            idxs.push( $(this).val() );
        });
    }else if(s_ordStatus.indexOf('ExchangeReturning') !== -1) {
        if( $("#processingOrdersTable tbody").find('input[name="o_i_idx[]"]:checked').length === 0 ){
            box_alert( '상태변경할 데이터를 선택해주세요.', 'e' );
            return false;
        }
        $("#processingOrdersTable tbody").find('input[name="o_i_idx[]"]:checked').each(function(){
            idxs.push( $(this).val() );
        });
    }else if(s_ordStatus.indexOf('ExchangeShippingComplete') !== -1) {
        if( $("#complateOrdersTable tbody").find('input[name="o_i_idx[]"]:checked').length === 0 ){
            box_alert( '상태변경할 데이터를 선택해주세요.', 'e' );
            return false;
        }
        $("#complateOrdersTable tbody").find('input[name="o_i_idx[]"]:checked').each(function(){
            idxs.push( $(this).val() );
        });
    }else if(s_ordStatus.indexOf('ExchangeRequestRetract') !== -1) {
        if( $("#rejectOrdersTable tbody").find('input[name="o_i_idx[]"]:checked').length === 0 ){
            box_alert( '상태변경할 데이터를 선택해주세요.', 'e' );
            return false;
        }
        $("#rejectOrdersTable tbody").find('input[name="o_i_idx[]"]:checked').each(function(){
            idxs.push( $(this).val() );
        });
    }

    var data = {
        'i_status': status,
        'o_i_idx' : idxs
    }

    box_confirm('상태변경 하시겠습니까?', 'q', '', orderStatusChangeLists, data);

}

function orderStatusChangeLists( data ){
    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_search').find('input, button, select');
    var aData = new FormData();
    aData.append('i_status', data.i_status); // 상태 값 추가
    data.o_i_idx.forEach((idx) => aData.append('o_i_idx[]', idx)); // 배열로 추가
    $.ajax({
        url: '/apis/order/orderStatusChange',
        method: 'POST',
        data: aData,
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