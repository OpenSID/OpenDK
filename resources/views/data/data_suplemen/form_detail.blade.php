{{ Form::hidden('suplemen_id', $suplemen->id) }}
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="penduduk_id">Warga</label>
    
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('penduduk_id', $data, null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('keterangan', null, ['class' => 'textarea', 'placeholder' => 'Keterangan', 'style' => 'width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;']) !!}
    </div>
</div>
<div class="ln_solid"></div>
