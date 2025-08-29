<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sasaran">Sasaran Data</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->select('sasaran', ['1' => 'Penduduk', '2' =>
        'Keluarga/KK'])->class('form-control')->value(old('sasaran', isset($suplemen) ? $suplemen->sasaran : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Data Suplemen <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('nama')->class('form-control')->required()->placeholder('Nama Data
        Suplemen')->value(old('nama', isset($suplemen) ? $suplemen->nama : '')) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->textarea('keterangan')->class('textarea')->placeholder('Keterangan')->value(old('keterangan',
        isset($suplemen) ? $suplemen->keterangan : '')) !!}
    </div>
</div>
<div class="ln_solid"></div>