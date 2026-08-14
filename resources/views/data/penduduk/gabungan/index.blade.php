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
                {{-- @include('forms.btn-social', ['export_url' => auth()->user()->can('access.data.penduduk.export') ? route('data.penduduk.export-excel') : null]) --}}
                <button type="button" id="export-btn" class="btn btn-primary btn-sm btn-social" title="{{ $export_text ?? 'Ekspor' }}">
                    <i class="fa fa-upload"></i>{{ $export_text ?? 'Ekspor' }}
                </button>
            </div>

            <div class="box-body">
                @include('layouts.fragments.list-desa')
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="datadesa-table">
                        <thead>
                            <tr>
                                <th style="max-width: 150px;">Aksi</th>
                                <th>Foto</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>No. KK</th>
                                <th>{{ config('setting.sebutan_desa') }}</th>
                                <th>Alamat</th>
                                <th>Pendidikan dalam KK</th>
                                <th>Umur</th>
                                <th>Pekerjaan</th>
                                <th>Status Kawin</th>
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
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/opendk/sync-penduduk-opendk-datatable?' .
                        http_build_query([
                            'filter[kode_kecamatan]' => str_replace('.', '', $profil->kecamatan_id),
                        ]) }}`,
                    headers: {
                        "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                    },
                    method: 'POST',
                    data: function(row) {

                        var selectedDesa = $('#list_desa').val();
                        var searchValue = row.search.value;

                        return {
                            "page[size]": row.length,
                            "page[number]": (row.start / row.length) + 1,
                            "filter[search]": searchValue,
                            "filter[kode_desa]": selectedDesa == 'Semua' ? '' : selectedDesa,
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
                            const _url = data.attributes.path === undefined ?
                                "{{ route('data.penduduk.detail', ['id' => '__ID__']) }}"
                                .replace('__ID__', encodeURIComponent(data.id)) :
                                `{{ url('data/penduduk/show') }}/${data.id}`
                            return `<a href="${_url}" title="Lihat" data-button="show" target="_blank">
                                <button type="button" class="btn btn-warning btn-sm" style="width: 40px;"><i class="fa fa-eye fa-fw"></i></button>
                            </a>`;
                        },
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'attributes.foto',
                        name: 'foto',
                        class: 'text-center',
                        render: function(data, type, row) {

                            const url = getImageUrl(data, row.attributes.sex);

                            return `<img src="${url}" class="img-rounded" alt="Foto Penduduk" height="50"/>`;
                        }
                    },
                    {
                        data: 'attributes.nik',
                        name: 'nik',
                        render: function(data) {
                            return data ? data : '<span class="text-muted">Tidak Ada NIK</span>';
                        }
                    },
                    {
                        data: 'attributes.nama',
                        name: 'nama',
                        render: function(data) {
                            return data ? data : '<span class="text-muted">Tidak Ada Nama</span>';
                        }
                    },
                    {
                        data: 'attributes.keluarga.no_kk',
                        name: 'no_kk',
                        render: function(data, type, row) {
                            // Periksa apakah 'keluarga.no_kk' ada atau tidak
                            if (!data || data === null || data === '') {
                                return '<span class="text-muted">Tidak Ada Nomor KK</span>';
                            }
                            return data; // Tampilkan nomor KK jika ada
                        }
                    },
                    {
                        data: 'attributes.config.nama_desa',
                        name: 'nama_desa',
                        render: function(data) {
                            return data ? data :
                                '<span class="text-muted">Tidak Ada Nama {{ config('setting.sebutan_desa') }}</span>';
                        }
                    },
                    {
                        data: 'attributes.alamat_sekarang',
                        name: 'alamat',
                        render: function(data) {
                            return data ? data : '<span class="text-muted">Tidak Ada Alamat</span>';
                        }
                    },
                    {
                        data: 'attributes.pendidikan_k_k.nama',
                        name: 'pendidikan',
                        render: function(data) {
                            return data ? data :
                                '<span class="text-muted">Tidak Ada Pendidikan</span>';
                        }
                    },
                    {
                        data: 'attributes.umur',
                        name: 'umur',
                        render: function(data) {
                            return data ? data : '<span class="text-muted">Tidak Ada Umur</span>';
                        }
                    },
                    {
                        data: 'attributes.pekerjaan.nama',
                        name: 'pekerjaan',
                        render: function(data) {
                            return data ? data :
                                '<span class="text-muted">Tidak Ada Pekerjaan</span>';
                        }
                    },
                    {
                        data: 'attributes.status_kawin.nama',
                        name: 'status_kawin',
                        render: function(data) {
                            return data ? data :
                                '<span class="text-muted">Tidak Ada Status Kawin</span>';
                        }
                    }

                ],
                order: [
                    [1, 'asc']
                ]
            });

            $('#list_desa').on('select2:select', function(e) {
                data.ajax.reload();
            });

            // Fungsi untuk menentukan URL gambar
            function getImageUrl(data, sex) {
                if (data !== null) {
                    return data; // Jika data tersedia, gunakan data sebagai URL
                }
                // Jika data kosong, gunakan gambar default berdasarkan jenis kelamin
                return sex == 2 ?
                    `{{ asset('img/pengguna/wuser.png') }}` :
                    `{{ asset('img/pengguna/kuser.png') }}`;
            }

            $('#export-btn').on('click', function() {
                var dataTable = $('#datadesa-table').DataTable();
                var params = dataTable.ajax.params(); // Ambil parameter DataTables

                // Buat URL ekspor dengan query string
                var exportUrl = `{{ route('data.penduduk.export-excel') }}?` + $.param(params);

                // Redirect ke URL ekspor
                window.location.href = exportUrl;
            });




        });
    </script>
    @include('forms.datatable-vertical')
@endpush
