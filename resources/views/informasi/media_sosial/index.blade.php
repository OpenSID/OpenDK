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
            {!!  Form::model($medsos, [ 'route' => ['informasi.media-sosial.update', $medsos->id], 'id' => 'form-medsos', 'class' => 'form-horizontal form-label-left'] ) !!}
            <div class="box-body">
                @if (Request::is('informasi/media-sosial/whatsapp'))
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Tipe</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::select('tipe', ['1' => 'Personal Chat', '2' => 'Grup Chat'], null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                @endif
                {{ Form::hidden('medsos', $page) }}
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Link akun / username <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::text('link', null, ['placeholder' => $placeholder,'class' => 'form-control', 'required'=>true]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::select('status', ['1' => 'Aktif', '0' => 'Tidak Aktif'], null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="ln_solid"></div>
                
            </div>
            <div class="box-footer">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a href="{{ route('informasi.media-sosial.index') }}">
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