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
        <li><a href="{{route('data.keluarga.index')}}">Keluarga</a></li>
        <li class="active">{{$page_title}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <!-- form start -->
                {!!  Form::model($keluarga, [ 'id' => 'form-keluarga', 'class' => 'form-horizontal form-label-left']) !!}

                <div class="box-body">

                    @include( 'flash::message' )
                    @include('data.keluarga.form_show')

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- /.row -->

</section>
<!-- /.content -->
@endsection
