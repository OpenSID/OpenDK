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
        <li class="active">Profil</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">

    @include( 'partials.flash_message' )
    
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
        {!!  Form::model($profil, [ 'route' => ['data.profil.update', $profil->id], 'method' => 'put','id' => 'form-profil', 'class' => 'form-horizontal form-label-left', 'files'=>true] ) !!}

        <div class="box-body">

            @include( 'flash::message' )
            @include('data.profil.form_edit')

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <div class="pull-center">
                <div class="control-group">
                    <a href="{{ route('data.profil.index') }}">
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i>&nbsp; Batal</button>
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i>&nbsp; Simpan</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}

    </div>
    <!-- /.row -->

</section>
<!-- /.content -->
@endsection

@include('partials.asset_wysihtml5')
@include(('partials.asset_select2'))
@push('scripts')
@include('partials.profil_select2')
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

        function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#showgambar2').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL3(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#showgambar3').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#file_struktur").change(function () {
            readURL(this);
        });
        
        $("#foto_kepala_wilayah").change(function () {
            readURL2(this);
        });

        $("#file_logo").change(function () {
            readURL3(this);
        });

        $('.textarea').wysihtml5();
    })
</script>
@endpush
