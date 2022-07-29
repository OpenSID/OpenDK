{{ Form::hidden('suplemen_id', $suplemen->id) }}
@if ($suplemen->sasaran == 3)
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="penduduk_id">Nama Desa</label>
        
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="desa_id" id="desa" class="form-control">
                <option class="form-control" value="">Pilih Desa</option>
                @foreach ($desa as $item)
                    <option value="{{ $item['desa_id'] }}">{{ $item['nama'] }}</option>
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
                    <option value="{{ $penduduk['id'] }}">{{ $penduduk['nama'] }}</option>
                @endforeach
            </select>
        </div>
    </div>
@else
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="penduduk_id">{{ $suplemen->sasaran == 2 ? 'Nama Kepala Keluarga' : 'Nama Penduduk' }}</label>
        
        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::select('penduduk_id', $data, null, ['class' => 'form-control']) !!}
        </div>
    </div>
@endif
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('keterangan', null, ['class' => 'textarea', 'placeholder' => 'Keterangan', 'style' => 'width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;']) !!}
    </div>
</div>
<div class="ln_solid"></div>
