@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Profil</li>
        </ol>
    </section>
    <section class="content container-fluid">

        @include('partials.flash_message')

        @if (!$profil->kecamatan_id)
            <div class="callout callout-warning">
                <h4><i class="icon fa fa-warning"></i> Peringatan!</h4>
                <p>Data profil wilayah belum lengkap. Silahkan dilengkapi terlebih dahulu!</p>
            </div>
        @endif

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
            {!! Form::model($profil, [
                'route' => ['data.profil.update', $profil->id],
                'method' => 'put',
                'id' => 'form-profil',
                'class' => 'form-horizontal form-label-left',
                'files' => true,
            ]) !!}

            <div class="box-body">

                @include('flash::message')
                @include('data.profil.form_edit')

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                @include('partials.button_reset_submit')
            </div>
            {!! Form::close() !!}

        </div>
    </section>
@endsection

@include('partials.asset_select2')
@include('partials.tinymce_min')
@include('partials.asset_inputmask')

@push('scripts')
    @include('partials.profil_select2')
    {!! JsValidator::formRequest('App\Http\Requests\ProfilRequest', '#form-profil') !!}
    <script>
        $('#kabupaten_offline').inputmask('99.99');
        $('#kecamatan_offline').inputmask('99.99.99');

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

            function readURL2(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#showgambar2').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            function readURL3(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#showgambar3').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#file_struktur").change(function() {
                readURL(this);
            });

            $("#foto_kepala_wilayah").change(function() {
                readURL2(this);
            });

            $("#file_logo").change(function() {
                readURL3(this);
            });
        });
    </script>
@endpush
