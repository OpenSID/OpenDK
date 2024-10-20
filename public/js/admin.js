$(function() {
    // AJAX Setup Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

function errorValidation(response) {
    var errors = response.responseJSON?.errors;

    if (!errors) {
        Swal.fire({
            title: 'Gagal!',
            text: response,
            icon: 'error',
            confirmButtonText: 'OK',
            timer: 1500
        });
    } else {
        $.each(errors, function(key, value) {
            $('#' + key)
                .closest('.form-group')
                .addClass('has-error')
                .find('.help-block').remove();

            $('<span class="help-block"><strong>' + value + '</strong></span>').insertAfter('#' + key);
        });
    }
}

$('#modal-form').on('input', 'input', function() {
    $(this).closest('.form-group').removeClass('has-error').find('.help-block').remove();
});

$('#modal-form').on('show.bs.modal', function() {
    $(this).find('.form-group').removeClass('has-error').find('.help-block').remove();
});