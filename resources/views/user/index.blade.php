@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header">
        <h1>
            {{ $page_title }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Tabel {{ $page_title }}</h3>

                <div class="pull-right"><a href="{{ route('setting.user.create') }}">
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary btn-sm">Create New User</button>
                        </div>
                    </a>
                </div>
            </div>
            <div class="box-body">
                @include( 'flash::message' )
                <table class="table table-striped table-bordered" id="user-table">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>

    </section>

@endsection
@include('partials.asset_datatables')
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        var data = $('#user-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route( 'setting.user.getdata' ) !!}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'first_name', name: 'first_name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'status', name: 'status'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', class: 'text-center', searchable: false, orderable: false}
            ],
            order: [[0, 'desc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@include('forms.suspend-modal')
@include('forms.active-modal', ['title'=>$page_title])
@endpush
