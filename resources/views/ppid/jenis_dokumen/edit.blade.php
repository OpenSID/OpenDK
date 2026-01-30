@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title }}
            <small>{{ $page_description }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('ppid.jenis-dokumen.index') }}">Daftar Jenis Dokumen PPID</a></li>
            <li class="active">{{ $page_description }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        {!! html()->form('PUT', route('ppid.jenis-dokumen.update', $jenis->id))->attribute('enctype', 'multipart/form-data')->open() !!}
        @include('ppid.jenis_dokumen._form')
        {!! html()->form()->close() !!}
    </section>
@endsection
