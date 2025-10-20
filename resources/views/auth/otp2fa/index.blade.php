@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header">
        <h1>
            {{ $page_title ?? 'OTP & 2FA' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pengaturan Kontak Verifikasi</h3>
                    </div>

                    <div class="box-body">
                        <p>Gunakan halaman pengaturan untuk menentukan alamat Email atau Chat ID Telegram yang akan
                            digunakan untuk OTP dan 2FA.</p>

                        <a href="{{ route('2fa.settings') }}" class="btn btn-primary">
                            <i class="fa fa-cogs"></i> Atur Email / Telegram
                        </a>

                        @if (session('success'))
                            <div class="alert alert-success" style="margin-top:10px">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger" style="margin-top:10px">{{ session('error') }}</div>
                        @endif
                    </div>
                </div>

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Status Konfigurasi</h3>
                    </div>
                    <div class="box-body">
                        <p><strong>Metode verifikasi:</strong>
                            {{ $user->otp_channel ? ucfirst($user->otp_channel) : '-' }}</p>
                        <p><strong>Identifier:</strong>
                            {{ $user->otp_identifier ?? '-' }}</p>

                        @if ($needsSetup)
                            <div class="alert alert-warning">
                                Anda belum mengatur metode verifikasi. Silakan klik tombol "Atur Email / Telegram".
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Status OTP</h3>
                    </div>
                    <div class="box-body">
                        <p>OTP diaktifkan: <strong>{{ $user->otp_enabled ? 'Ya' : 'Tidak' }}</strong></p>
                        <p>OTP metode: <strong>{{ $user->otp_channel ? ucfirst($user->otp_channel) : '-' }}</strong></p>
                        <p>Identifier: <strong>{{ $user->otp_identifier ?? '-' }}</strong></p>

                        <form action="{{ route('otp.request-activation') }}" method="POST">
                            @csrf
                            @if ($user->otp_enabled)
                                <a href="{{ route('otp.deactivate') }}" class="btn btn-warning">Nonaktifkan OTP</a>
                            @else
                                <button type="submit" class="btn btn-primary" {{ $needsSetup ? 'disabled' : '' }}>
                                    Kirim Kode Aktivasi OTP
                                </button>
                                @if ($needsSetup)
                                    <p class="help-block text-danger" style="margin-top:8px">Silakan atur kontak verifikasi
                                        terlebih dahulu.</p>
                                @endif
                            @endif
                        </form>
                    </div>
                </div>

                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Status 2FA</h3>
                    </div>
                    <div class="box-body">
                        <p>2FA diaktifkan: <strong>{{ $user->two_fa_enabled ? 'Ya' : 'Tidak' }}</strong></p>
                        <p>Metode 2FA: <strong>{{ $user->otp_channel ? ucfirst($user->otp_channel) : '-' }}</strong></p>
                        <p>Identifier: <strong>{{ $user->otp_identifier ?? '-' }}</strong></p>

                        <form action="{{ route('2fa.request-activation') }}" method="POST">
                            @csrf
                            @if ($user->two_fa_enabled)
                                <a href="{{ route('2fa.deactivate') }}" class="btn btn-warning">Nonaktifkan 2FA</a>
                            @else
                                <button type="submit" class="btn btn-primary" {{ $needsSetup ? 'disabled' : '' }}>
                                    Kirim Kode Aktivasi 2FA
                                </button>
                                @if ($needsSetup)
                                    <p class="help-block text-danger" style="margin-top:8px">Silakan atur kontak verifikasi
                                        terlebih dahulu.</p>
                                @endif
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.asset_sweetalert')
@endsection
