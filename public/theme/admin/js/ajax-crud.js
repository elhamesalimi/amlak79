$(document).on('click', 'a.page-link', function (event) {
    event.preventDefault();
    ajaxLoad($(this).attr('href'));

});
$(document).on('submit', 'form#frm', function (event) {
    event.preventDefault();
    var form = $(this);
    var data = new FormData($(this)[0]);
    var url = form.attr("action");
    $.ajax({
        type: form.attr('method'),
        url: url,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            $('.is-invalid').removeClass('is-invalid');
            $('.has-error').removeClass('has-error');
             $('.error').text('');
            if (data.fail) {
                 if (data.code){
                    $('#error-code').html(data.code);
                }
                for (control in data.errors) {
                    $('#' + control).addClass('is-invalid');
                    $('#error-' + control).html(data.errors[control]);

                }
            } else {
                ajaxLoad(data.redirect_url);
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            $('#validation-errors').html('');
            $.each(xhr.responseJSON.errors, function (key, value) {
                $('#' + key).addClass('is-invalid');
                $('#' + key).parent().addClass('has-error');
                $('#' + key).parent().append('<span class="error">' + value + '</span>');

            });
            $('.is-invalid:first').focus()

            // alert("Error: " + errorThrown);
        }
    });
    return false;
});
function ajaxLoad(filename, content) {
    content = typeof content !== 'undefined' ? content : 'content';
    $('input:first').focus()

    $('.loading').show();
    $.ajax({
        type: "GET",
        url: filename,
        contentType: false,
        success: function (data) {
            $("#" + content).html(data);
            $('.loading').hide();
            $(document.body).removeClass('modal-open');
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}
function ajaxDelete(filename, token, content) {
    content = typeof content !== 'undefined' ? content : 'content';
    $('.loading').show();
    $.ajax({
        type: 'POST',
        data: {_method: 'DELETE', _token: token},
        url: filename,
        success: function (data) {

            $("#" + content).html(data);
            $('.loading').hide();
            //regions delete

            $(document.body).removeClass('modal-open');
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}