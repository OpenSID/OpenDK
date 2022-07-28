@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('data.data-suplemen.index') }}">Suplemen Terdata</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>

    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed">
                        <tr>
                            <th class="col-md-2">Nama</th>
                            <td>: {{ $suplemen->nama }}</td>
                        </tr>
                        <tr>
                            <th>Sasaran</th>
                            <td>: {{ $sasaran[$suplemen->sasaran] }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>: {{ $suplemen->keterangan }}</td>
                        </tr>
                    </table>
                </div>
                <hr>
                <legend>Daftar Peserta suplemen</legend>
            </div>
        </div>
    </section>
@endsection
