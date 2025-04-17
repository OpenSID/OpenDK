<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('nama', $jenis_document->nama?? '', ['placeholder' => 'nama', 'class' => 'form-control', 'required' => true, 'readonly' => false]) !!}                                                                                                                                   
        {!! Form::hidden('id', $jenis_document->id?? '', ['placeholder' => 'nama', 'class' => 'form-control', 'required' => true, 'readonly' => false]) !!}                                                                                                                                   
    </div>
</div>                                                                                                                                                                                                                                              