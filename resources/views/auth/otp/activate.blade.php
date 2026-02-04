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
                        <h3 class="box-title">Aktivasi Autentikasi OTP</h3>
                    </div>

                    <form action="{{ route('otp.request-activation') }}" method="POST">
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
                                <p>Aktifkan OTP untuk menambahkan lapisan keamanan ekstra pada akun Anda. Anda dapat memilih
                                    untuk menerima kode OTP melalui email atau Telegram.</p>
                            </div>

                            @if ($user->otp_enabled)
                                <div class="alert alert-success">
                                    <h4><i class="icon fa fa-check"></i> OTP Sudah Aktif</h4>
                                    <p>Anda saat ini menggunakan OTP melalui:
                                        <strong>{{ ucfirst($user->otp_channel) }}</strong>
                                    </p>
                                    @if ($user->otp_channel === 'email')
                                        <p>Email: <strong>{{ $user->email }}</strong></p>
                                    @elseif($user->otp_channel === 'telegram')
                                        <p>Telegram ID: <strong>{{ $user->telegram_id }}</strong></p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>Ubah Konfigurasi OTP</label>
                                    <p class="help-block">Jika Anda ingin mengubah konfigurasi OTP, nonaktifkan terlebih
                                        dahulu lalu aktifkan kembali dengan konfigurasi baru.</p>
                                    <a href="{{ route('otp.deactivate') }}" class="btn btn-warning btn-deactivate-otp">
                                        <i class="fa fa-times"></i> Nonaktifkan OTP
                                    </a>
                                </div>
                            @else
                                @if (!$user->otp_channel)
                                    <div class="alert alert-warning">
                                        <h4><i class="icon fa fa-warning"></i> Konfigurasi Diperlukan</h4>
                                        <p>Anda perlu mengatur metode verifikasi (Email atau Telegram) terlebih dahulu
                                            sebelum
                                            mengaktifkan OTP.</p>
                                        <p>
                                            <a href="{{ route('2fa.settings') }}" class="btn btn-primary">
                                                <i class="fa fa-cogs"></i> Buka Pengaturan OTP/2FA
                                            </a>
                                        </p>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <h4><i class="icon fa fa-info-circle"></i> Siap untuk Diaktifkan</h4>
                                        <p>Metode verifikasi Anda telah diatur. Klik tombol di bawah untuk mengirim kode
                                            verifikasi dan mengaktifkan OTP.</p>
                                        <p>Metode verifikasi: <strong>{{ ucfirst($user->otp_channel) }}</strong></p>
                                        @if ($user->otp_channel === 'email')
                                            <p>Email: <strong>{{ $user->email }}</strong></p>
                                        @elseif($user->otp_channel === 'telegram')
                                            <p>Telegram ID: <strong>{{ $user->telegram_id }}</strong></p>
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="box-footer">
                            @if (!$user->otp_enabled)
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-send"></i> Kirim Kode OTP
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @include('partials.asset_sweetalert')
    @push('scripts')
        <script>
            $(document).ready(function() {
                // Toggle between email and telegram input
                $('input[name="channel"]').change(function() {
                    var channel = $(this).val();
                    if (channel === 'email') {
                        $('#email-group').show();
                        $('#telegram-group').hide();
                        $('#email').attr('name', 'identifier').prop('required', true);
                        $('#telegram').attr('name', 'telegram_identifier').prop('required', false);
                    } else if (channel === 'telegram') {
                        $('#email-group').hide();
                        $('#telegram-group').show();
                        $('#telegram').attr('name', 'identifier').prop('required', true);
                        $('#email').attr('name', 'email_identifier').prop('required', false);
                    }
                });

                // Trigger initial state
                $('input[name="channel"]:checked').trigger('change');

                // SweetAlert2 confirmation for deactivating OTP
                $(document).on('click', '.btn-deactivate-otp', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    Swal.fire({
                        title: 'Nonaktifkan OTP?',
                        text: 'Apakah Anda yakin ingin menonaktifkan OTP?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, nonaktifkan',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to the deactivate route
                            window.location.href = url;
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
