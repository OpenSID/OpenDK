@extends('layouts.dashboard_template')

@section('title') Permohonan Surat @endsection

@section('content')
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('surat.permohonan') }}">Surat</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<section class="content container-fluid">

    @include('partials.flash_message')

    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="float-right">
                <div class="btn-group">
                    <a href="{{ route('surat.permohonan') }}">
                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Kembali ke Permohonan Surat
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="box-body">
            <object data='{{ asset("storage/surat/{$surat->file}") }}' style="width: 100%;min-height: 400px;" type="application/pdf"></object>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        
    });
</script>
@endpush
