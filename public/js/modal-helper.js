var confirmCallback = null;

function openAlert(message, title, type, onClose) {
    var modal = $('#modal-alert');
    modal.find('.modal-title').text(title || 'Pesan');
    modal.find('.modal-body').text(message || '');
    modal.find('.modal-content').removeClass('modal-danger modal-success modal-warning modal-info');

    if (type) {
        modal.find('.modal-content').addClass('modal-' + type);
    }

    modal.off('hidden.bs.modal.alertCallback');
    if (typeof onClose === 'function') {
        modal.one('hidden.bs.modal.alertCallback', onClose);
    }

    modal.modal('show');
}

function openConfirm(message, title, callback, btnYesText) {
    var modal = $('#modal-confirm');
    modal.find('.modal-title').text(title || 'Konfirmasi');
    modal.find('.modal-body').text(message || '');
    confirmCallback = callback;
    modal.find('#modal-confirm-yes').text(btnYesText || 'Ya');
    modal.modal('show');
}

$(document).on('click', '#modal-confirm-yes', function () {
    $('#modal-confirm').modal('hide');
    if (typeof confirmCallback === 'function') {
        confirmCallback();
    }
});

$(document).on('click', '[data-confirm]', function (e) {
    e.preventDefault();
    var btn = $(this);
    var message = btn.data('confirm');
    var title = btn.data('confirm-title') || 'Konfirmasi';
    var btnText = btn.data('confirm-btn') || 'Ya';
    var form = btn.closest('form');
    var href = btn.attr('href');
    openConfirm(message, title, function () {
        if (form.length) {
            if (form[0].requestSubmit) {
                form[0].requestSubmit(btn[0]);
            } else {
                form.trigger('submit');
            }
        } else if (href) {
            window.location.href = href;
        }
    }, btnText);
});
