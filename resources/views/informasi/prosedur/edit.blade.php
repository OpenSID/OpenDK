@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('informasi.prosedur.index') }}">Daftar Prosedur</a></li>
        <li class="active">{{ $page_description ?? '' }}</li>
    </ol>
</section>

<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Oops!</strong> Ada kesalahan pada kolom inputan.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                    @endif

                    <!-- form start -->
                    {!!  Form::model($prosedur, [ 'route' => ['informasi.prosedur.update', $prosedur->id], 'method' => 'put','id' => 'form-event', 'class' => 'form-horizontal form-label-left', 'files'=>true ] ) !!}

                    <div class="box-body">

                        @include('informasi.prosedur.form_edit')

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
                    {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
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
                            $('#showpdf').attr('data', e.target.result + '#toolbar=1');
                            $('#showpdf').removeClass('hide');
                            $('#showgambar').addClass('hide');
                        }

                    }

                    reader.readAsDataURL(input.files[0]);
                } else { //no
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