<div class="form-group">
    <label for="siswa_paud" class="control-label col-md-4 col-sm-3 col-xs-12">Siswa PAUD <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('siswa_paud', null, ['class' => 'form-control', 'required' => true, 'id'=>'siswa_paud']) !!}
    </div>
</div>
<div class="form-group">
    <label for="anak_usia_paud" class="control-label col-md-4 col-sm-3 col-xs-12">Anak Usia PAUD <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('anak_usia_paud', null, ['class' => 'form-control', 'required' => true, 'id'=>'anak_usia_paud']) !!}
    </div>
</div>
<div class="form-group">
    <label for="siswa_sd" class="control-label col-md-4 col-sm-3 col-xs-12">Siswa SD <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('siswa_sd', null, ['class' => 'form-control', 'required' => true, 'id'=>'siswa_sd']) !!}
    </div>
</div>
<div class="form-group">
    <label for="anak_usia_sd" class="control-label col-md-4 col-sm-3 col-xs-12">Anak Usia SD <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('anak_usia_sd', null, ['class' => 'form-control', 'required' => true, 'id'=>'anak_usia_sd']) !!}
    </div>
</div>
<div class="form-group">
    <label for="siswa_smp" class="control-label col-md-4 col-sm-3 col-xs-12">Siswa SMP <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('siswa_smp', null, ['class' => 'form-control', 'required' => true, 'id'=>'siswa_smp']) !!}
    </div>
</div>
<div class="form-group">
    <label for="anak_usia_smp" class="control-label col-md-4 col-sm-3 col-xs-12">Anak Usia SMP <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('anak_usia_smp', null, ['class' => 'form-control', 'required' => true, 'id'=>'anak_usia_smp']) !!}
    </div>
</div>
<div class="form-group">
    <label for="siswa_sma" class="control-label col-md-4 col-sm-3 col-xs-12">Siswa SMA <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('siswa_sma', null, ['class' => 'form-control', 'required' => true, 'id'=>'siswa_sma']) !!}
    </div>
</div>
<div class="form-group">
    <label for="anak_usia_sma" class="control-label col-md-4 col-sm-3 col-xs-12">Anak Usia SMA <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('anak_usia_sma', null, ['class' => 'form-control', 'required' => true, 'id'=>'anak_usia_sma']) !!}
    </div>
</div>
<div class="form-group">
    <label for="bulan" class="control-label col-md-4 col-sm-3 col-xs-12">Bulan</label>

    <div class="col-md-8">
        <select class="form-control" id="bulan" name="bulan">
            @foreach(months_list() as $key=> $month)
                @if($siswa->bulan == $key)
                <option selected value="{{$key}}">{{$month}}</option>
                @else
                <option value="{{$key}}">{{$month}}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label for="list_year" class="control-label col-md-4 col-sm-3 col-xs-12">Tahun</label>

    <div class="col-md-8">
        <select class="form-control" id="list_year" name="tahun">
            @foreach(years_list() as $year)
                @if($siswa->tahun == $year)
                <option selected value="{{$year}}">{{$year}}</option>
                @else
                <option value="{{$year}}">{{$year}}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

<div class="ln_solid"></div>
