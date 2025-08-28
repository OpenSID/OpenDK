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

        {!! Html::form()
            ->url(route('publikasi.album.store'))
            ->attribute('files', true)
            ->open() !!}

        @include('publikasi.album._form')

        {!! Html::closeForm() !!}

    </section>
@endsection
