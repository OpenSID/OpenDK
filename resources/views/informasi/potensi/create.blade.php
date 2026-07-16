@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('informasi.potensi.index') }}">Daftar Potensi</a></li>
            <li class="active">{{ $page_description ?? '' }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-body">
                        <!-- form start -->
                        {!! html()->form('POST', route('informasi.potensi.store'))->acceptsFiles()->id('form-potensi')->class(
                                'form-horizontal
                                                                                                                                                                                                                    form-label-left',
                            )->open() !!}

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
                        @include('informasi.potensi.form_create')

                    </div>
                    <div class="box-footer">
                        @include('partials.button_reset_submit')
                    </div>
                    {!! html()->form()->close() !!}
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function() {

            var fileTypes = ['jpg', 'jpeg', 'png', 'bmp', 'gif'];

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var extension = input.files[0].name.split('.').pop().toLowerCase();
                    var isSuccess = fileTypes.indexOf(extension) > -1;

                    if (isSuccess) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#showgambar').attr('src', e.target.result);
                            $('#showgambar').removeClass('hide');
                        };
                        reader.readAsDataURL(input.files[0]);
                    } else {
                        $("#file_gambar").val('');
                        alert('File tersebut tidak diperbolehkan.');
                    }
                }
            }

            $("#file_gambar").change(function() {
                readURL(this);
            });
        });
    </script>
@endpush
