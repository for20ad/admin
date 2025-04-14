function getSearchList( page ) {

    const frm = $("#frm_search");
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.get('page') != undefined) {
        page = urlParams.get('page') || 1; // 기본값 1
    }


    frm.find( '[name=page]' ).val( page );


    var inputs = frm.find('input, button, select');
    $.ajax({
        url: '/apis/order/getOrderLists',
        method: 'POST',
        data: frm.serialize(),
        dataType: 'json',
        cache: false,
        beforeSend: function () {
            inputs.prop('disabled', true);
            setTimeout(function () { inputs.prop('disabled', false); }, 3000);
            //$('#preloader').show();
        },
        success: function (response) {
            submitSuccess(response);
            //$('#preloader').hide();
            inputs.prop('disabled', false);
            if (response.status == 'false') {
                var error_message = response.error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }
            var s_ordStatus = $('#s_ordStatus').val();

            if(s_ordStatus == 'all') {
                $('#AllOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#AllOrdersTable');
                }, 100);
            }else if(s_ordStatus == 'payWait') {
                $('#PayWaitOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#PayWaitOrdersTable');
                }, 100);
            }else if(s_ordStatus == 'prdWait') {
                $('#PrdWaitOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#PrdWaitOrdersTable');
                }, 100);
            }else if(s_ordStatus == 'cancel') {
                $('#CancelOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#CancelOrdersTable');
                }, 100);
            }else if(s_ordStatus == 'return') {
                $('#ReturnOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#ReturnOrdersTable');
                }, 100);
            }else if(s_ordStatus == 'exchange') {
                $('#ExchangeOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#ExchangeOrdersTable');
                }, 100);
            }else if(s_ordStatus == 'shipWait') {
                $('#ShipWaitOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#ShipWaitOrdersTable');
                }, 100);
            }else if(s_ordStatus == 'shippingProgress') {
                $('#ShippingProgressOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#ShippingProgressOrdersTable');
                }, 100);
            }else if(s_ordStatus == 'shippingComplete') {
                $('#ShippingCompleteOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#ShippingCompleteOrdersTable');
                }, 100);
            }else if(s_ordStatus == 'buyDecision') {
                $('#BuyDecisionOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#BuyDecisionOrdersTable');
                }, 100);
            }

            $('#allCnt').text( ' ( '+response.page_datas.allCnt+' )' );
            $('#payWaitCnt').text( ' ( '+response.page_datas.payWaitCnt+' )' );
            $('#prdWaitCnt').text( ' ( '+response.page_datas.prdWaitCnt+' )' );
            $('#cancelCnt').text( ' ( '+response.page_datas.cancelCnt+' )' );
            $('#returnCnt').text( ' ( '+response.page_datas.returnCnt+' )' );
            $('#exchangeCnt').text( ' ( '+response.page_datas.exchangeCnt+' )' );
            $('#shipWaitCnt').text( ' ( '+response.page_datas.shipWaitCnt+' )' );
            $('#shippingProgressCnt').text( ' ( '+response.page_datas.shipProgressCnt+' )' );
            $('#shippingCompleteCnt').text( ' ( '+response.page_datas.shipCompleteCnt+' )' );
            $('#buyDecisionCnt').text( ' ( '+response.page_datas.buyDecisionCnt+' )' );


            $("#pagination").empty().html( response.page_datas.pagination );
            var tabPane = $('.tab-pane');
            $('html, body').animate({
                scrollTop: tabPane.offset().top
            },300);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            //$('#preloader').hide();
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
window.addEventListener('popstate', function (event) {
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get('page') || 1;
    getSearchList(page);
});




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


    // 페이지 번호를 URL에서 가져오기 (기존 페이지 번호가 있으면 사용, 없으면 1로 설정)
    var urlParams = new URLSearchParams(window.location.search);
    var page = urlParams.get('page')??1;
    var navi = urlParams.get('navi')??'';
    var _startDate = startDate;
    var _endDate = endDate;

    //새 URL 생성 (기존의 페이지 번호와 탭 상태를 포함)
    var newUrl = window.location.origin + window.location.pathname + '?page=' + page + '&navi=' + navi + '&startDate=' + formatDate(_startDate) + '&endDate=' + formatDate(_endDate);

    //URL 업데이트
    history.pushState({ page: page, s_ordStatus: navi, startDate: formatDate(_startDate), endDate: formatDate(_endDate) }, null, newUrl);



}



function hideSelect( obj )
{
    obj.parent().hide(); obj.parent().parent().find('.orgStatus').show();
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
