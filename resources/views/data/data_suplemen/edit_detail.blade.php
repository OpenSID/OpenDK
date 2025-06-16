@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('data.data-suplemen.index') }}">Daftar Data Suplemen</a></li>
            <li><a href="{{ route('data.data-suplemen.show', $suplemen->id) }}">Data Suplemen {{ $suplemen->nama }}</a></li>
            <li class="active">{{ $page_description }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Ups!</strong> Ada beberapa masalah dengan masukan Anda.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                        </div>
                    @endif

                    <!-- form start -->
                    {!! Form::model($anggota, [
                        'route' => ['data.data-suplemen.updatedetail', $anggota->id],
                        'method' => 'post',
                        'id' => 'form-suplemen',
                        'class' => 'form-horizontal form-label-left',
                    ]) !!}

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
                        <legend>Daftar Anggota Suplemen</legend>

                        {{ method_field('PUT') }}
                        @include('flash::message')
                        @include('data.data_suplemen.form_detail')

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        @include('partials.button_reset_submit')
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $('select').attr('disabled', true);
    </script>
@endpush
