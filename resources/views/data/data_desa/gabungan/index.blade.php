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
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="datadesa-table">
                        <thead>
                            <tr>
                                <th style="max-width: 150px;">Aksi</th>
                                <th>Kode Desa</th>
                                <th>Nama Desa</th>
                                <th>Website</th>
                                <th>Luas Wilayah (km<sup>2</sup>)</th>
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
            var data = $('#datadesa-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/desa?' .
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
                        return {
                            "page[size]": row.length,
                            "page[number]": (row.start / row.length) + 1,
                            "filter[search]": row.search.value,
                            "fields[config]": "id,kode_desa,nama_desa,website,path",
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
                            const _url = data.attributes.path === null ? `javascript:void(0)` :
                                `{{ url('data/data-desa/peta') }}/${data.id}`
                            const _disabled = data.attributes.path === null ? 'disabled' : ''
                            return `<a href="${_url}" class="${_disabled}" title="Peta" data-button="peta" target="_blank">
                                <button type="button" class="btn btn-info btn-sm" style="width: 40px;"><i class="fa fa-map" aria-hidden="true"></i></button>
                            </a>`;

                        },
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'attributes.kode_desa',
                        name: 'kode_desa'
                    },
                    {
                        data: 'attributes.nama_desa',
                        name: 'nama_desa'
                    },
                    {
                        data: 'attributes.website',
                        name: 'website'
                    },
                    {
                        data: 'attributes.luas_wilayah',
                        name: 'luas_wilayah',
                        searchable: false,
                        orderable: false,
                        defaultContent: ''
                    },
                ],
                order: [
                    [1, 'asc']
                ]
            });
        });
    </script>
    @include('forms.datatable-vertical')
@endpush
