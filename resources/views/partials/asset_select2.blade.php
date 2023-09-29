@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset ("/bower_components/select2/dist/css/select2.min.css") }}">
@endpush

@push('scripts')
<!-- Select2 -->
<script src="{{ asset ("/bower_components/select2/dist/js/select2.full.min.js") }}"></script>
<script src="{{ asset ("/bower_components/select2/dist/js/i18n/en.js") }}"></script>
@endpush