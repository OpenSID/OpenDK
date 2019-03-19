<div class="form-group">
    <label for="tidak_tamat_sekolah" class="control-label col-md-4 col-sm-3 col-xs-12">Tidak Tamat Sekolah <span
                class="required">*</span></label>

    <div class="col-md-2 col-sm-6 col-xs-12">
        {!! Form::number('tidak_tamat_sekolah', null, ['class' => 'form-control', 'required' => true, 'id'=>'tidak_tamat_sekolah']) !!}
    </div>
</div>

<div class="form-group">
    <label for="tamat_sd" class="control-label col-md-4 col-sm-3 col-xs-12">Tamat SD <span
                class="required">*</span></label>

    <div class="col-md-2 col-sm-6 col-xs-12">
        {!! Form::number('tamat_sd', null, ['class' => 'form-control', 'required' => true, 'id'=>'tamat_sd']) !!}
    </div>
</div>

<div class="form-group">
    <label for="tamat_smp" class="control-label col-md-4 col-sm-3 col-xs-12">Tamat SMP <span
                class="required">*</span></label>

    <div class="col-md-2 col-sm-6 col-xs-12">
        {!! Form::number('tamat_smp', null, ['class' => 'form-control', 'required' => true, 'id'=>'tamat_smp']) !!}
    </div>
</div>

<div class="form-group">
    <label for="tamat_sma" class="control-label col-md-4 col-sm-3 col-xs-12">Tamat SMA <span
                class="required">*</span></label>

    <div class="col-md-2 col-sm-6 col-xs-12">
        {!! Form::number('tamat_sma', null, ['class' => 'form-control', 'required' => true, 'id'=>'tamat_sma']) !!}
    </div>
</div>

<div class="form-group">
    <label for="tamat_diploma_sederajat" class="control-label col-md-4 col-sm-3 col-xs-12">Tamat Diploma/Sederajat <span
                class="required">*</span></label>

    <div class="col-md-2 col-sm-6 col-xs-12">
        {!! Form::number('tamat_diploma_sederajat', null, ['class' => 'form-control', 'required' => true, 'id'=>'tamat_diploma_sederajat']) !!}
    </div>
</div>

<div class="form-group">
    <label for="bulan" class="control-label col-md-4 col-sm-3 col-xs-12">Bulan</label>

    <div class="col-md-4">
        <select class="form-control" id="bulan" name="bulan">
            @foreach(months_list() as $key=> $month)
                @if($pendidikan->bulan == $key)
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

    <div class="col-md-4">
        <select class="form-control" id="list_year" name="tahun">
            @foreach(years_list() as $year)
                @if($pendidikan->tahun == $year)
                    <option selected value="{{$year}}">{{$year}}</option>
                @else
                    <option value="{{$year}}">{{$year}}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

<div class="ln_solid"></div>
