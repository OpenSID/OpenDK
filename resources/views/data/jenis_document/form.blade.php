<div class="container-fluid">
    <div class="form-group">
        <label class="control-label">Nama <span class="required">*</span></label>
    
        <div class="">
            {!! Form::text('nama', $jenis_document->nama?? '', ['placeholder' => 'nama', 'class' => 'form-control', 'required' => true, 'readonly' => false]) !!}                                                                                                                                   
            {!! Form::hidden('id', $jenis_document->id?? '', ['placeholder' => 'nama', 'class' => 'form-control', 'required' => true, 'readonly' => false]) !!}                                                                                                                                   
        </div>
    </div>                                                                                                                                                                                                                                              
</div>