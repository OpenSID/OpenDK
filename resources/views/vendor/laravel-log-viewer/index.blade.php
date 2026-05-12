@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
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
                <li class="logs {{ $tab == 'log_viewer' ? 'active' : '' }}"><a data-toggle="tab" href="#log_viewer">Logs</a>
                </li>
                <li class="log_ekstensi {{ $tab == 'ekstensi' ? 'active' : '' }}"><a data-toggle="tab" href="#ekstensi">Kebutuhan Sistem</a></li>
                @role('super-admin')
                    <li class=" {{ $tab == 'info_sistem' ? 'active' : '' }}"><a data-toggle="tab" href="#info_sistem">Info
                            Sistem</a></li>
                    <li class=" {{ $tab == 'email_smtp' ? 'active' : '' }}"><a data-toggle="tab" href="#email_smtp">Email
                            SMTP</a></li>
                @endrole
            </ul>
            <div class="tab-content">
                <div id="log_viewer" class="logs tab-pane fade in  {{ $tab == 'log_viewer' ? 'active' : '' }}">
                    @include('vendor.laravel-log-viewer.log')
                </div>
                <div id="ekstensi" class="log_ekstensi tab-pane fade in {{ $tab == 'ekstensi' ? 'active' : '' }}">
                    @include('vendor.laravel-log-viewer.kebutuhan-sistem')
                </div>
                @role('super-admin')
                    <div id="info_sistem" class="tab-pane fade in {{ $tab == 'info_sistem' ? 'active' : '' }}">
                        @include('vendor.laravel-log-viewer.info-sistem')
                    </div>
                    <div id="email_smtp" class="tab-pane fade in {{ $tab == 'email_smtp' ? 'active' : '' }}">
                        @include('vendor.laravel-log-viewer.smtp.index')
                    </div>
                @endrole
            </div>
        </div>
    </section>
@endsection
