function getSearchList( page ) {

    const frm = $("#frm_search");
    const urlParams = new URLSearchParams(window.location.search);
    if (page === undefined) {
        page = urlParams.get('page') || 1; // 기본값 1
    }
    frm.find( '[name=page]' ).val( page );
    var inputs = frm.find('input, button, select');
    $.ajax({
        url: '/apis/promotion/getCouponLists',
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
    $(document).on('keyup', '#frm_search [name=s_keyword]', function(e){
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

function deleteCouponCheckedAll(){
    let c_idxs = [];
    if ($('input:checkbox[name="i_cpn_idx[]"]:checked').length > 0) {
        $('input:checkbox[name="i_cpn_idx[]"]:checked').each(function() {
            c_idxs.push($(this).val());
        });
        deleteCouponConfirm( c_idxs );
    }else{
        box_alert( '삭제할 쿠폰을 선택해주세요.', 'i' );
    }
}

function deleteCouponConfirm(i_idxs){
    let c_idxs = new Array();
    if( i_idxs.length < 1 ){
        box_alert( '쿠폰IDX 누락. 새로고침 후 이용해주세요.' );
        return false;
    }else{
        i_idxs.forEach(function(idx) {
            c_idxs.push(idx); // 각 쿠폰 ID를 c_idxs 배열에 추가
        });
    }
    const _i_dxs = c_idxs;

    box_confirm('쿠폰을 삭제하시겠습니까?', 'q', '', deleteCoupon, _i_dxs);
}

function deleteCoupon( i_idxs ){
    let frm = $('#frm_search');
    var inputs = frm.find('input, button, select');

    $.ajax({
        url: '/apis/promotion/deleteCoupon',
        type: 'post',
        data: 'i_idxs='+i_idxs,
        dataType: 'json',
        cache: false,
        beforeSend: function() {
            $('#preloader').show();
            inputs.prop('disabled', true);
        },
        success: function(response) {
            submitSuccess(response);
            inputs.prop('disabled', false);
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
            getSearchList( frm.find('[name=page]').val() );
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            inputs.prop('disabled', false);
            $('#preloader').hide();
            return false;
        },
        complete: function() { }
    });
}

function stopIssueConfirm(){
    let c_idxs = [];
    if ($('input:checkbox[name="i_cpn_idx[]"]:checked').length > 0) {
        $('input:checkbox[name="i_cpn_idx[]"]:checked').each(function() {
            c_idxs.push($(this).val());
        });
        box_confirm('쿠폰발행을 중지 하시겠습니까?', 'q', '', stopIssue, c_idxs);
    }else{
        box_alert( '발행중지할 쿠폰을 선택해주세요.', 'i' );
    }
}
function stopIssue( i_idxs ){
    let frm = $('#frm_search');
    var inputs = frm.find('input, button, select');

    $.ajax({
        url: '/apis/promotion/stopIssue',
        type: 'post',
        data: 'i_idxs='+i_idxs,
        dataType: 'json',
        cache: false,
        beforeSend: function() {
            $('#preloader').show();
            inputs.prop('disabled', true);
        },
        success: function(response) {
            submitSuccess(response);
            inputs.prop('disabled', false);
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
            getSearchList( frm.find('[name=page]').val() );
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            inputs.prop('disabled', false);
            $('#preloader').hide();
            return false;
        },
        complete: function() { }
    });
}

function checkCopyConfirm(){
    let c_idxs = [];
    if ($('input:checkbox[name="i_cpn_idx[]"]:checked').length > 0) {
        $('input:checkbox[name="i_cpn_idx[]"]:checked').each(function() {
            c_idxs.push($(this).val());
        });
        box_confirm('쿠폰을 복사하시겠습니까?', 'q', '', checkCopy, c_idxs);
    }else{
        box_alert( '복사할 쿠폰을 선택해주세요.', 'i' );
    }
}

function checkCopy( i_idxs ){
    let frm = $('#frm_search');
    var inputs = frm.find('input, button, select');

    $.ajax({
        url: '/apis/promotion/copyCoupon',
        type: 'post',
        data: 'i_idxs='+i_idxs,
        dataType: 'json',
        cache: false,
        beforeSend: function() {
            $('#preloader').show();
            inputs.prop('disabled', true);
        },
        success: function(response) {
            submitSuccess(response);
            inputs.prop('disabled', false);
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
            getSearchList( frm.find('[name=page]').val() );
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            inputs.prop('disabled', false);
            $('#preloader').hide();
            return false;
        },
        complete: function() { }
    });
}