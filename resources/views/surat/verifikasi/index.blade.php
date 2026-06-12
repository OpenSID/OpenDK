@extends('layouts.dashboard_template')

@section('title')
    Verifikasi Surat
@endsection

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        @include('partials.flash_message')
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Verifikasi Keaslian Surat Digital</h3>
            </div>
            <div class="box-body">
                <div class="callout callout-info">
                    <h4><i class="icon fa fa-info"></i> Info!</h4>
                    Unggah file surat (PDF) untuk memverifikasi keasliannya. Sistem akan memeriksa apakah file ini benar-benar
                    diterbitkan oleh Kecamatan {{ $profil->nama_kecamatan ?? '' }}.
                </div>
                <form method="POST" action="{{ route('surat.verifikasi.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Pilih File Surat (PDF)</label>
                        <input type="file" id="file" name="file" class="form-control" accept=".pdf" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Verifikasi</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
