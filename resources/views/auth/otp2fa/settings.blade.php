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
                                <p>Pilih metode verifikasi untuk OTP dan Two-Factor Authentication (2FA). Email dan Telegram
                                    ID akan
                                    diambil dari data profil Anda. Jika perlu mengubah, silakan update profil Anda terlebih
                                    dahulu.</p>
                                <p><strong>Setelah menyimpan, Anda akan diminta memasukkan kode verifikasi untuk memastikan
                                        metode verifikasi dapat menerima kode dengan baik.</strong></p>
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

                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}" id="email-group">
                                <label for="email">Alamat Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="email@example.com" value="{{ old('email', $user->email) }}" readonly
                                        required>
                                    <span class="input-group-addon bg-gray" style="cursor: not-allowed;">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                </div>
                                <span class="help-block">
                                    <i class="fa fa-info-circle"></i> Email diambil dari profil Anda.
                                    @if (empty($user->email))
                                        <strong class="text-danger">Email belum diisi!</strong>
                                        <a href="{{ route('data.profil.index') }}">Lengkapi profil</a>
                                    @endif
                                </span>
                                @if ($errors->has('email'))
                                    <span class="help-block text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('telegram_id') ? 'has-error' : '' }}"
                                id="telegram-group" style="display: none;">
                                <label for="telegram">Chat ID Telegram <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="telegram" name="telegram_id"
                                        placeholder="123456789" value="{{ old('telegram_id', $user->telegram_id) }}"
                                        readonly required>
                                    <span class="input-group-addon bg-gray" style="cursor: not-allowed;">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                </div>
                                <span class="help-block">
                                    <i class="fa fa-info-circle"></i> Telegram ID diambil dari profil Anda.
                                    @if (empty($user->telegram_id))
                                        <strong class="text-danger">Telegram ID belum diisi!</strong>
                                        <a href="{{ route('data.profil.index') }}">Lengkapi profil</a><br>
                                    @endif
                                    <strong>Cara mendapatkan Chat ID:</strong><br>
                                    1. Buka bot <a href="https://t.me/userinfobot" target="_blank">@userinfobot</a> di
                                    Telegram<br>
                                    2. Kirim pesan /start<br>
                                    3. Bot akan memberikan ID Anda<br>
                                </span>
                                @if ($errors->has('telegram_id'))
                                    <span class="help-block text-danger">{{ $errors->first('telegram_id') }}</span>
                                @endif
                            </div>

                            <div class="callout callout-warning">
                                <h4><i class="icon fa fa-warning"></i> Perhatian!</h4>
                                <p>Pastikan Anda memiliki akses ke email atau Telegram yang Anda daftarkan.
                                    Setelah menyimpan, sistem akan mengirim kode verifikasi untuk memastikan metode
                                    verifikasi berfungsi.</p>
                                @if ($user->otp_verified && $user->otp_channel)
                                    <p><strong class="text-info"><i class="fa fa-info-circle"></i> Jika Anda mengganti
                                            metode verifikasi, fitur OTP dan 2FA yang aktif akan dinonaktifkan dan Anda
                                            perlu verifikasi ulang serta mengaktifkan kembali.</strong></p>
                                @endif
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
                        $('#email').prop('required', true);
                        $('#telegram').prop('required', false);
                    } else if (channel === 'telegram') {
                        $('#email-group').hide();
                        $('#telegram-group').show();
                        $('#telegram').prop('required', true);
                        $('#email').prop('required', false);
                    }
                });

                // Trigger initial state
                $('input[name="channel"]:checked').trigger('change');
            });
        </script>
    @endpush
@endsection
