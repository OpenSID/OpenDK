<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'Laravel') }} | Verifikasi 2FA</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ is_logo($profil->file_logo) }}" />
    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        html {
            height: auto;
        }

        .otp-container {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin: 20px 0;
        }

        .otp-input {
            width: 45px;
            height: 45px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            border: 2px solid #ddd;
            border-radius: 5px;
        }

        .otp-input:focus {
            border-color: #3c8dbc;
            outline: none;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box" style="background-color: white; width: 400px;">
        <div class="login-box-body">
            <div class="login-logo" style="padding-top: 10px;">
                <a href="{{ route('beranda') }}">
                    <img src="{{ is_logo($profil->file_logo) }}" style="max-width:80px;white-space:normal"
                        alt="" width="70px">
                    <h3>PEMERINTAH KAB.
                        {{ strtoupper($profil->nama_kabupaten) }}<br /><b>{{ strtoupper($sebutan_wilayah . ' ' . $profil->nama_kecamatan) }}</b>
                    </h3>
                </a>
            </div>
            <hr />

            <div class="text-center" style="margin-bottom: 20px;">
                <h4><i class="fa fa-lock"></i> Verifikasi Two-Factor Authentication</h4>
                <p class="text-muted">Masukkan kode yang telah dikirim ke {{ $user->otp_channel === 'email' ? 'email' : 'Telegram' }} Anda</p>
            </div>

            @include('partials.flash_message')

            <div class="callout callout-info" style="margin-bottom: 15px;">
                <p style="margin: 0; font-size: 12px;">
                    <i class="fa fa-info-circle"></i> Kode 2FA berlaku selama
                    <strong>{{ config('otp.expiry_minutes', 5) }} menit</strong>
                </p>
            </div>

            <!-- Timer Display -->
            <div class="alert alert-info text-center" id="timer-alert" style="margin-bottom: 15px; padding: 8px;">
                <i class="fa fa-clock-o"></i>
                <strong style="font-size: 12px;">Sisa Waktu:</strong>
                <span id="timer-display" style="font-size: 18px; font-weight: bold; margin-left: 10px;">
                    {{ config('otp.expiry_minutes', 5) }}:00
                </span>
            </div>

            <form method="POST" action="{{ route('2fa.verify-login') }}">
                @csrf

                <div class="form-group text-center">
                    <div class="otp-container">
                        <input type="text" class="form-control otp-input" maxlength="1" data-index="0">
                        <input type="text" class="form-control otp-input" maxlength="1" data-index="1">
                        <input type="text" class="form-control otp-input" maxlength="1" data-index="2">
                        <input type="text" class="form-control otp-input" maxlength="1" data-index="3">
                        <input type="text" class="form-control otp-input" maxlength="1" data-index="4">
                        <input type="text" class="form-control otp-input" maxlength="1" data-index="5">
                    </div>
                    <input type="hidden" name="otp" id="otp-value">
                    @if ($errors->has('otp'))
                        <span class="help-block text-danger">{{ $errors->first('otp') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember"> Ingat saya
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">
                            <i class="fa fa-sign-in"></i> Login
                        </button>
                    </div>
                </div>
            </form>

            <hr />

            <div class="text-center">
                <p>Tidak menerima kode?</p>
                <button type="button" class="btn btn-link" id="resend-btn" disabled>
                    <i class="fa fa-refresh"></i> Kirim Ulang (<span id="countdown">30</span>s)
                </button>
            </div>

            <hr />

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-center">
                    <i class="fa fa-arrow-left"></i> Kembali ke Login
                </a>
            </div>

            <hr />
            <div class="text-center">
                <small>Hak Cipta &copy; 2017 <a href="http://www.kompak.or.id">KOMPAK</a>, 2018-{{ date('Y') }} <a
                        href="http://opendesa.id">OpenDesa</a>
                    <br />
                    <b><a href="https://github.com/openSID/openDK" target="_blank">OpenDK</a></b>
                    {{ config('app.version') }}
                </small>
            </div>
        </div>
    </div>

    <script src="{{ asset('/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var expiryMinutes = {{ config('otp.expiry_minutes', 5) }};
            var expirySeconds = expiryMinutes * 60;
            var resendCooldown = {{ config('otp.resend_cooldown', 30) }};
            var countdown = resendCooldown;

            // OTP Input handling
            $('.otp-input').on('input', function() {
                var value = $(this).val();

                // Only allow numbers
                if (!/^\d*$/.test(value)) {
                    $(this).val('');
                    return;
                }

                // Move to next input
                if (value.length === 1) {
                    var nextIndex = parseInt($(this).data('index')) + 1;
                    if (nextIndex < 6) {
                        $('.otp-input[data-index="' + nextIndex + '"]').focus();
                    }
                }

                // Update hidden input
                updateOtpValue();
            });

            // Handle backspace
            $('.otp-input').on('keydown', function(e) {
                if (e.keyCode === 8 && $(this).val() === '') {
                    var prevIndex = parseInt($(this).data('index')) - 1;
                    if (prevIndex >= 0) {
                        $('.otp-input[data-index="' + prevIndex + '"]').focus();
                    }
                }
            });

            // Handle paste
            $('.otp-input').on('paste', function(e) {
                e.preventDefault();
                var pastedData = e.originalEvent.clipboardData.getData('text');
                var digits = pastedData.replace(/\D/g, '').slice(0, 6);

                for (var i = 0; i < digits.length; i++) {
                    $('.otp-input[data-index="' + i + '"]').val(digits[i]);
                }

                updateOtpValue();
            });

            function updateOtpValue() {
                var otp = '';
                $('.otp-input').each(function() {
                    otp += $(this).val();
                });
                $('#otp-value').val(otp);
            }

            // Countdown timer for resend
            var resendInterval = setInterval(function() {
                countdown--;
                $('#countdown').text(countdown);

                if (countdown <= 0) {
                    clearInterval(resendInterval);
                    $('#resend-btn').prop('disabled', false).html(
                        '<i class="fa fa-refresh"></i> Kirim Ulang');
                }
            }, 1000);

            // Expiry timer countdown
            var expiryInterval = setInterval(function() {
                expirySeconds--;
                var percentage = (expirySeconds / (expiryMinutes * 60)) * 100;

                // Update timer display
                var minutes = Math.floor(expirySeconds / 60);
                var seconds = expirySeconds % 60;
                var timeString = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
                $('#timer-display').text(timeString);

                // Change timer color based on remaining time
                if (percentage < 20) {
                    $('#timer-alert').removeClass('alert-warning alert-info').addClass(
                        'alert-danger');
                } else if (percentage < 50) {
                    $('#timer-alert').removeClass('alert-info').addClass('alert-warning');
                }

                if (expirySeconds <= 0) {
                    clearInterval(expiryInterval);
                    clearInterval(resendInterval);
                    alert('Kode 2FA telah kadaluarsa. Silakan minta kode baru.');
                    window.location.href = '{{ route('login') }}';
                }
            }, 1000);

            // Resend OTP
            $('#resend-btn').click(function() {
                if ($(this).prop('disabled')) return;

                $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Mengirim...');

                $.ajax({
                    url: '{{ route('otp.resend') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        purpose: '2fa_login'
                    },
                    success: function(response) {
                        alert(response.message);

                        // Reset expiry timer
                        expirySeconds = expiryMinutes * 60;
                        $('#timer-display').text(expiryMinutes + ':00');
                        $('#timer-alert').removeClass('alert-danger alert-warning')
                            .addClass('alert-info');

                        // Reset resend countdown
                        countdown = resendCooldown;
                        $('#resend-btn').html(
                            '<i class="fa fa-refresh"></i> Kirim Ulang (<span id="countdown">' +
                            countdown + '</span>s)');

                        resendInterval = setInterval(function() {
                            countdown--;
                            $('#countdown').text(countdown);

                            if (countdown <= 0) {
                                clearInterval(resendInterval);
                                $('#resend-btn').prop('disabled', false).html(
                                    '<i class="fa fa-refresh"></i> Kirim Ulang');
                            }
                        }, 1000);
                    },
                    error: function(xhr) {
                        alert('Gagal mengirim ulang kode 2FA');
                        $('#resend-btn').prop('disabled', false).html(
                            '<i class="fa fa-refresh"></i> Kirim Ulang');
                    }
                });
            });

            // Auto focus first input
            $('.otp-input[data-index="0"]').focus();

            // Auto dismiss flash messages (except timer)
            window.setTimeout(function() {
                $("#notifikasi, .alert:not(#timer-alert)").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>
</body>

</html>