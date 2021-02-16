<?php
use Carbon\Carbon;
?>
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
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary limit-p-width">
                    <div class="box-header with-border">
                        <div class="float-right">
                            <div class="btn-group">
                                <a href="{{ route('data.jabatan.create') }}">
                                    <button type="button" class="btn btn-primary btn-sm" title="Tambah Data"><i class="fa fa-plus"></i> Tambah Jabatan</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="jabatan-table">
                                        <thead>
                                            <tr>
                                                <th width="80px">Aksi</th>
                                                <th>Jabatan</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
</section>
<!-- /.content -->
@endsection
@include('partials.asset_datatables')
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        var data = $('#jabatan-table').DataTable({
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{!! route( 'data.jabatan.getdata' ) !!}",
            },
            columns: [

                {data: 'action', name: 'action', class: 'text-center', searchable: false, orderable: false},
                {data: 'nama_jabatan', name: 'nama_jabatan'},
            ],
            order: [[0, 'desc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush