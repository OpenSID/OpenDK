@extends('layouts.dashboard_template')

@section('content')
        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.profil')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{route('data.apbdes.index')}}">Apbdes</a></li>
        <li class="active">{{ $apbdes->nama  }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <a href="{{ route('data.apbdes.index') }}">
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Kembali
                        </button>
                    </a>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- form start -->
                    <div class="row overflow-x">
                        <div class="col-md-12">
                            @if(isset($apbdes->nama_file) && $apbdes->mime_type != 'pdf')

                                <img src="{{ asset('storage/apbdes/'. $apbdes->nama_file) }}" width="100%">

                            @endif

                            @if(isset($apbdes->nama_file) && $apbdes->mime_type == 'pdf')
                                <object data="@if(isset($apbdes->nama_file)) {{ asset('storage/apbdes/'. $apbdes->nama_file) }} @endif" type="application/pdf" width="100%" height="500" class="" id="showpdf"> </object>
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
