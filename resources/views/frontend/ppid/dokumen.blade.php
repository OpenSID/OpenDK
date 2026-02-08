@extends('layouts.app_template')

@section('title', 'Dokumen PPID - ' . ($pengaturan['nama_ppid'] ?? 'PPID'))

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="bg-light py-2 mb-4">
        <div class="container">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ppid.index') }}">PPID</a></li>
                <li class="breadcrumb-item active">Dokumen</li>
            </ol>
        </div>
    </nav>

    <!-- Header -->
    <section class="py-4">
        <div class="container">
            <h1>Dokumen PPID</h1>
            @if($jenis)
            <p class="text-muted">Kategori: {{ $jenis->nama }}</p>
            @endif
        </div>
    </section>

    <!-- Filter Kategori -->
    <section class="mb-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('ppid.dokumen') }}">
                                <label for="kategori">Filter Kategori:</label>
                                <select name="kategori" id="kategori" class="form-control" onchange="this.form.submit()">
                                    <option value="">- Semua Kategori -</option>
                                    @foreach($jenis_dokumen as $j)
                                    <option value="{{ $j->id }}" {{ $jenis && $jenis->id == $j->id ? 'selected' : '' }}>
                                        {{ $j->nama }}
                                    </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Daftar Dokumen -->
    <section class="pb-5">
        <div class="container">
            @if($dokumen && $dokumen->count() > 0)
            <div class="row">
                @foreach($dokumen as $item)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->judul }}</h5>
                            @if($item->nomor_dokumen)
                            <p class="text-muted"><small>No: {{ $item->nomor_dokumen }}</small></p>
                            @endif
                            @if($item->jenisDokumen)
                            <span class="badge badge-primary">{{ $item->jenisDokumen->nama }}</span>
                            @endif
                            @if($item->tahun)
                            <span class="badge badge-secondary">{{ $item->tahun }}</span>
                            @endif
                            <p class="card-text mt-2">{{ Str::limit($item->deskripsi, 100) ?? '-' }}</p>
                        </div>
                        <div class="card-footer">
                            @if($item->file)
                            <a href="{{ route('ppid.dokumen.download', $item->id) }}" class="btn btn-success btn-sm">
                                <i class="fa fa-download"></i> Unduh
                            </a>
                            @else
                            <span class="text-muted small">Tidak ada file</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $dokumen->app(request()->all())->links() }}
            </div>
            @else
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> Belum ada dokumen tersedia.
            </div>
            @endif
        </div>
    </section>
@endsection
