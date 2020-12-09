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
            <li class="active"><a href="{{ route('data.penduduk.index') }}">Penduduk</a></li>
            {{--<li><a href="{{ route('data.keluarga.index') }}" >Keluarga</a></li>--}}
        </ul>

        <div class="tab-content">
            <div class="row">
                <div class="col-md-12">
                       <a href="{{ route('data.penduduk.create') }}">
                            <button type="button" class="btn btn-primary btn-sm" title="Tambah Data"><i class="fa fa-plus"></i> Tambah Penduduk</button>
                        </a>
                        <a href="{{ route('data.penduduk.import') }}">
                            <button type="button" class="btn btn-warning btn-sm" title="Upload Data"><i class="fa fa-upload"></i> Import</button>
                        </a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="penduduk-table">
                        <thead>
                        <tr>
                            <th style="max-width: 80px;">Aksi</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>No. KK</th>
                            <th>Alamat</th>
                           {{-- <th>Dusun</th>
                            <th>RW</th>
                            <th>RT</th>--}}
                            <th>Pendidikan dalam KK</th>
                            <th>Umur</th>
                            <th>Pekerjaan</th>
                            <th>Status Kawin</th>
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
        var data = $('#penduduk-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route( 'data.penduduk.getdata' ) !!}",
            columns: [
                {data: 'action', name: 'action', class: 'text-center', searchable: false, orderable: false},
                {data: 'nik', name: 'nik'},
                {data: 'nama', name: 'nama'},
                {data: 'no_kk', name: 'no_kk'},
                {data: 'alamat', name: 'alamat'},
                /*{data: 'dusun', name: 'dusun'},
                {data: 'rw', name: 'rw'},
                {data: 'rt', name: 'rt'},*/
                {data: 'pendidikan', name: 'ref_pendidikan_kk.nama'},
                {data: 'tanggal_lahir', name: 'tanggal_lahir'},
                {data: 'pekerjaan', name: 'ref_pekerjaan.nama'},
                {data: 'status_kawin', name: 'ref_kawin.nama'},
            ],
            order: [[0, 'desc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush
