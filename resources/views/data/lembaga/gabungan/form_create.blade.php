@include('partials.asset_select2')
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Lembaga <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('nama')->value(old('nama', isset($lembaga) ? $lembaga->nama : ''))->class('form-control')->required()->placeholder('Nama Lembaga') !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Lembaga <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('kode')->value(old('kode', isset($lembaga) ? $lembaga->kode : ''))->class('form-control')->required()->placeholder('Kode Lembaga') !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Kategori Lembaga <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <select name="lembaga_kategori_id" class="form-control select2" data-width="100%" required>
            <option value="">Pilih Kategori Lembaga</option>
            @foreach(\App\Models\KategoriLembaga::orderBy('nama')->get() as $kategori)
            <option value="{{ $kategori->id }}" {{ old('lembaga_kategori_id', isset($lembaga) ? $lembaga->lembaga_kategori_id : '') == $kategori->id ? 'selected' : '' }}>
                {{ $kategori->nama }}
            </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Ketua Lembaga <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <x-penduduk-gabungan-select
            name="penduduk_id_gabungan"
            required />
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi Lembaga</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->textarea('keterangan')->value(old('keterangan', isset($lembaga) ? $lembaga->keterangan : ''))->class('form-control')->placeholder('Deskripsi Lembaga')->rows(2) !!}
    </div>
</div>

<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')