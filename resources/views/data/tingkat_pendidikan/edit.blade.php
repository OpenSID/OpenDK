@extends('layouts.dashboard_template')

@section('content')
        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.profil')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{route('data.aki-akb.index')}}">Data AKI & AKB</a></li>
        <li class="active">{{$page_title}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('partials.flash_message')
            <div class="box box-primary">
                {{-- <div class="box-header with-border">
                     <h3 class="box-title">Aksi</h3>
                 </div>--}}
                <!-- /.box-header -->

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
                    {!!  Form::model($pendidikan, [ 'route' => ['data.tingkat-pendidikan.update', $pendidikan->id], 'method' => 'put','id' => 'form-pendidikan', 'class' => 'form-horizontal form-label-left'] ) !!}

                    <div class="box-body">

                        @include('data.tingkat_pendidikan.form_edit')

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="pull-right">
                            <div class="control-group">
                                <a href="{{ route('data.epidemi-penyakit.index') }}">
                                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Batal</button>
                                </a>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- /.row -->

</section>
<!-- /.content -->
@endsection