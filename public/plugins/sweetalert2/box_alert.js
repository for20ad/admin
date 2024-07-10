const typeShort = {
    s: 'success',
    e: 'error',
    w: 'warning',
    i: 'info',
    q: 'question',
    c: 'confirm',
};

const typeColors = {
    success: '#2FB344',
    error: '#D63939',
    warning: '#F76707',
    info: '#206BC4',
    question: '#87adbd',
    confirm: '#C29183'
};
function box_remove()
{
    Swal.close();
}

// success, warning, info, error, question
function box_alert(boxText, boxType, subTxt, callbackMethod, jsonData)
{
    if (boxType == '' || boxType == undefined) boxType = null;
    if (boxText == undefined) boxText = '알림';

    $(':focus').blur();

    setTimeout(function() {
        const color = typeColors[typeShort[boxType]] || '#2FB344'; // 기본 색상으로 #2FB344 사용
        Swal.fire({
            icon: typeShort[boxType],
            title: boxText,
            html: subTxt,
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonText: '확인',
            confirmButtonColor: color,
            reverseButtons:true,
            cancelButtonColor: '#fff',
            cancelButtonText: '취소',
            customClass: {
                popup: 'custom-popup',
                cancelButton: 'custom-cancel-button'
            },
            didOpen: () => {
                const color = typeColors[typeShort[boxType]] || '#2FB344'; // 기본 색상으로 #2FB344 사용
                const styleElement = document.createElement('style');
                styleElement.innerHTML = `.custom-popup::before { background-color: ${color} !important; }`;
                document.head.appendChild(styleElement);
            }
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

function box_alert_autoclose(boxText, boxType, subTxt)
{
    if (boxType == '' || boxType == undefined) boxType = null;
    if (boxText == undefined) boxText = '알림';

    $(':focus').blur();

    setTimeout(function() {
        const color = typeColors[typeShort[boxType]] || '#2FB344'; // 기본 색상으로 #2FB344 사용
        Swal.fire({
            icon: typeShort[boxType],
            title: boxText,
            html: subTxt,
            confirmButtonColor: color,
            cancelButtonColor: '#666',
            allowOutsideClick: false,
            confirmButtonText: '확인',
            reverseButtons:true,
            timer: ((boxText.length * 32) < 1500) ? 1500 : (boxText.length * 32),
            customClass: {
                popup: 'custom-popup',
                cancelButton: 'custom-cancel-button'
            },
            didOpen: () => {
                const color = typeColors[typeShort[boxType]] || '#2FB344'; // 기본 색상으로 #2FB344 사용
                const styleElement = document.createElement('style');
                styleElement.innerHTML = `.custom-popup::before { background-color: ${color} !important; }`;
                document.head.appendChild(styleElement);
            }
        });
    }, 150);
}

function box_alert_focus(boxText, obj, boxType, subTxt)
{
    if (boxType == '' || boxType == undefined) boxType = null;
    if (boxText == undefined) boxText = '알림';

    $(':focus').blur();

    setTimeout(function() {
        const color = typeColors[typeShort[boxType]] || '#2FB344'; // 기본 색상으로 #2FB344 사용
        Swal.fire({
            icon: typeShort[boxType],
            title: boxText,
            html: subTxt,
            confirmButtonColor: color,
            cancelButtonColor: '#666',
            allowOutsideClick: false,
            confirmButtonText: '확인',
            reverseButtons:true,
            customClass: {
                popup: 'custom-popup',
                cancelButton: 'custom-cancel-button'
            },
            didOpen: () => {
                const color = typeColors[typeShort[boxType]] || '#2FB344'; // 기본 색상으로 #2FB344 사용
                const styleElement = document.createElement('style');
                styleElement.innerHTML = `.custom-popup::before { background-color: ${color} !important; }`;
                document.head.appendChild(styleElement);
            }
        }).then((result) => {
            if (result.isConfirmed)
            {
                obj.focus();
            }
        });
    }, 150);
}

// confirm, prompt
function box_confirm(boxText, boxType, subTxt, callbackMethod, jsonData)
{
    if (boxType == '' || boxType == undefined) boxType = null;
    if (boxText == undefined) boxText = '알림';

    $(':focus').blur();

    setTimeout(function() {
        const color = typeColors[typeShort[boxType]] || '#2FB344'; // 기본 색상으로 #2FB344 사용
        Swal.fire({
            icon: typeShort[boxType],
            title: boxText,
            html: subTxt,
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonColor: color,
            confirmButtonText: '확인',
            cancelButtonText: '취소',
            reverseButtons:true,
            customClass: {
                popup: 'custom-popup',
                cancelButton: 'custom-cancel-button'
            },
            didOpen: () => {
                const color = typeColors[typeShort[boxType]] || '#2FB344'; // 기본 색상으로 #2FB344 사용
                const styleElement = document.createElement('style');
                styleElement.innerHTML = `.custom-popup::before { background-color: ${color} !important; }`;
                document.head.appendChild(styleElement);
            }
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

function box_loading(boxText, boxType, subTxt)
{
    if (boxType == '' || boxType == undefined) boxType = null;
    if (boxText == undefined) boxText = '알림';

    $(':focus').blur();

    setTimeout(function() {
        Swal.fire({
            icon: typeShort[boxType],
            title: boxText,
            html: subTxt,
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

function box_alert_back(boxText, boxType, subTxt)
{
    box_alert(boxText, boxType, subTxt, box_back);
}

function box_close()
{
    window.close();
}

function box_alert_close(boxText, boxType, subTxt)
{
    box_alert(boxText, boxType, subTxt, box_close);
}

function box_redirect(data)
{
    if (data.url)
    {
        document.location.href = data.url;
    }
    return false;
}

function box_alert_redirect(boxText, url, boxType, subTxt)
{
    box_alert(boxText, boxType, subTxt, box_redirect, {url: url});
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

function box_alert_opener_redirect(boxText, url, subTxt, boxTitle)
{
    box_alert(boxText, boxType, subTxt, box_opener_redirect, {url: url});
}

function box_replace(data)
{
    if (data.url)
    {
        document.location.replace(data.url);
    }
    return false;
}

function box_alert_replace(boxText, url, boxType, subTxt)
{
    box_alert(boxText, boxType, subTxt, box_replace, {url: url});
}

function box_reload()
{
    window.location.reload();
    window.location.href = window.location.href;
}

function box_alert_reload(boxText, boxType, subTxt)
{
    box_alert(boxText, boxType, subTxt, box_reload);
}
