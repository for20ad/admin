
function findSort() {
    const parentIdx = parseInt($('#frm_menu_write #i_parent_idx').val());
    let maxSort = 0;

    function findMaxSort(menuArray, parentIdx) {
        $.each(menuArray, function(index, menu) {
            if (parseInt(menu.MENU_PARENT_IDX) === parentIdx) {
                if (parseInt(menu.MENU_SORT) > maxSort) {
                    maxSort = parseInt(menu.MENU_SORT);
                }
            }
            // Recursive call for child menus
            if (menu.CHILD && menu.CHILD.length > 0) {
                findMaxSort(menu.CHILD, parentIdx);
            }
        });
    }

    findMaxSort(menuData, parentIdx);

    // Set the found maximum value + 1 to i_sort input
    $('#frm_menu_write #i_sort').val(maxSort + 1);
}

findSort()

// -----------------------------------------------------------------------------
// 메뉴
// -----------------------------------------------------------------------------
function modifyMenuConfirm()
{
    if ($('input:checkbox[name="i_menu_idx[]"]:checked').length > 0)
    {
        box_confirm('선택된 메뉴를 수정하시겠습니까?', 'q', '', modifyMenu);
    }
    else
    {
        box_alert('선택된 메뉴가 없습니다.', 'i');
    }
}

function modifyMenu()
{
    $.ajax({
        url: '/apis/setting/modifyMenu',
        type: 'post',
        data: $('#frm_menu_lists').serialize(),
        dataType: 'json',
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

function deleteMenuConfirm(menu_idx)
{
    box_confirm('메뉴를 삭제하시겠습니까?', 'q', '', deleteMenu, {'menu_idx': menu_idx});
}

function deleteMenu(param)
{
    $('#menu_idx').val(param.menu_idx);

    $.ajax({
        url: '/apis/setting/deleteMenu',
        type: 'post',
        data: 'menu_idx='+param.menu_idx,
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

$(document).ready(function () {
    $('#frm_menu_write').on('submit', function(event) {
        event.preventDefault();

        error_lists = [];
        $('.error_txt').html('');

        var inputs = $(this).find('input, button');
        var isSubmit = true;

        if ($.trim($('#i_name').val()) == '')
        {
            _form_error('i_name', '메뉴명을 입력하세요.');
            isSubmit = false;
        }

        if ($.trim($('#i_link').val()) == '')
        {
            _form_error('i_link', '링크를 입력하세요.');
            isSubmit = false;
        }

        if (isSubmit == false)
        {
            var error_message = '';
            error_message = error_lists.join('<br />');
            box_alert(error_message, 'e');

            inputs.prop('disabled', false);
            return false;
        }

        $.ajax({
            url: '/apis/setting/writeMenu',
            method: 'POST',
            data: new FormData(this),
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function()
            {
                inputs.prop('disabled', true);
                setTimeout(function() { inputs.prop('disabled', false); }, 3000);
                $('#preloader').show();
            },
            success: function(response)
            {
                submitSuccess(response);
                $('#preloader').hide();
                inputs.prop('disabled', false);

                if (response.status == 'false')
                {
                    var error_message = '';
                    error_message = error_lists.join('<br />');
                    if (error_message != '') {
                        box_alert(error_message, 'e');
                    }

                    return false;
                }
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                submitError(jqXHR.status, errorThrown);
                console.log(textStatus);
                $('#preloader').hide();
                inputs.prop('disabled', false);
                return false;
            },
            complete: function() { }
        });
    });
});