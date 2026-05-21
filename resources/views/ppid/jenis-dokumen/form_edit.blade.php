<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('nama', old('nama', $jenis_dokumen->nama))
        ->class('form-control')
        ->placeholder('Nama Jenis Dokumen')
        ->required() !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Slug</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        @if($jenis_dokumen->is_kunci)
        <input type="text" class="form-control" value="{{ $jenis_dokumen->slug }}" disabled>
        <p class="help-block text-danger">Slug terkunci dan tidak dapat diubah.</p>
        @else
        {!! html()->text('slug', old('slug', $jenis_dokumen->slug))
        ->class('form-control')
        ->placeholder('Slug (otomatis dari nama jika kosong)') !!}
        @endif
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Urutan</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->number('urutan', old('urutan', $jenis_dokumen->urutan))
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
                {!! html()->radio('status', $key, old('status', $jenis_dokumen->status))
                ->required() !!} {{ $label }}
            </label>
        </div>
        @endforeach
    </div>
</div>

<div class="ln_solid"></div>
