@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/bower_components/datatables.net-bs/css/dataTables.bootstrap.css') }}">
@endpush

@push('scripts')
    <!-- DataTables -->
    <script src="{{ asset('/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $.extend($.fn.dataTable.defaults, {
            language: {
                url: "{{ asset('/bower_components/datatables.net/i18n/id.json') }}"
            },
            searchDelay: 500,
        });
        $(document).on('init.dt', function(e, settings) {
            if (e.namespace !== 'dt') return;

            var table = new $.fn.dataTable.Api(settings);
            var searchDelay = table.init().searchDelay || 1500;
            var searchInput = $('div.dataTables_filter input', table.table().container());
            var debounceTimer = null;
            var previousSearch = null;

            searchInput.off('keyup.DT input.DT search.DT keydown.DT');

            searchInput.on('keyup input', function() {
                var currentValue = this.value;

                if (previousSearch === currentValue) return;

                previousSearch = currentValue;

                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(function() {
                    if (table.search() !== currentValue) {
                        table.search(currentValue).draw();
                    }
                }, searchDelay);
            });
        });
    </script>
@endpush
