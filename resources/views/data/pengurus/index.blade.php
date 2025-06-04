@extends('layouts.dashboard_template')

@section('title')
    Data Pengurus
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

        @include('partials.flash_message')

        <div class="box box-primary">
            <div class="box-header with-border">
                @include('forms.btn-social', ['create_url' => route('data.pengurus.create')])

                {{-- button bagan --}}
                <a href="{{ route('data.pengurus.bagan') }}" style="margin-left: 5px">
                    <button type="button" class="btn btn-success btn-sm btn-social" title="Bagan Organisasi">
                        <i class="fa fa-pie-chart"></i>Bagan Organisasi
                    </button>
                </a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id="status">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="pengurus-table">
                        <thead>
                            <tr>
                                <th style="min-width: 170px;">Aksi</th>
                                <th>Foto</th>
                                <th style="min-width: 150px;">Nama, NIP, NIK</th>
                                <th style="min-width: 150px;">Tempat, Tanggal Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th>Agama</th>
                                <th>Pangkat/Golongan</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Pendidikan Terakhir</th>
                                <th>No SK Pengangkatan</th>
                                <th>Tanggal SK Pengangkatan</th>
                                <th>No SK Pemberhentian</th>
                                <th>Tanggal SK Pemberhentian</th>
                                <th>Masa/Periode Jabatan</th>
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
            var data = $('#pengurus-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('data.pengurus.index') !!}",
                    data: function(d) {
                        d.status = $('#status').val();
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
                        data: 'foto',
                        name: 'foto',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'identitas',
                        name: 'identitas',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'ttl',
                        name: 'ttl',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'sex',
                        name: 'sex'
                    },
                    {
                        data: 'agama.nama',
                        name: 'agama.nama'
                    },
                    {
                        data: 'pangkat',
                        name: 'pangkat'
                    },
                    {
                        data: 'jabatan.nama',
                        name: 'jabatan.nama'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'pendidikan.nama',
                        name: 'pendidikan.nama'
                    },
                    {
                        data: 'no_sk',
                        name: 'no_sk'
                    },
                    {
                        data: 'tanggal_sk',
                        name: 'tanggal_sk'
                    },
                    {
                        data: 'no_henti',
                        name: 'no_henti'
                    },
                    {
                        data: 'tanggal_henti',
                        name: 'tanggal_henti'
                    },
                    {
                        data: 'masa_jabatan',
                        name: 'masa_jabatan'
                    },
                ],
                aaSorting: [],
            });

            $('#status').on('change', function(e) {
                data.ajax.reload();
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
    @include('forms.suspend-modal')
    @include('forms.active-modal', ['title' => $page_title])
@endpush
