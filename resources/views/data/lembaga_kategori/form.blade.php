<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Klasifikasi/Kategori Lembaga <span
            class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('nama')->value(old('nama', isset($kategori_lembaga) ? $kategori_lembaga->nama :
        ''))->class('form-control')->required()->placeholder('Kategori Lembaga') !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi Lembaga</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->textarea('deskripsi')->value(old('deskripsi', isset($kategori_lembaga) ?
        $kategori_lembaga->deskripsi : ''))->class('form-control')->placeholder('Deskripsi Lembaga')->rows(2) !!}
    </div>
</div>

<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')

@push('scripts')
{!! JsValidator::formRequest('App\Http\Requests\KategoriLembagaRequest', '#form-kategori-lembaga') !!}
@endpush