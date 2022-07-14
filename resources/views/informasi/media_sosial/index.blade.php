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
            <!-- form start -->
            {!!  Form::model($medsos, [ 'route' => ['informasi.media-sosial.update', $medsos->id], 'method' => 'post','id' => 'form-medsos', 'class' => 'form-horizontal form-label-left'] ) !!}
            <div class="box-body">
                Medsos
            </div>
            <div class="box-footer">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a href="{{ route('informasi.faq.index') }}">
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i>&nbsp; Batal</button>
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i>&nbsp; Simpan</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</section>
@endsection