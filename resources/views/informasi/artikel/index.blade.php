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
                @include('forms.btn-social', ['create_url' => route('informasi.artikel.create')])
            </div>
            <div class="box-body">
                <!-- Filter Dropdown -->
                <div class="form-group">
                    <label for="filter-kategori">Filter Kategori:</label>
                    <select id="filter-kategori" class="form-control" style="width: 200px;">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategori as $kate)
                            <option value="{{ $kate->id_kategori }}">{{ $kate->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="artikel-table">
                        <thead>
                            <tr>
                                <th style="max-width: 150px;">Aksi</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th style="max-width: 100px;">Status</th>
                                <th>Tanggal Terbit</th>
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
            var data = $('#artikel-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{!! route('informasi.artikel.getdata') !!}",
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'dibuat',
                        name: 'dibuat',
                        class: 'text-center',
                        searchable: false,
                        orderData: 4,
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        class: 'text-center',
                        searchable: false,
                        orderable: false,
                        visible: false
                    },
                ],
                order: [
                    [4, 'desc']
                ]
            });

            // Event listener untuk tombol filter
            $('#filter-kategori').on('change', function() {
                var filterValue = $(this).find(":selected").text();

                // Filter DataTable berdasarkan kolom ke-3 (kolom "Kategori")
                if (filterValue === 'Semua Kategori') {
                    data.search('').columns().search('').draw();
                } else {
                    data.column(2).search(filterValue).draw();
                }
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
