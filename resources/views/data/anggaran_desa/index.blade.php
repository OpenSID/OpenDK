@extends('layouts.dashboard_template')


@section('content')
        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title or "Page Title" }}
        <small>{{ $page_description or null }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.profil')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{$page_title}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    @include('partials.flash_message')

    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="">
                <a href="{{ route('data.anggaran-desa.import') }}">
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
                    <th>Desa</th>
                    <th>No Akun</th>
                    <th>Nama Akun</th>
                    <th>Jumlah</th>
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
            ajax: "{!! route( 'data.anggaran-desa.getdata' ) !!}",
            columns: [
                {data: 'actions', name: 'actions', class: 'text-center', searchable: false, orderable: false},
                {data: 'desa_id', name: 'desa_id'},
                {data: 'no_akun', name: 'no_akun'},
                {data: 'nama_akun', name: 'nama_akun'},
                {data: 'jumlah', name: 'jumlah',class: 'text-right'},
                {data: 'bulan', name: 'bulan'},
                {data: 'tahun', name: 'tahun'},
            ],
            order: [[0, 'desc']]
        });

        //$.fn.dataTable.ext.errMode = 'throw';

    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush
