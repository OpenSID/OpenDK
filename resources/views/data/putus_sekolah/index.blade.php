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
                <a href="{{ route('data.putus-sekolah.import') }}">
                    <button type="button" class="btn btn-warning btn-sm" title="Import Data"><i class="fa fa-upload"></i> Import</button>
                </a>
            </div>
        </div>
        <div class="box-body">
            @include( 'flash::message' )
            <table class="table table-bordered table-hover dataTable" id="imunisasi-table">
                <thead>
                <tr>
                    <th style="max-width: 100px;">Aksi</th>
                    <th>Desa</th>
                    <th>Siswa PAUD/RA</th>
                    <th>Anak Usia PAUD/RA</th>
                    <th>Siswa SD/MI</th>
                    <th>Anak Usia SD/MI</th>
                    <th>Siswa SMP/MTS</th>
                    <th>Anak Usia SMP/MTS</th>
                    <th>Siswa SMA/MA</th>
                    <th>Anak Usia SMA/MA</th>
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
        var data = $('#imunisasi-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route( 'data.putus-sekolah.getdata' ) !!}",
            columns: [
                {data: 'actions', name: 'actions', class:'text-center', searchable: false, orderable: false},
                {data: 'desa.nama', name: 'desa.nama'},
                {data: 'siswa_paud', name: 'siswa_paud'},
                {data: 'anak_usia_paud', name: 'anak_usia_paud'},
                {data: 'siswa_sd', name: 'siswa_sd'},
                {data: 'anak_usia_sd', name: 'anak_usia_sd'},
                {data: 'siswa_smp', name: 'siswa_smp'},
                {data: 'anak_usia_smp', name: 'anak_usia_smp'},
                {data: 'siswa_sma', name: 'siswa_sma'},
                {data: 'anak_usia_sma', name: 'anak_usia_sma'},
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
