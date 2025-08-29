<style>
    /* custom error css order */
    #lembaga_kategori_id-error,
    #penduduk_id-error {
        order: 2
    }
</style>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Lembaga <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('nama')->value(old('nama', isset($lembaga)->value(old('nama', isset($lembaga) ? $lembaga->nama
        : '')) ? $lembaga->nama : ''))->class('form-control')->required()->placeholder('Nama Lembaga')
        !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Lembaga <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('kode')->value(old('kode', isset($lembaga)->value(old('kode', isset($lembaga) ? $lembaga->kode
        : '')) ? $lembaga->kode : ''))->class('form-control')->required()->placeholder('Kode Lembaga')
        !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Kategori Lembaga <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <div style="display: flex; flex-direction: column;">
            {!! html()->select('lembaga_kategori_id', $kategoriLembagaList)->class('form-control
            select2')->placeholder('Pilih Kategori
            Lembaga')->value(old('lembaga_kategori_id', isset($lembaga)->value(old('lembaga_kategori_id',
            isset($lembaga) ? $lembaga->lembaga_kategori_id : '')) ? $lembaga->lembaga_kategori_id : '')) !!}
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Ketua Lembaga <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <div style="display: flex; flex-direction: column;">
            {!! html()->select('penduduk_id', $pendudukList)->class('form-control select2')->placeholder('Pilih Ketua
            Lembaga')->value(old('penduduk_id', isset($lembaga)->value(old('penduduk_id', isset($lembaga) ?
            $lembaga->penduduk_id : '')) ? $lembaga->penduduk_id : '')) !!}
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi Lembaga</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->textarea('keterangan')->value(old('keterangan', isset($lembaga)->value(old('keterangan',
        isset($lembaga) ? $lembaga->keterangan : '')) ? $lembaga->keterangan :
        ''))->class('form-control')->placeholder('Deskripsi Lembaga')->rows(2) !!}
    </div>
</div>

<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')
@include('partials.asset_select2')

@push('scripts')
{!! JsValidator::formRequest('App\Http\Requests\UpdateLembagaRequest', '#form-lembaga') !!}
@endpush