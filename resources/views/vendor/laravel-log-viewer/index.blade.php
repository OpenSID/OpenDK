@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        Info Sistem
        <small>Informasi dan Kebutuhan Sistem</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Info Sistem</li>
    </ol>
</section>
<section class="content container-fluid">

    @include('partials.flash_message')

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="logs active"><a data-toggle="tab" href="#log_viewer">Logs</a></li>
            <li class="log_ekstensi"><a data-toggle="tab" href="#ekstensi">Kebutuhan Sistem</a></li>
            <li><a data-toggle="tab" href="#info_sistem">Info Sistem</a></li>
        </ul>
        <div class="tab-content">
            <div id="log_viewer" class="logs tab-pane fade in active">
                @include('vendor.laravel-log-viewer.log')
            </div>
            <div id="ekstensi" class="log_ekstensi tab-pane fade in">
                @include('vendor.laravel-log-viewer.kebutuhan-sistem')
            </div>
            <div id="info_sistem" class="tab-pane fade in">
                @include('vendor.laravel-log-viewer.info-sistem')
            </div>
        </div>
    </div>
</section>
@endsection