@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        Artikel
        <small>Daftar</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">artikel</li>
    </ol>
</section>

<section class="content container-fluid">

    @include('partials.flash_message')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <a href="{{ route('informasi.artikel.create') }}" class="btn btn-primary btn-sm" judul="Tambah Data"><i
                    class="fa fa-plus"></i> Tambah</a>

                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="artikel-table">
                            <thead>
                                <tr>
                                    <th style="max-width: 150px;">Aksi</th>
                                    <th>Judul</th>
                                    <th>Tanggal Terbit</th>
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
        var data = $('#artikel-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: "{!! route( 'informasi.artikel.getdata' ) !!}",
            columns: [
                {data: 'action', name: 'action', class: 'text-center', searchable: false, orderable: false},
                {data: 'judul', name: 'judul'},
                {data: 'created_at', name: 'created_at', class: 'text-center', searchable: false, orderable: false},
                {data: 'status', name: 'status', class: 'text-center', searchable: false, orderable: false},
            ],
            order: [[2, 'desc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush