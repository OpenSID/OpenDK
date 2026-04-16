@extends('layouts.dashboard_template')

@section('content')
<section class="content-header block-breadcrumb">
    <h1>
        {{ $page_title ?? 'Page Title' }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('ppid.dokumen.index') }}">Dokumen PPID</a></li>
        <li class="active">{{ $page_description }}</li>
    </ol>
</section>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <a href="{{ route('ppid.dokumen.index') }}">
                        <button type="button" class="btn btn-primary btn-sm">
                            <i class="fa fa-arrow-left"></i>&nbsp; Kembali
                        </button>
                    </a>
                    @if($dokumen->tipe_dokumen === 'file' && !empty($dokumen->file_path))
                    <a href="{{ route('ppid.dokumen.download', $dokumen) }}" class="btn btn-success btn-sm pull-right">
                        <i class="fa fa-download"></i>&nbsp; Unduh File
                    </a>
                    @endif
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th style="width: 200px;">Judul</th>
                                    <td>{{ $dokumen->judul }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Dokumen</th>
                                    <td>{{ $dokumen->jenisDokumen->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tipe Dokumen</th>
                                    <td>{{ strtoupper($dokumen->tipe_dokumen) }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($dokumen->status === 'terbit')
                                            <span class="label label-success">Terbit</span>
                                        @else
                                            <span class="label label-danger">Tidak Terbit</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Publikasi</th>
                                    <td>{{ $dokumen->tanggal_publikasi ? $dokumen->tanggal_publikasi->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Ringkasan</th>
                                    <td>{{ $dokumen->ringkasan ?: '-' }}</td>
                                </tr>
                                @if($dokumen->tipe_dokumen === 'url')
                                <tr>
                                    <th>URL</th>
                                    <td><a href="{{ $dokumen->url }}" target="_blank">{{ $dokumen->url }}</a></td>
                                </tr>
                                @endif
                            </table>

                            @if($dokumen->tipe_dokumen === 'file' && !empty($dokumen->file_path))
                            <hr>
                            <h4>Preview Dokumen</h4>
                            <div class="preview-container">
                                @if(str_ends_with(strtolower($dokumen->file_path), '.pdf'))
                                <object data="{{ asset($dokumen->file_path . '#toolbar=1') }}" type="application/pdf" style="width: 100%; height: 600px;">
                                    <p>Browser Anda tidak mendukung preview PDF. <a href="{{ route('ppid.dokumen.download', $dokumen) }}">Unduh file</a> untuk melihat.</p>
                                </object>
                                @elseif(in_array(strtolower(pathinfo($dokumen->file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ asset($dokumen->file_path) }}" style="max-width: 100%; height: auto;" alt="Preview">
                                @else
                                <p>Preview tidak tersedia untuk tipe file ini. <a href="{{ route('ppid.dokumen.download', $dokumen) }}">Unduh file</a> untuk melihat.</p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    @include('forms.delete-modal')
@endpush
