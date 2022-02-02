@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('data.penduduk.index') }}">Penduduk</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                {!!  Form::model($penduduk, [ 'id' => 'form-penduduk', 'class' => 'form-horizontal form-label-left'] ) !!}

                <div class="box-body">

                    @include('data.penduduk.form_show')

                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
@endsection

@include(('partials.asset_select2'))
