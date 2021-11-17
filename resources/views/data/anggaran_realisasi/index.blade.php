@extends('layouts.dashboard_template')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    @include('partials.flash_message')

    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="">
                <a href="{{ route('data.anggaran-realisasi.import') }}">
                    <button type="button" class="btn btn-warning btn-sm" title="Import Data"><i class="fa fa-upload"></i> Import</button>
                </a>
            </div>
        </div>
        <div class="box-body">
            @include( 'flash::message' )
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

</section>
<!-- /.content -->
@endsection
@include('partials.asset_datatables')
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        var data = $('#anggaran-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route( 'data.anggaran-realisasi.getdata' ) !!}",
            columns: [
                {data: 'aksi', name: 'aksi', class: 'text-center', searchable: false, orderable: false},
                {data: 'total_anggaran', name: 'total_anggaran'},
                {data: 'total_belanja', name: 'total_belanja'},
                {data: 'belanja_pegawai', name: 'belanja_pegawai'},
                {data: 'belanja_barang_jasa', name: 'belanja_barang_jasa'},
                {data: 'belanja_modal', name: 'belanja_modal'},
                {data: 'belanja_tidak_langsung', name: 'belanja_tidak_langsung'},
                {data: 'bulan', name: 'bulan'},
                {data: 'tahun', name: 'tahun'},
            ],
            order: [[1, 'asc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')
@endpush
