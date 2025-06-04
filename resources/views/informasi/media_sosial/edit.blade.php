@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('informasi.media-sosial.index') }}">Daftar Media Sosial</a></li>
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
                    {!! Form::model($medsos, [
                        'route' => ['informasi.media-sosial.update', $medsos->id],
                        'method' => 'put',
                        'id' => 'form-media-sosial',
                        'class' => 'form-horizontal form-label-left',
                        'files' => true,
                    ]) !!}

                    <div class="box-body">

                        {{ method_field('PUT') }}
                        @include('flash::message')
                        @include('informasi.media_sosial.form')

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
        $(function() {

            var fileTypes = ['jpg', 'jpeg', 'png', 'jpg']; //acceptable file types

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var extension = input.files[0].name.split('.').pop()
                        .toLowerCase(), //file extension from input file
                        isSuccess = fileTypes.indexOf(extension) > -1; //is extension in acceptable types

                    if (isSuccess) { //yes
                        var reader = new FileReader();
                        reader.onload = function(e) {

                            $('#showgambar').attr('src', e.target.result);
                            $('#showgambar').removeClass('hide');
                        }

                        reader.readAsDataURL(input.files[0]);
                    } else { //no
                        //warning
                        $("#logo").val('');
                        alert('File tersebut tidak diperbolehkan.');
                    }
                }
            }

            $("#logo").change(function() {
                readURL(this);
            });
        });
    </script>
@endpush
