<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Judul Prosedur <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text( 'judul_prosedur', null, [ 'class' => 'form-control', 'placeholder' => 'Judul Prosedur'] ) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">File Prosedur</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="file" name="file_prosedur" id="file_prosedur" class="form-control"/>
        <br/>

        @if(isset($prosedur->file_prosedur) && $prosedur->mime_type != 'pdf')
            <img class="" src="@if(isset($prosedur->file_prosedur)) {{ asset($prosedur->file_prosedur) }} @else {{ "http://placehold.it/1000x600" }} @endif" id="showgambar" style="width:400px;max-height:250px;float:left;"/>
        @endif

        @if(isset($prosedur->file_prosedur) && $prosedur->mime_type == 'pdf')
            <object data="@if(isset($prosedur->file_prosedur)) {{ asset($prosedur->file_prosedur) }} @endif" type="application/pdf" width="500" height="400" class="" id="showpdf"> </object>
        @endif

        
        <img class="hide" src="@if(isset($prosedur->file_prosedur)) {{ asset($prosedur->file_prosedur) }} @else {{ "http://placehold.it/1000x600" }} @endif"  id="showgambar" style="max-width:400px;max-height:250px;float:left;"/>

        <object data="@if(isset($prosedur->file_prosedur)) {{ asset($prosedur->file_prosedur) }} @endif" type="application/pdf" width="500" height="400" class="hide" id="showpdf"> </object>

    </div>
</div>
<div class="ln_solid"></div>
