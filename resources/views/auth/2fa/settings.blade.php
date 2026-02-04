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
                        <h3 class="box-title">Pengaturan Two-Factor Authentication</h3>
                    </div>

                    <form action="{{ route('2fa.save-settings') }}" method="POST">
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
                                <p>Pilih metode verifikasi untuk Two-Factor Authentication (2FA). Setelah menyimpan
                                    pengaturan, Anda perlu mengaktifkan 2FA di halaman aktivasi.</p>
                            </div>

                            <div class="form-group {{ $errors->has('channel') ? 'has-error' : '' }}">
                                <label>Pilih Metode Verifikasi <span class="text-danger">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="channel" value="email"
                                            {{ old('channel', $user->otp_channel) == 'email' || (!old('channel') && !$user->otp_channel) ? 'checked' : '' }}
                                            required>
                                        <i class="fa fa-envelope"></i> Email
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="channel" value="telegram"
                                            {{ old('channel', $user->otp_channel) == 'telegram' ? 'checked' : '' }}>
                                        <i class="fa fa-telegram"></i> Telegram
                                    </label>
                                </div>
                                @if ($errors->has('channel'))
                                    <span class="help-block">{{ $errors->first('channel') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('identifier') ? 'has-error' : '' }}" id="email-group">
                                <label for="email">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="identifier"
                                    placeholder="email@example.com"
                                    value="{{ old('identifier', $user->otp_channel == 'email' ? $user->otp_identifier : $user->email) }}"
                                    required>
                                <span class="help-block">Masukkan alamat email yang valid untuk menerima kode OTP.</span>
                                @if ($errors->has('identifier'))
                                    <span class="help-block">{{ $errors->first('identifier') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('identifier') ? 'has-error' : '' }}" id="telegram-group"
                                style="display: none;">
                                <label for="telegram">Chat ID Telegram <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="telegram" name="telegram_identifier"
                                    placeholder="123456789"
                                    value="{{ old('identifier', $user->otp_channel == 'telegram' ? $user->otp_identifier : '') }}">
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
                                <p>Pastikan Anda memiliki akses ke email atau Telegram yang Anda daftarkan. Setelah
                                    menyimpan pengaturan ini, Anda perlu mengaktifkan 2FA di halaman aktivasi.</p>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Simpan Pengaturan
                            </button>
                            <a href="{{ route('otp2fa.index') }}" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

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
            });
        </script>
    @endpush
@endsection
