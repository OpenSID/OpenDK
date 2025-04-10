@push('css')
    <link rel="stylesheet" href="{{ asset('css/bagan.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('bower_components/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('bower_components/highcharts/sankey.js') }}"></script>
    <script src="{{ asset('bower_components/highcharts/organization.js') }}"></script>
    <script src="{{ asset('bower_components/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('bower_components/highcharts/accessibility.js') }}"></script>
@endpush
