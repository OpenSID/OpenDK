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

                    {!! Form::open([
                        'route' => ['data.data-suplemen.storedetail'],
                        'method' => 'post',
                        'id' => 'form-faq',
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

                        @include('flash::message')
                        @include('data.data_suplemen.form_detail')

                    </div>
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
        $(function() {
            $('#desa').on('change', function() {
                var desa = $(this).val();
                var suplemen = $("input[name='suplemen_id']").val();
                if (desa) {
                    $.ajax({
                        url: '/data/data-suplemen/getpenduduk/' + encodeURI(desa) + '/' + encodeURI(
                            suplemen),
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#penduduk').empty();
                            if (Object.keys(data).length === 0) {
                                $('#penduduk').prop('disabled', true);
                            } else {
                                $('#penduduk').prop('disabled', false);
                                $.each(data, function(key, value) {
                                    $('#penduduk').append($('<option>', {
                                        value: value.id,
                                        text: value.nama
                                    }));
                                });
                            }
                        }
                    });
                } else {
                    $('#penduduk').empty();
                    $('#penduduk').prop('disabled', true);
                }
            });
        });
    </script>
@endpush
