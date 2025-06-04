@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? '' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <section class="content container-fluid">

        @include('partials.flash_message')

        @if ($jenisPenyakit->count() > 0)
            <div class="box box-primary">
                <div class="box-header with-border">
                    @include('forms.btn-social', ['import_url' => route('data.epidemi-penyakit.import')])
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        @include('layouts.fragments.list-desa')
                        <hr>
                        <table class="table table-bordered table-hover dataTable" id="aki-table">
                            <thead>
                                <tr>
                                    <th style="max-width: 100px;">Aksi</th>
                                    <th>Desa</th>
                                    <th>Jenis Penyakit</th>
                                    <th>Jumlah Penderita</th>
                                    <th>Bulan</th>
                                    <th>Tahun</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="callout callout-warning">
                <h4>Informasi!</h4>
                <p>Data jenis penyakit belum tersedia. Silahkan tambah data <b><a href="{{ route('setting.jenis-penyakit.index') }}">jenis penyakit</a></b> terlebih dahulu.</p>
            </div>
        @endif
    </section>
@endsection

@include('partials.asset_select2')
@include('partials.asset_datatables')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var data = $('#aki-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('data.epidemi-penyakit.getdata') !!}",
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
                        data: 'nama_desa',
                        name: 'desa_id',
                    },
                    {
                        data: 'penyakit.nama',
                        name: 'penyakit.nama'
                    },
                    {
                        data: 'jumlah_penderita',
                        name: 'jumlah_penderita'
                    },
                    {
                        data: 'bulan',
                        name: 'bulan'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun'
                    },
                ],
                order: [
                    [1, 'asc']
                ]
            });

            $('#list_desa').on('select2:select', function(e) {
                data.columns(1).search(this.value).draw();
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
