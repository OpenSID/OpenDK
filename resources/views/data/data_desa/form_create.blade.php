<div class="form-group">
    <label for="desa_id" class="control-label col-md-4 col-sm-3 col-xs-12">Kode Wilayah Desa <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <?php $coba = config('app.default_profile'); ?>
        {!! Form::text('desa_id', null, ['class' => 'form-control', 'required' => true, 'id'=>'desa_id', 'placeholder'=>'Kode Wilayah Desa, Contoh : ' . config('app.default_profile') . '.xxxx', 'minlength' => 13, 'maxlength' => 13]) !!}
    </div>
</div>
<div class="form-group">
    <label for="nama" class="control-label col-md-4 col-sm-3 col-xs-12">Nama <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('nama', null, ['class' => 'form-control', 'required' => true, 'id'=>'nama', 'placeholder'=>'Nama Desa']) !!}
    </div>
</div>
<div class="form-group">
    <label for="website" class="control-label col-md-4 col-sm-3 col-xs-12">Website </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
         {!! Form::text('website', null, ['class' => 'form-control',  'id'=>'website', 'placeholder'=>'Website Desa']) !!}
    </div>
</div>
<div class="form-group">
    <label for="luas_wilayah" class="control-label col-md-4 col-sm-3 col-xs-12">Luas Wilayah (km<sup>2</sup>)<span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
         {!! Form::number('luas_wilayah', null, ['class' => 'form-control',  'id'=>'luas_wilayah', 'required' => true, 'placeholder'=>'Luas Wilayah Desa']) !!}
    </div>
</div>

<div class="ln_solid"></div>
@include('partials.asset_select2')
@push('scripts')
<script>
    $(function () {

        // Select 2 Kecamatan
        $('#kecamatan_id').select2();
    });
</script>
@endpush
