@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('setting.aplikasi.index') }}">Daftar Pengaturan Aplikasi</a></li>
            <li class="active">{{ $page_description ?? '' }}</li>
        </ol>
    </section>
    <section class="content container-fluid">

        @include('partials.flash_message')

        <section class="content">
            <div class="row">
                <div class="box box-primary">
                    {!! Form::model($aplikasi, [
                        'route' => ['setting.aplikasi.update', $aplikasi->id],
                        'method' => 'put',
                        'id' => 'form-setting-aplikasi',
                        'class' => 'form-horizontal form-label-left',
                    ]) !!}

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

                        @include('setting.aplikasi.form')

                    </div>

                    <div class="box-footer">
                        @include('partials.button_reset_submit')
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </section>

    </section>
@endsection
