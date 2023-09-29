@push('css')
    <!-- Bootstrap Datetimepicker -->
    <link rel="stylesheet" href="{{ asset ("/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css") }}">
@endpush

@push('scripts')
<!-- Bootstrap Datetimepicker -->
<script src="{{ asset ("/bower_components/moment/min/moment.min.js") }}"></script>
<script src="{{ asset ("/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js") }}"></script>
@endpush