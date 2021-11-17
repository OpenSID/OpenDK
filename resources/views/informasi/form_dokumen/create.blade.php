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
        <li><a href="{{ route('informasi.form-dokumen.index') }}">Daftar Dokumen</a></li>
        <li class="active">{{ $page_description ?? '' }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                <div class="box-body">
                    <!-- form start -->
                    {!! Form::open( [ 'route' => 'informasi.form-dokumen.store', 'method' => 'post', 'files' => true, 'id' => 'form-prosedur', 'class' => 'form-horizontal form-label-left' ] ) !!}

                    @if (count($errors) > 0)

                        <div class="alert alert-danger">
                            <strong>Ups!</strong> Ada beberapa masalah dengan masukan Anda.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @include( 'flash::message' )
                    @include('informasi.form_dokumen.form_create')

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <div class="control-group">
                            <a href="{{ route('informasi.form-dokumen.index') }}">
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i>&nbsp; Batal
                                </button>
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i>&nbsp; Simpan
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- /.row -->

</section>
<!-- /.content -->
@endsection
