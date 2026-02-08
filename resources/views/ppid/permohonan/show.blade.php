@extends('layouts.dashboard_template')

@section('content')
<section class="content-header block-breadcrumb">
    <h1>
        {{ $page_title ?? 'Page Title' }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('ppid.permohonan.index') }}">PPID</a></li>
        <li><a href="{{ route('ppid.permohonan.index') }}">Permohonan Informasi</a></li>
        <li class="active">{{ $page_description ?? '' }}</li>
    </ol>
</section>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Detail Permohonan</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('ppid.permohonan.index') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('ppid.permohonan.edit', $permohonan->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th style="width: 30%;">Nama Pemohon</th>
                                <td>{{ $permohonan->nama_pemohon }}</td>
                            </tr>
                            <tr>
                                <th>NIK</th>
                                <td>{{ $permohonan->nik }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $permohonan->alamat }}</td>
                            </tr>
                            <tr>
                                <th>Nomor Telepon</th>
                                <td>{{ $permohonan->nomor_telepon }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $permohonan->email ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>Cara Memperoleh</th>
                                <td>{{ $permohonan->cara_memperoleh == 'ONLINE' ? 'Online' : 'Offline' }}</td>
                            </tr>
                            <tr>
                                <th>Rincian Informasi</th>
                                <td>{{ $permohonan->rincian_informasi }}</td>
                            </tr>
                            <tr>
                                <th>Tujuan Penggunaan</th>
                                <td>{{ $permohonan->tujuan_penggunaan }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($permohonan->status == 'MENUNGGU')
                                        <span class="label label-warning">MENUNGGU</span>
                                    @elseif($permohonan->status == 'DIPROSES')
                                        <span class="label label-info">DIPROSES</span>
                                    @elseif($permohonan->status == 'SELESAI')
                                        <span class="label label-success">SELESAI</span>
                                    @elseif($permohonan->status == 'DITOLAK')
                                        <span class="label label-danger">DITOLAK</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Permohonan</th>
                                <td>{{ $permohonan->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Proses</th>
                                <td>{{ $permohonan->tanggal_proses ? $permohonan->tanggal_proses->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>{{ $permohonan->keterangan ?: '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($permohonan->status == 'MENUNGGU' || $permohonan->status == 'DIPROSES')
                <div class="box-footer">
                    <form method="POST" action="{{ route('ppid.permohonan.proses', $permohonan->id) }}" style="display: inline;">
                        @csrf
                        @method('PUT')
                        @if($permohonan->status == 'MENUNGGU')
                        <button type="submit" class="btn btn-info" onclick="return confirm('Proses permohonan ini?')">
                            <i class="fa fa-cog"></i> Proses
                        </button>
                        @endif
                        <button type="submit" formaction="{{ route('ppid.permohonan.selesaikan', $permohonan->id) }}" class="btn btn-success" onclick="return confirm('Selesaikan permohonan ini?')">
                            <i class="fa fa-check"></i> Selesaikan
                        </button>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-tolak">
                            <i class="fa fa-times"></i> Tolak
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@if($permohonan->status == 'MENUNGGU' || $permohonan->status == 'DIPROSES')
<div class="modal fade" id="modal-tolak">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('ppid.permohonan.tolak', $permohonan->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Tolak Permohonan</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="keterangan">Alasan Penolakan</label>
                        <textarea id="keterangan" name="keterangan" class="form-control" rows="4" required placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
