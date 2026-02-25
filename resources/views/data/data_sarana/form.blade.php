<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="desa_id">Pilih Desa <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        @include('layouts.fragments.select-desa', ['selectAttributes' => ['required' => 'required', 'name' => 'desa_id'], 'selectedOption' => (isset($sarana) ? $sarana->desa_id : '') ])
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Sarana <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('nama')->class('form-control')->required()->placeholder('Masukkan nama sarana')->value(old('nama', isset($sarana) ? $sarana->nama : '')) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jumlah">Jumlah <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->number('jumlah')->class('form-control')->required()->placeholder('0')->value(old('jumlah', isset($sarana) ? $sarana->jumlah : '')) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kategori">Kategori <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <select name="kategori" id="kategori" class="form-control" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach(\App\Enums\KategoriSarana::getGroupedOptions() as $group => $options)
                <optgroup label="{{ $group }}">
                    @foreach($options as $value => $label)
                        <option value="{{ $value }}" {{ (isset($sarana) && $sarana->kategori == $value) ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">Keterangan <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->text('keterangan')->class('form-control')->required()->placeholder('Keterangan tambahan')->value(old('keterangan', isset($sarana) ? $sarana->keterangan : '')) !!}
    </div>
</div>

<div class="ln_solid"></div>