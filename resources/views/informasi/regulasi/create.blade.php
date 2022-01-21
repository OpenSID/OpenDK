@extends('layouts.dashboard_template')

@section('content')
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

<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                {!! Form::open( [ 'route' => 'informasi.regulasi.store', 'method' => 'post','id' => 'form-regulasi', 'class' => 'form-horizontal form-label-left', 'files'=>true ] ) !!}
                <div class="box-body">

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
                    @include('informasi.regulasi.form_create')

                </div>
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
</section>
@endsection