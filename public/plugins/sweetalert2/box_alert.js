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
/* box_alert_resultView
*
*
*
*/

function box_alert_resultView(boxText, boxType, data) {
    if (boxType == '' || boxType == undefined) boxType = null;
    if (boxText == undefined) boxText = '알림';

    $(':focus').blur();

    setTimeout(function () {
        const color = typeColors[typeShort[boxType]] || '#2FB344'; // 기본 색상으로 #2FB344 사용

        const htmlContent = `
        <div class="custom-div">
            <div class="custom-content">
                <div class="custom-text-and-supporting-text">
                    <div class="custom-text">${boxText}</div>
                    <div class="custom-supporting-text" style="margin-bottom: 32px">
                        ${data.mainMessage}
                        <br />
                        ${data.subMessage}
                    </div>
                </div>
                <div class="alert-body" style="max-height: 278px; margin-bottom: 24px; overflow-y: auto; padding-right: 12px">
                    <div class="custom-group-21">
                        <div class="custom-frame-249">
                            <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                                viewBox="0 0 4 4" fill="none">
                                <circle cx="2" cy="2" r="2" fill="#206BC4" />
                            </svg>
                            <p class="body1-c ms-2 mt-1">
                                기본정보
                            </p>
                        </div>

                    </div>
                    <div class="custom-frame-253" style="text-align: left">
                        <div class="custom-frame-420">
                            <div class="custom-input-field">
                                <div class="custom-div2">등록 데이터</div>
                                <div class="custom-frame-11">
                                    <input type="text" class="form-control" name="example-text-input"
                                    placeholder="${data.registeredData}" disabled />
                                </div>
                            </div>
                            <div class="custom-input-field">
                                <div class="custom-div2">정상 데이터</div>
                                <div class="custom-frame-11">
                                    <input type="text" class="form-control" name="example-text-input"
                                    placeholder="${data.validData}" disabled />
                                </div>
                            </div>
                            <div class="custom-input-field">
                                <div class="custom-div2">실패 데이터</div>
                                <div class="custom-frame-11">
                                    <input type="text" class="form-control" name="example-text-input"
                                    placeholder="${data.failedData}" disabled />
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    ${boxType === 'e' ? `
                    <div class="custom-frame-410">
                        <div class="custom-frame-38">
                            <div class="custom-div2">실패사유</div>
                        </div>
                        <div class="table-responsive" style="text-align: left; border: 1px solid #E6E7E9; border-radius: 4px">
                            <table class="table table-vcenter">
                                <thead>
                                    <tr>
                                        <th>행번호</th>
                                        <th>실패사유</th>
                                    </tr>
                                </thead>
                                <tbody>
                                ${data.failureReasons.map(reason => `
                                    <tr>
                                        <td class="body2-c nowrap">
                                            ${reason.lineNumber}
                                        </td>
                                        <td class="text-dark body2-c">
                                        ${reason.reason}
                                        </td>
                                    </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>` : ''}
                </div>
            </div>
            <div class="custom-frame-23">
                <div class="custom-btn-btn-white" onclick="Swal.close()">
                    <div class="custom-div7">닫기</div>
                </div>
            </div>
        </div>`;

        Swal.fire({
            icon: typeShort[boxType],
            title: '',
            html: htmlContent,
            showConfirmButton: false,
            customClass: {
                popup: 'custom-popup'
            },
            width: '800px',
            padding: '20px',
            didOpen: () => {
                const styleElement = document.createElement('style');
                styleElement.innerHTML = `.custom-popup::before { background-color: ${color} !important; }`;
                document.head.appendChild(styleElement);

                $(".alert-body").mCustomScrollbar({
                    theme: "rounded-dark"
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                if (callbackMethod) {
                    callbackMethod(jsonData);
                }
            }
        });
    }, 150);
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
function box_confirm_cancel(boxText, boxType, subTxt, callbackMethod, jsonData, cancelCallback) {
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
            reverseButtons: true,
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
            if (result.isConfirmed) {
                if (callbackMethod) {
                    callbackMethod(jsonData); // 확인 버튼 클릭 시 호출
                }
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                if (cancelCallback) {
                    cancelCallback(jsonData); // 취소 버튼 클릭 시 호출
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
