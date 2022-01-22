@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('data.fasilitas-paud.index') }}">Daftar Fasilitas PAUD</a></li>
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
            {!!  Form::model($fasilitas, [ 'route' => ['data.fasilitas-paud.update', $fasilitas->id], 'method' => 'put','id' => 'form-siswa', 'class' => 'form-horizontal form-label-left'] ) !!}

            <div class="box-body">

                @include('data.fasilitas_paud.form_edit')

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="pull-right">
                    <div class="control-group">
                        <a href="{{ route('data.fasilitas-paud.index') }}">
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Batal</button>
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            
        </div>
    </div>
</section>
@endsection