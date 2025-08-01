@extends('layouts.dashboard_template')

@section('title')
    Data Profil
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
            <div class="box-body">
                @include('layouts.fragments.list-desa')
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="keluarga-table">
                        <thead>
                            <tr>
                                <th style="max-width: 100px;">Aksi</th>
                                <th>Foto</th>
                                <th>No. KK</th>
                                <th>Nama Kepala</th>
                                <th>Tanggal Daftar</th>
                                <th>Tanggal Cetak KK</th>
                                <th>Desa</th>
                                <th>Alamat</th>
                                <th>RW</th>
                                <th>RT</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('partials.asset_select2')
@include('partials.asset_datatables')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#list_desa').select2();

            var data = $('#keluarga-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('data.keluarga.getdata') !!}",
                    data: function(d) {
                        d.desa = $('#list_desa').val();
                    }
                },
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'foto',
                        name: 'foto',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'no_kk',
                        name: 'no_kk'
                    },
                    {
                        data: 'kepala_kk.nama',
                        name: 'kepala_kk.nama'
                    },
                    {
                        data: 'tgl_daftar',
                        name: 'tgl_daftar'
                    },
                    {
                        data: 'tgl_cetak_kk',
                        name: 'tgl_cetak_kk'
                    },
                    {
                        data: 'desa.nama',
                        name: 'desa.nama',
                        render: (data) => data == null ? '' : data
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'rw',
                        name: 'rw'
                    },
                    {
                        data: 'rt',
                        name: 'rt'
                    },
                ],
                order: [
                    [2, 'asc']
                ]
            });

            $('#list_desa').on('select2:select', function(e) {
                data.ajax.reload();
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
