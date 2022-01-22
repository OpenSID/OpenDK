<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Nama Pelapak <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::text( 'pelapak', null, [ 'class' => 'form-control', 'placeholder' => 'Nama Pelapak', 'required'] ) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Kontak Whatsapp Pelapak <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::text( 'kontak_pelapak', null, [ 'class' => 'form-control', 'placeholder' => 'Kontak WhatsApp Pelapak (62)', 'required'] ) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Nama Produk<span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::text( 'produk', null, [ 'class' => 'form-control', 'placeholder' => 'Nama Produk', 'required'] ) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Kategori Produk<span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        <select name="kategori_produk" id="kategori_produk" class="form-control">
            <option value="makanan">Makanan</option>
            <option value="elektronik">Elektronik</option>
            <option value="otomotif">Otomotif</option>

        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Harga<span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::text( 'harga', null, [ 'class' => 'form-control', 'placeholder' => 'Harga Produk', 'required'] ) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Satuan<span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::text( 'satuan', null, [ 'class' => 'form-control', 'placeholder' => 'Satuan', 'required'] ) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Potongan<span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::text( 'potongan', null, [ 'class' => 'form-control', 'placeholder' => 'Potongan Harga', 'required'] ) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Stok<span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::text( 'stok', null, [ 'class' => 'form-control', 'placeholder' => 'Stok', 'required'] ) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Deskripsi Produk<span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        {!! Form::textarea( 'deskripsi_produk', null, [ 'class' => 'form-control', 'placeholder' => 'Deskripsi Produk', 'required'] ) !!}
    </div>
</div>
<div class="ln_solid"></div>
