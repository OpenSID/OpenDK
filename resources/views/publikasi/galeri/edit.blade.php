@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('publikasi.galeri.index', \Session::get('album_id')) }}">Daftar Album</a></li>
            <li class="active">{{ $page_description }}</li>
        </ol>
    </section>
    <section class="content container-fluid">

        {!! Form::model($galeri, [
            'route' => ['publikasi.galeri.update', $galeri->id],
            'method' => 'put',
            'id' => 'form-galeri',
            'files' => true,
        ]) !!}

        @include('flash::message')
        @include('publikasi.galeri._form')

        {!! Form::close() !!}

    </section>
@endsection
