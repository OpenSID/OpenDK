@extends('layouts.app_template')

@section('title', 'PPID - ' . ($pengaturan['nama_ppid'] ?? 'PPID'))

@section('content')
    <!-- Hero Section -->
    <section class="bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 class="display-4 fw-bold">{{ $pengaturan['nama_ppid'] ?? 'PPID' }}</h1>
                    <p class="lead">Pejabat Pengelola Informasi dan Dokumentasi</p>
                    <p class="mb-0">{{ $pengaturan['alamat_ppid'] ?? '' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="mb-4">Tentang PPID</h3>
                    <p>PPID (Pejabat Pengelola Informasi dan Dokumentasi) adalah pejabat yang bertanggung jawab atas penyimpanan, pendokumentasian, penyediaan, dan/atau pelayanan informasi di badan publik.</p>
                    <p>PPID berfungsi mengelola dan menyampaikan dokumen publik, memastikan transparansi, serta mempermudah akses informasi bagi masyarakat sesuai amanat UU No. 14 Tahun 2008 tentang Keterbukaan Informasi Publik (KIP).</p>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Hubungi Kami</h5>
                            <p><i class="fa fa-phone"></i> {{ $pengaturan['nomor_telepon'] ?? '-' }}</p>
                            <p><i class="fa fa-envelope"></i> {{ $pengaturan['email_ppid'] ?? '-' }}</p>
                            <p><i class="fa fa-clock"></i> {{ $pengaturan['jam_operasional'] ?? '-' }}</p>
                            @if($pengaturan['nama_pejabat'])
                            <hr>
                            <p class="mb-1"><strong>Pejabat PPID:</strong></p>
                            <p class="mb-1">{{ $pengaturan['nama_pejabat'] }}</p>
                            <p class="mb-1">NIP: {{ $pengaturan['nip_pejabat'] ?? '-' }}</p>
                            <p class="mb-0">{{ $pengaturan['jabatan_pejabat'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kategori Dokumen -->
    @if($jenis_dokumen && $jenis_dokumen->count() > 0)
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Kategori Dokumen</h2>
            <div class="row">
                @foreach($jenis_dokumen as $jenis)
                <div class="col-md-3 mb-4">
                    <a href="{{ route('ppid.dokumen.jenis', $jenis->id) }}" class="card h-100 text-decoration-none text-dark">
                        <div class="card-body text-center">
                            <i class="fa fa-folder-open fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">{{ $jenis->nama }}</h5>
                            <p class="card-text text-muted">{{ $jenis->keterangan }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Dokumen Terbaru -->
    @if($dokumen_terbaru && $dokumen_terbaru->count() > 0)
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Dokumen Terbaru</h2>
                <a href="{{ route('ppid.dokumen') }}" class="btn btn-primary">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Dokumen</th>
                            <th>Jenis</th>
                            <th>Tahun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dokumen_terbaru as $index => $dokumen)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $dokumen->judul }}</td>
                            <td>{{ $dokumen->jenisDokumen->nama ?? '-' }}</td>
                            <td>{{ $dokumen->tahun ?? '-' }}</td>
                            <td>
                                @if($dokumen->file)
                                <a href="{{ route('ppid.dokumen.download', $dokumen->id) }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-download"></i> Unduh
                                </a>
                                @else
                                <span class="text-muted">Tidak ada file</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Permohonan -->
    <section class="py-5 bg-info text-white">
        <div class="container text-center">
            <h2 class="mb-4">Butuh Informasi Lainnya?</h2>
            <p class="lead mb-4">Ajukan permohonan informasi publik sesuai dengan UU Keterbukaan Informasi Publik</p>
            <a href="{{ route('ppid.permohonan') }}" class="btn btn-light btn-lg">
                <i class="fa fa-file-text"></i> Ajukan Permohonan
            </a>
        </div>
    </section>
@endsection
