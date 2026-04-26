@extends('layouts.app_template')

@section('title', 'Cek Status Permohonan - PPID')

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="bg-light py-2 mb-4">
        <div class="container">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ppid.index') }}">PPID</a></li>
                <li class="breadcrumb-item active">Cek Status Permohonan</li>
            </ol>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Cek Status Permohonan</h4>
                    </div>
                    <div class="card-body">
                        @include('partials.flash_message')

                        @if($permohonan)
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i> Permohonan ditemukan!
                        </div>

                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 30%;">Nomor Permohonan</th>
                                <td>#{{ $permohonan->id }}</td>
                            </tr>
                            <tr>
                                <th>Nama Pemohon</th>
                                <td>{{ $permohonan->nama_pemohon }}</td>
                            </tr>
                            <tr>
                                <th>NIK</th>
                                <td>{{ $permohonan->nik }}</td>
                            </tr>
                            <tr>
                                <th>Rincian Informasi</th>
                                <td>{{ $permohonan->rincian_informasi }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Permohonan</th>
                                <td>{{ $permohonan->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($permohonan->status == 'MENUNGGU')
                                        <span class="badge badge-warning">MENUNGGU</span>
                                    @elseif($permohonan->status == 'DIPROSES')
                                        <span class="badge badge-info">DIPROSES</span>
                                    @elseif($permohonan->status == 'SELESAI')
                                        <span class="badge badge-success">SELESAI</span>
                                    @elseif($permohonan->status == 'DITOLAK')
                                        <span class="badge badge-danger">DITOLAK</span>
                                    @endif
                                </td>
                            </tr>
                            @if($permohonan->tanggal_proses)
                            <tr>
                                <th>Tanggal Proses</th>
                                <td>{{ $permohonan->tanggal_proses->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endif
                            @if($permohonan->keterangan)
                            <tr>
                                <th>Keterangan</th>
                                <td>{{ $permohonan->keterangan }}</td>
                            </tr>
                            @endif
                        </table>
                        @else
                        <form method="POST" action="{{ route('ppid.cek-permohonan.post') }}">
                            @csrf
                            <div class="form-group">
                                <label for="nomor_permohonan">Nomor Permohonan</label>
                                <input type="text" id="nomor_permohonan" name="nomor_permohonan" class="form-control"
                                       placeholder="Masukkan nomor permohonan Anda" required>
                                <small class="text-muted">
                                    Masukkan nomor permohonan yang Anda dapatkan saat mengajukan permohonan.
                                    Contoh: 123
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Cek Status
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
