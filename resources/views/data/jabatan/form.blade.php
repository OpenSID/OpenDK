<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Jabatan <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Html::text('nama')->value(old('nama', isset($jabatan) ? $jabatan->nama : ''))->class('form-control')->required()->placeholder('Nama Jabatan')
        !!}
        {{ Html::hidden('jenis')->value(old('jenis', isset($jabatan) ? $jabatan->jenis : '')) }}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tupoksi</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Html::textarea('tupoksi')->value(old('tupoksi', isset($jabatan) ? $jabatan->tupoksi : ''))->class('textarea')->placeholder('Tupoksi') !!}
    </div>
</div>
<div class="ln_solid"></div>
@include('partials.asset_jqueryvalidation')

@push('scripts')
{!! JsValidator::formRequest('App\Http\Requests\JabatanRequest', '#form-jabatan') !!}
@endpush