@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}jenis-penyakit"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('setting.coa.index') }}">Daftar COA</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                @include('partials.flash_message')
                <div class="box box-primary">

                    {!! Form::open([
                        'route' => 'setting.coa.store',
                        'method' => 'post',
                        'id' => 'form-coa',
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

                        @include('setting.coa.form')

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
