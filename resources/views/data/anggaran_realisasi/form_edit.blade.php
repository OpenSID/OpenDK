<div class="form-group">
    <label for="total_anggaran" class="control-label col-md-4 col-sm-3 col-xs-12">Total Anggaran <span
            class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Html::number('total_anggaran')
            ->value(old('total_anggaran', $anggaran->total_anggaran))
            ->class('form-control')
            ->required()
            ->id('total_anggaran') !!}
    </div>
</div>
<div class="form-group">
    <label for="total_belanja" class="control-label col-md-4 col-sm-3 col-xs-12">Total Belanja <span
            class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Html::number('total_belanja')
            ->value(old('total_belanja', $anggaran->total_belanja))
            ->class('form-control')
            ->required()
            ->id('total_belanja') !!}
    </div>
</div>
<div class="form-group">
    <label for="belanja_pegawai" class="control-label col-md-4 col-sm-3 col-xs-12">Belanja Pegawai <span
            class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Html::number('belanja_pegawai')
            ->value(old('belanja_pegawai', $anggaran->belanja_pegawai))
            ->class('form-control')
            ->required()
            ->id('belanja_pegawai') !!}
    </div>
</div>
<div class="form-group">
    <label for="belanja_barang_jasa" class="control-label col-md-4 col-sm-3 col-xs-12">Belanja Barang & Jasa <span
            class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Html::number('belanja_barang_jasa')
            ->value(old('belanja_barang_jasa', $anggaran->belanja_barang_jasa))
            ->class('form-control')
            ->required()
            ->id('belanja_barang_jasa') !!}
    </div>
</div>
<div class="form-group">
    <label for="belanja_modal" class="control-label col-md-4 col-sm-3 col-xs-12">Belanja Modal <span
            class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Html::number('belanja_modal')
            ->value(old('belanja_modal', $anggaran->belanja_modal))
            ->class('form-control')
            ->required()
            ->id('belanja_modal') !!}
    </div>
</div>
<div class="form-group">
    <label for="belanja_tidak_langsung" class="control-label col-md-4 col-sm-3 col-xs-12">Belanja Tidak Langsung <span
            class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Html::number('belanja_tidak_langsung')
            ->value(old('belanja_tidak_langsung', $anggaran->belanja_tidak_langsung))
            ->class('form-control')
            ->required()
            ->id('belanja_tidak_langsung') !!}
    </div>
</div>
<div class="form-group">
    <label for="bulan" class="control-label col-md-4 col-sm-3 col-xs-12">Bulan</label>
    <div class="col-md-3">
        <select class="form-control" id="bulan" name="bulan">
            @foreach (months_list() as $key => $month)
            @if ($anggaran->bulan == $key)
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
            @foreach (years_list() as $year)
            @if ($anggaran->tahun == $year)
            <option selected value="{{ $year }}">{{ $year }}</option>
            @else
            <option value="{{ $year }}">{{ $year }}</option>
            @endif
            @endforeach
        </select>
    </div>
</div>

<div class="ln_solid"></div>