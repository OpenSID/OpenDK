@extends('layouts.dashboard_template')

@section('content')
        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{route('data.penduduk.index')}}">Penduduk</a></li>
        <li class="active">{{$page_title}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    @include('partials.flash_message')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                {{-- <div class="box-header with-border">
                     <h3 class="box-title">Aksi</h3>
                 </div>--}}
                <!-- /.box-header -->

                <!-- form start -->
                {!! Form::open( [ 'route' => 'data.penduduk.import-excel', 'method' => 'post','id' => 'form-import', 'class' => 'form-horizontal form-label-left', 'files' => true ] ) !!}

                <div class="box-body">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
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
                                <label class="control-label col-md-4 col-sm-3 col-xs-12" for="data_file">Data Penduduk <span class="required">*</span></label>

                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <input type="file" id="data_file" name="file" class="form-control" required accept=".zip, application/zip"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="well">
                                <p>Instruksi Unggah Data:</p>
                                <p>Silahkan unduh template unggah data di sini: <a href="{{ asset('storage/template_upload/penduduk_22_12_2020_opendk.zip') }}">Unduh</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <div class="control-group">
                            <a href="{{ route('data.penduduk.index') }}">
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Batal</button>
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-upload"></i> Impor</button>
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
@include(('partials.asset_select2'))
@include(('partials.asset_datetimepicker'))
@push('scripts')
<script>
    $(function () {

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#showgambar').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#foto").change(function () {
            readURL(this);
        });

        //Datetimepicker
        $('.datepicker').each(function () {
            var $this = $(this);
            $this.datetimepicker({
                format: 'YYYY-MM-D'
            });
        });

    })


</script>
@endpush
