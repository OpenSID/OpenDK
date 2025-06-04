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
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Desa</label>
                        <select class="form-control" id="list_desa">
                            <option value="">Semua Desa</option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover dataTable" id="pembangunan-table">
                        <thead>
                            <tr>
                                <th>Aksi</th>
                                <th>Nama Kegiatan</th>
                                <th>Sumber Dana</th>
                                <th>Anggaran</th>
                                <th>Volume</th>
                                <th>Tahun</th>
                                <th>Pelaksana</th>
                                <th>Lokasi</th>
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
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}/api/v1/opendk/desa/{{ str_replace('.', '', $profil->kecamatan_id) }}`,
                    method: "GET",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                        "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                    },
                    success: function(response) {
                        $('#list_desa').empty().append('<option value="">Semua Desa</option>');
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

            loadDesa();
            $('#list_desa').select2();

            var data = $('#pembangunan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/opendk/pembangunan?' .
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
                            "fields[pembangunan]": "*",
                            "sort": (row.order[0]?.dir === "asc" ? "" : "-") + row.columns[row.order[0]
                                ?.column]?.name,
                        };
                    },
                    dataSrc: function(json) {
                        json.recordsTotal = json.meta.pagination.total;
                        json.recordsFiltered = json.meta.pagination.total;

                        return json.data.map(item => ({
                            aksi: `<a href="{{ url('data/pembangunan/rincian') }}/${item.id}/${item.attributes.config.kode_desa}" class="btn btn-primary btn-sm">Detail</a>`,
                            judul: item.attributes.judul,
                            sumber_dana: item.attributes.sumber_dana || 'N/A',
                            anggaran: item.attributes.anggaran || 'N/A',
                            volume: item.attributes.volume || '-',
                            tahun_anggaran: item.attributes.tahun_anggaran || '-',
                            pelaksana_kegiatan: item.attributes.pelaksana_kegiatan || '-',
                            lokasi: item.attributes.lokasi || '-'
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
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'sumber_dana',
                        name: 'sumber_dana'
                    },
                    {
                        data: 'anggaran',
                        name: 'anggaran',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'volume',
                        name: 'volume',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tahun_anggaran',
                        name: 'tahun_anggaran',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'pelaksana_kegiatan',
                        name: 'pelaksana_kegiatan',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [1, 'asc']
                ]
            });

            $('#list_desa').on('select2:select', function() {
                data.ajax.reload();
            });
        });
    </script>
@endpush
