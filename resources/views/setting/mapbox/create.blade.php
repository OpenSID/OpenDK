@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('setting.mapbox.index') }}">Map box</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                @include('partials.flash_message')
                <div class="box box-primary">
                    {!! Form::open(['route' => 'setting.mapbox.store', 'method' => 'post', 'files' => true, 'id' => 'form-mapbox', 'class' => 'form-horizontal form-label-left']) !!}
                    @csrf
                    <div class="box-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Oops!</strong> Ada yang salah dengan inputan Anda.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @include('setting.mapbox.form')

                    </div>
                    <div class="box-footer">
                        @include('partials.button_reset_submit')
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@endsection
