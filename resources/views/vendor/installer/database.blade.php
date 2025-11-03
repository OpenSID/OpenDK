@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.database.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-database fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.database.title') }}
@endsection

@section('container')
    <form method="post" action="{{ route('installer.performInstallation') }}">
        {{ csrf_field() }}

        <div class="form-group">
            <label>Status Koneksi Database</label>
            @if(isset($canConnect) && $canConnect)
                <div class="alert alert-success">
                    <i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>
                    {{ $message }}
                </div>
            @else
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i>
                    {{ $message ?? 'Tidak dapat menghubungkan ke database' }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <p>
                <strong>Catatan:</strong> Pastikan pengaturan database di file .env sudah benar sebelum melanjutkan
                instalasi.
                Instalasi akan menjalankan migrasi database dan seeder.
            </p>
        </div>

        @if(isset($canConnect) && $canConnect)
            <div class="buttons">
                <button class="button" type="submit">
                    Lanjutkan Instalasi
                    <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                </button>
            </div>
        @else
            <div class="buttons">
                <a href="{{ route('installer.environment') }}" class="button">
                    <i class="fa fa-angle-left fa-fw" aria-hidden="true"></i>
                    Kembali ke Pengaturan Environment
                </a>
            </div>
        @endif
    </form>
@endsection