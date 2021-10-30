@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        Artikel
        <small>data artikel</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">artikel</li>
    </ol>
</section>

<section class="content container-fluid">

    @include('partials.flash_message')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <a href="{{route('informasi.artikel.create')}}" class="btn btn-primary btn-sm" title="Tambah Data"><i
                    class="fa fa-plus"></i> Tambah artikel</a>

                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="articles-table">
                            <thead>
                            <tr>
                                <th style="max-width: 150px;">Aksi</th>
                                <th>Nama artikel</th>
                                <th>Url</th>
                                <th style="max-width: 100px;">Status</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@include('partials.asset_datatables')

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        var data = $('#articles-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: "{!! route( 'informasi.artikel.getdata' ) !!}",
            columns: [
                {data: 'action', name: 'action', class: 'text-center', searchable: false, orderable: false},
                {data: 'title', name: 'title'},
                {data: 'slug', name: 'slug'},
                {data: 'is_active', name: 'is_active'},
            ],
        });
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush