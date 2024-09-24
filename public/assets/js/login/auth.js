let seconds = 180;
let countdownInterval;

function countdown() {
    console.log('카운트다운 시작');
    const counter = document.getElementById("countdown");
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;

    $('#countdown').html(`${minutes}:${remainingSeconds < 10 ? "0" : ""}${remainingSeconds}`);

    if (seconds > 0) {
        seconds--;
    } else {
        clearInterval(countdownInterval); // 타이머 멈추기
        box_alert("입력시간이 초과되었습니다. 재발송버튼을 눌러 다시 인증해주세요.", 'i');
    }
}

function startCountdown() {
    if (countdownInterval) {
        clearInterval(countdownInterval); // 기존 타이머 제거
    }
    countdownInterval = setInterval(countdown, 1000); // 새로운 타이머 설정
}

startCountdown();

function resendAuthNum() {
    var frm = $("#frm_loginProc");
    $.ajax({
        url: "/api/login/reSendAuthNum",
        method: "POST",
        data: frm.serialize(),
        dataType: "json",
        cache: false,
        async: true,
        beforeSend: function() {
            $('#preloader').show();
        },
        complete: function() {},
        success: function(response) {
            submitSuccess(response);
            $('#preloader').hide();
            $('#frm_loginProc [name=auth_num]').val(response.auth_num);
            $('#frm_loginProc [name=i_auth_number]').val(response.auth_num);
            seconds = 180;
            startCountdown(); // 새로운 카운트다운 시작
            if (response.status == 'false') {
                var error_message = '';
                error_message = response.errors.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            console.log(textStatus);
            return false;
        }
    });
}

function loginProc() {
    if ($('[name=i_auth_number]').val() == '') {
        box_alert('인증번호를 입력하세요.', 'i');
        return false;
    }
    if (seconds < 1) {
        box_alert('인증시간이 초과되었습니다. 재발송 버튼을 눌러주세요.', 'i');
        return false;
    }

    var frm = $("#frm_loginProc");

    $.ajax({
        url: "/api/login/loginProc",
        method: "POST",
        data: frm.serialize(),
        dataType: "json",
        cache: false,
        beforeSend: function() {
            $('#preloader').show();
        },
        complete: function() {},
        success: function(response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status == 'false') {
                var error_message = '';
                error_message = response.errors.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            console.log(textStatus);
            return false;
        }
    });
}
