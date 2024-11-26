{{-- @dd($kategori_lembaga->id) --}}
@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('data.lembaga.index') }}"> Daftar Lembaga </a></li>
            <li class="active">{{ $page_description }}</li>
        </ol>
    </section>
    
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    
                    {!! Form::model($lembaga, ['route' => ['data.lembaga.update', $lembaga->id], 'method' => 'PUT', 'id' => 'form-lembaga', 'class' => 'form-horizontal form-label-left']) !!}
                    
                    @include('layouts.fragments.error_message')

                    <div class="box-body">

                        @include('flash::message')
                        @include('data.lembaga.form_edit')

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
