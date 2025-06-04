@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('data.data-desa.index') }}">Data Desa</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                @include('partials.flash_message')

                {!! Form::open([
                    'route' => 'data.data-desa.store',
                    'method' => 'post',
                    'id' => 'form-datadesa',
                    'class' => 'form-horizontal form-label-left',
                ]) !!}

                <div class="box-body">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Oops!</strong> Ada yang salah dengan inputan Anda.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @include('data.data_desa.form_create')

                </div>
                <div class="box-footer">
                    @include('partials.button_reset_submit')
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        </div>
    </section>
@endsection

@include('partials.asset_select2')
@include('partials.asset_inputmask')

@push('scripts')
    @include('partials.desa_select2')
    <script>
        $('#desa_id').inputmask('99.99.99.9999');

        $(function() {

            $('#list_desa').change(function() {
                $("#desa_id").val($('#list_desa').val());
                $("#nama").val($('#list_desa option:selected').text());
            });
        })
    </script>
@endpush
