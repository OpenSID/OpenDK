@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li>PPID</li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <section class="content container-fluid">

        @include('partials.flash_message')

        <div class="box box-primary">
            <div class="box-header with-border">
                @include('forms.btn-social', ['create_url' => route('ppid.permohonan.create')])

                <!-- Filter Status -->
                <div class="pull-right" style="margin-left: 10px;">
                    <select id="filter-status" class="form-control" style="width: 150px;">
                        <option value="">- Semua Status -</option>
                        <option value="MENUNGGU">Menunggu</option>
                        <option value="DIPROSES">Diproses</option>
                        <option value="SELESAI">Selesai</option>
                        <option value="DITOLAK">Ditolak</option>
                    </select>
                </div>
            </div>

            <div class="box-body">
                <table class="table table-striped table-bordered" id="data_ppid_permohonan">
                    <thead>
                        <tr>
                            <th style="max-width: 150px;">Aksi</th>
                            <th>Nama Pemohon</th>
                            <th>NIK</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Tanggal Permohonan</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
@endsection

@include('partials.asset_datatables')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#data_ppid_permohonan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('ppid.permohonan.getdata') !!}",
                    data: function(d) {
                        d.status = $('#filter-status').val();
                    }
                },
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama_pemohon',
                        name: 'nama_pemohon'
                    },
                    {
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'nomor_telepon',
                        name: 'nomor_telepon'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        class: 'text-center'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ],
                order: [
                    [5, 'desc']
                ]
            });

            // Filter status
            $('#filter-status').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
