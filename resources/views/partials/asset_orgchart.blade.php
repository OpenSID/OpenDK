@push('css')
    <link rel="stylesheet" href="{{ asset('css/bagan.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/orgchart/css/jquery.orgchart.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('bower_components/orgchart/js/jquery.orgchart.min.js') }}"></script>
    <script src="{{ asset('bower_components/html2canvas/html2canvas.min.js') }}"></script>
@endpush