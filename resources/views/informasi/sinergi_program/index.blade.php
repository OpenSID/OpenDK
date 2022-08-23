@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        Sinergi Program
        <small>Daftar</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Sinergi Program</li>
    </ol>
</section>

<section class="content container-fluid">

    @include('partials.flash_message')

    <div class="box box-primary">
        <div class="box-header with-border">
            <a href="{{ route('informasi.sinergi-program.create') }}" class="btn btn-primary btn-sm" judul="Tambah Data"><i class="fa fa-plus"></i>&ensp;Tambah</a>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="sinergi-program-table">
                    <thead>
                        <tr>
                            <th style="max-width: 250px;">Aksi</th>
                            <th>Nama</th>
                            <th>URL</th>
                            <th>Urutan</th>
                            <th>Gambar</th>
                            <th style="max-width: 100px;">Status</th>
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
        var data = $('#sinergi-program-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: "{!! route( 'informasi.sinergi-program.getdata' ) !!}",
            columns: [
                {data: 'aksi', name: 'aksi', class: 'text-center', searchable: false, orderable: false},
                {data: 'nama', name: 'nama', orderable: false},
                {data: 'url', name: 'url', orderable: false},
                {data: 'urutan', name: 'urutan', orderable: false},
                {data: 'gambar', name: 'gambar', class: 'text-center', searchable: false, orderable: false},
                {data: 'status', name: 'status', class: 'text-center', searchable: false, orderable: false},
            ],
            order: [[3, 'asc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush