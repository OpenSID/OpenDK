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
        
        @if ($profil->kecamatan_id)
        <div class="box-header with-border">
            <a href="{{ route('data.data-desa.create') }}">
                <button type="button" class="btn btn-primary btn-sm" title="Tambah Data"><i class="fa fa-plus"></i>&ensp;Tambah</button>
            </a>
            <form action="{{ route('data.data-desa.getdesa') }}" method="POST" class="inline">
                {{ csrf_field() }}
                
                <button type="submit" class="btn bg-purple btn-sm" title="Ambil Data Desa Dari TrackSID"><i class="fa fa-retweet"></i>&ensp;Ambil Desa</button>
            </form>
        </div>
        @endif

        <div class="box-body">
            @include( 'flash::message' )
            <table class="table table-bordered table-hover dataTable" id="datadesa-table">
                <thead>
                <tr>
                    <th style="max-width: 100px;">Aksi</th>
                    <th>Kode Desa</th>
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
            serverSide: true,
            ajax: "{!! route( 'data.data-desa.getdata' ) !!}",
            columns: [
                {data: 'aksi', name: 'aksi', class: 'text-center', searchable: false, orderable: false},
                {data: 'desa_id', name: 'desa_id'},
                {data: 'nama', name: 'nama'},
                {data: 'website', name: 'website'},
                {data: 'luas_wilayah', name: 'luas_wilayah'},
            ],
            order: [[1, 'asc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')
@endpush
