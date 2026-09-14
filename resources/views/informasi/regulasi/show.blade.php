@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('informasi.regulasi.index') }}">Daftar Regulasi</a></li>
            <li class="active">{{ $page_description }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <a href="{{ route('informasi.regulasi.index') }}">
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i>&nbsp;
                                Kembali</button>
                        </a>
                        @if (!empty($regulasi->file_regulasi))
                            <a href="{{ route('informasi.regulasi.download', $regulasi->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-download"></i>&nbsp; Unduh
                            </a>
                        @endif
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- form start -->
                        <div class="row overflow-x">
                            <div class="col-md-12">
                                <label>Deskripsi : </label>
                                <p>{{ $regulasi->deskripsi }}</p>
                                <hr>
                                @if (!empty($regulasi->file_regulasi))
                                    @if ($regulasi->is_pdf)
                                        <iframe src="{{ asset($regulasi->file_regulasi) }}#toolbar=1" class="showpdf" id="showpdf" style="width: 100%; height: 750px; border: 1px solid #e0e0e0; border-radius: 4px;" frameborder="0">
                                            <p>Browser Anda tidak mendukung preview PDF langsung. <a href="{{ route('informasi.regulasi.download', $regulasi->id) }}">Klik di sini untuk mengunduh</a>.</p>
                                        </iframe>
                                    @else
                                        <img src="{{ asset($regulasi->file_regulasi) }}" class="img-responsive" style="max-width: 100%; margin: 0 auto; display: block;">
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @include('forms.delete-modal')
@endpush
