@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        Produk
        <small>Daftar</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Produk</li>
    </ol>
</section>

<section class="content container-fluid">

    @include('partials.flash_message')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <a href="{{ route('informasi.produk.create') }}" class="btn btn-success btn-sm" title="Tambah Data"><i class="fa fa-plus"></i>&nbsp; Tambah</a>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="produk-table">
                            <thead>
                            <tr>
                                <th style="max-width: 150px;">Aksi</th>
                                <th>Pelapak</th>
                                <th>Produk</th>
                                <th>Kategori Produk</th>
                                <th>Harga</th>
                                <th>Satuan</th>
                                <th>Stok</th>
                                <th>Potongan</th>
                                <th>Deskripsi Produk</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
@endsection

@include('partials.asset_datatables')

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        var data = $('#produk-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: "{!! route( 'informasi.produk.getdata' ) !!}",
            columns: [
                {data: 'aksi', name: 'aksi', class: 'text-center text-nowrap', searchable: false, orderable: false},
                {data: 'pelapak', name: 'pelapak'},
                {data: 'produk', name: 'produk'},
                {data: 'kategori_produk', name: 'kategori_produk'},
                {data: 'harga', name: 'harga'},
                {data: 'satuan', name: 'satuan'},
                {data: 'stok', name: 'stok'},
                {data: 'potongan', name: 'potongan'},
                {data: 'deskripsi_produk', name: 'deskripsi_produk'},
            ],
            order: [[1, 'asc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush