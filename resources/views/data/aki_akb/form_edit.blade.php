<div class="form-group">
    <label for="aki" class="control-label col-md-4 col-sm-3 col-xs-12">Angka Kematian Ibu <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('aki', null, ['class' => 'form-control', 'required' => true, 'id' => 'aki']) !!}
    </div>
</div>
<div class="form-group">
    <label for="akb" class="control-label col-md-4 col-sm-3 col-xs-12">Angka Kematian Bayi <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('akb', null, ['class' => 'form-control', 'required' => true, 'id' => 'akb']) !!}
    </div>
</div>
<div class="form-group">
    <label for="bulan" class="control-label col-md-4 col-sm-3 col-xs-12">Bulan</label>

    <div class="col-md-8">
        <select class="form-control" id="bulan" name="bulan">
            @foreach(months_list() as $key=> $month)
                @if($akib->bulan == $key)
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

    <div class="col-md-8">
        <select class="form-control" id="list_year" name="tahun">
            @foreach(years_list() as $year)
                @if($akib->tahun == $year)
                <option selected value="{{ $year }}">{{ $year }}</option>
                @else
                <option value="{{ $year }}">{{ $year }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

<div class="ln_solid"></div>
