<div class="box-header text-center  with-border bg-blue">
    <h2 class="box-title text-bold" data-toggle="tooltip" data-placement="top" title="Sistem Keluhan Masyarakat">SIKEMA</h2>
</div>
   <div class="pad text-bold bg-white" >
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