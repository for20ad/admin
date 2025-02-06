function getSearchList( page ) {

    const frm = $("#frm_search");
    const urlParams = new URLSearchParams(window.location.search);
    if (page === undefined) {
        page = urlParams.get('page') || 1; // 기본값 1
    }
    frm.find( '[name=page]' ).val( page );


    var inputs = frm.find('input, button, select');
    $.ajax({
        url: '/apis/order/getOrderPayStatusLists',
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

            if(s_ordStatus.indexOf('PayRequest') !== -1) {
                $('#waitOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#waitOrdersTable');
                }, 100);
            }else if(s_ordStatus.indexOf('PayComplete') !== -1) {
                $('#completeOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#completeOrdersTable');
                }, 100);
            }else if(s_ordStatus.indexOf('PayCancelRequest') !== -1) {
                $('#failOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#failOrdersTable');
                }, 100);
            }else if(s_ordStatus.indexOf('ProductOutStock') !== -1) {
                $('#overStockOrdersTable tbody').empty().html( response.page_datas.lists_row );
                setTimeout(function(){
                    initCheckAll('#overStockOrdersTable');
                }, 100);
            }

            $('#waitCnt').text( ' ( '+response.waitCnt+' )' );
            $('#compCnt').text( ' ( '+response.compCnt+' )' );
            $('#failCnt').text( ' ( '+response.failCnt+' )' );
            $('#overStockCnt').text( ' ( '+response.overStockCnt+' )' );

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


function deleteNoticeConfirm(i_idx){
    box_confirm('공지사항을 삭제하시겠습니까?', 'q', '', deleteNotice, i_idx);
}

function deleteNotice( i_idx ){
    const frm = $('#frm_lists')
    $.ajax({
        url: '/apis/board/deleteNotice',
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
            getSearchList();
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

function changeAnswerStatusConfirm( obj ){
    var message = '"문의접수" 상태를 변경하시겠습니까?';

    if( obj.val() == 'PREPARING' ){
        message = '"답변준비중" 상태를 변경하시겠습니까?';
    }
    box_confirm_cancel( message, 'q', '', changeAnswerStatus, obj, hideSelect);
}
function changeAnswerStatus( obj ){

    $.ajax({
        url: '/apis/board/changePostsAnswerStatus',
        method: 'POST',
        data: 'i_idx='+obj.data('idx')+'&i_answer_status='+obj.val(),
        dataType: 'json',
        cache: false,
        beforeSend: function () {

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
