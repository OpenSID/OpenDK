@extends('layouts.dashboard_template')
@push('css')
    <style>
        .nowrap {
            white-space: nowrap;
        }
    </style>
@endpush
@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('data.lembaga.index') }}"> Daftar Lembaga</a></li>
            <li class="active">{{ $page_title }} </li>
        </ol>
    </section>

    <section class="content container-fluid">

        @include('partials.flash_message')

        <div class="box box-primary">
            <div class="box-header with-border">
                @include('forms.btn-social', [
                    'create_url' => route('data.lembaga_anggota.create', $lembaga->slug),
                ])
            </div>
            <div class="box-body">

                <h4 class="box-title" style="padding: 5px 0px 10px 0px">Rincian Lembaga</h4>
                <table class="table table-striped" style="width: 100%">
                    <tbody>
                        <tr>
                            <td style="width: 20%">Kode Lembaga</td>
                            <td style="width: 1%">:</td>
                            <td>{{ $lembaga->kode }}</td>
                        </tr>
                        <tr>
                            <td>Nama Lembaga</td>
                            <td>:</td>
                            <td>{{ $lembaga->nama }}</td>
                        </tr>
                        <tr>
                            <td>Ketua Lembaga</td>
                            <td>:</td>
                            <td>{{ $lembaga->penduduk->nama }}</td>
                        </tr>
                        <tr>
                            <td>Kategori Lembaga</td>
                            <td>:</td>
                            <td>{{ $lembaga->lembagaKategori->nama }}</td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td>:</td>
                            <td>{{ $lembaga->keterangan ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>

                <h4 class="box-title" style="padding: 15px 0px 10px 0px">Anggota Lembaga</h4>
                <div class="table-responsive">
                    <table class="table nowrap table-striped table-bordered" id="lembaga-anggota-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="max-width: 150px;">Aksi</th>
                                {{-- <th>Foto</th> --}}
                                <th>No. Anggota</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Tempat/Tanggal Lahir</th>
                                <th>Umur (Tahun)</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th>Jabatan</th>
                                <th>No. SK Jabatan</th>
                                <th>No. SK Pengangkatan</th>
                                <th>Tanggal SK Pengangkatan</th>
                                <th>No. SK Pemberhentian</th>
                                <th>Tanggal SK Pemberhentian</th>
                                <th>Periode Masa Jabatan</th>
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
            var data = $('#lembaga-anggota-table').DataTable({
                processing: true,
                serverSide: false,
                ordering: false,
                scrollX: true,
                ajax: "{!! route('data.lembaga_anggota.getdata', $lembaga->slug) !!}",
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'no_anggota',
                        name: 'no_anggota'
                    },
                    {
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'tempat_tgl_lahir',
                        name: 'tempat_tgl_lahir'
                    },
                    {
                        data: 'umur',
                        name: 'umur'
                    },
                    {
                        data: 'sex',
                        name: 'sex'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'jabatan',
                        name: 'jabatan'
                    },
                    {
                        data: 'no_sk_jabatan',
                        name: 'no_sk_jabatan'
                    },
                    {
                        data: 'no_sk_pengangkatan',
                        name: 'no_sk_pengangkatan'
                    },
                    {
                        data: 'tgl_sk_pengangkatan',
                        name: 'tgl_sk_pengangkatan'
                    },
                    {
                        data: 'no_sk_pemberhentian',
                        name: 'no_sk_pemberhentian'
                    },
                    {
                        data: 'tgl_sk_pemberhentian',
                        name: 'tgl_sk_pemberhentian'
                    },
                    {
                        data: 'periode',
                        name: 'periode'
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
