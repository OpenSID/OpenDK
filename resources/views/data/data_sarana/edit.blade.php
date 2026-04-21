@extends('layouts.dashboard_template')
@include('partials.asset_select2')
@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('data.data-sarana.index') }}">Daftar Data Sarana</a></li>
            <li class="active">{{ $page_description }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    {!! html()->form('POST', route('data.data-sarana.update', $sarana->id))->id('form-sarana')->class(
                            'form-horizontal
                                                                                                form-label-left',
                        )->open() !!}
                    @method('PUT')

                    <div class="box-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Ups!</strong> Ada beberapa masalah dengan masukan Anda.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @include('flash::message')
                        @include('data.data_sarana.form')

                    </div>
                    <div class="box-footer">
                        <a href="{{ route('data.data-sarana.index') }}" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Batal</a>
                        <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-save"></i> Update</button>
                    </div>
                    {!! html()->form()->close() !!}
                </div>
            </div>
        </div>
    </section>
@endsection
