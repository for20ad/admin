function getSearchList( page ) {

    const frm = $("#frm_search");
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('page') != undefined) {
        page = urlParams.get('page') || 1; // 기본값 1
    }

    frm.find( '[name=page]' ).val( page );


    var inputs = frm.find('input, button, select');
    $.ajax({
        url: '/apis/board/getPostsLists',
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

            $('#listsTable tbody').empty().html( response.page_datas.lists_row );
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
window.addEventListener('popstate', function (event) {
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get('page') || 1;
    getSearchList(page);
});

function showReplyBox(commentId) {
    const replyBox = $(`#reply-box-${commentId}`);
    replyBox.toggle(); // 토글로 열고 닫기
}
// 댓글 등록 버튼 클릭 이벤트
$(document).on('click', '.reply-submit-btn', function () {
    const commentId = $(this).data('c-idx');
    const textarea = $(`#reply-textarea-${commentId}`);

    const comment = textarea.val();

    if (!comment) {
        alert('댓글 내용을 입력해주세요.');
        return;
    }

    $.ajax({
        url: '/apis/board/addPostsReply',
        type: 'post',
        data: 'c_idx='+commentId+'&comment='+comment,
        processData: false,
        cache: false,
        beforeSend: function() { },
        success: function(response) {
            submitSuccess(response);

            if (response.status == 'false')
            {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }

                return false;
            }
            getPostsComments();

        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);

            return false;
        },
        complete: function() { }
    });
});
function chamgeReplyStatusConfirm( _c_idx, _c_status  ){
    var message = '댓글을 삭제 하시겠습니까?';
    if( _c_status == 9 ){
        message = '댓글을 블라인드 처리 하시겠습니까?';
    }else if( _c_status == 1 ){
        message = '댓글을 노출처리 하시겠습니까?';
    }
    box_confirm( message, 'q', '', chamgeReplyStatus, {c_idx: _c_idx, c_status: _c_status});
}

function chamgeReplyStatus( obj ){
    const commentId = obj.c_idx;
    const commentStatus = obj.c_status

    $.ajax({
        url: '/apis/board/changePostsStatus',
        type: 'post',
        data: 'c_idx='+commentId+'&c_status='+commentStatus,
        processData: false,
        cache: false,
        beforeSend: function() { },
        success: function(response) {
            submitSuccess(response);

            if (response.status == 'false')
            {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }

                return false;
            }
            getPostsComments();

        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);

            return false;
        },
        complete: function() { }
    });
}
function getPostsComments( $page ){
    var frm  = $('#frm_sub_lists');
    if( $page != undefined ){
        frm.find( '[name=page]' ).val( $page );
    }

    let data = frm.serialize();
    let url  = '/apis/board/getPostsComments';

    console.log( $page );
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        processData: false,
        cache: false,
        beforeSend: function() { },
        success: function(response) {
            submitSuccess(response);

            if (response.status == 'false')
            {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }

                return false;
            }
            $('#frm_sub_lists #comments').empty().html( response.page_datas.lists_row );
            $('#pagination').empty().html( response.page_datas.pagination );

        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);

            return false;
        },
        complete: function() { }
    });

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

// 함수 외부에서 호출 가능하도록 설정
window.setPeriodDate = setPeriodDate;
