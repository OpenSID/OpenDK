<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Judul Prosedur <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::text( 'judul_prosedur', null, [ 'class' => 'form-control', 'placeholder' => 'Judul Prosedur', 'required'] ) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">File Prosedur <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        <input type="file" name="file_prosedur" id="file_prosedur" class="form-control" required >
        <br>

        <img class="hide" src="@if(isset($regulasi->file_regulasi)) {{ asset($regulasi->file_regulasi) }} @else {{ "http://placehold.it/1000x600" }} @endif"  id="showgambar" style="max-width:400px;max-height:250px;float:left;"/>

        <object data="" type="application/pdf" width="500" height="400" class="hide" id="showpdf"> </object>

    </div>
</div>
<div class="ln_solid"></div>
