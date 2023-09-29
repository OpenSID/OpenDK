@push('css')
    <!-- AmCharts3 -->
    <link rel="stylesheet" href="{{ asset ("/bower_components/amcharts3/amcharts/plugins/export/export.css") }}">
@endpush

@push('scripts')
<!-- AmCharts3 -->
<script src="{{ asset ("/bower_components/amcharts3/amcharts/amcharts.js") }}"></script>
<script src="{{ asset ("/bower_components/amcharts3/amcharts/pie.js") }}"></script>
<script src="{{ asset ("/bower_components/amcharts3/amcharts/serial.js") }}"></script>
<script src="{{ asset ("/bower_components/amcharts3/amcharts/plugins/export/export.min.js") }}"></script>
<script src="{{ asset ("/bower_components/amcharts3/amcharts/plugins/animate/animate.min.js") }}"></script>
<script src="{{ asset ("/bower_components/amcharts3/amcharts/themes/light.js") }}"></script>
@endpush