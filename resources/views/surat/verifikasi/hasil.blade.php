@extends('layouts.dashboard_template')

@section('title')
    Hasil Verifikasi Surat
@endsection

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            Hasil Verifikasi Surat
            <small>Verifikasi keaslian surat digital</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('surat.verifikasi') }}">Verifikasi Surat</a></li>
            <li class="active">Hasil Verifikasi</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="callout callout-success">
            <h4><i class="icon fa fa-check"></i> Surat Terverifikasi!</h4>
            File surat yang diunggah telah sesuai dengan data yang diterbitkan oleh Kecamatan.
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Detail Surat</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%;">Nomor Surat</th>
                        <td>{{ $surat->nomor }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Surat</th>
                        <td>{{ format_date($surat->tanggal) }}</td>
                    </tr>
                    <tr>
                        <th>Nama Surat</th>
                        <td>{{ $surat->nama }}</td>
                    </tr>
                    <tr>
                        <th>{{ config('setting.sebutan_desa') }}</th>
                        <td>{{ $surat->desa->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Atas Nama</th>
                        <td>{{ $surat->penduduk->nama ?? $surat->nama_penduduk }}</td>
                    </tr>
                    <tr>
                        <th>Ditandatangani Oleh</th>
                        <td>{{ $surat->pengurus->nama ?? '-' }} ({{ $surat->pengurus->jabatan->nama ?? '-' }})</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><span class="label label-success">Arsip</span></td>
                    </tr>
                    <tr>
                        <th>Hash File (SHA-256)</th>
                        <td><code>{{ $surat->file_hash }}</code></td>
                    </tr>
                </table>
            </div>
            <div class="box-footer">
                <a href="{{ route('surat.verifikasi') }}" class="btn btn-primary">Verifikasi Ulang</a>
                <a href="{{ route('surat.arsip.qrcode', $surat->id) }}" class="btn btn-info" target="_blank">Lihat Halaman Verifikasi</a>
            </div>
        </div>
    </section>
@endsection
