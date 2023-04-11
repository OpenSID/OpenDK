@extends('layouts.dashboard_template')

@section('content')


<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('data.profil.index') }}">Profil</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<section class="content container-fluid">

    @include('partials.flash_message')

    <div class="row">
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $page_title ?? "Page Title" }}</h3>
                </div>
                <div class="box-body">
                    <p>Data Profil berhasil disimpan!</p>
                    <p>Apakah ingin lanjut mengisi form <strong>Data Umum Profil</strong>?</p>

                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <a href="{{ route('data.data-umum.index', $id) }}" class="btn btn-primary">Lanjut</a>
                        <a href="{{ route('data.profil.index') }}" class="btn btn-default">Lewati</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection