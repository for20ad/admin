function getSearchList( page ) {

    const frm = $("#frm_search");
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('page') != undefined) {
        page = urlParams.get('page') || 1; // 기본값 1
    }
    frm.find( '[name=page]' ).val( page );
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
            $("#pagination").empty().html( response.page_datas.pagination );
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
function openLayerPopup(cIdx) {
    $('#popupContent').empty();
    $.ajax({
        url: '/apis/membership/getCouponUseRange',
        method: 'POST',
        data: 'cIdx='+cIdx,
        dataType: 'json',
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
        },
        success: function (response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status != 200) {
                var error_message = response.error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            $('#popupContent').html( response.txt );

            // 레이어 팝업과 배경을 보이게 설정
            $('#layerPopup').css('display','flex').css('position','fixed' );
            $('#layerPopupBg').css('display', 'block');

        },
        error: function (jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            return false;
        },
        complete: function () { }
    });


}

function closeLayerPopup() {
    // 레이어 팝업과 배경을 다시 숨김
    document.getElementById('layerPopup').style.display = 'none';
    document.getElementById('layerPopupBg').style.display = 'none';
}


function openLayer( mb_idx, id, viewTab ){
    show_mb_idx = mb_idx;
    let data = '';
    let url  = '/apis/membership/register';

    if( mb_idx != '' ){
        viewTab = viewTab == undefined ? 'summery': viewTab;
        data = 'memIdx='+mb_idx+'&tab='+viewTab;
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

            if( id == 'dataModal' ){
                let tabTitle = '#tab1-tab';
                let tab      = '#tab1';
                let isPointPagingInitialized = false;
                let isCouponPagingInitialized = false;

                if( viewTab == 'info' ){

                    tabTitle = '#tab2-tab';
                    tab      = '#tab2';

                }else if( viewTab == 'orderList' ){
                    tabTitle = '#tab3-tab';
                    tab      = '#tab3';

                    if (!isPointPagingInitialized) {
                        $( '#frm_sub_lists #pagination').off('click', 'a');  // 기존 페이징 이벤트 제거
                        subSelectorPagination.initPagingNumFunc( '#tab3', getOrderLists);
                        subSelectorPagination.initPagingSelectFunc( '#tab3', getOrderLists);

                        isPointPagingInitialized = true;  // 플래그 설정
                    }

                }else if( viewTab == 'point' ){
                    tabTitle = '#tab4-tab';
                    tab      = '#tab4';

                    if (!isPointPagingInitialized) {
                        $( '#frm_sub_lists #pagination').off('click', 'a');  // 기존 페이징 이벤트 제거
                        subSelectorPagination.initPagingNumFunc( '#tab4', getPointLists);
                        subSelectorPagination.initPagingSelectFunc( '#tab4', getPointLists);

                        isPointPagingInitialized = true;  // 플래그 설정
                    }


                }else if( viewTab == 'coupon' ){
                    tabTitle = '#tab5-tab';
                    tab      = '#tab5';

                    if (!isCouponPagingInitialized) {
                        $( '#frm_sub_lists #pagination').off('click', 'a');  // 기존 페이징 이벤트 제거
                        subSelectorPagination.initPagingNumFunc('#tab5', getCouponLists);
                        subSelectorPagination.initPagingSelectFunc('#tab5', getCouponLists);

                        isCouponPagingInitialized = true;  // 플래그 설정
                    }
                }

                var modalElement = document.getElementById(id);
                var isModalShown = $(modalElement).hasClass('show'); // 모달이 이미 열려 있는지 확인

                if (!isModalShown) {
                    var modal = new bootstrap.Modal(modalElement, {
                        backdrop: 'static', // 마스크 클릭해도 닫히지 않게 설정
                        keyboard: false     // esc 키로 닫히지 않게 설정
                    });
                    modal.show();

                    // 기존 탭들을 비활성화하고 첫 번째 탭을 활성화
                    $('#'+id+' .nav-item').removeClass('active show');
                    $('#'+id+' .nav-link ').removeClass('active show');

                    $('#tab1-tab').addClass('active show');
                    $('#tab1').addClass('active show');

                }

                // 데이터를 업데이트

                $('#'+id+' #headerInfo').empty().html(response.page_datas.header);

                $('#'+id+' '+tab+' .viewData').empty().html(response.page_datas.detail);


            }else{
                $('#'+id+' .viewData').empty().html(response.page_datas.detail);
                var modalElement = document.getElementById(id);
                var modal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static', // 마스크 클릭해도 닫히지 않게 설정
                    keyboard: false     // esc 키로 닫히지 않게 설정
                });
                modal.show();

            }

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

function openCounselLayer( mb_idx, id, viewTab ){
    show_mb_idx = mb_idx;
    let data = 'memIdx='+mb_idx;
    let url  = '/apis/membership/getCounselLists';

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

// Bootstrap의 탭 기능을 사용할 경우
$('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
    let targetTab = $(e.target).attr('href'); // 활성화된 탭의 셀렉터
    console.log( targetTab );
    if (targetTab === '#tab3') { // Order 탭
        if (!isPointPagingInitialized) {
            $('#tab3 #frm_sub_lists #pagination').off('click', 'a'); // 기존 페이징 이벤트 제거
            subPagination.initPagingNumFunc(getOrderLists);
            subPagination.initPagingSelectFunc(getOrderLists);
            isPointPagingInitialized = true; // 플래그 설정
        }
    }
    else if (targetTab === '#tab4') { // Point 탭
        if (!isPointPagingInitialized) {
            $('#tab4 #frm_sub_lists #pagination').off('click', 'a'); // 기존 페이징 이벤트 제거
            subPagination.initPagingNumFunc(getPointLists);
            subPagination.initPagingSelectFunc(getPointLists);
            isPointPagingInitialized = true; // 플래그 설정
        }
    } else if (targetTab === '#tab5') { // Coupon 탭
        if (!isCouponPagingInitialized) {
            $('#tab5 #frm_sub_lists #pagination').off('click', 'a'); // 기존 페이징 이벤트 제거
            subPagination.initPagingNumFunc(getCouponLists);
            subPagination.initPagingSelectFunc(getCouponLists);
            isCouponPagingInitialized = true; // 플래그 설정
        }
    }
});


function getOrderLists( page )
{
    event.preventDefault();
    event.stopPropagation(); // 이벤트 버블링 방지

    const frm = $("#frm_order_search");
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('page') != undefined) {
        page = urlParams.get('page') || 1; // 기본값 1
    }


    var inputs = frm.find('input, button, select');
    $.ajax({
        url: '/apis/membership/getOrderLists',
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
            if (response.status != 200) {
                var error_message = response.error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            $('#orderListsTable tbody').empty().html( response.page_datas.lists_row );
            $("#orderListsTableWrap #pagination").empty().html( response.page_datas.pagination );

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
function getPointLists( page )
{
    event.preventDefault();
    event.stopPropagation(); // 이벤트 버블링 방지
    const frm = $("#frm_point_search");
    if( page != undefined ){
        frm.find( '[name=page]' ).val( page );
    }
    var inputs = frm.find('input, button, select');
    $.ajax({
        url: '/apis/membership/getPointLists',
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
            if (response.status != 200) {
                var error_message = response.error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            $('#pointListsTable tbody').empty().html( response.page_datas.lists_row );
            $("#pointListsTableWrap #pagination").empty().html( response.page_datas.pagination );

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

function modifyReason( $obj )
{
    let top = $obj.closest( 'tr' );
    top.find('.org_reason').hide();
    top.find('.new_reason').show();
    top.find('.modBtnArea').hide();
    top.find('.confirmBtnArea').show();
}

function cancelReason( $obj ){
    let top = $obj.closest( 'tr' );
    top.find('.org_reason').show();
    top.find('.new_reason').hide();
    top.find('.modBtnArea').show();
    top.find('.confirmBtnArea').hide();
}

function confirmModifyReason( $obj ){
    let top = $obj.closest( 'tr' );
    if( top.find('[name=a_reason_gbn').val() == '' ){
        box_alert( '사유구분을 선택해주세요.' );
        return false;
    }
    if( top.find('[name=a_m_type').val() == '' ){
        box_alert( '타입을 선택해주세요.' );
        return false;
    }
    if( $.trim( top.find('[name=a_reason]').val()  ) == '' ){
        box_alert( '사유를 입력해주세요.' );
        return false;
    }
    var opIdx = top.data('point-idx');
    var a_reason_gbn = top.find('[name=a_reason_gbn').val();
    var a_m_type = top.find('[name=a_m_type').val();
    var a_reason = top.find('[name=a_reason]').val();
    var data = 'opIdx='+opIdx+'&a_reason_gbn='+a_reason_gbn+'&a_m_type='+a_m_type+'&a_reason='+a_reason;

    $.ajax({
        url: '/apis/membership/changePointReason',
        method: 'POST',
        data: data,
        dataType: 'json',
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
        },
        success: function (response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status != 200) {
                var error_message = response.error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }
            const frm = $("#frm_point_search");

            getPointLists( frm.find( '[name=page]' ).val() );
        },
        error: function (jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            return false;
        },
        complete: function () { }
    });
}

function getCouponLists( page )
{
    event.preventDefault();
    event.stopPropagation(); // 이벤트 버블링 방지
    const frm = $("#frm_coupon_search");
    if( page != undefined ){
        frm.find( '[name=page]' ).val( page );
    }
    var inputs = frm.find('input, button, select');
    $.ajax({
        url: '/apis/membership/getCouponLists',
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
            if (response.status != 200) {
                var error_message = response.error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            $('#couponListsTable tbody').empty().html( response.page_datas.lists_row );
            $("#couponListsTableWrap #pagination").empty().html( response.page_datas.pagination );

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