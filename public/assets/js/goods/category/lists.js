function openLayer( cateIdx, parentIdx ){
    let data = 'parentIdx='+parentIdx+"&keywords="+keywords;
    let url  = '/apis/goods/cateRegister';

    if( cateIdx != '' ){
        data = 'cate_idx='+cateIdx+'&parentIdx='+parentIdx+"&keywords="+keywords;
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


// 키워드 추가
function addKeyword(event) {
    event.preventDefault(); // 기본 동작 방지
    const list = document.getElementById(`keyword-list`);
    const newKeyword = prompt("새 키워드를 입력하세요:");
    if (newKeyword) {
        $.ajax({
            url: '/apis/goods/questionKeywordRegister',
            type: 'POST',
            processData: false,
            cache: false,
            data: 'keyword='+newKeyword , // 객체 형태로 데이터 전송
            async: false, // 비동기 설정
            success: function (response) {
                // 서버에서 오류 응답 처리
                if (response.status == 400) {
                    let error_message = response.message || '알 수 없는 오류가 발생했습니다.';
                    box_alert(error_message, 'e');
                    return false;
                }

                // 고유 ID와 키워드 이름을 서버에서 받음

                const newKeywordId = response.keywordId || `keyword-${Date.now()}`;
                keywords.push({
                    K_IDX: newKeywordId,
                    K_NAME: newKeyword
                });
                $('[id^="keyword-select-"]').each(function () {
                    const select = $(this);
                    const option = $(`<option value="${newKeywordId}">${newKeyword}</option>`);
                    select.append(option);
                });
                // DOM에 키워드 추가
                const div = $(`
                    <div id="${newKeywordId}" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                        <span>${newKeyword}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" onclick="deleteKeyword(event, '${keywords.K_IDX}')">
                            <path d="M2.5 10C2.5 10.9849 2.69399 11.9602 3.0709 12.8701C3.44781 13.7801 4.00026 14.6069 4.6967 15.3033C5.39314 15.9997 6.21993 16.5522 7.12987 16.9291C8.03982 17.306 9.01509 17.5 10 17.5C10.9849 17.5 11.9602 17.306 12.8701 16.9291C13.7801 16.5522 14.6069 15.9997 15.3033 15.3033C15.9997 14.6069 16.5522 13.7801 16.9291 12.8701C17.306 11.9602 17.5 10.9849 17.5 10C17.5 9.01509 17.306 8.03982 16.9291 7.12987C16.5522 6.21993 15.9997 5.39314 15.3033 4.6967C14.6069 4.00026 13.7801 3.44781 12.8701 3.0709C11.9602 2.69399 10.9849 2.5 10 2.5C9.01509 2.5 8.03982 2.69399 7.12987 3.0709C6.21993 3.44781 5.39314 4.00026 4.6967 4.6967C4.00026 5.39314 3.44781 6.21993 3.0709 7.12987C2.69399 8.03982 2.5 9.01509 2.5 10Z" fill="white"/>
                            <path d="M8.33333 8.33333L11.6667 11.6667L8.33333 8.33333ZM11.6667 8.33333L8.33333 11.6667L11.6667 8.33333Z" fill="white"/>
                            <path d="M8.33333 8.33333L11.6667 11.6667M11.6667 8.33333L8.33333 11.6667M2.5 10C2.5 10.9849 2.69399 11.9602 3.0709 12.8701C3.44781 13.7801 4.00026 14.6069 4.6967 15.3033C5.39314 15.9997 6.21993 16.5522 7.12987 16.9291C8.03982 17.306 9.01509 17.5 10 17.5C10.9849 17.5 11.9602 17.306 12.8701 16.9291C13.7801 16.5522 14.6069 15.9997 15.3033 15.3033C15.9997 14.6069 16.5522 13.7801 16.9291 12.8701C17.306 11.9602 17.5 10.9849 17.5 10C17.5 9.01509 17.306 8.03982 16.9291 7.12987C16.5522 6.21993 15.9997 5.39314 15.3033 4.6967C14.6069 4.00026 13.7801 3.44781 12.8701 3.0709C11.9602 2.69399 10.9849 2.5 10 2.5C9.01509 2.5 8.03982 2.69399 7.12987 3.0709C6.21993 3.44781 5.39314 4.00026 4.6967 4.6967C4.00026 5.39314 3.44781 6.21993 3.0709 7.12987C2.69399 8.03982 2.5 9.01509 2.5 10Z" stroke="#616876" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                `);
                $(list).append(div);

                // 셀렉트 박스에 추가
                const select = $(`#keyword-select`);
                const option = $(`<option value="${newKeywordId}">${newKeyword}</option>`);
                select.append(option);


            },
            error: function (xhr, status, error) {
                console.error('에러 발생:', error);
                alert('감우진에게 문의하세요.');
            },
        });
    }
}

// 키워드 삭제
function deleteKeyword(event, keywordId) {
    event.preventDefault(); // 기본 동작 방지
    // AJAX로 삭제 요청
    $.ajax({
        url: '/apis/goods/questionKeywordDelete', // 삭제 엔드포인트
        type: 'POST',
        data: "keywordIdx="+keywordId,
        processData: false,
        cache: false,
        async: false, // 비동기 설정
        success: function (response) {
            if (response.status == 400) {
                let error_message = response.message || '알 수 없는 오류가 발생했습니다.';
                box_alert(error_message, 'e');
                return false;
            }
            // 키워드 목록에서 제거
            const keywordDiv = document.getElementById( 'keyword-row-'+keywordId);
            console.log( "keywordDiv :: " );
            console.log( keywordDiv );
            if (keywordDiv) {
                keywordDiv.remove();
            }

            // 모든 select 요소에서 키워드 제거
            $('[id^="keyword-select-"]').each(function () {
                const select = $(this);
                select.find(`option[value="${keywordId}"]`).remove(); // 옵션 삭제
            });

            const index = keywords.findIndex(keyword => keyword.K_IDX === keywordId);
                if (index !== -1) {
                    keywords.splice(index, 1); // 배열에서 삭제
                }


            alert('키워드가 삭제되었습니다.');

        },
        error: function (xhr, status, error) {
            console.error('키워드 삭제 중 에러 발생:', error);
            alert('키워드 삭제 중 오류가 발생했습니다.');
        },
    });
}



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
            bindEventListeners();
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

let rowCount = 0; // To keep track of the rows

function addRows() {

    var row = `
        <div>
            <div style="clear:both">
                이동 링크 : <input type="text" name="i_link_url[]" class="form-control" style="width:200px;">
            </div>
            <div class="file-row" data-idx="" style="margin-bottom:20px; display:flex; align-items:center; gap:10px;">
                <input type="file" name="files[]" class="form-control" style="width:200px;">
                <button type="button" class="btn btn-danger btn-sm" onclick="deleteRows( $(this).parent().parent() )">삭제</button>
            </div>
        </div>


    `;
    $('#fileArea').append( row );
}


function deleteRowsConfirm( $row ){
    box_confirm( '추가 파일을 삭제하시겠습니까?', 'q', '', deleteRows, $row);
}
function deleteRows($row) {
    const dataIdx = $row.data('idx'); // Get data-idx attribute

    if (dataIdx) {
        // If data-idx exists, perform an AJAX call to delete from the server
        $.ajax({
            url: '/apis/goods/deleteCategoryImages', // Replace with the actual API URL
            method: 'POST',
            data: { f_idx: dataIdx },
            dataType: 'json',
            beforeSend: function () {
                $('#preloader').show();
            },
            success: function (response) {
                $('#preloader').hide();
                if (response.status ===200) {
                    $row.remove(); // Remove from DOM
                } else {
                    alert('삭제 실패: ' + response.message);
                }
            },
            error: function () {
                $('#preloader').hide();
                alert('삭제 요청 중 오류가 발생했습니다.');
            },
        });
    } else {
        // If no data-idx, just remove the row from DOM
        $row.remove();
    }
}
function showInput(element) {
    const $textSpan = $(element);
    const $inputSpan = $textSpan.next('.aLinkInput');
    const $inputField = $inputSpan.find('input');

    // Hide the text span and show the input span
    $textSpan.hide();
    $inputSpan.show();
    $inputField.focus(); // Focus the input field
}

// Handle ESC key or blur
function hideInput(inputElement) {
    const $inputField = $(inputElement);
    const $inputSpan = $inputField.closest('.aLinkInput');
    const $textSpan = $inputSpan.prev('.aLinkText');
    const originalValue = $inputField.data('original-value');
    const currentValue = $inputField.val();

    if (currentValue !== originalValue) {
        // If the value has changed, send an AJAX request to update
        updateLink($inputField, originalValue, currentValue);
    } else {
        // If no change, simply hide input and show text
        $inputSpan.hide();
        $textSpan.show();
    }
}

// AJAX update function
function updateLink($inputField, originalValue, newValue) {

    const inputIdx = $inputField.data('f-idx');
    const data = {
        idx:inputIdx,
        value: newValue
    };

    $.ajax({
        url: '/apis/goods/updateCategoryFileLink',
        type: 'POST',
        data: data,
        success: function(response) {
            if (response.status == 200) {
                // Update the displayed text and reset the original value
                const $inputSpan = $inputField.closest('.aLinkInput');
                const $textSpan = $inputSpan.prev('.aLinkText');

                $inputField.data('original-value', newValue);
                $textSpan.text(newValue);

                // Hide input and show updated text
                $inputSpan.hide();
                $textSpan.show();
            } else {
                alert('Error updating the link: ' + response.message);
                $inputField.val(originalValue); // Revert to original value
                $inputSpan.hide();
                $textSpan.show();
            }
        },
        error: function() {
            alert('An error occurred while updating the link.');
            $inputField.val(originalValue); // Revert to original value
            const $inputSpan = $inputField.closest('.aLinkInput');
            const $textSpan = $inputSpan.prev('.aLinkText');
            $inputSpan.hide();
            $textSpan.show();
        }
    });
}

// Handle ESC key
$(document).on('keydown', '.aLinkInputField', function(event) {
    if (event.key === 'Escape') {
        // Cancel and revert changes
        const $inputField = $(this);
        const originalValue = $inputField.data('original-value');
        $inputField.val(originalValue);
        hideInput(this); // Revert to the original text view
    }
});