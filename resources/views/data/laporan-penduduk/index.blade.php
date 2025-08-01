@extends('layouts.dashboard_template')

@section('content')
    <!-- Content Header (Page header) -->
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
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        @include('forms.btn-social', [
                            'import_url' => route('data.laporan-penduduk.import'),
                        ])
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        @include('layouts.fragments.list-desa')
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="table-data">
                                        <thead>
                                            <tr>
                                                <th style="max-width: 100px;">Aksi</th>
                                                <th>Desa</th>
                                                <th>Nama</th>
                                                <th>Bulan</th>
                                                <th>Tahun</th>
                                                <th>Tgl. Lapor</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
            var data = $('#table-data').DataTable({
                autoWidth: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('data.laporan-penduduk.getdata') !!}",
                    data: function(d) {
                        d.desa = $('#list_desa').val();
                    }
                },
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        "searchable": false,
                        "orderable": false
                    },
                    {
                        data: 'nama_desa',
                        name: 'nama_desa'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'bulan',
                        name: 'bulan'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'imported_at',
                        name: 'imported_at'
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
    @include('forms.delete-modal')
@endpush
