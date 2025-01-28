@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header">
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
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Desa</label>
                            <select class="form-control" id="list_desa">
                                <option value="Semua">Semua Desa</option>
                                @foreach ($list_desa as $desa)
                                    <option value="{{ $desa->kode_desa }}">{{ $desa->nama_desa }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
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
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/opendk/laporan-penduduk?'.http_build_query([
                                'kode_kecamatan' => str_replace('.','',$profil->kecamatan_id),
                            ]) }}`,
                    headers: {
                            "Accept" : "application/ld+json",
                            "Content-Type": "text/json; charset=utf-8",
                            "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                        },
                    method: 'get',
                    data: function(row) {
                        return {
                            "page[size]": row.length,
                            "page[number]": (row.start / row.length) + 1,
                            "filter[search]": row.search.value,
                            "sort": (row.order[0]?.dir === "asc" ? "" : "-") + row.columns[row.order[0]?.column]
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
                            const _url = data.attributes.path === undefined ? `javascript:void(0)` : `asset('storage/laporan_penduduk')/${data.nama_file}`
                            const _disabled = data.attributes.path === undefined ? 'disabled' : '' 
                            return `<a href="${_url}" title="Unduh" data-button="download" target="_blank">
                                <button type="button" class="btn btn-info btn-sm" style="width: 40px;" ${_disabled}>download</button>
                            </a>`;
                        },               
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'attributes.config.nama_desa',
                        render: function(data) {
                            return data ? data : '<span class="text-muted">Tidak Ada Nama Desa</span>';
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
                            return data ? data : '<span class="text-muted">Tidak Ada Tanggal Lapor</span>';
                        }
                    },

                ],
                order: [
                    [1, 'asc']
                ]
            });     

            $('#list_desa').on('select2:select', function(e) {
                data.ajax.reload();
            });
            
        });
    </script>
    @include('forms.datatable-vertical')    
@endpush