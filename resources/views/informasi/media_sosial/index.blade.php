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

    @include('informasi.media_sosial.navigasi')
    
    <div class="col-md-9 col-lg-9">
        <div class="box box-info">
            <div class="box-body">
                Medsos
            </div>
        </div>
    </div>
</section>
@endsection