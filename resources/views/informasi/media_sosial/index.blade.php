@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{!! $page_title !!}</li>
    </ol>
</section>

<section class="content container-fluid">
    
    @include('partials.flash_message')

    <div class="box box-primary">
        <div class="box-header with-border">
            <a href="{{ route('informasi.form-dokumen.create') }}" class="btn btn-success btn-sm {{auth()->guest() ? 'hidden':''}}" title="Tambah"><i class="fa fa-plus"></i>&ensp;Tambah</a>
        </div>
        
        <div class="box-body">
            Media sosial
        </div>
    </div>
</section>
@endsection