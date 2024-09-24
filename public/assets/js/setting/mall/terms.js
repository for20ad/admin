$(function(){

    $('#frm_register').on('submit', function(event) {
        event.preventDefault();

        error_lists = [];
        $('.error_txt').html('');

        var inputs = $(this).find('input, button, textarea');
        var isSubmit = true;

        if (isSubmit == false)
        {
            var error_message = '';
            error_message = error_lists.join('<br />');
            box_alert(error_message, 'e');

            inputs.prop('disabled', false);
            return false;
        }

        $.ajax({
            url: '/apis/setting/policyTermsSet',
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
                        box_alert(error_message, 'i');
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