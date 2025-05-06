<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('nama', null, ['placeholder' => 'Nama', 'class' => 'form-control', 'required' => true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">URL <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('url', null, ['placeholder' => 'Link URL', 'class' => 'form-control', 'required' => true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Logo / Gambar <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="file" name="logo" id="logo" accept="jpg,jpeg,png" class="form-control" {{ $medsos ? '' : 'required' }}>
        <br>

        <img src="@if (isset($medsos->logo)) {{ asset($medsos->logo) }} @else {{ 'http://placehold.co/1000x600' }} @endif" id="showgambar" style="max-width:400px;max-height:250px;float:left;" />

    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('status', ['1' => 'Aktif', '0' => 'Tidak Aktif'], null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\MediaSosialRequest', '#form-media-sosial') !!}
@endpush
