@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        Artikel
        <small>Ubah</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('informasi.artikel.index') }}">artikel</a></li>
        <li class="active">ubah</li>
    </ol>
</section>

<section class="content container-fluid">

    {!!  Form::model($artikel, ['route' => ['informasi.artikel.update', $artikel->id], 'method' => 'post', 'id' => 'form-artikel', 'files' => true] ) !!}

        @include('flash::message')
        @include('informasi.artikel._form')
    
    {!! Form::close() !!}

</section>
@endsection

@include(('partials.asset_wysihtml5'))