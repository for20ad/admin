var error_lists = [];

function numberWithComma(n)
{
    n = n.toString().replace(/,/g, '');
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function _form_error(id, error_message)
{
    $('#error_' + id).html('');
    $('#' + id).removeClass('is-invalid');

    if (error_message != '')
    {
        $('#error_' + id).html(error_message);
        $('#' + id).addClass('is-invalid');
        error_lists.push(error_message);
    }
}

function submitSuccess(response) {
    var boxType = 'warning';

    if (response.status == '200')
    {
        boxType = 'success';
    }
    else
    {
        $.each(response.errors, function(key, value) {
            _form_error(key, value);
        });
    }

    if (response.alert != '')
    {

        if (response.redirect_url != undefined)
        {
            box_alert_redirect(response.alert, response.redirect_url, boxType);
        }
        else if (response.replace_url != undefined)
        {
            box_alert_replace(response.alert, response.replace_url, boxType);
        }
        else if (response.goback == 'true')
        {
            box_alert_back(response.alert, boxType);
        }
        else if (response.reload == 'true')
        {
            box_alert_reload(response.alert, boxType);
        }
        else
        {
            box_alert_autoclose(response.alert, boxType);
        }
    }
    else
    {
        if (response.redirect_url != '')
        {
            document.location.href = response.redirect_url;
        }
        else if (response.replace_url != '')
        {
            document.location.replace(response.replace_url);
        }
        else if (response.goback == 'true')
        {
            history.go(-1);
        }
        else if (response.reload == 'true')
        {
            document.location.reload();
        }
    }
}

function submitError(status, error)
{
    var msg = '처리중 에러가 발생했습니다.<br />잠시 후 다시 이용해 주세요.' + '<br /><br />에러 : [' + status + '] ' + error;
    console.log(msg);
    box_alert(msg, 'error');
    return false;
}

$(document).ready(function() {
    $('.v_numbers').keyup(function() {
        var ev = $(this).val();
        $(this).val(ev.replace(/[^0-9]/gi, ''));
    });

    $('.v_unspace').keyup(function() {
        var ev = $(this).val();
        $(this).val(ev.replace(/ /gi, ''));
    });

    $(document).on("keyup", "input:text[numberwithcomma]", function() {
        var num = numberWithComma($(this).val());
        $(this).val(num);
    });

    $(document).on('change', "input, textarea", function () {
        var id = $(this).attr('id');
        if (id == undefined)
        {
            id = $(this).attr('name');
        }

        $('#error_' + id).html('');
        $('#' + id).removeClass('is-invalid');
    });
});