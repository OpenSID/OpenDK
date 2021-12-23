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
        <li><a href="{{ route('informasi.regulasi.index') }}">Daftar Regulasi</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

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

                    <!-- form start -->
                    {!!  Form::model($regulasi, [ 'route' => ['informasi.regulasi.update', $regulasi->id], 'method' => 'put','id' => 'form-visimisi', 'class' => 'form-horizontal form-label-left', 'files'=>true] ) !!}

                    <div class="box-body">


                        @include( 'flash::message' )
                        @include('informasi.regulasi.form_update')

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="pull-right">
                            <div class="control-group">
                                <button type="reset" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Batal</button>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
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