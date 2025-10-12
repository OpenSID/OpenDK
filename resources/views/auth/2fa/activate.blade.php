@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        {{ $page_title ?? 'Page Title' }}
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
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Aktivasi Two-Factor Authentication</h3>
                </div>

                <form action="{{ route('2fa.request-activation') }}" method="POST">
                    @csrf
                    <div class="box-body">
                        @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('success') }}
                        </div>
                        @endif

                        @if (session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('error') }}
                        </div>
                        @endif

                        <div class="callout callout-info">
                            <h4><i class="icon fa fa-info-circle"></i> Informasi</h4>
                            <p>Two-Factor Authentication (2FA) menambahkan lapisan keamanan ekstra pada akun Anda dengan
                                memerlukan verifikasi tambahan saat login.</p>
                        </div>

                        @if ($user->two_fa_enabled)
                        <div class="alert alert-success">
                            <h4><i class="icon fa fa-check"></i> 2FA Sudah Aktif</h4>
                            <p>Two-Factor Authentication saat ini aktif pada akun Anda.</p>
                            <p>Metode verifikasi: <strong>{{ ucfirst($user->otp_channel) }}</strong></p>
                            <p>Identifier: <strong>{{ $user->otp_identifier }}</strong></p>
                        </div>

                        <div class="form-group">
                            <label>Nonaktifkan 2FA</label>
                            <p class="help-block">Jika Anda ingin menonaktifkan 2FA, klik tombol di bawah.</p>
                            <a href="{{ route('2fa.deactivate') }}" class="btn btn-warning"
                                onclick="return confirm('Apakah Anda yakin ingin menonaktifkan Two-Factor Authentication?')">
                                <i class="fa fa-times"></i> Nonaktifkan 2FA
                            </a>
                        </div>
                        @else
                        @if ($needsSetup)
                        <div class="alert alert-warning">
                            <h4><i class="icon fa fa-warning"></i> Konfigurasi Diperlukan</h4>
                            <p>Anda perlu mengatur metode verifikasi terlebih dahulu sebelum mengaktifkan 2FA.</p>
                            <a href="{{ route('2fa.settings') }}" class="btn btn-primary">
                                <i class="fa fa-cog"></i> Atur Metode Verifikasi
                            </a>
                        </div>
                        @else
                        <div class="alert alert-info">
                            <h4><i class="icon fa fa-info-circle"></i> Siap untuk Diaktifkan</h4>
                            <p>Metode verifikasi Anda telah diatur. Klik tombol di bawah untuk mengirim kode verifikasi
                                dan mengaktifkan 2FA.</p>
                            <p>Metode verifikasi: <strong>{{ ucfirst($user->otp_channel) }}</strong></p>
                            <p>Identifier: <strong>{{ $user->otp_identifier }}</strong></p>
                        </div>
                        @endif
                        @endif
                    </div>

                    <div class="box-footer">
                        @if (!$user->two_fa_enabled && !$needsSetup)
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-send"></i> Kirim Kode Verifikasi
                        </button>
                        @endif
                        <a href="{{ route('2fa.settings') }}" class="btn btn-default">
                            <i class="fa fa-cogs"></i> Konfigurasi 2FA
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection