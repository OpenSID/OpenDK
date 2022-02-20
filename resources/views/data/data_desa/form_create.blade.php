<div class="form-group">
    <label for="website" class="control-label col-md-4 col-sm-3 col-xs-12">Kode / Nama Desa <span class="required">*</span></label>
    <div class="col-md-2 col-sm-2 col-xs-12">
        <input id="desa_id" class="form-control" placeholder="00.00.00.0000" name="desa_id" type="text" required readonly/>
    </div>
    <input id="nama" type="hidden" name="nama"/>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <select class="form-control" id="list_desa" name="desa_id" data-placeholder="Pilih Desa" style="width: 100%;"></select>
    </div>
</div>

<div class="form-group">
    <label for="website" class="control-label col-md-4 col-sm-3 col-xs-12">Website </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::input('url', 'website', null, ['class' => 'form-control',  'id' => 'website', 'placeholder' => 'Contoh : https://berputar.opensid.or.id/']) !!}
    </div>
</div>

<div class="form-group">
    <label for="luas_wilayah" class="control-label col-md-4 col-sm-3 col-xs-12">Luas Wilayah (km<sup>2</sup>)<span class="required">*</span></label>
    <div class="col-md-2 col-sm-2 col-xs-12">
        {!! Form::number('luas_wilayah', 0, ['class' => 'form-control',  'id' => 'luas_wilayah', 'placeholder' => 'Luas Wilayah Desa', 'step' => '0.1']) !!}
    </div>
</div>

<div class="ln_solid"></div>
