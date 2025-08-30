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

    {!! html()->form()
    ->action(route('publikasi.album.store'))
    ->attribute('enctype', 'multipart/form-data')
    ->open() !!}

    @include('publikasi.album._form')

    {!! html()->form()->close() !!}

</section>
@endsection