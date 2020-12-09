@extends('layouts.dashboard_template')

@section('content')
    {{-- <div class="row"> --}}
        <div class="col-md-8">
            <div class="box box-widget">
                <div class="box-header">
                    {{-- <h2>{{ $kategoriPotensi->nama_kategori }}</h2> --}}
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12">
                        <img src="{{ asset($potensi->file_gambar) }}" width="100%">
                    </div>
                    <div class="col-md-12">
                        <h3>{{ $potensi->nama_potensi }}</h3>
                        <p>{{ $potensi->deskripsi }}</p>
                    </div>
                <!-- /.box-body -->
                </div>
                <div class="box-footer clearfix" style="padding:0px; margin: 0px !important;">
                    
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    <!-- /.row -->
<!-- /.content -->
@endsection
