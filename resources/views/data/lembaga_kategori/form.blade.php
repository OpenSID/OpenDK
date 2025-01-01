<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Klasifikasi/Kategori Lembaga <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('nama', null, ['placeholder' => 'Kategori Lembaga', 'class' => 'form-control', 'required' => true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi Lembaga</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('deskripsi', null, ['placeholder' => 'Deskripsi Lembaga', 'class' => 'form-control', 'rows' => 2]) !!}
    </div>
</div>

<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\KategoriLembagaRequest', '#form-kategori-lembaga') !!}
@endpush
