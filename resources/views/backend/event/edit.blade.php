@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('informasi.event.index') }}">Daftar Event</a></li>
            <li class="active">{{ $page_description }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <!-- form start -->
                    {!! Form::model($event, [
                        'route' => ['informasi.event.update', $event->id],
                        'method' => 'PUT',
                        'id' => 'form-event',
                        'class' => 'form-horizontal form-label-left',
                        'files' => true,
                    ]) !!}
                    @include('layouts.fragments.error_message')

                    <div class="box-body">
                        @include('flash::message')
                        @include('backend.event.form_edit')
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        @include('partials.button_reset_submit')
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@endsection

@include('partials.tinymce_min')
@include('partials.asset_datetimepicker')
