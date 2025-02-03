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
        {!! Form::text('nama', null, ['placeholder' => 'Nama Lembaga', 'class' => 'form-control', 'required' => true]) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Lembaga <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('kode', null, ['placeholder' => 'Kode Lembaga', 'class' => 'form-control', 'required' => true]) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Kategori Lembaga <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <div style="display: flex; flex-direction: column;">
            {!! Form::select('lembaga_kategori_id', $kategoriLembagaList, null, ['placeholder' => 'Pilih Kategori Lembaga', 'class' => 'form-control select2', 'required' => true, 'style' => 'width:100%;']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Ketua Lembaga <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <div style="display: flex; flex-direction: column;">
            {!! Form::select('penduduk_id', $pendudukList, null, ['class' => 'form-control select2', 'placeholder' => 'Pilih Ketua Lembaga', 'required' => true, 'style' => 'width:100%;']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi Lembaga</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('keterangan', null, ['placeholder' => 'Deskripsi Lembaga', 'class' => 'form-control', 'rows' => 2]) !!}
    </div>
</div>

<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')
@include('partials.asset_select2')

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\StoreLembagaRequest', '#form-lembaga') !!}
@endpush
