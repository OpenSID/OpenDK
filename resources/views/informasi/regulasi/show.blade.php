@extends('layouts.dashboard_template')

@section('content')
        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{route('informasi.regulasi.index')}}">Regulasi</a></li>
        <li class="active">{{ $page_title  }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $regulasi->judul }}</h3>

                    <div class="pull-right box-tools">
                        <a href="{{ route('informasi.regulasi.index') }}">
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Kembali
                            </button>
                        </a>
                        @unless(!Sentinel::check())

                            <a href="{!! route('informasi.regulasi.edit', $regulasi->id) !!}"
                               class="btn btn-sm btn-primary"
                               title="Ubah" data-button="edit"><i class="fa fa-edit"></i> Ubah
                            </a>

                            <a href="javascript:void(0)" class="" title="Hapus"
                               data-href="{!! route('informasi.regulasi.destroy', $regulasi->id) !!}"
                               data-button="delete"
                               id="deleteModal">
                                <button type="button" class="btn btn-icon btn-danger btn-sm"><i class="fa fa-trash"
                                                                                                aria-hidden="true"></i>
                                    Hapus
                                </button>
                            </a>
                        @endunless
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- form start -->
                    <div class="row overflow-x">
                        <div class="col-md-12">
                            <label>Deskripsi : </label>

                            <p>{{ $regulasi->deskripsi }}</p>
                            @if(isset($regulasi->file_regulasi) && $regulasi->mime_type != 'pdf')

                                <img src="{{ asset($regulasi->file_regulasi) }}" width="100%">
                            @endif

                            @if(isset($regulasi->file_regulasi) && $regulasi->mime_type == 'pdf')
                                <object data="@if(isset($regulasi->file_regulasi)) {{ asset($regulasi->file_regulasi) }} @endif" type="application/pdf" width="100%" height="500" class="" id="showpdf"> </object>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">

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