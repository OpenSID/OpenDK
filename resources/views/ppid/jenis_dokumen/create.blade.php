@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>{{ $page_title }} <small>{{ $page_description }}</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('ppid.jenis-dokumen.index') }}">Daftar Jenis Dokumen</a></li>
            <li class="active">Tambah</li>
        </ol>
    </section>

    <section class="content container-fluid">
        {!! html()->form('POST', route('ppid.jenis-dokumen.store'))->id('form-ppid')->open() !!}
        @include('ppid.jenis_dokumen._form')
        {!! html()->form()->close() !!}
    </section>
@endsection
