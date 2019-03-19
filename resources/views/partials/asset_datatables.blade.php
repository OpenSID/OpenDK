@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset ("/bower_components/datatables.net-bs/css/dataTables.bootstrap.css") }}">
@endpush

@push('scripts')
<!-- DataTables -->
<script src="{{ asset ("/bower_components/datatables.net/js/jquery.dataTables.min.js") }}"></script>
<script src="{{ asset ("/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js") }}"></script>
@endpush