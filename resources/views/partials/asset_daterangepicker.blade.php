@push('css')
    <!-- Bootstrap Datetimepicker -->
    <link rel="stylesheet" href="{{ asset ("/bower_components/bootstrap-daterangepicker/daterangepicker.css") }}">
@endpush

@push('scripts')
<!-- Bootstrap Datetimepicker -->
<script src="{{ asset ("/bower_components/moment/min/moment.min.js") }}"></script>
<script src="{{ asset ("/bower_components/bootstrap-daterangepicker/daterangepicker.js") }}"></script>
@endpush