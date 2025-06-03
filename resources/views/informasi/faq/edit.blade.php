@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('informasi.faq.index') }}">Daftar FAQ</a></li>
            <li class="active">{{ $page_description }}</li>
        </ol>
    </section>

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <!-- form start -->
                    {!! Form::model($faq, ['route' => ['informasi.faq.update', $faq->id], 'method' => 'post', 'id' => 'form-faq', 'class' => 'form-horizontal form-label-left']) !!}
                    @include('layouts.fragments.error_message')

                    <div class="box-body">

                        @include('flash::message')
                        @include('informasi.faq.form')

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
