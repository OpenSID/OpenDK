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
            <h3 class="box-title">Data {{ $page_title ?? "Page Title" }}</h3>
        </div>
        <div class="box-body">
            @include( 'flash::message' )
            <table class="table table-striped table-bordered" id="komplain-table">
                <thead>
                <tr>
                    <th style="max-width: 150px;">Aksi</th>
                    <th>Judul</th>
                    <th>Pelapor</th>
                    <th>Kategori</th>                    
                    <th>Status</th>
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
        var data = $('#komplain-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route( 'admin-komplain.getdata' ) !!}",
            columns: [
                {data: 'aksi', name: 'aksi', class: 'text-center', searchable: false, orderable: false},
                {data: 'judul', name: 'judul'},
                {data: 'nama', name: 'nama'},
                {data: 'kategori', name: 'kategori'},
                {data: 'status', name: 'status'},
            ],
            order: [[1, 'asc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')
@include('forms.agree-modal')

@endpush
