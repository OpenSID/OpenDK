@extends('layouts.dashboard_template')

@section('content')
<section class="content-header block-breadcrumb">
    <h1>
        {{ $page_title ?? 'Page Title' }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('ppid.jenis-dokumen.index') }}">Jenis Dokumen PPID</a></li>
        <li class="active">{{ $page_description }}</li>
    </ol>
</section>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <a href="{{ route('ppid.jenis-dokumen.index') }}">
                        <button type="button" class="btn btn-primary btn-sm">
                            <i class="fa fa-arrow-left"></i>&nbsp; Kembali
                        </button>
                    </a>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th style="width: 200px;">Nama</th>
                                    <td>{{ $jenis_dokumen->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Slug</th>
                                    <td>{{ $jenis_dokumen->slug }}</td>
                                </tr>
                                <tr>
                                    <th>Urutan</th>
                                    <td>{{ $jenis_dokumen->urutan }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($jenis_dokumen->status === 'aktif')
                                            <span class="label label-success">Aktif</span>
                                        @else
                                            <span class="label label-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Terkunci</th>
                                    <td>
                                        @if($jenis_dokumen->is_kunci)
                                            <span class="label label-warning"><i class="fa fa-lock"></i> Ya</span>
                                        @else
                                            <span class="label label-default">Tidak</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jumlah Dokumen</th>
                                    <td>{{ $jenis_dokumen->dokumen()->count() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
