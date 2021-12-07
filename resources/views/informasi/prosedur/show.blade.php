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
        <li><a href="{{ route('informasi.prosedur.index') }}">Prosedur</a></li>
        <li class="active">{{ $prosedur->judul_prosedur  }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <a href="{{ route('informasi.prosedur.index') }}">
                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</button>
                    </a>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- form start -->
                    <div class="row overflow-x">
                        <div class="col-md-12">
                            @if(isset($prosedur->file_prosedur) && $prosedur->mime_type != 'pdf')

                                <img src="{{ asset($prosedur->file_prosedur) }}" width="100%">
                            @endif

                            @if(isset($prosedur->file_prosedur) && $prosedur->mime_type == 'pdf')
                                <object data="@if(isset($prosedur->file_prosedur)) {{ asset($prosedur->file_prosedur . '#toolbar=1') }} @endif" type="application/pdf" class="showpdf" id="showpdf"> </object>
                            @endif
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