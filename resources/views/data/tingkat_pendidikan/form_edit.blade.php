<div class="form-group">
    <label for="tidak_tamat_sekolah" class="control-label col-md-4 col-sm-3 col-xs-12">Tidak Tamat Sekolah <span
            class="required">*</span></label>
    <div class="col-md-2 col-sm-6 col-xs-12">
        {!! html()->number('tidak_tamat_sekolah')->value(old('tidak_tamat_sekolah', isset($pendidikan) ?
        $pendidikan->tidak_tamat_sekolah : ''))->class('form-control')->id('tidak_tamat_sekolah')->required() !!}
    </div>
</div>

<div class="form-group">
    <label for="tamat_sd" class="control-label col-md-4 col-sm-3 col-xs-12">Tamat SD <span
            class="required">*</span></label>
    <div class="col-md-2 col-sm-6 col-xs-12">
        {!! html()->number('tamat_sd')->value(old('tamat_sd', isset($pendidikan) ? $pendidikan->tamat_sd :
        ''))->class('form-control')->id('tamat_sd')->required() !!}
    </div>
</div>

<div class="form-group">
    <label for="tamat_smp" class="control-label col-md-4 col-sm-3 col-xs-12">Tamat SMP <span
            class="required">*</span></label>
    <div class="col-md-2 col-sm-6 col-xs-12">
        {!! html()->number('tamat_smp')->value(old('tamat_smp', isset($pendidikan) ? $pendidikan->tamat_smp :
        ''))->class('form-control')->id('tamat_smp')->required() !!}
    </div>
</div>

<div class="form-group">
    <label for="tamat_sma" class="control-label col-md-4 col-sm-3 col-xs-12">Tamat SMA <span
            class="required">*</span></label>
    <div class="col-md-2 col-sm-6 col-xs-12">
        {!! html()->number('tamat_sma')->value(old('tamat_sma', isset($pendidikan) ? $pendidikan->tamat_sma :
        ''))->class('form-control')->id('tamat_sma')->required() !!}
    </div>
</div>

<div class="form-group">
    <label for="tamat_diploma_sederajat" class="control-label col-md-4 col-sm-3 col-xs-12">Tamat Diploma/Sederajat <span
            class="required">*</span></label>
    <div class="col-md-2 col-sm-6 col-xs-12">
        {!! html()->number('tamat_diploma_sederajat')->value(old('tamat_diploma_sederajat', isset($pendidikan) ?
        $pendidikan->tamat_diploma_sederajat : ''))->class('form-control')->id('tamat_diploma_sederajat')->required()
        !!}
    </div>
</div>

<div class="form-group">
    <label for="semester" class="control-label col-md-4 col-sm-3 col-xs-12">Semester</label>
    <div class="col-md-4">
        <select class="form-control" id="semester" name="semester">
            <option value="1" @if ($pendidikan->semester === 1) {{ 'selected' }} @endif>Semester 1</option>
            <option value="2" @if ($pendidikan->semester === 2) {{ 'selected' }} @endif>Semester 2</option>
        </select>
    </div>
</div>

<div class="form-group">
    <label for="list_year" class="control-label col-md-4 col-sm-3 col-xs-12">Tahun</label>
    <div class="col-md-4">
        <select class="form-control" id="list_year" name="tahun">
            @foreach (years_list() as $year)
            @if ($pendidikan->tahun == $year)
            <option selected value="{{ $year }}">{{ $year }}</option>
            @else
            <option value="{{ $year }}">{{ $year }}</option>
            @endif
            @endforeach
        </select>
    </div>
</div>

<div class="ln_solid"></div>