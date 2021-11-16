<div class="form-group">
    <label for="sasaran" class="control-label col-md-4 col-sm-3 col-xs-12">Sasaran <span class="required">*</span></label>

    <div class="col-md-4 col-sm-6 col-xs-12">
        {!! Form::select('sasaran', [1=> 'Penduduk/Perorangan', 2=> 'Keluarga - KK'], null, ['class' => 'form-control', 'required'=>true, 'id' => 'sasaran']) !!}
    </div>
</div>
<div class="form-group">
    <label for="nama_program" class="control-label col-md-4 col-sm-3 col-xs-12">Nama Program <span class="required">*</span></label>

    <div class="col-md-4 col-sm-6 col-xs-12">
        {!! Form::text('nama', null, ['placeholder' => 'Nama Program', 'required' => 'required', 'class' => 'form-control', 'id' => 'nama_program']) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Rentang Waktu Program <span class="required">*</span></label>

    <div class="row">
        <div class="col-md-2 col-sm-2 col-xs-3">
            {!! Form::text('start_date', null, ['placeholder' => 'Tanggal Mulai','class' => 'form-control datepicker', 'required'=>true]) !!}
        </div>
        <div class="col-md-2 col-sm-2 col-xs-3">
            {!! Form::text('end_date', null, ['placeholder' => 'Tanggal Selesai','class' => 'form-control datepicker', 'required'=>true]) !!}
        </div>
    </div>
</div>
<div class="form-group">
    <label for="description" class="control-label col-md-4 col-sm-3 col-xs-12">Keterangan</label>

    <div class="col-md-4 col-sm-6 col-xs-12">
        {!! Form::textarea('description', null, ['rows'=>2, 'class' => 'textarea', 'placeholder' => 'Keterangan', 'class' => 'form-control', 'id' => 'description']) !!}
    </div>
</div>
<div class="ln_solid"></div>
