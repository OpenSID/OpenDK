@extends('layouts.dashboard_template')

@section('title')
    Permohonan Surat
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
        <div class="alert alert-warning alert-dismissible">
            <h4><i class="icon fa fa-warning"></i> Info Penting!</h4>
            Fitur Sinkronisasi Surat TTE ke kecamatan saat ini masih berupa demo menunggu proses penyempurnaan dan terdapat
            kecamatan yang sudah mengimplementasikan TTE.
            Kami juga mengimbau kepada seluruh pengguna memberikan masukan terkait penyempurnaan fitur ini baik dari sisi
            OpenSID maupun OpenDK.
            Masukan dapat disampaikan di grup telegram, forum opendesa maupun issue di github.
        </div>
        @include('surat.permohonan.widget')

        <div class="box box-primary">
            @include('partials.flash_message')
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="pengurus-table">
                        <thead>
                            <tr>
                                <th style="min-width: 80px;">Aksi</th>
                                <th>Desa</th>
                                <th>Nama Surat</th>
                                <th>Nama Penduduk</th>
                                <th>Ditandatangani oleh</th>
                                <th>Tanggal Surat</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('partials.asset_datatables')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var data = $('#pengurus-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('surat.permohonan.getdata') !!}"
                },
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'desa.nama',
                        name: 'desa.nama'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'penduduk.nama',
                        name: 'penduduk.nama',
                        orderable: false
                    },
                    {
                        data: 'pengurus.nama',
                        name: 'pengurus.nama',
                        orderable: false
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'log_verifikasi',
                        name: 'log_verifikasi'
                    },
                ]
            });
        });
    </script>
    @include('forms.datatable-vertical')
@endpush
