<div class="box-header text-center  with-border">
    <h2 class="box-title" data-toggle="tooltip" data-placement="top" title="Sistem Keluhan Masyarakat">SIKEMA</h2>
</div>
   <div class="pad text-bold" >
    @include('sistem_komplain.komplain._tracking')

            @include('sistem_komplain.komplain._komplain_populer')

            @include('sistem_komplain.komplain._komplain_sukses')
    </div>
<!-- /.col -->

@push('scripts')
<script>
    $(function () {
    $('[data-toggle="tooltip"]').tooltip()
    })
</script>
@endpush