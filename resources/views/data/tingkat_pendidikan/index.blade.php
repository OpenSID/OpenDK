@extends('layouts.dashboard_template')


@section('content')
        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }} {{ $sebutan_wilayah }} {{ $nama_wilayah }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{$page_title}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    @include('partials.flash_message')

    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="">
                <a href="{{ route('data.tingkat-pendidikan.import') }}">
                    <button type="button" class="btn btn-warning btn-sm" title="Import Data"><i class="fa fa-upload"></i> Import</button>
                </a>
            </div>
        </div>
        <div class="box-body">
            @include( 'flash::message' )
            <table class="table table-bordered table-hover dataTable" id="aki-table">
                <thead>
                <tr>
                    <th style="max-width: 100px;">Aksi</th>
                    <th>Desa</th>
                    <th>Tidak Tamat Sekolah</th>
                    <th>Tamat SD Sederajat</th>
                    <th>Tamat SMP Sederajat</th>
                    <th>Tamat SMA Sederajat</th>
                    <th>Tamat Diploma/Sederajat</th>
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
        var data = $('#aki-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route( 'data.tingkat-pendidikan.getdata' ) !!}",
            columns: [
                {data: 'actions', name: 'actions', class: 'text-center', searchable: false, orderable: false},
                {data: 'desa.nama', name: 'desa.nama'},
                {data: 'tidak_tamat_sekolah', name: 'tidak_tamat_sekolah'},
                {data: 'tamat_sd', name: 'tamat_sd'},
                {data: 'tamat_smp', name: 'tamat_smp'},
                {data: 'tamat_sma', name: 'tamat_sma'},
                {data: 'tamat_diploma_sederajat', name: 'tamat_diploma_sederajat'},
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
