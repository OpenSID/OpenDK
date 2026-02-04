<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Kategori <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('nama_kategori')
        ->value(old('nama_kategori', isset($kategori) ? $kategori->nama_kategori : null))
        ->placeholder('Nama Kategori')
        ->class('form-control')
        ->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->select('status', ['Ya' => 'Aktif', 'Tidak' => 'Tidak Aktif'], old('status', isset($kategori) ?
        $kategori->status : null))
        ->class('form-control') !!}
    </div>
</div>
<div class="ln_solid"></div>