function getSearchList( $page ) {

    const frm = $("#frm_search");
    frm.find( '[name=page]' ).val( $page );
    var inputs = frm.find('input, button, select');
    $.ajax({
        url: '/apis/design/getPopupLists',
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
            inputs.prop('disabled', false);
            $('#preloader').hide();
            if (response.status == 'false') {
                var error_message = response.error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            $('#listsTable tbody').empty().html( response.page_datas.lists_row );
            $("#paginatoon").empty().html( response.page_datas.pagination );
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


function deletePopupConfirm(i_idx){
    box_confirm('팝업을 삭제하시겠습니까?', 'q', '', deletePopup, i_idx);
}

function deletePopup( i_idx ){
    const frm = $('#frm_lists')
    $.ajax({
        url: '/apis/design/deletePopup',
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
            endDate;
            break;
        case '7Day':
            endDate.setDate(startDate.getDate() - 7);
            break;
        case '15Day':
            endDate.setDate(startDate.getDate() + 15);
            break;
        case '1Month':
            endDate.setMonth(startDate.getMonth() + 1);
            break;
        case '3Month':
            endDate.setMonth(startDate.getMonth() + 3);
            break;
        case '6Month':
            endDate.setMonth(startDate.getMonth() + 6);
            break;
        case '1Years':
            endDate.setFullYear(startDate.getFullYear() + 1);
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


// 함수 외부에서 호출 가능하도록 설정
window.setPeriodDate = setPeriodDate;
