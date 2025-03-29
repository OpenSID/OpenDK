@push('css')
    <!-- bootstrap-colorpicker -->
    <link rel="stylesheet" href="{{ asset('css/bagan.css') }}">
@endpush

@push('scripts')
    <!-- bootstrap-colorpicker -->
    <script src="{{ asset('bower_components/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('bower_components/highcharts/sankey.js') }}"></script>
    <script src="{{ asset('bower_components/highcharts/organization.js') }}"></script>
    <script src="{{ asset('bower_components/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('bower_components/highcharts/accessibility.js') }}"></script>

    {{-- <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/sankey.js"></script>
    <script src="https://code.highcharts.com/modules/organization.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script> --}}
@endpush
