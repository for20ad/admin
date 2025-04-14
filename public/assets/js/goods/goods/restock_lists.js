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
        url: '/apis/goods/getGoodsRestockAlimLists',
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

            $('#frm_lists #listsTable tbody').empty().html( response.page_datas.lists_row );
            $("#frm_lists #pagination").empty().html( response.page_datas.pagination );
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
    getSearchList();
}, 300);
window.addEventListener('popstate', function (event) {
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get('page') || 1;
    getSearchList(page);
});


function deleteGoodsConfirm(i_idx){
    box_confirm('상품을 삭제하시겠습니까?', 'q', '', deleteGoods, i_idx);
}

function deleteGoods( i_idx ){
    const frm = $('#frm_lists')
    $.ajax({
        url: '/apis/goods/deleteGoods',
        type: 'post',
        data: 'g_idx='+i_idx,
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