@extends('layouts.dashboard_template')

@section('content')
<div class="container">
</div>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>
<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                        <a href="{{ route('poll.home') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
   
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if(Session::has('errors'))
                    <div class="alert alert-danger">
                        {{ session('errors') }}
                    </div>
                    @endif
                    @if(Session::has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                <div class="w-80 m-auto">
                    <h5 class="text-bold text-blue">{{ $question }}</h5>
                    @foreach($options as $option)
                    <div class="mt-20px">
                        <div class="font-size-14 font-size-xs-12 text-blue-100">{{ $option->name }} {{ $option->percent }}%</div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="{{  $option->percent  }}" aria-valuemin="0" aria-valuemax="100" style="width: {{  $option->percent  }}%">
                            <span class="sr-only">{{ $option->percent }}% Complete</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                   <strong>Total Vote : {{ $option->total }}</strong> 
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
@endsection