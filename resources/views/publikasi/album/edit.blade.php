@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('publikasi.album.index') }}">Daftar Album</a></li>
            <li class="active">{{ $page_description }}</li>
        </ol>
    </section>
    <section class="content container-fluid">

        {!! Form::model($album, [
            'route' => ['publikasi.album.update', $album->id],
            'method' => 'put',
            'id' => 'form-album',
            'files' => true,
        ]) !!}

        @include('flash::message')
        @include('publikasi.album._form')

        {!! Form::close() !!}

    </section>
@endsection
