@extends('surat.verifikasi.layout')

@section('content')
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
                <div class="form-group {{ $errors->has('file') ? 'has-error' : '' }}">
                    <label for="file">Pilih File Surat (PDF)</label>
                    <input type="file" id="file" name="file" class="form-control" accept=".pdf" required>
                    @if ($errors->has('file'))
                        <span class="help-block">{{ $errors->first('file') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
@endsection
