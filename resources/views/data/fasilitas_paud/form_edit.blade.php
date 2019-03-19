<div class="form-group">
    <label for="jumlah_paud" class="control-label col-md-4 col-sm-3 col-xs-12">Jumlah PAUD <span class="required">*</span></label>

    <div class="col-md-3 col-sm-6 col-xs-12">
        {!! Form::number('jumlah_paud', null, ['class' => 'form-control', 'required' => true, 'id'=>'jumlah_paud']) !!}
    </div>
</div>
<div class="form-group">
    <label for="jumlah_guru_paud" class="control-label col-md-4 col-sm-3 col-xs-12">Jumlah Guru PAUD <span class="required">*</span></label>

    <div class="col-md-3 col-sm-6 col-xs-12">
        {!! Form::number('jumlah_guru_paud', null, ['class' => 'form-control', 'required' => true, 'id'=>'jumlah_guru_paud']) !!}
    </div>
</div>
<div class="form-group">
    <label for="jumlah_siswa_paud" class="control-label col-md-4 col-sm-3 col-xs-12">Jumlah Siswa PAUD <span class="required">*</span></label>

    <div class="col-md-3 col-sm-6 col-xs-12">
        {!! Form::number('jumlah_siswa_paud', null, ['class' => 'form-control', 'required' => true, 'id'=>'jumlah_siswa_paud']) !!}
    </div>
</div>
<div class="form-group">
    <label for="bulan" class="control-label col-md-4 col-sm-3 col-xs-12">Bulan</label>

    <div class="col-md-4">
        <select class="form-control" id="bulan" name="bulan">
            @foreach(months_list() as $key=> $month)
                @if($fasilitas->bulan == $key)
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
                @if($fasilitas->tahun == $year)
                <option selected value="{{$year}}">{{$year}}</option>
                @else
                <option value="{{$year}}">{{$year}}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

<div class="ln_solid"></div>
