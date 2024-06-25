function get_unread_noti()
{
    $.ajax({
        url: noti_url + "apis/notiApi/getUnreadNoti",
        method: "GET",
        dataType: "json",
        cache: false,
        beforeSend: function() { },
        success: function(response)
        {
            if (response.status == 'true')
            {
                if (response.total_count != '0')
                {
                    $('#noti_unread_count').text(response.total_count);
                    $('#noti_unread_count').addClass('count');
                }
                else
                {
                    $('#noti_unread_count').text('');
                    $('#noti_unread_count').removeClass('count');
                }

                return true;
            }

            return false;
        },
        error: function()
        {
            return false;
        },
        complete: function() { }
    });
}

$(document).ready(function() {
    // if (site_is_login == '1')
    // {
    //     if ($('#noti_unread_count').length > 0)
    //     {
    //         get_unread_noti();

    //         setInterval(function() {
    //             get_unread_noti();
    //         }, 30000);
    //     }
    // }
});
