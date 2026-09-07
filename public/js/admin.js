$(function() {
    // AJAX Setup Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Fix dropdown clipping inside scrollable DataTables and responsive tables
    $(document).on('shown.bs.dropdown', '.table-responsive, .dataTables_scrollBody', function(e) {
        var $dropdown = $(e.target);
        var $menu = $dropdown.find('.dropdown-menu');
        if (!$menu.length) return;

        $('body').append($menu.detach());

        function updateDropdownPosition() {
            if (!$dropdown.is(':visible') || !$dropdown.hasClass('open')) {
                $dropdown.trigger('hide.bs.dropdown');
                return;
            }
            var offset = $dropdown.offset();
            var menuWidth = $menu.outerWidth();
            var left = offset.left;

            if (left + menuWidth > $(window).width()) {
                left = offset.left + $dropdown.outerWidth() - menuWidth;
            }

            $menu.css({
                display: 'block',
                position: 'absolute',
                top: (offset.top + $dropdown.outerHeight()) + 'px',
                left: left + 'px',
                zIndex: 1060
            });
        }

        updateDropdownPosition();
        $dropdown.data('detached-dropdown-menu', $menu);

        $(window).on('scroll.dropdown_fix resize.dropdown_fix', updateDropdownPosition);
        $('.dataTables_scrollBody, .table-responsive').on('scroll.dropdown_fix', updateDropdownPosition);
    });

    $(document).on('hide.bs.dropdown', '.table-responsive, .dataTables_scrollBody', function(e) {
        var $dropdown = $(e.target);
        var $menu = $dropdown.data('detached-dropdown-menu');
        if ($menu && $menu.length) {
            $dropdown.append($menu.detach());
            $menu.css({
                display: '',
                position: '',
                top: '',
                left: '',
                zIndex: ''
            });
            $dropdown.removeData('detached-dropdown-menu');
        }
        $(window).off('.dropdown_fix');
        $('.dataTables_scrollBody, .table-responsive').off('.dropdown_fix');
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