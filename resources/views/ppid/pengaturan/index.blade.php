@extends('layouts.dashboard_template')

@section('content')
<section class="content-header block-breadcrumb">
    <h1>
        {{ $page_title ?? 'Page Title' }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('ppid.pengaturan.index') }}">PPID</a></li>
        <li class="active">{{ $page_title ?? '' }}</li>
    </ol>
</section>

<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Pengaturan PPID</h3>
                </div>

                <div class="box-body">
                    {!! html()->form('POST', route('ppid.pengaturan.update'))
                    ->method('POST')
                    ->class('form-horizontal form-label-left')
                    ->enctype('multipart/form-data')
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

                    <!-- Banner -->
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Banner</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! html()->text('banner', $pengaturan['banner'])
                            ->class('form-control')
                            ->placeholder('Nama file banner') !!}
                            <p class="help-block">Nama file banner untuk halaman PPID.</p>
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                    <!-- Memperoleh Informasi -->
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Opsi Memperoleh Informasi</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div id="memperoleh_informasi_container">
                                @foreach($pengaturan['memperoleh_informasi'] as $index => $item)
                                <div class="input-group" style="margin-bottom: 10px;">
                                    <span class="input-group-addon"><i class="fa fa-text-width"></i></span>
                                    <input type="text" name="memperoleh_informasi[]" class="form-control" value="{{ $item }}" placeholder="Opsi memperoleh informasi">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-danger remove-item"><i class="fa fa-trash"></i></button>
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" id="add_memperoleh_informasi">
                                <i class="fa fa-plus"></i> Tambah Opsi
                            </button>
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                    <!-- Alasan Keberatan -->
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Opsi Alasan Keberatan</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div id="alasan_keberatan_container">
                                @foreach($pengaturan['alasan_keberatan'] as $index => $item)
                                <div class="input-group" style="margin-bottom: 10px;">
                                    <span class="input-group-addon"><i class="fa fa-text-width"></i></span>
                                    <input type="text" name="alasan_keberatan[]" class="form-control" value="{{ $item }}" placeholder="Opsi alasan keberatan">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-danger remove-item"><i class="fa fa-trash"></i></button>
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" id="add_alasan_keberatan">
                                <i class="fa fa-plus"></i> Tambah Opsi
                            </button>
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                    <!-- Salinan Informasi -->
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Opsi Salinan Informasi</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div id="salinan_informasi_container">
                                @foreach($pengaturan['salinan_informasi'] as $index => $item)
                                <div class="input-group" style="margin-bottom: 10px;">
                                    <span class="input-group-addon"><i class="fa fa-text-width"></i></span>
                                    <input type="text" name="salinan_informasi[]" class="form-control" value="{{ $item }}" placeholder="Opsi salinan informasi">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-danger remove-item"><i class="fa fa-trash"></i></button>
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" id="add_salinan_informasi">
                                <i class="fa fa-plus"></i> Tambah Opsi
                            </button>
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                </div>
                <div class="box-footer">
                    @include('partials.button_submit')
                </div>
                {!! html()->form()->close() !!}
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Add new item function
        function addNewItem(containerId, name, placeholder) {
            var container = $('#' + containerId);
            var newItem = `
                <div class="input-group" style="margin-bottom: 10px;">
                    <span class="input-group-addon"><i class="fa fa-text-width"></i></span>
                    <input type="text" name="${name}[]" class="form-control" placeholder="${placeholder}">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-danger remove-item"><i class="fa fa-trash"></i></button>
                    </span>
                </div>
            `;
            container.append(newItem);
        }

        // Add buttons
        $('#add_memperoleh_informasi').click(function() {
            addNewItem('memperoleh_informasi_container', 'memperoleh_informasi', 'Opsi memperoleh informasi');
        });

        $('#add_alasan_keberatan').click(function() {
            addNewItem('alasan_keberatan_container', 'alasan_keberatan', 'Opsi alasan keberatan');
        });

        $('#add_salinan_informasi').click(function() {
            addNewItem('salinan_informasi_container', 'salinan_informasi', 'Opsi salinan informasi');
        });

        // Remove item (using event delegation)
        $(document).on('click', '.remove-item', function() {
            var container = $(this).closest('[id$="_container"]');
            if (container.children('.input-group').length > 1) {
                $(this).closest('.input-group').remove();
            } else {
                alert('Minimal harus ada satu opsi.');
            }
        });
    });
</script>
@endpush
