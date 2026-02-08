@extends('layouts.app_template')

@section('title', 'Permohonan Informasi - PPID')

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="bg-light py-2 mb-4">
        <div class="container">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ppid.index') }}">PPID</a></li>
                <li class="breadcrumb-item active">Permohonan Informasi</li>
            </ol>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Formulir Permohonan Informasi Publik</h4>
                    </div>
                    <div class="card-body">
                        @include('partials.flash_message')

                        <div class="alert alert-info">
                            <strong>Informasi:</strong> Sesuai UU No. 14 Tahun 2008, permohonan informasi akan diproses dalam waktu maksimal 10 hari kerja.
                        </div>

                        <form method="POST" action="{{ route('ppid.permohonan.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="nama_pemohon">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" id="nama_pemohon" name="nama_pemohon" class="form-control"
                                       value="{{ old('nama_pemohon') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="nik">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                                <input type="text" id="nik" name="nik" class="form-control"
                                       value="{{ old('nik') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea id="alamat" name="alamat" class="form-control" rows="3" required>{{ old('alamat') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomor_telepon">Nomor Telepon/WA <span class="text-danger">*</span></label>
                                        <input type="text" id="nomor_telepon" name="nomor_telepon" class="form-control"
                                               value="{{ old('nomor_telepon') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email (jika ada)</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                               value="{{ old('email') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="rincian_informasi">Rincian Informasi yang Dibutuhkan <span class="text-danger">*</span></label>
                                <textarea id="rincian_informasi" name="rincian_informasi" class="form-control" rows="4"
                                          placeholder="Jelaskan rincian informasi yang Anda butuhkan" required>{{ old('rincian_informasi') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="tujuan_penggunaan">Tujuan Penggunaan Informasi <span class="text-danger">*</span></label>
                                <textarea id="tujuan_penggunaan" name="tujuan_penggunaan" class="form-control" rows="3"
                                          placeholder="Jelaskan tujuan penggunaan informasi" required>{{ old('tujuan_penggunaan') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="cara_memperoleh">Cara Memperoleh Informasi <span class="text-danger">*</span></label>
                                <select id="cara_memperoleh" name="cara_memperoleh" class="form-control" required>
                                    <option value="">- Pilih Cara Memperoleh -</option>
                                    <option value="ONLINE" {{ old('cara_memperoleh') == 'ONLINE' ? 'selected' : '' }}>Online (Dikirim via Email)</option>
                                    <option value="OFFLINE" {{ old('cara_memperoleh') == 'OFFLINE' ? 'selected' : '' }}>Offline (Diambil Langsung)</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-paper-plane"></i> Kirim Permohonan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Kontak PPID</h6>
                    </div>
                    <div class="card-body">
                        <p><i class="fa fa-building"></i> {{ $pengaturan['nama_ppid'] ?? 'PPID' }}</p>
                        <p><i class="fa fa-map-marker"></i> {{ $pengaturan['alamat_ppid'] ?? '-' }}</p>
                        <p><i class="fa fa-phone"></i> {{ $pengaturan['nomor_telepon'] ?? '-' }}</p>
                        <p><i class="fa fa-envelope"></i> {{ $pengaturan['email_ppid'] ?? '-' }}</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Cek Status Permohonan</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('ppid.cek-permohonan.post') }}">
                            @csrf
                            <div class="form-group">
                                <label for="nomor_permohonan">Nomor Permohonan</label>
                                <input type="text" id="nomor_permohonan" name="nomor_permohonan" class="form-control"
                                       placeholder="Masukkan nomor permohonan Anda" required>
                                <small class="text-muted">Nomor permohonan akan Anda dapatkan setelah mengajukan permohonan.</small>
                            </div>
                            <button type="submit" class="btn btn-secondary btn-sm">
                                <i class="fa fa-search"></i> Cek Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
