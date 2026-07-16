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
        openAlert(response, 'Gagal!', 'danger');
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