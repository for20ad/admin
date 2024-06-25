function box_remove()
{
    Swal.close();
}

// success, warning, info, error, question
function box_alert(txt, boxType, boxTitle, callbackMethod, jsonData)
{
    if (boxType == '' || boxType == undefined) boxType = null;
    if (boxTitle == undefined) boxTitle = '알림';

    $(':focus').blur();

    setTimeout(function() {
        Swal.fire({
            icon: boxType,
            title: boxTitle,
            html: txt,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#666',
            allowOutsideClick: false,
            confirmButtonText: '확인'
        }).then((result) => {
            if (result.isConfirmed)
            {
                if (callbackMethod)
                {
                    callbackMethod(jsonData);
                }
            }
        });
    }, 150);
}

function box_alert_autoclose(txt, boxType, boxTitle)
{
    if (boxType == '' || boxType == undefined) boxType = null;
    if (boxTitle == undefined) boxTitle = '알림';

    $(':focus').blur();

    setTimeout(function() {
        Swal.fire({
            icon: boxType,
            title: boxTitle,
            html: txt,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#666',
            allowOutsideClick: false,
            confirmButtonText: '확인',
            timer: ((txt.length * 32) < 1500) ? 1500 : (txt.length * 32)
        });
    }, 150);
}

function box_alert_focus(txt, obj, boxType, boxTitle)
{
    if (boxType == '' || boxType == undefined) boxType = null;
    if (boxTitle == undefined) boxTitle = '알림';

    $(':focus').blur();

    setTimeout(function() {
        Swal.fire({
            icon: boxType,
            title: boxTitle,
            html: txt,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#666',
            allowOutsideClick: false,
            confirmButtonText: '확인'
        }).then((result) => {
            if (result.isConfirmed)
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
        Swal.fire({
            icon: boxType,
            title: boxTitle,
            html: txt,
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonText: '확인',
            cancelButtonText: '취소'
        }).then((result) => {
            if (result.isConfirmed)
            {
                if (callbackMethod)
                {
                    callbackMethod(jsonData);
                }
            }
        });
    }, 150);
}

function box_loading(txt, boxType, boxTitle)
{
    if (boxType == '' || boxType == undefined) boxType = null;
    if (boxTitle == undefined) boxTitle = '알림';

    $(':focus').blur();

    setTimeout(function() {
        Swal.fire({
            icon: boxType,
            title: boxTitle,
            html: txt,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading()
            }
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
    if (data.url)
    {
        opener.location.href = data.url;
    }
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
