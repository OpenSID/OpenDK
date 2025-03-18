<div class="form-group">
    <label for="no_kk" class="control-label col-md-4 col-sm-3 col-xs-12">NO Kartu keluarga </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" class="form-control" value="{{ $keluarga->no_kk }}" disabled>
    </div>
</div>

<div class="form-group">

    <label for="nik_kepala" class="control-label col-md-4 col-sm-3 col-xs-12">Kepala Keluarga</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" class="form-control" value="{{ $keluarga->nama_kk }}" disabled>
    </div>
</div>
<div class="form-group">
    <label for="tgl_daftar" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Daftar</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" class="form-control" value="{{ format_date($keluarga->tgl_daftar) }}" disabled>
    </div>
</div>
<div class="form-group">
    <label for="alamat" class="control-label col-md-4 col-sm-3 col-xs-12">Alamat </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" class="form-control col-md-1" value="{{ $keluarga->alamat }}" disabled>
    </div>
</div>
<div class="form-group">
    <label for="desa" class="control-label col-md-4 col-sm-3 col-xs-12">Desa </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" class="form-control col-md-1" value="{{ $keluarga->desa }}" disabled>
    </div>
</div>
<div class="form-group">
    <label for="rw" class="control-label col-md-4 col-sm-3 col-xs-12">RW </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" class="form-control col-md-1" value="{{ $keluarga->rw }}" disabled>
    </div>
</div>
<div class="form-group">
    <label for="rt" class="control-label col-md-4 col-sm-3 col-xs-12">RT </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" class="form-control col-md-1" value="{{ $keluarga->rt }}" disabled>
    </div>
</div>
<div class="form-group">
    <label for="tgl_cetak_kk" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Cetak KK </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" class="form-control" value="{{ format_datetime($keluarga->tgl_cetak_kk) }}" disabled>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover" id="datadesa-table">
        <thead>
            <tr>
                <th>NIK</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penduduk->anggota as $item)
                <tr>
                    <td>{{ $item['nik'] }}</td>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ $item['sex'] == 1 ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ $item['tempatlahir'] }}</td>
                    <td>{{ $item['tanggallahir'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="ln_solid"></div>
@include('partials.asset_select2')
@push('scripts')
    <script>
        $(function() {

            $('#nik_kepala').select2();

        });
    </script>
@endpush
