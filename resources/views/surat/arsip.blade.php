@extends('layouts.dashboard_template')

@section('title') Arsip Surat @endsection

@section('content')
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

<section class="content container-fluid">

    @include('partials.flash_message')

    <div class="box box-primary">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="pengurus-table">
                    <thead>
                        <tr>
                            <th style="min-width: 110px;">Aksi</th>
                            <th>Jenis Surat</th>
                            <th>Nama Surat</th>
                            <th>Nama Penduduk</th>
                            <th>Ditandatangani oleh</th>
                            <th>Tanggal</th>
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
    $(document).ready(function () {
        var data = $('#pengurus-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{!! route( 'surat.permohonan.getdata' ) !!}"
            },
            columns: [
                {data: 'aksi', name: 'aksi', class: 'text-center', searchable: false, orderable: false},
                {data: 'format', name: 'format'},
                {data: 'nama', name: 'nama'},
                {data: 'nik', name: 'nik'},
                {data: 'pengurus_id', name: 'pengurus_id'},
                {data: 'tanggal', name: 'tanggal'},
            ]
        });
    });
</script>
@include('forms.datatable-vertical')
@endpush
