<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Judul Prosedur <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! html()->text('judul_prosedur', old('judul_prosedur'))->class('form-control')->placeholder('Judul Prosedur')->required() !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">File Prosedur <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        <input type="file" name="file_prosedur" id="file_prosedur" class="form-control" accept=".jpg,.jpeg,.png,.gif,.pdf" required>
        <x-upload-hint formats="JPG, JPEG, PNG, GIF, PDF" />
        <div class="clearfix"></div>
        <br>
        <img class="hide" id="showgambar" style="max-width:400px;max-height:250px;float:left;" />
        <iframe src="" class="showpdf hide" id="showpdf" style="width: 100%; height: 400px; border: none;"></iframe>

    </div>
</div>
<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\ProsedurRequest', '#form-prosedur') !!}
@endpush
