<div class="box-header text-center  with-border bg-blue">
    <h2 class="box-title text-bold" data-toggle="tooltip" data-placement="top" title="Sistem Keluhan Masyarakat">SIKEMA</h2>
</div>
<div class="pad text-bold bg-white">
    @include('pages.komplain._tracking')

    @include('pages.komplain._komplain_populer')

    @include('pages.komplain._komplain_sukses')
</div>
<!-- /.col -->

@push('scripts')
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endpush
