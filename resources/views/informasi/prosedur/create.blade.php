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
        <li><a href="{{route('informasi.prosedur.index')}}">Kumpulan Prosedur</a></li>
        <li class="active">Tambah</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                {{-- <div class="box-header with-border">
                     <h3 class="box-title">Aksi</h3>
                 </div>--}}
                <!-- /.box-header -->

                <div class="box-body">
                    <!-- form start -->
                    {!! Form::open( [ 'route' => 'informasi.prosedur.store', 'method' => 'post', 'files' => true, 'id' => 'form-prosedur', 'class' => 'form-horizontal form-label-left' ] ) !!}

                    @if (count($errors) > 0)

                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @include( 'flash::message' )
                    @include('informasi.prosedur.form_create')

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <div class="control-group">
                            <a href="{{ route('informasi.prosedur.index') }}">
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Batal
                                </button>
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan
                            </button>
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

                        if(extension != 'pdf'){
                            $('#showgambar').attr('src', e.target.result);
                            $('#showgambar').removeClass('hide');
                            $('#showpdf').addClass('hide');
                        }else{
                            $('#showpdf').attr('data', e.target.result);
                            $('#showpdf').removeClass('hide');
                            $('#showgambar').addClass('hide');
                        }

                    }

                    reader.readAsDataURL(input.files[0]);
                }
                else { //no
                    //warning
                    $("#file_prosedur").val('');
                    alert('File tersebut tidak diperbolehkan.');
                }
            }
        }

        $("#file_prosedur").change(function () {
            readURL(this);
        });
    });
</script>
@endpush