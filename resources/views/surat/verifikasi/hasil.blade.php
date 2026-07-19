@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 30px; margin-bottom: 30px;">
        <div class="callout callout-success">
            <h4><i class="icon fa fa-check"></i> Surat Terverifikasi!</h4>
            File surat yang diunggah telah sesuai dengan data yang diterbitkan oleh
            Kecamatan {{ optional($profil)->nama_kecamatan ?? 'terkait' }}.
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Detail Verifikasi</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%;">Status</th>
                        <td><span class="label label-success">TERVERIFIKASI</span></td>
                    </tr>
                    <tr>
                        <th>Hash File (SHA-256)</th>
                        <td><code>{{ $surat->file_hash }}</code></td>
                    </tr>
                    <tr>
                        <th>Kecamatan Penerbit</th>
                        <td>{{ optional($profil)->nama_kecamatan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-footer">
                <a href="{{ route('surat.verifikasi') }}" class="btn btn-primary">Verifikasi Ulang</a>
                <a href="{{ route('surat.arsip.qrcode', $surat->id) }}" class="btn btn-info" target="_blank">Lihat Halaman Verifikasi</a>
            </div>
        </div>
    </div>
@endsection
