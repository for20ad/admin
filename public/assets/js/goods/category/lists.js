function openLayer( cateIdx, parentIdx ){
    let data = 'parentIdx='+parentIdx;
    let url  = '/apis/goods/cateRegister';

    if( cateIdx != '' ){
        data = 'cate_idx='+cateIdx+'&parentIdx='+parentIdx;
        url  = '/apis/goods/cateDetail';
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
            if (response.status == 'false') {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }
            $('#viewData').empty().html( response.page_datas.detail );
            bindEventListeners();
            //restoreAccordionState();
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
setTimeout( function(){openLayer('', 0);}, 200 );
function getSearchList( parentIdx = 0 ){

    $.ajax({
        url: '/apis/goods/getCategoryLists',
        method: 'POST',
        data: 'parentIdx='+parentIdx,
        dataType: 'json',
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
        },
        success: function (response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status == 'false') {
                var error_message = response.error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            $('#category-list').empty().html( response.page_datas.lists_row );
            initSortable();
            //restoreAccordionState();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            return false;
        },
        complete: function () { }
    });
}

function initSortable() {
    // 1차 카테고리 드래그 앤 드롭 설정
    $("#category-list").sortable({
        handle: ".move-icons",
        items: "> .category-wrapper",
        stop: function (event, ui) {
            var order = [];
            $("#category-list .category-wrapper").each(function () {
                order.push($(this).data("idx"));
            });

            updateCategoryOrder(order);
        }
    });

    // 2차 및 3차 카테고리 드래그 앤 드롭 설정
    $(".category-wrapper").each(function () {
        var parentIdx = $(this).data("idx");

        // 2차 카테고리
        $(this).find(".child-container").sortable({
            connectWith: ".category-wrapper[data-idx='" + parentIdx + "'] .child-container",
            handle: ".move-icons",
            items: "> .child-category-wrapper",
            stop: function (event, ui) {
                if (ui.sender) return; // 중복 업데이트 방지
                var order = [];
                $(".child-container[data-parent-idx='" + parentIdx + "'] .child-category-wrapper").each(function () {
                    order.push($(this).data("idx"));
                });

                updateCategoryOrder(order);
            }
        });

        // 3차 카테고리
        $(this).find(".child-category-wrapper").each(function () {
            var childParentIdx = $(this).data("idx");
            $(this).find(".child-container2").sortable({
                connectWith: ".child-container2",
                handle: ".move-icons",
                items: "> .child-category",
                stop: function (event, ui) {
                    if (ui.sender) return; // 중복 업데이트 방지
                    var order = [];
                    $(".child-container2[data-parent-idx='" + childParentIdx + "'] .child-category").each(function () {
                        order.push($(this).data("idx"));
                    });

                    updateCategoryOrder(order);

                }
            });
        });
    });
}

function bindEventListeners() {
    // 아코디언 기능 및 아이콘 토글
    $('.category-div, .child-category, .child-container2').off('dblclick').on('dblclick', function (event) {
        if ($(event.target).is('svg') || $(event.target).closest('svg').length) {
            return; // SVG를 클릭한 경우 아코디언 기능을 작동하지 않음
        }
        var $childContainers = $(this).nextAll('.child-container, .child-container2');
        if ($childContainers.length) {
            $childContainers.toggle(200);
            //saveAccordionState();
        }
    });

    // 상세 정보를 여는 클릭 이벤트
    $('.detail').off('click').on('click', function (event) {
        event.stopPropagation(); // 이벤트 버블링 방지
        $('.selected-add-category').removeClass('selected-add-category');
        $('.selected-category').removeClass('selected-category');
        if (!$(event.target).closest('svg').length) {
            if ( $(this).hasClass('category-div') ) {
                $(this).addClass('selected-category');
            } else {
                $(this).find('.child-category').addClass('selected-category');
            }
            openLayer($(this).data('idx'), $(this).attr('data-parent-idx'));
        }else{
            if( !$(event.target).closest('svg').hasClass('move-up') ){
                if ( $(this).hasClass('category-div') ) {
                    $(this).addClass('selected-add-category');
                } else {
                    $(this).find('.child-category').addClass('selected-add-category');
                }
            }

        }
    });
}

$(document).ready(function () {
    setTimeout(function(){
        initSortable();
        bindEventListeners();
    }, 1000);
    // restoreAccordionState();
});


function updateCategoryOrder(order) {
    $.ajax({
        url: '/apis/goods/updateCategoryOrder', // 서버에 맞는 URL로 변경
        type: 'POST',
        data: { order: order },
        beforeSend:function(){
            $('#preloader').show();
        },
        success: function(response) {
            $('#preloader').hide();
            console.log('Order updated:', response);
        },
        error: function(xhr, status, error) {
            $('#preloader').hide();
            console.error('Error updating order:', status, error);
        }
    });
}


function deleteCategoryConfirm( cate_idx )
{
    box_confirm('카테고리를 삭제하시겠습니까?\n삭제 시 하위 카테고리 전부가 삭제됩니다.', 'q', '', deleteCategory, cate_idx);
}
function deleteCategory( cate_idx ){
    $.ajax({
        url: '/apis/goods/deleteCategory',
        type: 'post',
        data: 'cate_idx='+cate_idx,
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
            //getSearchList();
            getSearchList();
            openLayer('',0);
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