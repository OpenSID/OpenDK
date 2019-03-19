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
                <a href="{{ route('data.data-desa.create') }}">
                    <button type="button" class="btn btn-primary btn-sm" title="Tambah Data Desa"><i class="fa fa-plus"></i> Tambah</button>
                </a>
            </div>
        </div>
        <div class="box-body">
            @include( 'flash::message' )
            <table class="table table-bordered table-hover dataTable" id="datadesa-table">
                <thead>
                <tr>
                    <th style="max-width: 100px;">Aksi</th>
                    <th>ID</th>
                    <th>Nama Desa</th>
                    <th>Website</th>
                    <th>Luas Wilayah (km<sup>2</sup>)</th>
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
        var data = $('#datadesa-table').DataTable({
            processing: true,
            //serverSide: true,
            ajax: "{!! route( 'data.data-desa.getdata' ) !!}",
            columns: [
                {data: 'action', name: 'action', class: 'text-center', searchable: false, orderable: false},
                {data: 'desa_id', name: 'desa_id'},
                {data: 'nama', name: 'nama'},
                {data: 'website', name: 'website'},
                {data: 'luas_wilayah', name: 'luas_wilayah'},
            ],
            order: [[0, 'desc']]
        });

        $.fn.dataTable.ext.errMode = 'throw';

    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush
