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
                    {!! html()->form('PUT', route('ppid.dokumen.update', $dokumen))
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

                    @include('ppid.dokumen.form_edit', compact('dokumen'))

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
        // BUG-005 & BUG-007: Dynamic required attribute and proper initial state
        // In edit mode, file and URL are optional (user can keep existing values)
        var isEditMode = true;
        var currentDokumenType = '{{ $dokumen->tipe_dokumen }}';

        // Get current tipe from checked radio or fallback to current dokumen type
        function getCurrentType() {
            var checked = $('input[name="tipe_dokumen"]:checked').val();
            if (checked) return checked;
            return currentDokumenType || 'file';
        }

        function updateRequiredAttribute() {
            var selectedType = getCurrentType();
            if (selectedType === 'file') {
                $('#file-field-group').show();
                $('#url-field-group').hide();
                // In edit mode, file is optional (keep existing if not changed)
                $('input[name="file_path"]').prop('required', false);
                $('input[name="url"]').prop('required', false);
            } else {
                $('#file-field-group').hide();
                $('#url-field-group').show();
                // In edit mode, url is optional (keep existing if not changed)
                $('input[name="file_path"]').prop('required', false);
                $('input[name="url"]').prop('required', false);
            }
        }

        // Set initial state based on current dokumen tipe BEFORE any radio changes
        // BUG-007: Ensure correct initial state based on $dokumen->tipe_dokumen
        if (currentDokumenType === 'file') {
            $('#file-field-group').show();
            $('#url-field-group').hide();
        } else {
            $('#file-field-group').hide();
            $('#url-field-group').show();
        }

        // Also set required attributes initially
        updateRequiredAttribute();

        // Update on change
        $('input[name="tipe_dokumen"]').on('change', function() {
            updateRequiredAttribute();
        });
    });
</script>
@endpush
