@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('data.data-suplemen.index') }}">Data Suplemen</a></li>
            <li class="active">Data Suplemen {{ $suplemen->nama }}</li>
        </ol>
    </section>
    <section class="content container-fluid">

        @include('partials.flash_message')

        <div class="box box-primary">
            <div class="box-header with-border">
                <a href="{{ route('data.data-suplemen.createdetail', $suplemen->id) }}" class="btn btn-primary btn-sm" judul="Tambah Data"><i class="fa fa-plus"></i>&ensp;Tambah</a>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed">
                        <tr>
                            <th class="col-md-2">Nama</th>
                            <td>: {{ $suplemen->nama }}</td>
                        </tr>
                        <tr>
                            <th>Sasaran</th>
                            <td>: {{ $sasaran[$suplemen->sasaran] }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>: {{ $suplemen->keterangan }}</td>
                        </tr>
                    </table>
                </div>
                <hr>
                <legend>Daftar Anggota Suplemen</legend>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="suplemen-terdata-table">
                        <thead>
                            <tr>
                                <th style="max-width: 150px;">Aksi</th>
                                <th>Desa</th>
                                <th>No. KK</th>
                                <th>NIK Penduduk</th>
                                <th>Nama Penduduk</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th>Keterangan</th>
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
            var data = $('#suplemen-terdata-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! route('data.data-suplemen.getsuplementerdata', $suplemen->id) !!}",
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'penduduk.desa.nama',
                        name: 'penduduk.desa.nama'
                    },
                    {
                        data: 'penduduk.no_kk',
                        name: 'penduduk.no_kk'
                    },
                    {
                        data: 'penduduk.nik',
                        name: 'penduduk.nik'
                    },
                    {
                        data: 'penduduk.nama',
                        name: 'penduduk.nama'
                    },
                    {
                        data: 'penduduk.tempat_lahir',
                        name: 'penduduk.tempat_lahir'
                    },
                    {
                        data: 'penduduk.tanggal_lahir',
                        name: 'penduduk.tanggal_lahir'
                    },
                    {
                        data: 'penduduk.sex',
                        name: 'penduduk.sex'
                    },
                    {
                        data: 'penduduk.alamat',
                        name: 'penduduk.alamat'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                ],
                order: [
                    [1, 'asc']
                ]
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
