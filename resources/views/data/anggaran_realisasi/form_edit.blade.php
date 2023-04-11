<div class="form-group">
    <label for="total_anggaran" class="control-label col-md-4 col-sm-3 col-xs-12">Total Anggaran <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('total_anggaran', null, ['class' => 'form-control', 'required' => true, 'id' => 'total_anggaran']) !!}
    </div>
</div>
<div class="form-group">
    <label for="total_belanja" class="control-label col-md-4 col-sm-3 col-xs-12">Total Belanja <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('total_belanja', null, ['class' => 'form-control', 'required' => true, 'id' => 'total_belanja']) !!}
    </div>
</div>
<div class="form-group">
    <label for="belanja_pegawai" class="control-label col-md-4 col-sm-3 col-xs-12">Belanja Pegawai <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('belanja_pegawai', null, ['class' => 'form-control', 'required' => true, 'id' => 'belanja_pegawai']) !!}
    </div>
</div>
<div class="form-group">
    <label for="belanja_barang_jasa" class="control-label col-md-4 col-sm-3 col-xs-12">Belanja Barang & Jasa <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('belanja_barang_jasa', null, ['class' => 'form-control', 'required' => true, 'id' => 'belanja_barang_jasa']) !!}
    </div>
</div>
<div class="form-group">
    <label for="belanja_modal" class="control-label col-md-4 col-sm-3 col-xs-12">Belanja Modal <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('belanja_modal', null, ['class' => 'form-control', 'required' => true, 'id' => 'belanja_modal']) !!}
    </div>
</div>
<div class="form-group">
    <label for="belanja_tidak_langsung" class="control-label col-md-4 col-sm-3 col-xs-12">Belanja Tidak Langsung <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('belanja_tidak_langsung', null, ['class' => 'form-control', 'required' => true, 'id' => 'belanja_tidak_langsung']) !!}
    </div>
</div>
<div class="form-group">
    <label for="bulan" class="control-label col-md-4 col-sm-3 col-xs-12">Bulan</label>
    <div class="col-md-3">
        <select class="form-control" id="bulan" name="bulan">
            @foreach(months_list() as $key=> $month)
                @if($anggaran->bulan == $key)
                <option selected value="{{ $key }}">{{ $month }}</option>
                @else
                <option value="{{ $key }}">{{ $month }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label for="list_year" class="control-label col-md-4 col-sm-3 col-xs-12">Tahun</label>
    <div class="col-md-3">
        <select class="form-control" id="list_year" name="tahun">
            @foreach(years_list() as $year)
                @if($anggaran->tahun == $year)
                <option selected value="{{ $year }}">{{ $year }}</option>
                @else
                <option value="{{ $year }}">{{ $year }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

<div class="ln_solid"></div>
