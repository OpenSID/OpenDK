@extends('layouts.dashboard_template')

@section('content')

<section class="content-header block-breadcrumb">
    <h1>
        {{ $page_title ?? 'Page Title' }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>
<section class="content container-fluid">

    @include('partials.flash_message')

    <div class="row">
        <div class="col-md-12">
            <!-- kirim komplain form -->
            {!! Html::form()!!}
            <div class="box box-primary">
                <div class="box-body">
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

                    <div class="row">
                        <div class="col-md-12">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('nik') ? ' has-error' : '' }}">
                                <label class="control-label col-md-2 col-sm-3 col-xs-12">NIK <span
                                        class="required">*</span></label>

                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    {!! Html::text('nik')->class('form-control')->readonly()->placeholder('NIK')->value(old('nik', isset($komplain) ? $komplain->nik : '')) !!}
                                    @if ($errors->has('nik'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nik') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }}">
                                <label class="control-label col-md-2 col-sm-3 col-xs-12">Nama <span
                                        class="required">*</span></label>

                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    {!! Html::text('nama')->class('form-control')->readonly()->placeholder('Nama')->value(old('nama', isset($komplain) ? $komplain->nama : '')) !!}
                                    @if ($errors->has('nama'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nama') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('kategori') ? ' has-error' : '' }}">
                                <label class="control-label col-md-2 col-sm-3 col-xs-12">Kategori <span
                                        class="required">*</span></label>

                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    {!! Html::select('kategori', \App\Models\KategoriKomplain::pluck('nama', 'id')->value(old('kategori', isset($komplain) ? $komplain->kategori : '')),
                                    null, [
                                    'class' => 'form-control',
                                    'id' => 'kategori',
                                    'required',
                                    ]) !!}
                                    @if ($errors->has('kategori'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('kategori') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('judul') ? ' has-error' : '' }}">
                                <label class="control-label col-md-2 col-sm-3 col-xs-12">Judul <span
                                        class="required">*</span></label>

                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    {!! Html::text('judul')->class('form-control')->placeholder('Judul')->value(old('judul', isset($komplain) ? $komplain->judul : '')) !!}
                                    @if ($errors->has('judul'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('judul') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('laporan') ? ' has-error' : '' }}">
                                <label class="control-label col-md-2 col-sm-3 col-xs-12">Laporan <span
                                        class="required">*</span></label>

                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    {!! Html::textarea('laporan')->class('form-control')->placeholder('Laporan')->value(old('laporan', isset($komplain) ? $komplain->laporan : '')) !!}
                                    @if ($errors->has('laporan'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('laporan') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('laporan1') ? ' has-error' : '' }}">
                                <label class="control-label col-md-2 col-sm-3 col-xs-12">Lampiran</label>

                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="lampiran1" name="lampiran1"
                                                accept=".png, .jpg, .jpeg" />
                                            <label for="lampiran1"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="lampiranPreview1"
                                                style="background-image: url(@if (!$komplain->lampiran1 == '') {{ asset($komplain->lampiran1) }} @else {{ 'https://via.placeholder.com/80x100' }} @endif );">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="lampiran2" name="lampiran2"
                                                accept=".png, .jpg, .jpeg" />
                                            <label for="lampiran2"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="lampiranPreview2"
                                                style="background-image: url(@if (!$komplain->lampiran2 == '') {{ asset($komplain->lampiran2) }} @else {{ 'https://via.placeholder.com/80x100' }} @endif );">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="lampiran3" name="lampiran3"
                                                accept=".png, .jpg, .jpeg" />
                                            <label for="lampiran3"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="lampiranPreview3"
                                                style="background-image: url(@if (!$komplain->lampiran3 == '') {{ asset($komplain->lampiran3) }} @else {{ 'https://via.placeholder.com/80x100' }} @endif );">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="lampiran4" name="lampiran4"
                                                accept=".png, .jpg, .jpeg" />
                                            <label for="lampiran4"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="lampiranPreview4"
                                                style="background-image: url(@if (!$komplain->lampiran4 == '') {{ asset($komplain->lampiran4) }} @else {{ 'https://via.placeholder.com/80x100' }} @endif );">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                <label class="control-label col-md-2 col-sm-3 col-xs-12">Status</label>

                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Html::select('status', ['BELUM' => 'Belum', 'PROSES' => 'Proses', 'SELESAI' =>
                                    'Selesai'])->class('form-control')->value(old('status', isset($komplain) ? $komplain->status : '')) !!}
                                    @if ($errors->has('status'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="box-footer clearfix">
                    @include('partials.button_reset_submit')
                </div>
            </div>
            {!! Html::form()->close() !!}
        </div>
    </div>
</section>
@endsection

@include('partials.asset_upload_images')

@push('scripts')
<script type="text/javascript">
    $(".btn-refresh").click(function() {
            $.ajax({
                type: 'GET',
                url: '/refresh-captcha',
                success: function(data) {
                    $(".captcha span").html(data.captcha);
                }
            });
        });
</script>
@endpush