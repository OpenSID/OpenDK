<div class="form-group">
    <label for="jumlah_penderita" class="control-label col-md-4 col-sm-3 col-xs-12">Angka Kematian Ibu <span
            class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('jumlah_penderita', null, ['class' => 'form-control', 'required' => true, 'id' => 'jumlah_penderita']) !!}
    </div>
</div>
<div class="form-group">
    <label for="penyakit_id" class="control-label col-md-4 col-sm-3 col-xs-12">Jenis Penyakit</label>

    <div class="col-md-8">
        <select class="form-control" id="penyakit_id" name="penyakit_id">
            @foreach ($jenis_penyakit as $key => $penyakit)
                <option value="{{ $key }}" @selected($epidemi->penyakit_id === $key)>{{ $penyakit }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label for="bulan" class="control-label col-md-4 col-sm-3 col-xs-12">Bulan</label>

    <div class="col-md-8">
        <select class="form-control" id="bulan" name="bulan">
            @foreach (months_list() as $key => $month)
                @if ($epidemi->bulan == $key)
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
            @foreach (years_list() as $year)
                @if ($epidemi->tahun == $year)
                    <option selected value="{{ $year }}">{{ $year }}</option>
                @else
                    <option value="{{ $year }}">{{ $year }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

<div class="ln_solid"></div>
