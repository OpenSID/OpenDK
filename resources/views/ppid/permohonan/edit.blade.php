@extends('layouts.dashboard_template')

@section('content')
<section class="content-header block-breadcrumb">
    <h1>
        {{ $page_title ?? 'Page Title' }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('ppid.permohonan.index') }}">PPID</a></li>
        <li><a href="{{ route('ppid.permohonan.index') }}">Permohonan Informasi</a></li>
        <li class="active">{{ $page_description ?? '' }}</li>
    </ol>
</section>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Oops!</strong> Ada kesalahan pada kolom inputan.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- form start -->
                {!! html()->form('PUT', route('ppid.permohonan.update', $permohonan->id))->id('form-permohonan')->class('form-horizontal form-label-left')->open() !!}

                <div class="box-body">

                    @include('ppid.permohonan.form_edit')

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    @include('partials.button_reset_submit')
                </div>
                {!! html()->form()->close() !!}
            </div>
        </div>
    </div>
</section>
@endsection
