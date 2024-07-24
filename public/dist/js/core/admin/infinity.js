var infinity_timer;

function infinityTimer(duration, id) {
    var timer = duration;
    var hours, minutes, seconds;

    clearInterval(infinity_timer);

    infinity_timer = setInterval(function() {
        hours = parseInt(timer / 3600, 10);
        minutes = parseInt(timer / 60 % 60, 10);
        seconds = parseInt(timer % 60, 10);

        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        $('.' + id).text(hours + ':' + minutes + ':' + seconds);
        if (--timer < 0) {
            timer = 0;
            clearInterval(infinity_timer);
            document.location.href = "/logOut";
        }
    }, 1000);
}

function check_login() {
    $.ajax({
        url: "/api/login/checkLogin",
        method: "GET",
        dataType: "json",
        cache: false,
        beforeSend: function() {},
        success: function(response) {
            if (response.status == "true") {
                console.log('User is logged in');
            } else {
                console.log('User is not logged in, redirecting to /logOut');
                document.location.href = "/logOut";
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('AJAX Error: ', textStatus, errorThrown);
            document.location.href = "/logOut";
        },
        complete: function() {}
    });
}

function delay_login() {
    $.ajax({
        url: "/api/login/delayLogin",
        method: "GET",
        dataType: "json",
        cache: false,
        beforeSend: function() {},
        success: function(response) {
            console.log('Delay login response: ', response);
            submitSuccess(response);

            if (response.status == 'true') {
                infinityTimer(response.admin_auth_time, 'admin_auth_timer');
            }

            return false;
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('AJAX Error: ', textStatus, errorThrown);
            document.location.href = "/logOut";
            return false;
        },
        complete: function() {}
    });
}

$(document).ready(function() {
    if (site_is_admin_login == '1') {
        if ($('.admin_auth_timer').length > 0) {
            if (typeof admin_auth_time !== 'undefined') {
                infinityTimer(admin_auth_time, 'admin_auth_timer');
            }
        }

        check_login();

        setInterval(function() {
            check_login();
        }, 60000);
    } else {
        document.location.href = "/login";
    }
});
