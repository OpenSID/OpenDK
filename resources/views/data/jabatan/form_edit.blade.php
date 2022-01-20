<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Jabatan<span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('nama_jabatan', null, ['class' => 'form-control', 'required'=>true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Parent Jabatan <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('parent_id', $parent_jabatan, null, ['placeholder' => 'Pilih Parent Jabatan', 'class' => 'form-control']) !!}
    </div>
</div>
<div class="ln_solid"></div>
