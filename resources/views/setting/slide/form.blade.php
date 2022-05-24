<div class="form-group">
    <label for="nama" class="control-label col-md-4 col-sm-3 col-xs-12">Judul <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('judul', null, ['class' => 'form-control', 'required'=>true, 'id' => 'judul']) !!}
    </div>
</div>
<div class="form-group">
    <label for="nama" class="control-label col-md-4 col-sm-3 col-xs-12">Deskripsi<span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('deskripsi', null, ['class' => 'textarea', 'placeholder' => 'deskripsi', 'style' => 'width: 100%;
        height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;']) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Gambar<span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <input accept="image/*" type="file" id="gambar" name="gambar" class="form-control" @isset($slide->gambar) 'required' @endisset>
        <code>Dimensi gambar 1360 x 400 Piksel</code>
        <br>
            <img src="@if(isset($slide->gambar)) {{ asset($slide->gambar) }} @else {{ "http://placehold.it/1000x600" }} @endif"  id="showgambar"
            style="max-width:400px;max-height:250px;float:left;"/>
    </div>
</div>
<div class="ln_solid"></div>
