<div class="form-group">
    <label for="no_kk" class="control-label col-md-4 col-sm-3 col-xs-12">NO Kartu keluarga </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('no_kk', null, ['class' => 'form-control', 'disabled', 'id'=>'no_kk']) !!}
    </div>
</div>

<div class="form-group">
  
    <label for="nik_kepala" class="control-label col-md-4 col-sm-3 col-xs-12">Kepala Keluarga</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control" id="nik_kepala" disabled name="nik_kepala">
          @foreach($penduduk as $kk)
              @if($kk->nik == $keluarga->nik_kepala)
                <option value="{{ $kk->nik }}" selected="true">{{ $kk->nama }}</option>      
              @else
                <option value="{{ $kk->nik }}" >{{ $kk->nama }}</option>
              @endif
          @endforeach
      </select>
    </div>
</div>
<div class="form-group">
    <label for="tgl_daftar" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Daftar</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('tgl_daftar', null, ['class'=>'form-control', 'disabled', 'id'=>'tgl_daftar']) !!}
    </div>
</div>
<div class="form-group">
    <label for="alamat" class="control-label col-md-4 col-sm-3 col-xs-12">Alamat </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
         {!! Form::text('alamat', null, ['class' => 'form-control', 'disabled', 'id'=>'alamat']) !!}
    </div>
</div>
<div class="form-group">
    <label for="dusun" class="control-label col-md-4 col-sm-3 col-xs-12">Dusun </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
         {!! Form::text('dusun', null, ['class' => 'form-control',  'disabled', 'id'=>'dusun']) !!}
    </div>
</div>
<div class="form-group">
    <label for="rw" class="control-label col-md-4 col-sm-3 col-xs-12">RW/RT </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
         {!! Form::text('rw', null, ['class' => 'form-control col-md-1', 'disabled', 'id'=>'rw']) !!}
    </div>
</div>
<div class="form-group">
    <label for="rt" class="control-label col-md-4 col-sm-3 col-xs-12">RW/RT </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
          {!! Form::text('rt', null, ['class' => 'form-control col-md-1', 'disabled', 'id'=>'rt']) !!}
    </div>
</div>
<div class="form-group">
    <label for="tgl_cetak_kk" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Cetak KK </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('tgl_cetak_kk', null,['class'=>'form-control', 'disabled', 'id'=>'tgl_cetak_kk']) !!}
    </div>
</div>

<div class="ln_solid"></div>
@include('partials.asset_select2')
@push('scripts')
<script>
    $(function () {

        // Select 2 Kecamatan
        $('#nik_kepala').select2();
      
    });
</script>
@endpush