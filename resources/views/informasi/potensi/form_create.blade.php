<div class="form-group">
    <label for="kategori_id" class="control-label col-md-4 col-sm-3 col-xs-12">Kategori<span class="required">*</span></label>

    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::select('kategori_id', \App\Models\TipePotensi::pluck('nama_kategori', 'id'), null,['placeholder'=>'-Pilih', 'class'=>'form-control', 'id'=>'kategori_id', 'required'=>true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Nama Potensi <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::text( 'nama_potensi', null, [ 'class' => 'form-control', 'placeholder' => 'Nama Potensi', 'required'] ) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Deskripsi <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::textArea( 'deskripsi', null, [ 'class' => 'form-control', 'placeholder' => 'Deskripsi', 'required'] ) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Lokasi <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::text( 'lokasi', null, [ 'class' => 'form-control', 'placeholder' => 'Lokasi', 'required'] ) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Gambar <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        <input type="file" name="file_gambar" id="file_gambar" class="form-control" required accept="image/*">
        <br>
        <img src="http://placehold.it/1000x600" id="showgambar"
             style="max-width:400px;max-height:250px;float:left;"/>
    </div>
</div>
<div class="ln_solid"></div>
