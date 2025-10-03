<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('nama')
        ->value(old('nama', isset($medsos) ? $medsos->nama : ''))
        ->placeholder('Nama')
        ->class('form-control')
        ->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">URL <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('url')
        ->value(old('url', isset($medsos) ? $medsos->url : ''))
        ->placeholder('Link URL')
        ->class('form-control')
        ->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Logo / Gambar <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->file('logo')
        ->accept('image/jpeg,image/png,image/jpg')
        ->class('form-control')
        ->attributes($medsos ? [] : ['required' => 'required']) !!}
        <br>
        <img src="{{ isset($medsos->logo) ? asset($medsos->logo) : 'http://placehold.co/1000x600' }}" id="showgambar"
            style="max-width:400px;max-height:250px;float:left;" />
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->select('status', ['1' => 'Aktif', '0' => 'Tidak Aktif'])
        ->value(old('status', isset($medsos) ? $medsos->status : ''))
        ->class('form-control') !!}
    </div>
</div>
<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')

@push('scripts')
{!! JsValidator::formRequest('App\Http\Requests\MediaSosialRequest', '#form-media-sosial') !!}
@endpush