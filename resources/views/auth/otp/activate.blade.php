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
                                    <p>Identifier: <strong>{{ $user->otp_identifier }}</strong></p>
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
                                <div class="form-group {{ $errors->has('channel') ? 'has-error' : '' }}">
                                    <label>Pilih Saluran OTP <span class="text-danger">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="channel" value="email"
                                                {{ old('channel') == 'email' ? 'checked' : '' }} required>
                                            <i class="fa fa-envelope"></i> Email
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="channel" value="telegram"
                                                {{ old('channel') == 'telegram' ? 'checked' : '' }}>
                                            <i class="fa fa-telegram"></i> Telegram
                                        </label>
                                    </div>
                                    @if ($errors->has('channel'))
                                        <span class="help-block">{{ $errors->first('channel') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('identifier') ? 'has-error' : '' }}"
                                    id="email-group">
                                    <label for="email">Alamat Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="identifier"
                                        placeholder="email@example.com" value="{{ old('identifier', $user->email) }}"
                                        required>
                                    <span class="help-block">Masukkan alamat email yang valid untuk menerima kode
                                        OTP.</span>
                                    @if ($errors->has('identifier'))
                                        <span class="help-block">{{ $errors->first('identifier') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('identifier') ? 'has-error' : '' }}"
                                    id="telegram-group" style="display: none;">
                                    <label for="telegram">Chat ID Telegram <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="telegram" name="telegram_identifier"
                                        placeholder="123456789" value="{{ old('identifier') }}">
                                    <span class="help-block">
                                        <strong>Cara mendapatkan Chat ID:</strong><br>
                                        1. Buka bot <a href="https://t.me/userinfobot" target="_blank">@userinfobot</a> di
                                        Telegram<br>
                                        2. Kirim pesan /start<br>
                                        3. Bot akan memberikan ID Anda<br>
                                    </span>
                                    @if ($errors->has('identifier'))
                                        <span class="help-block">{{ $errors->first('identifier') }}</span>
                                    @endif
                                </div>

                                <div class="callout callout-warning">
                                    <h4><i class="icon fa fa-warning"></i> Perhatian!</h4>
                                    <p>Pastikan Anda memiliki akses ke email atau Telegram yang Anda daftarkan. Kode
                                        verifikasi akan dikirim ke saluran yang Anda pilih.</p>
                                </div>
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
