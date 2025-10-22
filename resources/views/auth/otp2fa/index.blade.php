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
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success" style="margin-top:10px">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger" style="margin-top:10px">{{ session('error') }}</div>
                @endif
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Status Konfigurasi</h3>
                        <a href="{{ route('otp2fa.settings') }}" class="btn btn-primary" style="float: right;">
                            <i class="fa fa-cogs"></i> Atur Email / Telegram
                        </a>
                    </div>
                    <div class="box-body">
                        <p><strong>Metode verifikasi:</strong>
                            {{ $user->otp_channel ? ucfirst($user->otp_channel) : '-' }}</p>

                        @if ($user->otp_channel === 'email')
                            <p><strong>Email:</strong> {{ $user->email ?? '-' }}</p>
                        @elseif($user->otp_channel === 'telegram')
                            <p><strong>Telegram ID:</strong> {{ $user->telegram_id ?? '-' }}</p>
                        @endif

                        <p><strong>Status Verifikasi:</strong>
                            @if ($user->otp_verified)
                                <span class="label label-success"><i class="fa fa-check-circle"></i> Terverifikasi</span>
                            @else
                                <span class="label label-warning"><i class="fa fa-exclamation-circle"></i> Belum
                                    Verifikasi</span>
                            @endif
                        </p>

                        @if (!$user->otp_channel)
                            <div class="alert alert-warning">
                                Anda belum mengatur metode verifikasi. Silakan klik tombol "Atur Email / Telegram".
                            </div>
                        @elseif(!$user->otp_verified)
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle"></i> Anda belum memverifikasi channel verifikasi.
                                Silakan <a href="{{ route('otp2fa.settings') }}"><strong>verifikasi di halaman
                                        pengaturan</strong></a> untuk dapat mengaktifkan OTP atau 2FA.
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
                        <p>OTP diaktifkan: <strong>{{ $otpEnabled ? 'Ya' : 'Tidak' }}</strong></p>

                        <form action="{{ route('otp.request-activation') }}" method="POST">
                            @csrf
                            @if ($otpEnabled)
                                <a href="{{ route('otp.deactivate') }}" class="btn btn-warning">Nonaktifkan OTP</a>
                            @else
                                <button type="submit" class="btn btn-primary"
                                    {{ !$user->otp_verified ? 'disabled' : '' }}>
                                    Aktifkan OTP
                                </button>
                                @if (!$user->otp_verified)
                                    <p class="help-block text-danger" style="margin-top:8px">Silakan verifikasi channel
                                        terlebih dahulu di halaman pengaturan.</p>
                                @endif
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">

                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Status 2FA</h3>
                    </div>
                    <div class="box-body">
                        <p>2FA diaktifkan: <strong>{{ $twoFaEnabled ? 'Ya' : 'Tidak' }}</strong></p>

                        <form action="{{ route('2fa.request-activation') }}" method="POST">
                            @csrf
                            @if ($twoFaEnabled)
                                <a href="{{ route('2fa.deactivate') }}" class="btn btn-warning">Nonaktifkan 2FA</a>
                            @else
                                <button type="submit" class="btn btn-primary"
                                    {{ !$user->otp_verified ? 'disabled' : '' }}>
                                    Aktifkan 2FA
                                </button>
                                @if (!$user->otp_verified)
                                    <p class="help-block text-danger" style="margin-top:8px">Silakan verifikasi channel
                                        terlebih dahulu di halaman pengaturan.</p>
                                @endif
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.asset_sweetalert')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deactivateButtons = document.querySelectorAll('.btn-warning');
            deactivateButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const url = this.href;
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Tindakan ini akan menonaktifkan fitur ini.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, nonaktifkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });
        });
    </script>
@endsection
