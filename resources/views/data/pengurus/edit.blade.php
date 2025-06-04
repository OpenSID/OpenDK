@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('data.pengurus.index') }}">Daftar Pengurus</a></li>
            <li class="active">{{ $page_description }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <!-- form start -->
                    {!! Form::model($pengurus, [
                        'route' => ['data.pengurus.update', $pengurus->id],
                        'method' => 'post',
                        'files' => true,
                        'id' => 'form-pengurus',
                        'class' => 'form-horizontal form-label-left',
                    ]) !!}
                    @include('layouts.fragments.error_message')

                    <div class="box-body">

                        {{ method_field('PUT') }}
                        @include('flash::message')
                        @include('data.pengurus.form')

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

            var fileTypes = ['jpg', 'jpeg', 'png']; //acceptable file types

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var extension = input.files[0].name.split('.').pop()
                        .toLowerCase(), //file extension from input file
                        isSuccess = fileTypes.indexOf(extension) > -1; //is extension in acceptable types

                    if (isSuccess) { //yes
                        var reader = new FileReader();
                        reader.onload = function(e) {

                            $('#showfoto').attr('src', e.target.result);
                            $('#showfoto').removeClass('hide');
                        }

                        reader.readAsDataURL(input.files[0]);
                    } else { //no
                        //warning
                        $("#foto").val('');
                        alert('File tersebut tidak diperbolehkan.');
                    }
                }
            }

            $("#foto").change(function() {
                readURL(this);
            });
        });
    </script>
@endpush
