@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            {{-- <small>{{ $page_description ?? '' }}</small> --}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('data.lembaga.index') }}"> Daftar Lembaga</a></li>
            <li><a href="{{ route('data.lembaga_anggota.index', $lembaga->slug) }}"> Daftar Anggota Lembaga
                    {{ $lembaga->nama }} </a></li>
            <li class="active">{{ $page_description }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    {!! Form::open([
                        'route' => ['data.lembaga_anggota.store', $lembaga->slug],
                        'method' => 'post',
                        'id' => 'form-lembaga-anggota',
                        'class' => 'form-horizontal form-label-left',
                    ]) !!}
                    @include('layouts.fragments.error_message')

                    <div class="box-body">

                        @include('flash::message')
                        @include('data.lembaga_anggota.form_create')

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

@include('partials.tinymce_min')
