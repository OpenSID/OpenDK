@extends('layouts.app')

@section('content')
    {{-- <div class="row"> --}}
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> {{ $kategoriPotensi->nama_kategori }}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if(count($potensis) > 0)
                        @foreach($potensis as $potensi)
                        <!-- Attachment -->
                    <div class="attachment-block clearfix">
                        <img id="myImg" class="attachment-img responsive" src="{{ asset($potensi->file_gambar) }}" alt="{{ $potensi->nama_potensi }}">
                        <!-- The Modal -->
                        <div id="myModal" class="modal">
                            <span class="close">&times;</span>
                            <img class="modal-content" id="img01">
                            <div id="caption">{{ $potensi->nama_potensi }}</div>
                        </div>
                        <div class="attachment-pushed">
                        <h4 class="attachment-heading"><a href="{{ route('potensi.kategori.show',  ['kategori'=> $kategoriPotensi->slug, 'slug'=> str_slug($potensi->nama_potensi)]) }}"><i class="fa fa-industry" aria-hidden="true"></i>  {{ $potensi->nama_potensi }}</a></h4>
                        <div class="attachment-text">
                            {{ str_limit($potensi->deskripsi, 300, ' ...') }}
                            <div class="pull-right button-group" style="position:relative; bottom:0px; margin-bottom: 0px;">
                                <a href="{{ route('potensi.kategori.show',  ['kategori'=> $kategoriPotensi->slug, 'slug'=> str_slug($potensi->nama_potensi)]) }}" class="btn btn-xs btn-primary"><i class="fa fa-angle-double-right"></i> Baca Selengkapnya</a>
                            </div>
                        </div>
                        <!-- /.attachment-text -->
                        </div>
                        <!-- /.attachment-pushed -->
                    </div>
                    <!-- /.attachment-block -->
                    @endforeach
                    
                    @else
                    
                        <h4 class="text-center"><span class="fa fa-times"></span> Data tidak ditemukan.</h4>
                    @endif
                    <!-- /.box-body -->
                </div>
                <div class="box-footer clearfix" style="padding:0px; margin: 0px !important;">
                    {{ $potensis->links() }}
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    <!-- /.row -->
<!-- /.content -->
@endsection
