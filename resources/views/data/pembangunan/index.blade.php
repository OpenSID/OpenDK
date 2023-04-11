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
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Desa</label>
                            <select class="form-control" id="list_desa">
                                <option value="Semua">Semua Desa</option>
                                @foreach ($list_desa as $desa)
                                    <option value="{{ $desa->desa_id }}">{{ $desa->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="pembangunan-table">
                        <thead>
                            <tr>
                                <th width="80px">Aksi</th>
                                <th>Nama Kegiatan</th>
                                <th>Sumber Dana</th>
                                <th>Anggaran</th>
                                <th>Volume</th>
                                <th>Tahun</th>
                                <th>Pelaksana</th>
                                <th>Lokasi</th>
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

            var data = $('#pembangunan-table').DataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('data.pembangunan.getdata') !!}",
                    data: function(d) {
                        d.desa = $('#list_desa').val();
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
                        data: 'judul',
                        name: 'judul',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'sumber_dana',
                        name: 'sumber_dana',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'anggaran',
                        name: 'anggaran',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'volume',
                        name: 'volume',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tahun_anggaran',
                        name: 'tahun_anggaran',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'pelaksana_kegiatan',
                        name: 'pelaksana_kegiatan',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi',
                        searchable: false,
                        orderable: false
                    },
                ],

            });

            $('#list_desa').on('select2:select', function(e) {
                data.ajax.reload();
            });
        });
    </script>
    @include('forms.datatable-vertical')
@endpush
