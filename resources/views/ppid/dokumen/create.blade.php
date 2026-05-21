@extends('layouts.dashboard_template')

@section('content')
<section class="content-header block-breadcrumb">
    <h1>
        {{ $page_title ?? 'Page Title' }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('ppid.dokumen.index') }}">Dokumen PPID</a></li>
        <li class="active">{{ $page_description ?? '' }}</li>
    </ol>
</section>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                <div class="box-body">
                    {!! html()->form('POST', route('ppid.dokumen.store'))
                    ->acceptsFiles()
                    ->id('form-ppid-dokumen')
                    ->class('form-horizontal form-label-left')
                    ->open() !!}

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
                    @include('ppid.dokumen.form')

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

@include('partials.asset_jqueryvalidation')

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle file/url fields based on tipe_dokumen
        $('input[name="tipe_dokumen"]').on('change', function() {
            var value = $(this).val();
            if (value === 'file') {
                $('#file-field-group').show();
                $('#url-field-group').hide();
                $('input[name="file_path"]').prop('required', true);
                $('input[name="url"]').prop('required', false);
            } else {
                $('#file-field-group').hide();
                $('#url-field-group').show();
                $('input[name="file_path"]').prop('required', false);
                $('input[name="url"]').prop('required', true);
            }
        }).trigger('change');
    });
</script>
@endpush
