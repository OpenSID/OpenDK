<div class="form-group">
    <label for="toilet" class="control-label col-md-4 col-sm-3 col-xs-12">Toilet <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('toilet', null, ['class' => 'form-control', 'required' => true, 'id' => 'toilet']) !!}
    </div>
</div>
<div class="form-group">
    <label for="sanitasi" class="control-label col-md-4 col-sm-3 col-xs-12">Sanitasi <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('sanitasi', null, ['class' => 'form-control', 'required' => true, 'id' => 'sanitasi']) !!}
    </div>
</div>
<div class="form-group">
    <label for="bulan" class="control-label col-md-4 col-sm-3 col-xs-12">Bulan</label>

    <div class="col-md-8">
        <select class="form-control" id="bulan" name="bulan">
            @foreach(months_list() as $key=> $month)
                @if($toilet->bulan == $key)
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
                @if($toilet->tahun == $year)
                <option selected value="{{ $year }}">{{ $year }}</option>
                @else
                <option value="{{ $year }}">{{ $year }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

<div class="ln_solid"></div>
