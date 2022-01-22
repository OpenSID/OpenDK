@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{!! $page_title !!}</li>
    </ol>
</section>

<section class="content container-fluid">

    @include('partials.flash_message')

    <div class="box box-primary">
        <div class="box-header with-border">
            <a href="{{ route('informasi.potensi.create') }}" class="btn btn-primary btn-sm {{Sentinel::guest() ? 'hidden':''}}" title="Tambah Data"><i class="fa fa-plus"></i>&ensp; Tambah</a>
            <div class="box-tools pull-right col-sm-4">
                {!! Form::select('kategori_id', \App\Models\TipePotensi::pluck('nama_kategori', 'id'), (isset($_GET['id'])? $_GET['id']:0), ['placeholder' => 'Semua Kategori', 'class' => 'form-control', 'id' => 'kategori_id', 'required'=>true, 'onchange'=>"changeCategori(this)"]) !!}
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
        @if(count($potensis) > 0)
            @foreach($potensis as $potensi)
            <div class="row overflow-x">
                <div class="col-md-3">
                    <img src="{{ asset($potensi->file_gambar) }}" width="100%">
                </div>
                <div class="col-md-8">
                    <h3>{{ $potensi->nama_potensi }}</h3>
                    <article><p>{{ str_limit($potensi->deskripsi, 200, ' ...') }}</p></article>
                    <div class="pull-right button-group" style="position:relative; bottom:0px; margin-bottom: 0px;">
                        <a href="{{ route('informasi.potensi.show', $potensi->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i>&nbsp; Detail</a>
                        @unless(!Sentinel::check())
                        
                        <a href="{!! route('informasi.potensi.edit', $potensi->id) !!}" class="btn btn-sm btn-primary" title="Ubah" data-button="edit"><i class="fa fa-edit"></i>&nbsp; Ubah</a>
                        
                        <a href="javascript:void(0)" class="" title="Hapus" data-href="{!! route('informasi.potensi.destroy', $potensi->id) !!}" data-button="delete" id="deleteModal">
                            <button type="button" class="btn btn-icon btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp; Hapus</button>
                        </a>
                        @endunless
                    </div>
                </div>
            </div>
            <hr>
            @endforeach
        @else
            <h3>Data tidak ditemukan.</h3>
        @endif
        <div class="box-footer clearfix" style="padding:0px; margin: 0px !important;">
            {{ $potensis->links() }}
        </div>
    </div>
</section>
@endsection

@include('partials.asset_datatables')

@push('scripts')
<script type="text/javascript">
    function changeCategori(obj) {
        document.location = "{{ route('informasi.potensi.kategori') }}" +'?id=' + obj.value ;
    }
</script>
@include('forms.datatable-vertical')
@include('forms.delete-modal')

@endpush
