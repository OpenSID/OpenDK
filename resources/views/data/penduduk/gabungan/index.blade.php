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
                {{-- @include('forms.btn-social', ['export_url' => route('data.penduduk.export-excel')]) --}}
                <button type="button" id="export-btn" class="btn btn-primary btn-sm btn-social" title="{{ $export_text ?? 'Ekspor' }}">
                    <i class="fa fa-download"></i>{{ $export_text ?? 'Ekspor' }}
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
                                <th>Desa</th>
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
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/opendk/sync-penduduk-opendk?' .
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
                            let d = data.attributes
                            let obj = {
                                'id': data.id,
                                'nama': d.nama,
                                'nik': d.nik,
                                'no_kk_sebelumnya': d.no_kk_sebelumnya,
                                'hubungan_dalam_keluarga': d.penduduk_hubungan?.nama ?? null,
                                'jenis_kelamin': d.jenis_kelamin?.nama ?? null,
                                'agama': d.agama.nama,
                                'status_penduduk': d.penduduk_status?.nama ?? null,
                                'akta_lahir': d.akta_lahir,
                                'tempat_lahir': d.tempatlahir,
                                'tanggal_lahir': d.tanggallahir,
                                'tanggal_lahir': d.tanggallahir,
                                'wajib_ktp': d.wajibKTP,
                                'status_rekam': d.status_rekam_ktp?.nama ?? null,
                                'elktp': d.elKTP,
                                'pendidikan_dalam_kk': d.pendidikan_k_k?.nama ?? null,
                                'pendidikan_sedang_ditempuh': d.pendidikan?.nama ?? null,
                                'pekerjaan': d.pekerjaan?.nama ?? null,
                                'warga_negara': d.warga_negara?.nama ?? null,
                                'nomor_passport': d.dokumen_pasport,
                                'tanggal_akhir_passport': d.tanggal_akhir_paspor,
                                'nomor_kitas': d.dokumen_kitas,
                                'nik_ayah': d.ayah_nik,
                                'nama_ayah': d.nama_ayah,
                                'nik_ibu': d.ibu_nik,
                                'nama_ibu': d.nama_ibu,
                                'nomor_telepon': d.telepon,
                                'alamat_sebelumnya': d.alamat_sebelumnya,
                                'alamat_sekarang': d.alamat_sekarang,
                                'status_kawin': d.status_kawin?.nama ?? null,
                                'no_akta_nikah': d.akta_perkawinan,
                                'tanggal_nikah': d.tanggalperkawinan,
                                'akta_perceraian': d.akta_perceraian,
                                'tanggal_perceraian': d.tanggalperceraian,
                                'golongan_darah': d.golongan_darah?.nama ?? null,
                                'cacat': d.cacat?.nama ?? null,
                                'sakit_menahun': d.sakit_menahun?.nama ?? null,
                                'cara_kb': d.kb?.nama ?? null,
                                'status_kehamilan': d.statusHamil
                            }

                            let jsonData = encodeURIComponent(JSON.stringify(obj));

                            const _url = data.attributes.path === undefined ?
                                "{{ route('data.penduduk.detail', ['data' => '__DATA__']) }}"
                                .replace('__DATA__', jsonData) :
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
                                '<span class="text-muted">Tidak Ada Nama Desa</span>';
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
