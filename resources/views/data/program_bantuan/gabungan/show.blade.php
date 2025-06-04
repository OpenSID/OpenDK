@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('data.program-bantuan.index') }}">Program Bantuan</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed">
                        <tr>
                            <th class="col-md-2">Nama</th>
                            <td id="program-name">: Loading...</td>
                        </tr>
                        <tr>
                            <th>Desa</th>
                            <td id="program-desa">: Loading...</td>
                        </tr>
                        <tr>
                            <th>Sasaran</th>
                            <td id="program-sasaran">: Loading...</td>
                        </tr>
                        <tr>
                            <th>Periode Program</th>
                            <td id="program-period">: Loading...</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td id="program-description">: Loading...</td>
                        </tr>
                    </table>
                </div>
                <hr>
                <legend>Daftar Peserta Program</legend>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover dataTable" id="program-table">
                        <thead>
                            <tr>
                                <th style="max-width: 150px;" rowspan="2" valign="center">Aksi</th>
                                <th rowspan="2">No. KK / NIK</th>
                                <th rowspan="2">Kepala Keluarga / Nama Peserta</th>
                                <th colspan="5" class="text-center">Identitas di Kartu Peserta</th>
                            </tr>
                            <tr>
                                <th>No. Kartu Peserta</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>Alamat</th>
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
    <script>
        $(document).ready(function() {
            let idProgram = '{{ $id }}';
            let kodeDesa = '{{ $desa_id }}';

            // Ambil data program dan peserta secara bersamaan menggunakan AJAX
            $.ajax({
                url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/opendk/bantuan/${idProgram}?' .
                    http_build_query([
                        'filter[kode_kecamatan]' => str_replace('.', '', $profil->kecamatan_id),
                    ]) }}`,
                method: 'GET',
                headers: {
                    "Accept": "application/ld+json",
                    "Content-Type": "application/json; charset=utf-8",
                    "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                },
                success: function(response) {
                    // Tampilkan data program
                    if (response.data) {
                        $('#program-name').text(": " + response.data.attributes.nama);
                        $('#program-desa').text(": " + response.data.attributes.desa);
                        $('#program-sasaran').text(": " + response.data.attributes.nama_sasaran);
                        $('#program-period').text(": " + response.data.attributes.masa_berlaku);
                        $('#program-description').text(": " + response.data.attributes.ndesc);
                    }
                },
                error: function() {
                    $('#program-name').text(": Error loading data");
                    $('#program-desa').text(": Error loading data");
                    $('#program-sasaran').text(": Error loading data");
                    $('#program-period').text(": Error loading data");
                    $('#program-description').text(": Error loading data");
                }
            });

            // Mengambil data peserta
            var data = $('#program-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/opendk/bantuan-peserta/${idProgram}/${kodeDesa}?' .
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
                        return {
                            "page[size]": row.length,
                            "page[number]": (row.start / row.length) + 1,
                            "filter[search]": row.search.value,
                            "fields[program_peserta]": "*",
                            "sort": (row.order[0]?.dir === "asc" ? "" : "-") + row.columns[row.order[0]
                                ?.column]?.name,
                        };
                    },
                    dataSrc: function(json) {
                        json.recordsTotal = json.meta.pagination.total;
                        json.recordsFiltered = json.meta.pagination.total;

                        return json.data.map(item => ({
                            peserta: item.attributes.peserta,
                            nama: item.attributes.nama,
                            no_id_kartu: item.attributes.no_id_kartu,
                            kartu_nik: item.attributes.kartu_nik,
                            kartu_nama: item.attributes.kartu_nama,
                            kartu_tempat_lahir: item.attributes.kartu_tempat_lahir,
                            kartu_tanggal_lahir: item.attributes.kartu_tanggal_lahir,
                            kartu_alamat: item.attributes.kartu_alamat,
                        }));
                    },
                },
                columnDefs: [{
                    targets: [0, 1, 2],
                    orderable: false,
                    searchable: false,
                }, ],
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Menampilkan nomor urut
                        },
                        title: "No", // Judul kolom
                    },
                    {
                        data: 'peserta',
                        name: 'peserta'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_id_kartu',
                        name: 'no_id_kartu'
                    },
                    {
                        data: 'kartu_nik',
                        name: 'kartu_nik'
                    },
                    {
                        data: 'kartu_nama',
                        name: 'kartu_nama'
                    },
                    {
                        data: 'kartu_tempat_lahir',
                        name: 'kartu_tempat_lahir'
                    },
                    {
                        data: 'kartu_tanggal_lahir',
                        name: 'kartu_tanggal_lahir'
                    },
                    {
                        data: 'kartu_alamat',
                        name: 'kartu_alamat'
                    },
                ],
                order: [
                    [5, 'asc']
                ]
            });

        });
    </script>
@endpush
