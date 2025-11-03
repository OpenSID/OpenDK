@extends('layouts.dashboard_template')

@section('title')
    Permohonan Surat
@endsection

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
        <div class="alert alert-warning alert-dismissible">
            <h4><i class="icon fa fa-warning"></i> Info Penting!</h4>
            Fitur Sinkronisasi Surat TTE ke kecamatan saat ini masih berupa demo menunggu proses penyempurnaan dan terdapat
            kecamatan yang sudah mengimplementasikan TTE.
            Kami juga mengimbau kepada seluruh pengguna memberikan masukan terkait penyempurnaan fitur ini baik dari sisi
            OpenSID maupun OpenDK.
            Masukan dapat disampaikan di grup telegram, forum opendesa maupun issue di github.
        </div>
        @include('surat.permohonan.widget')

        <div class="box box-primary">
            @include('partials.flash_message')
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="pengurus-table">
                        <thead>
                            <tr>
                                <th style="min-width: 80px;">Aksi</th>
                                <th>{{ config('setting.sebutan_desa') }}</th>
                                <th>Nama Surat</th>
                                <th>Nama Penduduk</th>
                                <th>Ditandatangani oleh</th>
                                <th>Tanggal Surat</th>
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
        $(document).ready(function () {
            var data = $('#pengurus-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ $settings['api_server_database_gabungan'] }}/api/v1/opendk/permohonan-surat?`,
                    headers: {
                        "Accept": "application/ld+json",
                        "Content-Type": "text/json; charset=utf-8",
                        "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                    },
                    method: 'get',
                    data: function (row) {
                        var searchValue = row.search.value;

                        return {
                            "page[size]": row.length,
                            "page[number]": (row.start / row.length) + 1,
                            "filter[search]": searchValue,
                        };

                    },
                    dataSrc: function (json) {
                        json.recordsTotal = json.meta.pagination.total
                        json.recordsFiltered = json.meta.pagination.total
                        return json.data
                    },
                },
                columns: [
                    {
                        data: function (data) {

                            const _url = `{{ url('data/keluarga/show') }}/${data.id}`

                            return `<a href="${_url}" title="Lihat" data-button="show">
                                                <button type="button" class="btn btn-warning btn-sm" style="width: 40px;"><i class="fa fa-eye fa-fw"></i></button>
                                            </a>`;
                        },
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'attributes.nama_desa',
                        name: 'config.nama_desa',
                        defaultContent: '-'
                    },
                    {
                        data: 'attributes.nama_surat',
                        name: 'formatSurat.nama_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'attributes.nama_penduduk',
                        name: 'penduduk.nama_penduduk',
                        defaultContent: '-'
                    },
                    {
                        data: 'attributes.ditandatangani_oleh',
                        name: 'nama_pamong',
                        defaultContent: '-'
                    },
                    {
                        data: 'attributes.tanggal_surat',
                        name: 'tanggal_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'attributes.status',
                        name: 'status',
                        defaultContent: '-'
                    },

                ],
                order: [
                    [5, 'asc']
                ]
            });

            // $('#list_desa').on('select2:select', function (e) {
            //     data.ajax.reload();
            // });
        });
    </script>
    @include('forms.datatable-vertical')
@endpush