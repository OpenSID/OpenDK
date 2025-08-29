@extends('layouts.dashboard_template')

@section('content')
<section class="content-header block-breadcrumb">
    <h1>
        {{ $page_title ?? 'Page Title' }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('data.anggaran-realisasi.index') }}">Daftar Anggaran & Realisasi</a></li>
        <li class="active">{{ $page_description ?? '' }}</li>
    </ol>
</section>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('partials.flash_message')

            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Oops!</strong> Ada kesalahan pada inputan Anda..<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- form start -->
            {!! Html::form('PUT', route('data.anggaran-realisasi.update', $anggaran->id))
                ->id('form-anggaran')
                ->class('form-horizontal form-label-left')
                ->open() !!}

            <div class="box-body">

                @include('data.anggaran_realisasi.form_edit')

            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                @include('partials.button_reset_submit')
            </div>

            {!! Html::form()->close() !!}

        </div>
    </div>
</section>
@endsection