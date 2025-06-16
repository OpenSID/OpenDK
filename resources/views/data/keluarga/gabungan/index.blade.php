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
                @include('layouts.fragments.list-desa')
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="datadesa-table">
                        <thead>
                            <tr>
                                <th style="max-width: 150px;">Aksi</th>
                                <th>Foto</th>
                                <th>NO KK</th>
                                <th>Nama Kepala</th>
                                <th>Tanggal Daftar</th>
                                <th>Tanggal Cetak KK</th>
                                <th>Desa</th>
                                <th>Alamat</th>
                                <th>RW</th>
                                <th>RT</th>
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
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/keluarga?' .
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

                            const _url = `{{ url('data/keluarga/show') }}/${data.id}`

                            return `<a href="${_url}" title="Lihat" data-button="show">
                                <button type="button" class="btn btn-warning btn-sm" style="width: 40px;"><i class="fa fa-eye fa-fw"></i></button>
                            </a>`;
                        },
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'attributes.foto',
                        class: 'text-center',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {

                            const url = getImageUrl(data, row.attributes.sex);

                            return `<img src="${url}" class="img-rounded" alt="Foto Penduduk" height="50"/>`;
                        }
                    },
                    {
                        data: 'attributes.no_kk',
                        render: function(data) {
                            return data ? data : '<span class="text-muted">Tidak Ada NO KK</span>';
                        }
                    },
                    {
                        data: 'attributes.nama_kk',
                    },
                    {
                        data: 'attributes.tgl_daftar',
                    },
                    {
                        data: 'attributes.tgl_cetak_kk',
                    },
                    {
                        data: 'attributes.desa',
                    },
                    {
                        data: 'attributes.alamat_plus_dusun',
                    },
                    {
                        data: 'attributes.rt',
                    },
                    {
                        data: 'attributes.rw',
                    },
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



        });
    </script>
    @include('forms.datatable-vertical')
@endpush
