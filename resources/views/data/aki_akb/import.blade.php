@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('data.aki-akb.index') }}">Daftar AKI & AKB</a></li>
            <li class="active">{{ $page_description ?? '' }}</li>
        </ol>
    </section>
    <section class="content container-fluid">

        @include('partials.flash_message')
        <div class="row">
            <div class="col-md-12">

                {!! Form::open([
                    'route' => 'data.aki-akb.do_import',
                    'method' => 'post',
                    'id' => 'form-import',
                    'class' => 'form-horizontal form-label-left',
                    'files' => true,
                ]) !!}

                <div class="box-body">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <strong>Ups!</strong> Ada beberapa masalah dengan masukan Anda.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bulan" class="control-label col-md-4 col-sm-3 col-xs-12">Bulan</label>

                                <div class="col-md-8">
                                    <select class="form-control" id="bulan" name="bulan">
                                        @foreach ($months_list as $key => $month)
                                            <option value="{{ $key }}">{{ $month }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="list_year" class="control-label col-md-4 col-sm-3 col-xs-12">Tahun</label>

                                <div class="col-md-8">
                                    <select class="form-control" id="list_year" name="tahun">
                                        @foreach ($years_list as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-3 col-xs-12" for="data_file">Data AKI &
                                    AKB</label>

                                <div class="col-md-8">
                                    <input type="file" id="data_file" name="file" class="form-control" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="well">
                                <p>Instruksi Upload Data:</p>
                                <p>Silahkan download template upload data di sini: <a href="{{ asset('storage/template_upload/Format_Upload_AKI_&_AKB.xlsx') }}">Download</a>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="box-footer">
                    @include('partials.button_reset_impor')
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        </div>
    </section>
@endsection
@include('partials.asset_select2')
@include('partials.asset_datetimepicker')
@push('scripts')
    <script>
        $(function() {

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#showgambar').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#foto").change(function() {
                readURL(this);
            });

            //Datetimepicker
            $('.datepicker').each(function() {
                var $this = $(this);
                $this.datetimepicker({
                    format: 'YYYY-MM-D'
                });
            });

        });
    </script>
@endpush
