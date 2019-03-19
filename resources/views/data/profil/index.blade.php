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
               {{-- <a href="{{ route('data.profil.create') }}">
                    <button type="button" class="btn btn-primary btn-sm" title="Tambah Data"><i class="fa fa-plus"></i> Tambah Profil</button>
                </a>--}}
            </div>
        </div>
        <div class="box-body">
            @include( 'flash::message' )
            <table class="table table-bordered table-hover dataTable" id="kecamatan-table">
                <thead>
                <tr>
                    <th style="max-width: 80px;">Aksi</th>
                    <th>Kode</th>
                    <th>Nama Kecamatan</th>
                    <th>Nama Camat</th>
                    <th>Sekretaris Camat</th>
                    <th>Alamat</th>
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
        var data = $('#kecamatan-table').DataTable({
            processing: true,
            //serverSide: true,
            ajax: "{!! route( 'data.profil.getdata' ) !!}",
            columns: [
                {data: 'action', name: 'action', class: 'text-center', searchable: false, orderable: false},
                {data: 'kecamatan.id', name: 'id'},
                {data: 'kecamatan.nama', name: 'kecamatan'},
               /* {data: 'kabupaten.nama', name: 'kabupaten'},
                {data: 'provinsi.nama', name: 'provinsi'},*/
                {data: 'nama_camat', name: 'nama_camat'},
                {data: 'sekretaris_camat', name: 'sekretaris_camat'},
                {data: 'alamat', name: 'alamat'},
            ],
            order: [[0, 'desc']]
        });

        $.fn.dataTable.ext.errMode = 'throw';

    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush
