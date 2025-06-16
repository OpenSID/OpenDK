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
                <a href="{{ route('informasi.artikel-kategori.create') }}" class="btn btn-primary btn-sm" judul="Tambah Data"><i class="fa fa-plus"></i>&ensp;Tambah</a>
            </div>
            <div class="box-body">
                <!-- Filter Dropdown -->
                <div class="form-group">
                    <label for="filter-status">Filter Status:</label>
                    <select id="filter-status" class="form-control" style="width: 200px;">
                        <option value="All">Semua Status</option>
                        <option value="Ya">Aktif</option>
                        <option value="Tidak">Tidak Aktif</option>
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="datatable-artikel-kategori" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="max-width: 150px;">Aksi</th>
                                <th>Nama Kategori</th>
                                <th>Slug</th>
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
            var table = $('#datatable-artikel-kategori').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{!! route('informasi.artikel-kategori.getdata') !!}",
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_kategori',
                        name: 'nama_kategori'
                    },
                    {
                        data: 'slug',
                        name: 'slug'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: false
                    },
                ]
            });

            $('#filter-status').on('change', function() {
                var filterValue = $(this).val(); // Ambil value yang dipilih

                // Lakukan request dengan filter status yang dipilih
                table.ajax.url('{!! route('informasi.artikel-kategori.getdata') !!}?status=' + filterValue).load();
            });


        });
    </script>

    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
