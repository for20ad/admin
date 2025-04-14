function getSearchList( page ) {
    const frm = $("#frm_search");
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('page') != undefined) {
        page = urlParams.get('page') || 1; // 기본값 1
    }else{
        let newUrl = window.location.origin + window.location.pathname + '?page=' + page;
        history.pushState({ page: page }, null, newUrl);
    }

    frm.find('[name=page]').val(page); // 폼에 페이지 값 설정
    var inputs = frm.find('input, button, select');
    $.ajax({
        url: '/apis/goods/getDaHaeGoodsHeader',
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

function goodsRegisterConfirm(){
    const frm  = $("#frm_lists");
    var regChk = true;
    var msg    = '다해inc에 등록된 상품을 동기화 하시겠습니까?';
    if( $('#frm_lists tbody input[type="checkbox"]:checked').length < 1 ){
        box_alert( '등록할 싱픔데이터를 선택해주세요.', 'i' );
        return false;
    }
    $('#frm_lists tbody input[type="checkbox"]:checked').each(function(){
        if( $(this).closest('tr').find('.gidxs').text() != '' ){
            regChk = false;
        }
    });
    if( regChk == false ){
        msg    = '이미 등록된 상품이 있습니다. \n등록하시겠습니까? \n ****등록 시 같은상품이 추가됩니다.****';
    }
    box_confirm(msg, 'q', '', goodsRegister);


}
function goodsRegister(){
    const aFrm = $("#frm_search");
    var inputs = aFrm.find('input, button, select');
    const frm  = $("#frm_lists");
    $.ajax({
        url: '/apis/goods/registerDahaeProduct',
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
