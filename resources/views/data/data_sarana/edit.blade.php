@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>{{ $page_title }}</h1>
    <small>{{ $page_description }}</small>
</section>

<section class="content container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Edit Data Sarana</h3>
        </div>
        <div class="panel-body">
            <form action="{{ route('data.data-sarana.update', $sarana->id) }}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')

                <!-- Pilih Desa -->
                <div class="form-group">
                    <label for="desa_id" class="col-sm-2 control-label">Pilih Desa</label>
                    <div class="col-sm-10">
                        <select name="desa_id" id="desa_id" class="form-control" required>
                            @foreach($desas as $desa)
                                <option value="{{ $desa->id }}" {{ $sarana->desa_id == $desa->id ? 'selected' : '' }}>
                                    {{ $desa->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Nama Sarana -->
                <div class="form-group">
                    <label for="nama" class="col-sm-2 control-label">Nama Sarana</label>
                    <div class="col-sm-10">
                        <input type="text" name="nama" id="nama" class="form-control" 
                               value="{{ old('nama', $sarana->nama) }}" required>
                    </div>
                </div>

                <!-- Jumlah -->
                <div class="form-group">
                    <label for="jumlah" class="col-sm-2 control-label">Jumlah</label>
                    <div class="col-sm-10">
                        <input type="number" name="jumlah" id="jumlah" class="form-control" min="0" 
                               value="{{ old('jumlah', $sarana->jumlah) }}" required>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="form-group">
                    <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                    <div class="col-sm-10">
                        <input type="text" name="keterangan" id="keterangan" class="form-control" 
                               value="{{ old('keterangan', $sarana->keterangan) }}">
                    </div>
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label for="kategori" class="col-sm-2 control-label">Kategori</label>
                    <div class="col-sm-10">
                        <select name="kategori" id="kategori" class="form-control" required>
                            <optgroup label="Sarana Kesehatan">
                                <option value="puskesmas" {{ $sarana->kategori == 'puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                                <option value="puskesmas_pembantu" {{ $sarana->kategori == 'puskesmas_pembantu' ? 'selected' : '' }}>Puskesmas Pembantu</option>
                                <option value="posyandu" {{ $sarana->kategori == 'posyandu' ? 'selected' : '' }}>Posyandu</option>
                                <option value="pondok_bersalin" {{ $sarana->kategori == 'pondok_bersalin' ? 'selected' : '' }}>Pondok Bersalin</option>
                            </optgroup>
                            <optgroup label="Sarana Pendidikan">
                                <option value="paud" {{ $sarana->kategori == 'paud' ? 'selected' : '' }}>PAUD/Sederajat</option>
                                <option value="sd" {{ $sarana->kategori == 'sd' ? 'selected' : '' }}>SD/Sederajat</option>
                                <option value="smp" {{ $sarana->kategori == 'smp' ? 'selected' : '' }}>SMP/Sederajat</option>
                                <option value="sma" {{ $sarana->kategori == 'sma' ? 'selected' : '' }}>SMA/Sederajat</option>
                            </optgroup>
                            <optgroup label="Sarana Umum">
                                <option value="masjid_besar" {{ $sarana->kategori == 'masjid_besar' ? 'selected' : '' }}>Masjid Besar</option>
                                <option value="mushola" {{ $sarana->kategori == 'mushola' ? 'selected' : '' }}>Mushola</option>
                                <option value="gereja" {{ $sarana->kategori == 'gereja' ? 'selected' : '' }}>Gereja</option>
                                <option value="pasar" {{ $sarana->kategori == 'pasar' ? 'selected' : '' }}>Pasar</option>
                                <option value="balai_pertemuan" {{ $sarana->kategori == 'balai_pertemuan' ? 'selected' : '' }}>Balai Pertemuan</option>
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
                        <button type="submit" class="btn btn-success">
                            <i class="glyphicon glyphicon-ok"></i> Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
