@extends('layouts.dashboard_template')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
</section>

<!-- Main content -->
<section class="content container-fluid">

    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $data['desa'] }}</h3>
                    <p>Desa</p>
                </div>
                <div class="icon">
                    <i class="fa fa-building-o"></i>
                </div>
                <a href="{{ route('data.data-desa.index') }}" class="small-box-footer">
                    Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $data['penduduk'] }}</h3>
                    <p>Penduduk</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user"></i>
                </div>
                <a href="{{ route('data.penduduk.index') }}" class="small-box-footer">
                    Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $data['keluarga'] }}</h3>
                    <p>Keluarga</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="{{ route('data.keluarga.index') }}" class="small-box-footer">
                    Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $data['program_bantuan'] }}</h3>
                    <p>Program Bantuan</p>
                </div>
                <div class="icon">
                    <i class="fa fa-heart-o"></i>
                </div>
                <a href="{{ route('data.program-bantuan.index') }}" class="small-box-footer">
                    Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->
@endsection