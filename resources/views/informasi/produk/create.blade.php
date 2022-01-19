@extends('layouts.dashboard_template')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('informasi.produk.index') }}">Daftar Produk</a></li>
        <li class="active">{{ $page_description ?? '' }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        {!! Form::open( [ 'route' => 'informasi.produk.store', 'method' => 'post', 'files' => true, 'id' => 'form-produk', 'class' => 'form-horizontal form-label-left' ] ) !!}
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-body">
                    <!-- form start -->
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
                    @include( 'flash::message' )
                    @include('informasi.produk.form_create')
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <div class="control-group">
                            <button type="reset" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i>&nbsp; Batal</button>
                            <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-save"></i>&nbsp; Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="box box-primary">
                <div class="box-body">
                        <label>Foto Produk <span class="required">*</span></label>
                        <img class="hide" src="@if(isset($produk->foto)) {{ asset($produk->foto) }} @else {{ "http://placehold.it/1000x600" }} @endif"  id="showgambar" style="max-width:300px;max-height:250px;float:left;"/>
                        <object data="" type="image/jpg" class="showimage hide" id="showimage" style="width:240px" > </object>
                        <input type="file" name="foto" id="foto" class="form-control" required >
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
@endsection
@push('scripts')
<script>
    $(function () {

        var fileTypes = ['jpg', 'jpeg', 'png', 'jpg', 'bmp', 'pdf'];  //acceptable file types

        function readURL(input) {
            if (input.files && input.files[0]) {
                var extension = input.files[0].name.split('.').pop().toLowerCase(),  //file extension from input file
                        isSuccess = fileTypes.indexOf(extension) > -1;  //is extension in acceptable types

                if (isSuccess) { //yes
                    var reader = new FileReader();
                    reader.onload = function (e) {

                        if(extension != 'jpg' ){
                            $('#showgambar').attr('src', e.target.result);
                            $('#showgambar').removeClass('hide');
                            $('#showimage').addClass('hide');
                        }else{
                            $('#showimage').attr('data', e.target.result + '#toolbar=1');
                            $('#showimage').removeClass('hide');
                            $('#showgambar').addClass('hide');
                        }

                    }

                    reader.readAsDataURL(input.files[0]);
                } else { //no
                    //warning
                    $("#foto").val('');
                    alert('File tersebut tidak diperbolehkan.');
                }
            }
        }

        $("#foto").change(function () {
            readURL(this);
        });
    });
</script>
@endpush