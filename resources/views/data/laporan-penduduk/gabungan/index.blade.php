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
                @include('forms.btn-social', ['export_url' => route('data.laporan-penduduk.export-excel')])
            </div>

            <div class="box-body">
                @include('layouts.fragments.list-desa')
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="datadesa-table">
                        <thead>
                            <tr>
                                <th style="max-width: 150px;">Aksi</th>
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
    </section>
@endsection
@include('partials.asset_select2')
@include('partials.asset_datatables')
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#list_desa').select2();

            var data = $('#datadesa-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/opendk/laporan-penduduk?' .
                        http_build_query([
                            'filter[kode_kecamatan]' => str_replace('.', '', $profil->kecamatan_id),
                        ]) }}`,
                    headers: {
                        "Accept": "application/ld+json",
                        "Content-Type": "text/json; charset=utf-8",
                        "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                    },
                    method: 'get',
                    data: function(row) {
                        var selectedDesa = $('#list_desa').val(); // Ambil nilai kode_desa yang dipilih
                        var searchValue = row.search.value; // Ambil nilai search dari DataTables

                        // Jika searchValue dan selectedDesa kosong, ambil semua data
                        var filterSearch = (searchValue || selectedDesa) ? (searchValue ||
                            selectedDesa) : '';

                        return {
                            "page[size]": row.length,
                            "page[number]": (row.start / row.length) + 1,
                            "filter[search]": filterSearch == 'Semua' ? searchValue : filterSearch, // Gunakan filterSearch di sini
                            "sort": (row.order[0]?.dir === "asc" ? "" : "-") + row.columns[row.order[0]
                                    ?.column]
                                ?.name,
                        };
                    },
                    dataSrc: function(json) {
                        json.recordsTotal = json.meta.pagination.total
                        json.recordsFiltered = json.meta.pagination.total
                        return json.data
                    },
                },
                columns: [{
                        data: function(data) {

                            let d = data.attributes
                            let obj = {
                                'id': data.id,
                                'nama_desa': d.config.nama_desa,
                                'judul': d.judul,
                                'bulan': d.bulan,
                                'tahun': d.tahun,
                                'tanggal_lapor': d.tanggal_lapor,
                            }

                            let jsonData = encodeURIComponent(JSON.stringify(obj));

                            const _url = data.attributes.path === undefined ?
                                `{{ route('data.laporan-penduduk.export-excel.by-id', ['data' => '__DATA__']) }}`
                                .replace('__DATA__', jsonData) :
                                `asset('storage/laporan_penduduk')/${data.nama_file}`
                            const _disabled = data.attributes.path === undefined ? 'disabled' : ''

                            return `<a href="${_url}" title="Unduh" data-button="download" target="_blank">
                                <button type="button" class="btn btn-info btn-sm">download</button>
                            </a>`;
                        },
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'attributes.config.nama_desa',
                        render: function(data) {
                            return data ? data :
                                '<span class="text-muted">Tidak Ada Nama Desa</span>';
                        }
                    },
                    {
                        data: 'attributes.judul',
                        render: function(data) {
                            return data ? data : '<span class="text-muted">Tidak Ada Nama</span>';
                        }
                    },
                    {
                        data: 'attributes.bulan',
                        name: 'bulan',
                        render: function(data) {
                            return data ? data : '<span class="text-muted">Tidak Ada Bulan</span>';
                        }
                    },
                    {
                        data: 'attributes.tahun',
                        name: 'tahun',
                        render: function(data) {
                            return data ? data : '<span class="text-muted">Tidak Ada Tahun</span>';
                        }
                    },
                    {
                        data: 'attributes.tanggal_lapor',
                        render: function(data) {
                            return data ? data :
                                '<span class="text-muted">Tidak Ada Tanggal Lapor</span>';
                        }
                    },

                ],
                order: [
                    [1, 'asc']
                ]
            });

            // Event saat list desa berubah
            $('#list_desa').on('select2:select', function(e) {
                let selectedDesa = $(this).val(); // Ambil nilai kode_desa yang dipilih

                data.ajax.reload(); // Reload tabel dengan filter baru
            });

        });
    </script>
    @include('forms.datatable-vertical')
@endpush
