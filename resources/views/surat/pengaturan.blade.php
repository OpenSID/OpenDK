@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header">
        <h1>
            {{ $page_title ?? "Page Title" }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <section class="content">
        @include('partials.flash_message')
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $page_title }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <form method="POST" action="{{ $formAction }}" id="form-pengaturan-surat">
                        @include('layouts.fragments.error_message')
                        <div class="box-body">
                            @include( 'flash::message' )
                            {{ method_field('PUT') }}
                            @csrf
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="tte">Aktifkan Modul TTE</label>
                                <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                                    <label id="n1"
                                        class="tipe btn btn-primary btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label {{ $settings['tte'] ? 'active' : '' }}">
                                        <input id="q1" type="radio" name="tte" class="form-check-input" type="radio"
                                            value="1" {{ $settings['tte'] ? 'checked' : '' }} autocomplete="off">Ya
                                    </label>
                                    <label id="n2"
                                        class="tipe btn btn-primary btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label {{ $settings['tte'] ? '' : 'active' }}">
                                        <input id="q2" type="radio" name="tte" class="form-check-input" type="radio"
                                            value="0" {{ $settings['tte'] ? '' : 'checked' }} autocomplete="off">Tidak
                                    </label>
                                </div>
                            </div>
                            <br/>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="tte_api">URL API Server TTE</label>
                                <div class="col-sm-9">
                                    <input id="tte_api" class="form-control input-sm" type="text" placeholder="Masukkan URL API Server TTE" name="tte_api" value="{{ $settings['tte_api'] }}" {{ $settings['tte'] ? '' : 'disabled' }}>
                                </div>
                            </div>
                            <br/>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="tte_username">Username API Server TTE</label>
                                <div class="col-sm-9">
                                    <input id="tte_username" class="form-control input-sm" type="text" placeholder="Masukkan Username API Server TTE" name="tte_username" value="{{ $settings['tte_username'] }}" {{ $settings['tte'] ? '' : 'disabled' }}>
                                </div>
                            </div>
                            <br/>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="tte_password">Password API Server TTE</label>
                                <div class="col-sm-9">
                                    <input id="tte_password" class="form-control input-sm" type="text" placeholder="Masukkan Password API Server TTE" name="tte_password" value="{{ $settings['tte_password'] }}" {{ $settings['tte'] ? '' : 'disabled' }}>
                                </div>
                            </div>
                            <br/>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="pemeriksaan_camat">Pemeriksaan Camat</label>
                                <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                                    <label id="n1"
                                        class="tipe btn btn-primary btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label {{ $settings['pemeriksaan_camat'] ? 'active' : '' }}">
                                        <input id="q1" type="radio" name="pemeriksaan_camat" class="form-check-input" type="radio"
                                            value="1" {{ $settings['pemeriksaan_camat'] ? 'checked' : '' }} autocomplete="off">Ya
                                    </label>
                                    <label id="n2"
                                        class="tipe btn btn-primary btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label {{ $settings['pemeriksaan_camat'] ? '' : 'active' }}">
                                        <input id="q2" type="radio" name="pemeriksaan_camat" class="form-check-input" type="radio"
                                            value="0" {{ $settings['pemeriksaan_camat'] ? '' : 'checked' }} autocomplete="off">Tidak
                                    </label>
                                </div>
                            </div>
                            <br/>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="pemeriksaan_sekretaris">Pemeriksaan Sekretaris</label>
                                <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                                    <label id="n1"
                                        class="tipe btn btn-primary btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label {{ $settings['pemeriksaan_sekretaris'] ? 'active' : '' }}">
                                        <input id="q1" type="radio" name="pemeriksaan_sekretaris" class="form-check-input" type="radio"
                                            value="1" {{ $settings['pemeriksaan_sekretaris'] ? 'checked' : '' }} autocomplete="off">Ya
                                    </label>
                                    <label id="n2"
                                        class="tipe btn btn-primary btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label {{ $settings['pemeriksaan_sekretaris'] ? '' : 'active' }}">
                                        <input id="q2" type="radio" name="pemeriksaan_sekretaris" class="form-check-input" type="radio"
                                            value="0" {{ $settings['pemeriksaan_sekretaris'] ? '' : 'checked' }} autocomplete="off">Tidak
                                    </label>
                                </div>
                            </div>
                            <br/>
                            <div class="ln_solid"></div>
                        </div>
                        <div class="box-footer">
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <a href="{{ route('surat.pengaturan') }}">
                                        <button type="button" class="btn btn-default">Batal</button>
                                    </a>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- /.box-body -->
                </div>
                <!-- /. box -->
            </div>
        </div>
    </section>
@endsection
@include('partials.asset_jqueryvalidation')

@push('scripts')
{!! JsValidator::formRequest('App\Http\Requests\PengaturanSuratRequest', '#form-pengaturan-surat') !!}

<script>
    $('input[type=radio][name=tte]').change(function() {
        if (this.value == 1) {
            $('#tte_api').prop("disabled", false);
            $('#tte_username').prop("disabled", false);
            $('#tte_password').prop("disabled", false);
        }
        else {
            $('#tte_api').prop("disabled", true);
            $('#tte_username').prop("disabled", true);
            $('#tte_password').prop("disabled", true);
        }
    });
</script>    
@endpush