<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('nama', old('nama'))
        ->class('form-control')
        ->placeholder('Nama Jenis Dokumen')
        ->required() !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Slug</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('slug', old('slug'))
        ->class('form-control')
        ->placeholder('Slug (otomatis dari nama jika kosong)') !!}
        <p class="help-block">Slug akan digenerate otomatis dari nama jika dikosongkan.</p>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Urutan</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->number('urutan', old('urutan'))
        ->class('form-control')
        ->placeholder('Urutan')
        ->min(0) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Status <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        @foreach($status_options as $key => $label)
        <div class="radio">
            <label>
                {!! html()->radio('status', $key, old('status', 'aktif'))
                ->required() !!} {{ $label }}
            </label>
        </div>
        @endforeach
    </div>
</div>

<div class="ln_solid"></div>
