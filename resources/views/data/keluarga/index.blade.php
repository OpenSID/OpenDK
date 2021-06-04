@extends('layouts.dashboard_template')

@section('title') Data Profil @endsection

@section('content')
        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.profil')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{$page_title}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    @include('partials.flash_message')
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li><a href="{{ route('data.penduduk.index') }}">Penduduk</a></li>
            <li class="active"><a href="{{ route('data.keluarga.index') }}" >Keluarga</a></li>
        </ul>

        <div class="tab-content active">
            <div class="row">
                <div class="col-md-12">
                        <a href="{{ route('data.keluarga.create') }}">
                            <button type="button" class="btn btn-primary btn-sm" title="Tambah Data"><i class="fa fa-plus"></i> Tambah Keluarga</button>
                        </a>
                        <a href="{{ route('data.keluarga.import') }}">
                            <button type="button" class="btn btn-warning btn-sm" title="Upload Data"><i class="fa fa-upload"></i> Import</button>
                        </a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="keluarga-table">
                        <thead>
                        <tr>
                            <th style="max-width: 80px;">Aksi</th>
                            <th>No. KK</th>
                            <th>Nama Kepala</th>
                            <th>Tanggal Daftar</th>
                            <th>Tanggal Cetak KK</th>
                            <th>Alamat</th>
                            <th>RW</th>
                            <th>RT</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.nav-tabs-custom -->
    </div>
</section>
<!-- /.content -->
@endsection

@include('partials.asset_datatables')

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        var data = $('#keluarga-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route( 'data.keluarga.getdata' ) !!}",
            columns: [
                {data: 'action', name: 'action', class: 'text-center', searchable: false, orderable: false},
                {data: 'no_kk', name: 'no_kk'},
                {data: 'nik_kepala', name: 'nik_kepala'},
                {data: 'tgl_daftar', name: 'tgl_daftar'},
                {data: 'tgl_cetak_kk', name: 'tgl_cetak_kk'},
                {data: 'alamat', name: 'alamat'},
                {data: 'rw', name: 'rw'},
                {data: 'rt', name: 'rt'},
            ],
            order: [[0, 'desc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush
