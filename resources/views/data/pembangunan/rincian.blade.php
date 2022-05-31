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
            <div class="box-body">
                <h5 class="text-bold">Rincian Dokumentasi Pembangunan</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover tabel-rincian">
                        <tbody>
                            <tr>
                                <td width="20%">Nama Kegiatan</td>
                                <td width="1">:</td>
                                <td>{{ $pembangunan->judul }}</td>
                            </tr>
                            <tr>
                                <td>Sumber Dana</td>
                                <td> : </td>
                                <td>{{ $pembangunan->sumber_dana }}</td>
                            </tr>
                            <tr>
                                <td>Lokasi Pembangunan</td>
                                <td> : </td>
                                <td>{{ $pembangunan->lokasi }}</td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td> : </td>
                                <td>{{ $pembangunan->keterangan }}</td>
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
    <script type="text/javascript">
        $(document).ready(function() {
            var data = $('#pembangunan-table').DataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('data.pembangunan.getrinciandata', ['id' => $pembangunan->id, 'desa_id' => $pembangunan->desa_id]) !!}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        class: 'text-center',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'persentase',
                        name: 'persentase',
                        class: 'text-center',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        class: 'text-center',
                        searchable: false,
                        orderable: true
                    },
                ],

            });
        });
    </script>
    @include('forms.datatable-vertical')
@endpush
