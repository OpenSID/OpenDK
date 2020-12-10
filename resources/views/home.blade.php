<?php
use Carbon\Carbon;

?>
@extends('layouts.dashboard_template')

@section('content')

        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
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
        <h1>Content Under Construction!</h1>

    </section>

</section>
<!-- /.content -->
@endsection