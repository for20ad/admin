function box_remove()
{
    $('#modal-window').hide();
    $('#modal-window').remove();
}

function box_alert(txt, boxType, boxTitle, callbackMethod, jsonData)
{
    if (boxType == '' || boxType == undefined) boxType = null;
    if (boxTitle == undefined) boxTitle = '알림';

    $(':focus').blur();

    setTimeout(function() {
        modal({
            type: boxType,
            title: boxTitle,
            text: txt,
            autoclose: false,
            closeClick: false,
            callback: function()
            {
                if (callbackMethod)
                {
                    callbackMethod(jsonData);
                }
            }
        });
    }, 150);
}

function box_alert_autoclose(txt, boxType, boxTitle, callbackMethod, jsonData)
{
    if (boxType == '' || boxType == undefined) boxType = null;
    if (boxTitle == undefined) boxTitle = '알림';

    $(':focus').blur();

    setTimeout(function() {
        $.alertx('Info Title','This is an alert message').delayClose(5);
    }, 150);
}

function box_alert_focus(txt, obj, boxType, boxTitle)
{
    if (boxType == '' || boxType == undefined) boxType = null;
    if (boxTitle == undefined) boxTitle = '알림';

    $(':focus').blur();

    setTimeout(function() {
        modal({
            type: boxType,
            title: boxTitle,
            text: txt,
            closeClick: false,
            callback: function()
            {
                obj.focus();
            }
        });
    }, 150);
}

// confirm, prompt
function box_confirm(txt, boxType, boxTitle, callbackMethod, jsonData)
{
    if (boxType == '' || boxType == undefined) boxType = null;
    if (boxTitle == undefined) boxTitle = '알림';

    $(':focus').blur();

    setTimeout(function() {
        modal({
            type: boxType,
            title: boxTitle,
            text: txt,
            closeClick: false,
            callback: function(result)
            {
                if (result)
                {
                    if (callbackMethod)
                    {
                        callbackMethod(jsonData);
                    }
                }
            }
        });
    }, 150);
}


function box_loading(txt, boxType, boxTitle)
{
    if (boxType == '' || boxType == undefined) boxType = null;
    if (boxTitle == undefined) boxTitle = '알림';

    var template = '<div class="modal-box"><div class="modal-inner"><div class="modal-title"></div><div class="modal-text"></div><div class="modal-loader"></div></div></div>';

    if (txt == '')
    {
        template = '<div class="modal-box"><div class="modal-inner"><div class="modal-title"></div><div class="modal-loader"></div></div></div>';
    }
    else if (boxTitle == '')
    {
        template = '<div class="modal-box"><div class="modal-inner"><div class="modal-text" style="padding:30px 0;"></div><div class="modal-loader"></div></div></div>';
    }

    $(':focus').blur();

    setTimeout(function() {
        modal({
            title: boxTitle,
            text: txt,
            autoclose: false,
            closeClick: false,
            template: template
        });
    }, 150);
}

function box_back()
{
    history.go(-1);
}

function box_alert_back(txt, boxType, boxTitle)
{
    box_alert(txt, boxType, boxTitle, box_back);
}

function box_close()
{
    window.close();
}

function box_alert_close(txt, boxType, boxTitle)
{
    box_alert(txt, boxType, boxTitle, box_close);
}

function box_redirect(data)
{
    if (data.url)
    {
        document.location.href = data.url;
    }
    return false;
}

function box_alert_redirect(txt, url, boxType, boxTitle)
{
    box_alert(txt, boxType, boxTitle, box_redirect, {url: url});
}

function box_opener_redirect(data)
{
    opener.location.href = data.url;
    window.close();
    return false;
}

function box_alert_opener_redirect(txt, url, boxType, boxTitle)
{
    box_alert(txt, boxType, boxTitle, box_opener_redirect, {url: url});
}

function box_replace(data)
{
    if (data.url)
    {
        document.location.replace(data.url);
    }
    return false;
}

function box_alert_replace(txt, url, boxType, boxTitle)
{
    box_alert(txt, boxType, boxTitle, box_replace, {url: url});
}

function box_reload()
{
    window.location.reload();
    window.location.href = window.location.href;
}

function box_alert_reload(txt, boxType, boxTitle)
{
    box_alert(txt, boxType, boxTitle, box_reload);
}
