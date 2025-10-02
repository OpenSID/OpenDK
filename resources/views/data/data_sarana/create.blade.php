@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>{{ $page_title }}</h1>
    <small>{{ $page_description }}</small>
</section>

<section class="content container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Tambah Data Sarana</h3>
        </div>
        <div class="panel-body">
            <form action="{{ route('data.data-sarana.store') }}" method="POST" class="form-horizontal">
                @csrf

                <!-- Pilih Desa -->
                <div class="form-group">
                    <label for="desa_id" class="col-sm-2 control-label">Pilih Desa</label>
                    <div class="col-sm-10">
                        <select name="desa_id" id="desa_id" class="form-control" required>
                            <option value="">-- Pilih Desa --</option>
                            @foreach($desas as $desa)
                                <option value="{{ $desa->id }}">{{ $desa->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Nama Sarana -->
                <div class="form-group">
                    <label for="nama" class="col-sm-2 control-label">Nama Sarana</label>
                    <div class="col-sm-10">
                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama sarana" required>
                    </div>
                </div>

                <!-- Jumlah -->
                <div class="form-group">
                    <label for="jumlah" class="col-sm-2 control-label">Jumlah</label>
                    <div class="col-sm-10">
                        <input type="number" name="jumlah" id="jumlah" class="form-control" min="0" placeholder="0" required>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="form-group">
                    <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                    <div class="col-sm-10">
                        <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan tambahan">
                    </div>
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label for="kategori" class="col-sm-2 control-label">Kategori</label>
                    <div class="col-sm-10">
                        <select name="kategori" id="kategori" class="form-control" required>
                            <optgroup label="Sarana Kesehatan">
                                <option value="puskesmas">Puskesmas</option>
                                <option value="puskesmas_pembantu">Puskesmas Pembantu</option>
                                <option value="posyandu">Posyandu</option>
                                <option value="pondok_bersalin">Pondok Bersalin</option>
                            </optgroup>
                            <optgroup label="Sarana Pendidikan">
                                <option value="paud">PAUD/Sederajat</option>
                                <option value="sd">SD/Sederajat</option>
                                <option value="smp">SMP/Sederajat</option>
                                <option value="sma">SMA/Sederajat</option>
                            </optgroup>
                            <optgroup label="Sarana Umum">
                                <option value="masjid_besar">Masjid Besar</option>
                                <option value="mushola">Mushola</option>
                                <option value="gereja">Gereja</option>
                                <option value="pasar">Pasar</option>
                                <option value="balai_pertemuan">Balai Pertemuan</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <a href="{{ route('data.data-sarana.index') }}" class="btn btn-default">
                            <i class="glyphicon glyphicon-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="glyphicon glyphicon-floppy-disk"></i> Simpan
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</section>
@endsection
