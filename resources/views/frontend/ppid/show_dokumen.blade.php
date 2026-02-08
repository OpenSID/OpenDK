@extends('layouts.app_template')

@section('title', $dokumen->judul . ' - Dokumen PPID')

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="bg-light py-2 mb-4">
        <div class="container">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ppid.index') }}">PPID</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ppid.dokumen') }}">Dokumen</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($dokumen->judul, 30) }}</li>
            </ol>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">{{ $dokumen->judul }}</h2>
                        <hr>

                        <div class="mb-3">
                            @if($dokumen->jenisDokumen)
                            <span class="badge badge-primary">{{ $dokumen->jenisDokumen->nama }}</span>
                            @endif
                            @if($dokumen->tahun)
                            <span class="badge badge-secondary">{{ $dokumen->tahun }}</span>
                            @endif
                        </div>

                        @if($dokumen->nomor_dokumen)
                        <p><strong>Nomor Dokumen:</strong> {{ $dokumen->nomor_dokumen }}</p>
                        @endif

                        <div class="mb-4">
                            <strong>Deskripsi:</strong>
                            <p>{{ $dokumen->deskripsi ?: '-' }}</p>
                        </div>

                        <div class="mb-4">
                            <strong>File Dokumen:</strong>
                            @if($dokumen->file)
                            <p>
                                <a href="{{ route('ppid.dokumen.download', $dokumen->id) }}" class="btn btn-success">
                                    <i class="fa fa-download"></i> Unduh Dokumen
                                </a>
                            </p>
                            @else
                            <p class="text-muted">Tidak ada file tersedia.</p>
                            @endif
                        </div>

                        <a href="{{ route('ppid.dokumen') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Kembali ke Daftar Dokumen
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Informasi PPID</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Nama:</strong> {{ $pengaturan['nama_ppid'] ?? 'PPID' }}</p>
                        <p><strong>Alamat:</strong> {{ $pengaturan['alamat_ppid'] ?? '-' }}</p>
                        <p><strong>Telepon:</strong> {{ $pengaturan['nomor_telepon'] ?? '-' }}</p>
                        <p><strong>Email:</strong> {{ $pengaturan['email_ppid'] ?? '-' }}</p>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">Butuh Informasi Lain?</h6>
                    </div>
                    <div class="card-body text-center">
                        <p>Ajukan permohonan informasi publik</p>
                        <a href="{{ route('ppid.permohonan') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-file-text"></i> Ajukan Permohonan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
