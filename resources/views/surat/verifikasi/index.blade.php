@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 30px; margin-bottom: 30px;">
        @include('partials.flash_message')
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Verifikasi Keaslian Surat Digital</h3>
            </div>
            <div class="box-body">
                <div class="callout callout-info">
                    <h4><i class="icon fa fa-info"></i> Info!</h4>
                    Unggah file surat (PDF) untuk memverifikasi keasliannya. Sistem akan memeriksa apakah file ini benar-benar
                    diterbitkan oleh Kecamatan {{ optional($profil)->nama_kecamatan ?? '' }}.
                </div>
                <form method="POST" action="{{ route('surat.verifikasi.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Pilih File Surat (PDF)</label>
                        <input type="file" id="file" name="file" class="form-control" accept=".pdf" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Verifikasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
