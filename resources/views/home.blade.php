<?php
use Carbon\Carbon;

?>
@extends('layouts.dashboard_template')

@section('content')

        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }} {{ $nama_wilayah }}</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="{{route('dashboard.profil')}}"><i class="fa fa-dashboard"></i> {{$page_title}}</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    @if ($message = Session::get('success'))

        <div class="alert alert-success">

            <p>{{ $message }}</p>

        </div>

    @endif

    <section class="content">
        {{-- <h1>Content Under Construction!</h1> --}}
        <p>Jika Anda butuh bantuan untuk menggunakan Aplikasi Dashboard Kecamatan, silahkan Anda Unduh Panduan Pengguna di bawah ini.</p>
        <br>
            <a href="{{ asset('storage/template_upload/Panduan_Pengguna_Kecamatan_Dashboard.pdf') }}" target="_blank" class="btn btn-primary btn-lg"><i class="fa fa-download"></i> Unduh Panduan</a>
    </section>

</section>
<!-- /.content -->
@endsection