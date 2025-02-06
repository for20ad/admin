function getSearchList( page ) {

    const frm = $("#frm_search");
    const urlParams = new URLSearchParams(window.location.search);
    if (page === undefined) {
        page = urlParams.get('page') || 1; // 기본값 1
    }

    frm.find( '[name=page]' ).val( page );


    var inputs = frm.find('input, button, select');
    $.ajax({
        url: '/apis/order/orderReturnProcessLists',
        method: 'POST',
        data: frm.serialize(),
        dataType: 'json',
        cache: false,
        beforeSend: function () {
            inputs.prop('disabled', true);
            setTimeout(function () { inputs.prop('disabled', false); }, 3000);
            $('#preloader').show();
        },
        success: function (response) {
            submitSuccess(response);
            $('#preloader').hide();
            inputs.prop('disabled', false);
            if (response.status == 'false') {
                var error_message = response.error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            var s_ordStatus = $('#s_ordStatus').val().split(',');

            if(s_ordStatus.indexOf('ReturnRequest') !== -1) {
                $('#requestOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#requestOrdersTable');
                }, 100);
            }else if(s_ordStatus.indexOf('ReturnApproved') !== -1) {
                $('#handleOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#handleOrdersTable');
                }, 100);
            }else if(s_ordStatus.indexOf('ReturnShippingProgress') !== -1) {
                $('#processingOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#processingOrdersTable');
                }, 100);
            }else if(s_ordStatus.indexOf('ReturnShippingComplete') !== -1) {
                $('#complateOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#complateOrdersTable');
                }, 100);
            }else if(s_ordStatus.indexOf('ReturnCancelRequest') !== -1) {
                $('#paycancelOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#paycancelOrdersTable');
                }, 100);
            }else if(s_ordStatus.indexOf('ReturnRefundRequest') !== -1) {
                $('#refoundRequestOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#refoundRequestOrdersTable');
                }, 100);
            }else if(s_ordStatus.indexOf('ReturnRefundComplete') !== -1) {
                $('#refoundComplateOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#refoundComplateOrdersTable');
                }, 100);
            }else if(s_ordStatus.indexOf('ReturnRequestRetract') !== -1) {
                $('#rejectOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#rejectOrdersTable');
                }, 100);
            }

            $('#requestCnt').text( ' ( '+response.requestCnt+' )' );
            $('#handleCnt').text( ' ( '+response.handleCnt+' )' );
            $('#processingCnt').text( ' ( '+response.processingCnt+' )' );
            $('#complateCnt').text( ' ( '+response.complateCnt+' )' );
            $('#paycancelCnt').text( ' ( '+response.paycancelCnt+' )' );
            $('#refoundRequestCnt').text( ' ( '+response.refoundRequestCnt+' )' );
            $('#refoundComplateCnt').text( ' ( '+response.refoundComplateCnt+' )' );
            $('#rejectCnt').text( ' ( '+response.rejectCnt+' )' );

            $("#pagination").empty().html( response.page_datas.pagination );


        },
        error: function (jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            inputs.prop('disabled', false);
            return false;
        },
        complete: function () { }
    });
}
$(function(){
    $(document).on('keyup', '[name=s_keyword]', function(e){
        e.preventDefault();
        if( e.keyCode == 13 ){
            getSearchList();
        }
    });
    /* paging 한 묶음 S */
    Pagination.initPagingNumFunc(getSearchList);
    Pagination.initPagingSelectFunc(getSearchList);
    /* paging 한 묶음 E */

});

setTimeout(function(){
    getSearchList(1);
}, 300);


function changeShipNumConfirm( $obj ){
    box_confirm('운송장 정보를 수정하시겠습니까?', 'q', '', changeShipNum, $obj);
}
function changeShipNum( $obj ){
    const shipNum = $obj.val();
    const orderIdx = $obj.data('idx');
    const shipCompany = $obj.closest('td').find('[name=i_delivery_comp]').val();

    if( shipNum == ''  ){
        box_alert( '송장번호를 입력해주세요.' );
        return false;
    }
    if(orderIdx == '' || shipCompany == ''){
        box_alert( '새로고침 후 이용해주세요.' );
        return false;
    }

    console.log( 'shipNum::'+shipNum );
    console.log( 'orderIdx::'+orderIdx );
    console.log( 'shipCompany::'+shipCompany );

    let url  = '/apis/order/updateShipInfo';
    let data = 'ordIdx='+orderIdx+'&shipNum='+shipNum+'&shipCompany='+shipCompany;
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        processData: false,
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
                    box_alert(error_message, 'e');
                }

                return false;
            }
            getSearchList();

        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            console.log(textStatus);

            return false;
        },
        complete: function() { }
    });


}
function openMemoLayer( odr_idx, id, viewTab ){
    show_ord_idx = odr_idx;
    let data = 'ordIdx='+odr_idx;
    let url  = '/apis/order/getMemoLists';

    $.ajax({
        url: url,
        type: 'post',
        data: data,
        processData: false,
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
                    box_alert(error_message, 'e');
                }

                return false;
            }

            $('#'+id+' .viewData').empty().html(response.page_datas.detail);
            var modalElement = document.getElementById(id);
            var modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static', // 마스크 클릭해도 닫히지 않게 설정
                keyboard: false     // esc 키로 닫히지 않게 설정
            });
            modal.show();



        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            console.log(textStatus);

            return false;
        },
        complete: function() { }
    });
        // 모달 열기

}
// setPeriodDate 함수 정의
function setPeriodDate(period) {
    var startDate = new Date();
    var endDate = new Date();

    switch(period) {
        case '1Day':
            startDate;
            break;
        case '7Day':
            startDate.setDate(endDate.getDate() - 7);
            break;
        case '15Day':
            startDate.setDate(endDate.getDate() - 15);
            break;
        case '1Month':
            startDate.setMonth(endDate.getMonth() - 1);
            break;
        case '3Month':
            startDate.setMonth(endDate.getMonth() - 3);
            break;
        case '6Month':
            startDate.setMonth(endDate.getMonth() - 6);
            break;
        case '1Years':
            startDate.setFullYear(endDate.getFullYear() - 1);
            break;
        default:
            console.error('Invalid period specified');
            return;
    }

    // yyyy-mm-dd 형식으로 날짜를 포맷
    var formatDate = function(date) {
        var year = date.getFullYear();
        var month = ('0' + (date.getMonth() + 1)).slice(-2);
        var day = ('0' + date.getDate()).slice(-2);
        return year + '-' + month + '-' + day;
    };

    // 날짜 필드 업데이트
    $('[name=s_start_date]').val(formatDate(startDate));
    $('[name=s_end_date]').val(formatDate(endDate));
}


function hideSelect( obj )
{
    obj.parent().hide(); obj.parent().parent().find('.orgStatus').show();
}

function enableEdit(button) {
    const input = button.closest('.form-inline').querySelector('[name="i_ship_number"]');
    input.removeAttribute('disabled');
    input.focus();
}


function openTrackingLayer( odr_idx, id, viewTab ){
    show_ord_idx = odr_idx;
    let data = 'ordIdx='+odr_idx;
    let url  = '/apis/order/getOrderTracking';

    $.ajax({
        url: url,
        type: 'post',
        data: data,
        processData: false,
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
                    box_alert(error_message, 'e');
                }

                return false;
            }

            $('#'+id+' .viewData').empty().html(response.page_datas.detail);
            var modalElement = document.getElementById(id);
            var modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static', // 마스크 클릭해도 닫히지 않게 설정
                keyboard: true     // esc 키로 닫히지 않게 설정
            });
            modal.show();



        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            console.log(textStatus);

            return false;
        },
        complete: function() { }
    });
        // 모달 열기

}


// 함수 외부에서 호출 가능하도록 설정
window.setPeriodDate = setPeriodDate;
