{{ html()->hidden('suplemen_id', $suplemen->id) }}
@if ($suplemen->sasaran == 3)
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="penduduk_id">Nama
        {{ config('setting.sebutan_desa') }}</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <select name="desa_id" id="desa" class="form-control">
            <option class="form-control" value="">Pilih {{ config('setting.sebutan_desa') }}</option>
            @foreach ($desa as $item)
            @if ($anggota == null)
            <option value="{{ $item['desa_id'] }}">{{ $item['nama'] }}</option>
            @else
            <option {{ $anggota->penduduk->desa->desa_id == $item['desa_id'] ? 'selected' : '' }} value="{{
                $item['desa_id'] }}">{{ $item['nama'] }}</option>
            @endif
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="penduduk_id">Nama Penduduk</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <select name="penduduk_id" id="penduduk" class="form-control" disabled>
            <option class="form-control" value="">Pilih penduduk</option>
            @foreach ($data as $penduduk)
            @if ($anggota == null)
            <option value="{{ $penduduk['id'] }}">{{ $penduduk['nama'] }}</option>
            @else
            <option {{ $anggota->penduduk->id == $penduduk['id'] ? 'selected' : '' }} value="{{ $penduduk['id'] }}">{{
                $penduduk['nama'] }}</option>
            @endif
            @endforeach
        </select>
    </div>
</div>
@else
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="penduduk_id">Nama
        {{ config('setting.sebutan_desa') }}</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <select name="desa_id" id="desa" class="form-control">
            <option class="form-control" value="">Pilih {{ config('setting.sebutan_desa') }}</option>
            @foreach ($desa as $item)
            @if ($anggota == null)
            <option value="{{ $item['desa_id'] }}">{{ $item['nama'] }}</option>
            @else
            <option {{ $anggota->penduduk->desa->desa_id == $item['desa_id'] ? 'selected' : '' }} value="{{
                $item['desa_id'] }}">{{ $item['nama'] }}</option>
            @endif
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="penduduk_id">{{ $suplemen->sasaran == 2 ? 'Nama Kepala
        Keluarga' : 'Nama Penduduk' }}</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <select name="penduduk_id" id="penduduk" class="form-control" disabled>
            <option class="form-control" value="">Pilih Penduduk</option>
            @foreach ($data as $penduduk)
            @if ($anggota == null)
            <option value="{{ $penduduk['id'] }}">{{ $penduduk['nama'] }}</option>
            @else
            <option {{ $anggota->penduduk->id == $penduduk['id'] ? 'selected' : '' }} value="{{ $penduduk['id'] }}">{{
                $penduduk['nama'] }}</option>
            @endif
            @endforeach
        </select>
    </div>
</div>
@endif
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! html()->textarea('keterangan')->class('textarea')->placeholder('Keterangan')->value(old('keterangan',
        isset($suplemen) ? $suplemen->keterangan : '')) !!}
    </div>
</div>
<div class="ln_solid"></div>