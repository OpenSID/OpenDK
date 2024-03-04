<div class="form-group">
    <label for="website" class="control-label col-md-4 col-sm-3 col-xs-12">Kode / Nama Desa <span class="required">*</span></label>
    <div class="col-md-2 col-sm-2 col-xs-12">
        <input
            id="desa_id"
            name="desa_id"
            class="form-control"
            placeholder="00.00.00.0000"
            type="text"
            @if ($status_pantau) readonly @endif
            value="{{ $desa->desa_id }}"
        />
    </div>
    <input id="nama" type="hidden" name="nama" value="{{ $desa->nama }}" />
    <div class="col-md-4 col-sm-4 col-xs-12">
        @if ($status_pantau)
            <select class="form-control" id="list_desa" name="desa_id" data-placeholder="Pilih Desa" style="width: 100%;">
                <option selected value="" disabled>Pilih Desa</option>
                @if ($desa->desa_id || $desa->nama)
                    <option selected value="{{ $desa->desa_id }}">{{ $desa->nama }}</option>
                @endif
            </select>
        @else
            <input type="text" class="form-control" value="{{ $desa->nama }}" name="nama">
        @endif
    </div>
</div>

<div class="form-group">
    <label for="website" class="control-label col-md-4 col-sm-3 col-xs-12">Sebutan Desa </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::input('sebutan_desa', 'sebutan_desa', $desa->sebutan_desa, ['class' => 'form-control', 'placeholder' => 'Contoh : desa']) !!}
    </div>
</div>

<div class="form-group">
    <label for="website" class="control-label col-md-4 col-sm-3 col-xs-12">Website </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::input('url', 'website', $desa->website, ['class' => 'form-control', 'id' => 'website', 'placeholder' => 'Contoh : https://berputar.opensid.or.id/']) !!}
    </div>
</div>

<div class="form-group">
    <label for="luas_wilayah" class="control-label col-md-4 col-sm-3 col-xs-12">Luas Wilayah (km<sup>2</sup>)<span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('luas_wilayah', $desa->luas_wilayah ?? 0, ['class' => 'form-control', 'id' => 'luas_wilayah', 'placeholder' => 'Luas Wilayah Desa', 'step' => '0.1']) !!}
    </div>
</div>

<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\DesaRequest', '#form-datadesa') !!}
@endpush
