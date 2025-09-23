<div class="form-group">
    <label for="website" class="control-label col-md-4 col-sm-3 col-xs-12">Kode / Nama
        {{ config('setting.sebutan_desa') }} <span class="required">*</span></label>
    <div class="col-md-2 col-sm-2 col-xs-12">
        <input id="desa_id" class="form-control" placeholder="00.00.00.0000" name="desa_id" type="text" required @if($status_pantau) readonly @endif />
    </div>
    <input id="nama" type="hidden" name="nama" />
    <div class="col-md-4 col-sm-4 col-xs-12">
        @if ($status_pantau)
        <select class="form-control" id="list_desa" name="desa_id"
            data-placeholder="Pilih {{ config('setting.sebutan_desa') }}" style="width: 100%;"></select>
        @else
        <input type="text" class="form-control" name="nama">
        @endif
    </div>
</div>

<div class="form-group">
    <label for="sebutan_desa" class="control-label col-md-4 col-sm-3 col-xs-12">Sebutan Desa </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('sebutan_desa')
            ->class('form-control')
            ->placeholder('Contoh : desa')
            ->id('sebutan_desa')
            ->value(old('sebutan_desa', isset($desa) ? $desa->sebutan_desa : '')) !!}
    </div>
</div>

<div class="form-group">
    <label for="website" class="control-label col-md-4 col-sm-3 col-xs-12">Website </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('website')
            ->class('form-control')
            ->id('website')
            ->placeholder('Contoh : https://berputar.opensid.or.id/')
            ->value(old('website', isset($desa) ? $desa->website : '')) !!}
    </div>
</div>

<div class="form-group">
    <label for="luas_wilayah" class="control-label col-md-4 col-sm-3 col-xs-12">Luas Wilayah (km<sup>2</sup>)<span class="required">*</span></label>
    <div class="col-md-2 col-sm-2 col-xs-12">
        {!! html()->number('luas_wilayah')
            ->class('form-control')
            ->id('luas_wilayah')
            ->placeholder('Luas Wilayah Desa')
            ->value(old('luas_wilayah', isset($desa) ? $desa->luas_wilayah : '')) !!}
    </div>
</div>

<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')

@push('scripts')
{!! JsValidator::formRequest('App\Http\Requests\DesaRequest', '#form-datadesa') !!}
@endpush