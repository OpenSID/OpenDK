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
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Desa</label>
                        <select class="form-control" id="list_desa">
                            <option value="">Semua Desa</option>
                            @foreach ($list_desa as $desa)
                                <option value="{{ $desa->kode_desa }}">{{ $desa->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover dataTable" id="program-table">
                        <thead>
                            <tr>
                                <th style="max-width: 150px;">Aksi</th>
                                <th>Nama Program</th>
                                <th>Desa</th>
                                <th>Masa Berlaku</th>
                                <th>Sasaran</th>
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
            function loadDesa() {
                $.ajax({
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/opendk/desa/' . str_replace('.', '', $profil->kecamatan_id) }}`,
                    method: "GET",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                        "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                    },
                    success: function(response) {
                        // Bersihkan select box
                        $('#list_desa').empty().append('<option value="">Semua Desa</option>');

                        // Tambahkan data desa ke select box
                        response.data.forEach(desa => {
                            $('#list_desa').append(
                                `<option value="${desa.attributes.kode_desa}">${desa.attributes.nama_desa}</option>`
                            );
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Gagal memuat daftar desa:", error);
                    }
                });
            }

            // Panggil fungsi loadDesa untuk memuat data saat halaman dimuat
            loadDesa();

            // Inisialisasi Select2 untuk dropdown desa
            $('#list_desa').select2();

            // Konfigurasi DataTables
            var data = $('#program-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/opendk/bantuan?' .
                        http_build_query([
                            'filter[kode_kecamatan]' => str_replace('.', '', $profil->kecamatan_id),
                        ]) }}`,
                    headers: {
                        "Accept": "application/ld+json",
                        "Content-Type": "application/json; charset=utf-8",
                        "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                    },
                    method: 'GET',
                    data: function(row) {
                        const desaId = $('#list_desa').val();
                        return {
                            "page[size]": row.length,
                            "page[number]": (row.start / row.length) + 1,
                            "filter[kode_desa]": desaId || '',
                            "filter[search]": row.search.value,
                            "fields[program]": "*",
                            "sort": (row.order[0]?.dir === "asc" ? "" : "-") + row.columns[row.order[0]
                                ?.column]?.name,
                        };
                    },
                    dataSrc: function(json) {
                        json.recordsTotal = json.meta.pagination.total;
                        json.recordsFiltered = json.meta.pagination.total;

                        return json.data.map(item => ({
                            aksi: `<a href="{{ url('data/program-bantuan/show') }}/${item.id}/${item.attributes.kode_desa}/${item.attributes.nama}" class="btn btn-primary btn-sm">Detail</a>`,
                            nama: item.attributes.nama,
                            desa: item.attributes.desa || 'N/A',
                            masa_berlaku: item.attributes.masa_berlaku || '-',
                            nama_sasaran: item.attributes.nama_sasaran || '-'
                        }));
                    },
                },
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        class: 'text-center'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'desa',
                        name: 'desa',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'masa_berlaku',
                        name: 'masa_berlaku',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_sasaran',
                        name: 'nama_sasaran',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [1, 'asc']
                ]
            });

            // Reload data tabel ketika desa berubah
            $('#list_desa').on('select2:select', function() {
                data.ajax.reload();
            });
        });
    </script>
@endpush
