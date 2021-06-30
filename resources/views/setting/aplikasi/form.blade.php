<div class="form-group">
    <label for="key" class="control-label col-md-4 col-sm-3 col-xs-12">Kata Kunci Pengaturan <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('key', null, ['class' => 'form-control', 'required'=>true, 'id'=>'key']) !!}
    </div>
</div>
<div class="form-group">
    <label for="value" class="control-label col-md-4 col-sm-3 col-xs-12">Nilai Pengaturan <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        @if($aplikasi->type == 'textarea')
        {!! Form::textarea('value', null, ['class' => 'form-control', 'required'=>true, 'id'=>'value']) !!}
        @else
        {!! Form::text('value', null, ['class' => 'form-control', 'required'=>true, 'id'=>'value']) !!}
        @endif
        @if($aplikasi->isBrowserTitle())
        <p class="text-info">Jika kosong akan diubah dengan judul standar <strong>{{ $default_browser_title }}</strong>.</p>
        @endif
        <small></small>
    </div>
</div>
<div class="form-group">
    <label for="description" class="control-label col-md-4 col-sm-3 col-xs-12">Deskripsi <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('description', null, ['class' => 'form-control', 'required'=>true, 'id'=>'description']) !!}
    </div>
</div>
<div class="form-group">
    <label for="kategori" class="control-label col-md-4 col-sm-3 col-xs-12">Kategori <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('kategori', null, ['class' => 'form-control', 'required'=>true, 'id'=>'kategori']) !!}
    </div>
</div>
<div class="form-group">
    <label for="type" class="control-label col-md-4 col-sm-3 col-xs-12">Tipe Form <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('type', ['input' => 'input', 'textarea' => 'textarea'], null, ['class' => 'form-control', 'required'=>true, 'id'=>'type']) !!}
    </div>
</div>
<div class="form-group">
    <label for="option" class="control-label col-md-4 col-sm-3 col-xs-12">Opsi Pengaturan <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('option', null, ['class' => 'form-control', 'required'=>true, 'id'=>'option']) !!}
    </div>
</div>
<div class="ln_solid"></div>
