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
        <div class="box box-primary">
            <div class="box-body">
                <h5 class="text-bold">Rincian Dokumentasi Pembangunan</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td width="20%">Nama Kegiatan</td>
                                <td width="1">:</td>
                                <td id="nama-kegiatan">Loading...</td>
                            </tr>
                            <tr>
                                <td>Sumber Dana</td>
                                <td>:</td>
                                <td id="sumber-dana">Loading...</td>
                            </tr>
                            <tr>
                                <td>Lokasi Pembangunan</td>
                                <td>:</td>
                                <td id="lokasi-pembangunan">Loading...</td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>:</td>
                                <td id="keterangan">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="pembangunan-table">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Presentase</th>
                                <th>Keterangan</th>
                                <th>Tanggal Rekam</th>
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
            let idPembangunan = '{{ $id }}';
            let kodeDesa = '{{ $desa_id }}';

            // Ambil data pembangunan dari API
            $.ajax({
                url: `{{ $settings['api_server_database_gabungan'] ?? '' }}/api/v1/opendk/pembangunan/${idPembangunan}`,
                headers: {
                    "Accept": "application/ld+json",
                    "Content-Type": "application/json; charset=utf-8",
                    "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                },
                method: 'GET',
                success: function(response) {
                    if (response.data) {
                        $('#nama-kegiatan').text(response.data.attributes.judul);
                        $('#sumber-dana').text(response.data.attributes.sumber_dana);
                        $('#lokasi-pembangunan').text(response.data.attributes.lokasi);
                        $('#keterangan').text(response.data.attributes.keterangan);
                    }
                },
                error: function() {
                    $('#nama-kegiatan, #sumber-dana, #lokasi-pembangunan, #keterangan').text(
                        "Error loading data");
                }
            });

            // Ambil data rincian pembangunan
            $('#pembangunan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}/api/v1/opendk/pembangunan-rincian/${idPembangunan}/${kodeDesa}`,
                    headers: {
                        "Accept": "application/ld+json",
                        "Content-Type": "application/json; charset=utf-8",
                        "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                    },
                    method: 'GET',
                    dataSrc: function(json) {
                        json.recordsTotal = json.meta.pagination.total;
                        json.recordsFiltered = json.meta.pagination.total;

                        return json.data.map(item => ({
                            DT_RowIndex: item.attributes.nomor,
                            persentase: item.attributes.persentase,
                            keterangan: item.attributes.keterangan,
                            created_at: item.attributes.created_at,
                        }));
                    }
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Menampilkan nomor urut otomatis
                        },
                        class: 'text-center',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'persentase',
                        class: 'text-center',
                        orderable: true
                    },
                    {
                        data: 'keterangan',
                        class: 'text-center',
                        orderable: false
                    },
                    {
                        data: 'created_at',
                        class: 'text-center',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
