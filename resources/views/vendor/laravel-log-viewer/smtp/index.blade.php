<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Pengaturan SMTP</h3>
            </div>
            {!! Form::open(['route' => 'setting.info-sistem.store-email-smtp', 'method' => 'post', 'id' => 'form-smtp', 'class' => 'form-horizontal form-label-left']) !!}
            <div class="box-body">
                @include('vendor.laravel-log-viewer.smtp.form')
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-save"></i> Simpan</button>
                <button class="btn btn-danger btn-sm pull-right"><i class="fa fa-envelope-o"></i> Tes Email</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    
</script>
@endpush
