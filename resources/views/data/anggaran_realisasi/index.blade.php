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
            <div class="box-header with-border">
                @include('forms.btn-social', ['import_url' => route('data.anggaran-realisasi.import')])
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover dataTable" id="anggaran-table">
                        <thead>
                            <tr>
                                <th style="max-width: 100px;">Aksi</th>
                                <th>Total Anggaran</th>
                                <th>Total Belanja</th>
                                <th>Belanja Pegawai</th>
                                <th>Belanja Barang & Jasa</th>
                                <th>Belanja Modal</th>
                                <th>Belanja Tidak Langsung</th>
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
@include('partials.asset_datatables')
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var data = $('#anggaran-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! route('data.anggaran-realisasi.getdata') !!}",
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'total_anggaran',
                        name: 'total_anggaran'
                    },
                    {
                        data: 'total_belanja',
                        name: 'total_belanja'
                    },
                    {
                        data: 'belanja_pegawai',
                        name: 'belanja_pegawai'
                    },
                    {
                        data: 'belanja_barang_jasa',
                        name: 'belanja_barang_jasa'
                    },
                    {
                        data: 'belanja_modal',
                        name: 'belanja_modal'
                    },
                    {
                        data: 'belanja_tidak_langsung',
                        name: 'belanja_tidak_langsung'
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
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
