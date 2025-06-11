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
                    <table class="table table-bordered table-hover dataTable" id="anggaran-table">
                        <thead>
                            <tr>
                                <th>Desa</th>
                                <th>No Akun</th>
                                <th>Nama Akun</th>
                                <th>Jumlah</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
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
            var data = $('#anggaran-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/keuangan/apbdes?' .
                        http_build_query([
                            'filter[kode_kecamatan]' => str_replace('.', '', $profil->kecamatan_id),
                        ]) }}`,
                    headers: {
                        "Accept": "application/ld+json",
                        "Content-Type": "text/json; charset=utf-8",
                        "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                    },
                    data: function(row) {
                        return {
                            "page[size]": row.length,
                            "page[number]": (row.start / row.length) + 1,
                            "filter[search]": row.search.value,
                            "filter[config_id]": $('#list_desa').val(),
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
                        data: 'attributes.nama_desa',
                        name: 'config.nama_desa'
                    },
                    {
                        data: 'attributes.template_uuid',
                        name: 'template_uuid'
                    },
                    {
                        data: 'attributes.uraian',
                        name: 'keuangan_template.uraian',
                    },
                    {
                        data: 'attributes.anggaran_local',
                        name: 'anggaran',
                        class: 'text-right',
                    },
                    {
                        data: null,
                        name: null,
                        defaultContent: '-',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'attributes.tahun',
                        name: 'tahun'
                    },
                ],
                order: [
                    [5, 'desc']
                ],

            });
            $('#list_desa').on('select2:select', function(e) {
                data.ajax.reload();
            });
        });
    </script>
@endpush
