function getSearchList( $page ) {

    const frm = $("#frm_search");
    frm.find( '[name=page]' ).val( $page );
    var inputs = frm.find('input, button, select');
    $.ajax({
        url: '/apis/membership/getMileageLists',
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

function getSubSearchList($page) {

    const frm = $("#frm_sub_search");
    frm.find( '[name=page]' ).val( $page );
    var inputs = frm.find('input, button, select');
    $.ajax({
        url: '/apis/membership/getSubMileageLists',
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

            $('#subListsTable tbody').empty().html( response.page_datas.lists_row );
            $("#frm_sub_lists #paginatoon").empty().html( response.page_datas.pagination );


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


    subPagination.initPagingNumFunc(getSubSearchList);
    subPagination.initPagingSelectFunc(getSubSearchList);
});
setTimeout(function(){
    getSearchList(1);
}, 300);


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
    $('#excelForm input[name="s_form_idx"]').val(s_form_idx);

    // excelForm 전송
    $('#excelForm').attr('action', '/apis/membership/getMileageListsExcel');
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

var _mb_idx = '';
var _total_page = 0; // 초기값을 0으로 설정
var _currentPage = 1; // currentPage도 전역 변수로 설정

function resetLoopScrollParams()
{
    _mb_idx = '';
    _total_page = 0; // 초기값을 0으로 설정
    _currentPage = 1; // currentPage도 전역 변수로 설정
}

function openLayer(mb_idx, id, page = 1) {
    let data = 'memIdx=' + mb_idx;
    _mb_idx = mb_idx;
    let url = '/apis/membership/getMileageHistoryList';

    $.ajax({
        url: url,
        type: 'post',
        data: data + '&page=' + page,
        processData: false,
        cache: false,
        async: true,
        beforeSend: function () {
            $('#preloader').show();
        },
        success: function (response) {
            console.log("AJAX 성공");
            console.log(response);

            submitSuccess(response);
            $('#preloader').hide();
            if (response.status == 'false') {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            if (page == 1) {
                $('#' + id + ' tbody').empty().html(response.page_datas.detail);

                console.log("총 페이지 수:", _total_page);
                currentPage = 1; // 첫 페이지 로드시 currentPage를 1로 초기화
                var modalElement = document.getElementById(id);
                var modal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static', // 마스크 클릭해도 닫히지 않게 설정
                    keyboard: true     // esc 키로 닫히지 않게 설정
                });
                modal.show();
            } else {
                $('#' + id + ' tbody').append(response.page_datas.detail);
            }
            _total_page = response.total_page; // 여기서 _total_page를 설정


        },
        error: function (jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            console.log(textStatus);
            return false;
        },
        complete: function () { }
    });
}
$(document).ready(function () {
    const $tableResponsive = $('.table-responsive');

    $tableResponsive.on('scroll', function () {
        if ($tableResponsive.scrollTop() + $tableResponsive.innerHeight() >= $tableResponsive[0].scrollHeight - 10) { // 10픽셀 여유를 둡니다.
            console.log("현재 페이지:", currentPage, "총 페이지:", _total_page);
            if (currentPage < _total_page) {
                currentPage++;
                console.log("다음 페이지 로드:", currentPage);
                openLayer(_mb_idx, 'memberModal', currentPage);
            }
        }
    });
});

function openMileageLayer( mb_idx, id){
    let data = '';
    let url  = '/apis/membership/mileageRegister';

    if( mb_idx != '' ){

        data = 'memIdx='+mb_idx;
        url  = '/apis/membership/mileageUserRegister';
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