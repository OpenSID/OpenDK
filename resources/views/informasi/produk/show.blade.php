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
        <li><a href="{{ route('informasi.produk.index') }}">Produk</a></li>
        <li class="active">{{ $produk->produk  }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <a href="{{ route('informasi.produk.index') }}">
                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</button>
                    </a>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- form start -->
                    <div class="row overflow-x">
                        <div class="col-md-3">
                            @if(isset($produk->foto) && $produk->mime_type == 'jpg')
                                <img src="{{ asset($produk->foto) }}" width="200px">
                            @endif
                        </div>
                        <div class="col-md-9">
                            <ol style="list-style: none">
                                <li>Nama Pelapak : {{ $produk->pelapak }}</li>
                                <li>Kontak Pelapak : {{ $produk->kontak_pelapak }}</li>
                                <li>Nama Produk : {{ $produk->produk }}</li>
                                <li>Kategori : {{ $produk->kategori_produk }}</li>
                                <li>Harga : {{ $produk->harga }}</li>
                                <li>Stok : {{ $produk->stok }}  {{ $produk->satuan }}</li>
                                <li>Potongan : {{ $produk->potongan }}</li>
                            </ol>
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

@push('scripts')

@include('forms.delete-modal')

@endpush