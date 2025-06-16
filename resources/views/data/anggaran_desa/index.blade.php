@extends('layouts.dashboard_template')

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
                @include('forms.btn-social', ['import_url' => route('data.anggaran-desa.import')])
            </div>
            <div class="box-body">
                @include('layouts.fragments.list-desa')
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover dataTable" id="anggaran-table">
                        <thead>
                            <tr>
                                <th style="max-width: 100px;">Aksi</th>
                                <th>Desa</th>
                                <th>No Akun</th>
                                <th>Nama Akun</th>
                                <th>Jumlah</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
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
            var data = $('#anggaran-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('data.anggaran-desa.getdata') !!}",
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
                        data: 'desa.nama',
                        name: 'desa.nama'
                    },
                    {
                        data: 'no_akun',
                        name: 'no_akun'
                    },
                    {
                        data: 'nama_akun',
                        name: 'nama_akun'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah',
                        class: 'text-right'
                    },
                    {
                        data: 'bulan',
                        name: 'bulan'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun'
                    },
                ],
                order: [
                    [1, 'asc']
                ]
            });

            $('#list_desa').on('select2:select', function(e) {
                data.ajax.reload();
            });
        });
    </script>
    @include('forms.datatable-vertical')
@endpush
