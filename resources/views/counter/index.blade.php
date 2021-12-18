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
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>
<!-- Main content -->
<section class="content container-fluid">
    @include('partials.flash_message')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Halaman Terpopuler</h3>
        </div>
        <div class="box-body no-padding">
            <div class="row">
                <div class="col-md-9 col-sm-8">
                    <div class="pad">
                        <ul class="list-group">
                            @foreach($top_pages as $key=>$page)
                                <a href="{{ $page['url'] }}" class="list-group-item"><span class="badge label-primary pull-right">
                                        {{ $page['total'] }}</span>{{ $page['url'] }}
                                </a>
                            @endforeach
                        </ul>
                    </div>

                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-4">
                    <div class="pad box-pane-right bg-green" style="min-height: 280px">
                        <div class="description-block margin-bottom">
                            <div class="sparkbar pad" data-color="#fff">Hari Ini</div>
                            <h5 class="description-header">{{ Counter::allVisitors(1) }}</h5>
                            <span class="description-text">Kunjungan</span>
                        </div>
                        <!-- /.description-block -->
                        <div class="description-block margin-bottom">
                            <div class="sparkbar pad" data-color="#fff">7 Hari yang Lalu</div>
                            <h5 class="description-header">{{ Counter::allVisitors(7) }}</h5>
                            <span class="description-text">Kunjungan</span>
                        </div>
                        <!-- /.description-block -->
                        <div class="description-block">
                            <div class="sparkbar pad" data-color="#fff">Total</div>
                            <h5 class="description-header">{{ Counter::allVisitors() }}</h5>
                            <span class="description-text">Kunjungan</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </div>
</section>
<!-- /.content -->
@endsection
