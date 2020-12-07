@extends('layouts.dashboard_template')


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

    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="">
                <a href="{{ route('data.fasilitas-paud.import') }}">
                    <button type="button" class="btn btn-warning btn-sm" title="Import Data"><i class="fa fa-upload"></i> Import</button>
                </a>
            </div>
        </div>
        <div class="box-body">
            @include( 'flash::message' )
            <table class="table table-bordered table-hover dataTable" id="fasilitas-table">
                <thead>
                <tr>
                    <th style="max-width: 100px;">Aksi</th>
                    <th>Desa</th>
                    <th>Jumlah PAUD/RA</th>
                    <th>Jumlah Guru PAUD/RA</th>
                    <th>Jumlah Siswa PAUD/RA</th>
                    <th>Semester</th>
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
        var data = $('#fasilitas-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route( 'data.fasilitas-paud.getdata' ) !!}",
            columns: [
                {data: 'actions', name: 'actions', class: 'text-center', searchable: false, orderable: false},
                {data: 'desa.nama', name: 'desa.nama'},
                {data: 'jumlah_paud', name: 'jumlah_paud'},
                {data: 'jumlah_guru_paud', name: 'jumlah_guru_paud'},
                {data: 'jumlah_siswa_paud', name: 'jumlah_siswa_paud'},
                {data: 'semester', name: 'semester'},
                {data: 'tahun', name: 'tahun'},
            ],
            order: [[0, 'desc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush
