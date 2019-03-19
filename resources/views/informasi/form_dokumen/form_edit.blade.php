<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Dokumen <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text( 'nama_dokumen', null, [ 'class' => 'form-control', 'placeholder' => 'Nama Dokumen'] ) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">File Dokumen</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="file" name="file_dokumen" id="file_prosedur" class="form-control" >
        <br>
        @if(! $dokumen->file_dokumen == '') <a class="btn btn-sm btn-primary" href="{{ asset($dokumen->file_dokumen) }}">Download File</a>@endif
    </div>
</div>
<div class="ln_solid"></div>
