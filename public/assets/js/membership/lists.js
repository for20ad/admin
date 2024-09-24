function getSearchList( $page ) {

    const frm = $("#frm_search");
    frm.find( '[name=page]' ).val( $page );
    var inputs = frm.find('input, button, select');
    $.ajax({
        url: '/apis/membership/getLists',
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
            $("#paginatoon").empty().html( response.page_datas.pagination );
            getButtonSet();

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

    // const exampleData = {
    //     mainMessage: "잘못 입력된 데이터가 있어 등록에 실패했습니다",
    //     subMessage: "아래 실패사유 확인 후 재 등록 부탁드립니다",
    //     registeredData: "10,000건",
    //     validData: "9,000건",
    //     failedData: "1,000건",
    //     failureReasons: [
    //         { lineNumber: 15, reason: "미입력 : 판매가, 공급사 입력오류 : 공급가, 소비자가" },
    //         { lineNumber: 16, reason: "미입력 : 재고수량, 유효기간 입력오류 : 가격" }
    //     ]
    // };

    // box_alert_resultView('업로드실패', 'e', exampleData, function(data) {
    //     console.log('Callback executed with data:', data);
    // });

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

getSearchList(1);

function excelDownloadConfirm( id ){
    var modalElement = document.getElementById(id);
    var modal = new bootstrap.Modal(modalElement, {
        backdrop: 'static', // 마스크 클릭해도 닫히지 않게 설정
        keyboard: false     // esc 키로 닫히지 않게 설정
    });

    modal.show();
}

function selectFormAndDownload() {
    var s_form_idx = $('input[name="s_form_idx"]:checked').val();

    // s_form_idx 체크여부 검사
    if (!s_form_idx) {
        box_alert('폼 리스트를 선택해주세요.', 'e');
        return false;
    }

    // frm_search의 데이터를 excelForm에 복사
    $('#excelForm input[name="s_condition"]').val($('#s_condition').val());
    $('#excelForm input[name="s_keyword"]').val($('#ssubject').val());
    $('#excelForm input[name="s_status"]').val($('#s_status').val());
    $('#excelForm input[name="s_grade"]').val($('#s_grade').val());
    $('#excelForm input[name="s_start_date"]').val($('input[name="s_start_date"]').val());
    $('#excelForm input[name="s_end_date"]').val($('input[name="s_end_date"]').val());
    $('#excelForm input[name="s_form_idx"]').val(s_form_idx);

    // excelForm 전송
    $('#excelForm').attr('action', '/apis/membership/getListsExcel');
    $('#excelForm').attr('method', 'POST');
    $('#excelForm').attr('target', '_blank'); // 새 창으로 전송
    $('#excelForm').submit();

    // 모달 닫기
    resetExcelForm();
    $('#formListModal').modal('hide');
}

function resetExcelForm(){
    $('#excelForm input[name="s_condition"]').val('');
    $('#excelForm input[name="s_keyword"]').val('');
    $('#excelForm input[name="s_status"]').val('');
    $('#excelForm input[name="s_grade"]').val('');
    $('#excelForm input[name="s_start_date"]').val('');
    $('#excelForm input[name="s_end_date"]').val('');
    $('#excelForm input[name="s_form_idx"]').val('');
}


function setWaitMembersConfirm(){
    if ($('input:checkbox[name="i_member_idx[]"]:checked').length > 0)
    {
        box_confirm('선택된 회원을 "대기" 상태로 수정하시겠습니까?', 'q', '', setWaitMembers);
    }
    else
    {
        box_alert('선택된 회원이 없습니다.', 'i');
    }
}
function setWaitMembers(){
    const frm = $('#frm_lists');
    var formData = new FormData(frm[0]);
    formData.append('i_status', '1');
    $.ajax({
        url: '/apis/membership/modifyMembershipStatus',
        type: 'post',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
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
            getButtonSet()
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            return false;
        },
        complete: function() {
            formData.delete('i_status');
        }
    });
}

function setApprovalMembersConfirm(){
    if ($('input:checkbox[name="i_member_idx[]"]:checked').length > 0)
    {
        box_confirm('선택된 회원을 "승인" 상태로 수정하시겠습니까?', 'q', '', setApprovalMembers);
    }
    else
    {
        box_alert('선택된 회원이 없습니다.', 'i');
    }
}
function setApprovalMembers(){
    const frm = $('#frm_lists');
    var formData = new FormData(frm[0]);
    formData.append('i_status', '2');
    $.ajax({
        url: '/apis/membership/modifyMembershipStatus',
        type: 'post',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
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
            getButtonSet();
            getSearchList();

        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            return false;
        },
        complete: function() {
            formData.delete('i_status');
        }
    });
}
function getButtonSet(){
    $.ajax({
        url: '/apis/membership/getListInButtonSet',
        type: 'post',
        data: 'xStatus='+$("#frm_search [name=s_status]").val(),
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
                    box_alert(error_message, 'i');
                }

                return false;
            }
            $('#buttons').empty().html( response.button );
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            return false;
        },
        complete: function() {

        }
    });
}


function openLayer( mb_idx, id ){
    let data = '';
    let url  = '/apis/membership/register';

    if( mb_idx != '' ){

        data = 'memIdx='+mb_idx;
        url  = '/apis/membership/detail';
    }
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
            $('#'+id+' .viewData').empty().html( response.page_datas.detail );
            //var modal = new bootstrap.Modal(document.getElementById(id));
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