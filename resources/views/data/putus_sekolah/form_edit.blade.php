<div class="form-group">
    <label for="siswa_paud" class="control-label col-md-4 col-sm-3 col-xs-12">Siswa PAUD <span
            class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->number('siswa_paud', old('siswa_paud'))->class('form-control')->required()->id('siswa_paud') !!}
    </div>
</div>
<div class="form-group">
    <label for="anak_usia_paud" class="control-label col-md-4 col-sm-3 col-xs-12">Anak Usia PAUD <span
            class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->number('anak_usia_paud',
        old('anak_usia_paud'))->class('form-control')->required()->id('anak_usia_paud') !!}
    </div>
</div>
<div class="form-group">
    <label for="siswa_sd" class="control-label col-md-4 col-sm-3 col-xs-12">Siswa SD <span
            class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->number('siswa_sd', old('siswa_sd'))->class('form-control')->required()->id('siswa_sd') !!}
    </div>
</div>
<div class="form-group">
    <label for="anak_usia_sd" class="control-label col-md-4 col-sm-3 col-xs-12">Anak Usia SD <span
            class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->number('anak_usia_sd', old('anak_usia_sd'))->class('form-control')->required()->id('anak_usia_sd')
        !!}
    </div>
</div>
<div class="form-group">
    <label for="siswa_smp" class="control-label col-md-4 col-sm-3 col-xs-12">Siswa SMP <span
            class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->number('siswa_smp', old('siswa_smp'))->class('form-control')->required()->id('siswa_smp') !!}
    </div>
</div>
<div class="form-group">
    <label for="anak_usia_smp" class="control-label col-md-4 col-sm-3 col-xs-12">Anak Usia SMP <span
            class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->number('anak_usia_smp',
        old('anak_usia_smp'))->class('form-control')->required()->id('anak_usia_smp')
        !!}
    </div>
</div>
<div class="form-group">
    <label for="siswa_sma" class="control-label col-md-4 col-sm-3 col-xs-12">Siswa SMA <span
            class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->number('siswa_sma', old('siswa_sma'))->class('form-control')->required()->id('siswa_sma') !!}
    </div>
</div>
<div class="form-group">
    <label for="anak_usia_sma" class="control-label col-md-4 col-sm-3 col-xs-12">Anak Usia SMA <span
            class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->number('anak_usia_sma',
        old('anak_usia_sma'))->class('form-control')->required()->id('anak_usia_sma')
        !!}
    </div>
</div>
<div class="form-group">
    <label for="semester" class="control-label col-md-4 col-sm-3 col-xs-12">Semester</label>
    <div class="col-md-8">
        <select class="form-control" id="semester" name="semester">
            <option value="1" @if ($siswa->semester === 1) {{ 'selected' }} @endif>Semester 1</option>
            <option value="2" @if ($siswa->semester === 2) {{ 'selected' }} @endif>Semester 2</option>
        </select>
    </div>
</div>

<div class="form-group">
    <label for="list_year" class="control-label col-md-4 col-sm-3 col-xs-12">Tahun</label>
    <div class="col-md-8">
        <select class="form-control" id="list_year" name="tahun">
            @foreach (years_list() as $year)
            @if ($siswa->tahun == $year)
            <option selected value="{{ $year }}">{{ $year }}</option>
            @else
            <option value="{{ $year }}">{{ $year }}</option>
            @endif
            @endforeach
        </select>
    </div>
</div>

<div class="ln_solid"></div>