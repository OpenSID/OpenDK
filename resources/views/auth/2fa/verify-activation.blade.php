@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('otp2fa.index') }}">OTP & 2FA</a></li>
            <li class="active">Verifikasi</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Verifikasi Kode 2FA</h3>
                    </div>

                    <form action="{{ route('2fa.verify-activation') }}" method="POST">
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

                            <div class="callout callout-info" style="margin-bottom: 15px;">
                                <p style="margin: 0; font-size: 12px;">
                                    <i class="fa fa-info-circle"></i> Kode OTP berlaku selama
                                    <strong>{{ config('otp.expiry_minutes', 5) }} menit</strong>
                                </p>
                            </div>

                            <!-- Timer Display -->
                            <div class="alert alert-info text-center" id="timer-alert"
                                style="margin-bottom: 15px; padding: 8px;">
                                <i class="fa fa-clock-o"></i>
                                <strong style="font-size: 12px;">Sisa Waktu:</strong>
                                <span id="timer-display" style="font-size: 18px; font-weight: bold; margin-left: 10px;">
                                    {{ config('otp.expiry_minutes', 5) }}:00
                                </span>
                            </div>

                            <div class="form-group text-center">
                                <label>Masukkan 6 Digit Kode OTP</label>
                                <div class="otp-container"
                                    style="display: flex; justify-content: center; gap: 10px; margin-top: 15px;">
                                    <input type="text" class="form-control otp-input" maxlength="1"
                                        style="width: 50px; height: 50px; text-align: center; font-size: 24px;"
                                        data-index="0">
                                    <input type="text" class="form-control otp-input" maxlength="1"
                                        style="width: 50px; height: 50px; text-align: center; font-size: 24px;"
                                        data-index="1">
                                    <input type="text" class="form-control otp-input" maxlength="1"
                                        style="width: 50px; height: 50px; text-align: center; font-size: 24px;"
                                        data-index="2">
                                    <input type="text" class="form-control otp-input" maxlength="1"
                                        style="width: 50px; height: 50px; text-align: center; font-size: 24px;"
                                        data-index="3">
                                    <input type="text" class="form-control otp-input" maxlength="1"
                                        style="width: 50px; height: 50px; text-align: center; font-size: 24px;"
                                        data-index="4">
                                    <input type="text" class="form-control otp-input" maxlength="1"
                                        style="width: 50px; height: 50px; text-align: center; font-size: 24px;"
                                        data-index="5">
                                </div>
                                <input type="hidden" name="otp" id="otp-value">
                                @if ($errors->has('otp'))
                                    <span class="help-block text-danger">{{ $errors->first('otp') }}</span>
                                @endif
                            </div>

                            <div class="form-group text-center">
                                <p>Tidak menerima kode?</p>
                                <button type="button" class="btn btn-link" id="resend-btn" disabled>
                                    <i class="fa fa-refresh"></i> Kirim Ulang (<span
                                        id="countdown">{{ config('otp.resend_cooldown', 30) }}</span>s)
                                </button>
                            </div>
                        </div>

                        <div class="box-footer">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" id="verify-btn">
                                    <i class="fa fa-check"></i> Verifikasi
                                </button>
                                <a href="{{ route('otp2fa.index') }}" class="btn btn-default">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
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
                        alert('Kode OTP telah kadaluarsa. Silakan minta kode baru.');
                        window.location.href = '{{ route('otp2fa.index') }}';
                    }
                }, 1000);

                // Resend OTP
                $('#resend-btn').click(function() {
                    if ($(this).prop('disabled')) return;

                    $.post('{{ route('otp.resend') }}', {
                        purpose: '2fa_activation',
                        _token: '{{ csrf_token() }}'
                    }, function(response) {
                        if (response.success) {
                            // Reset countdown
                            countdown = resendCooldown;
                            $('#resend-btn').prop('disabled', true).html(
                                '<i class="fa fa-refresh"></i> Kirim Ulang (<span id="countdown">' +
                                countdown + '</span>s)');

                            // Restart resend interval
                            clearInterval(resendInterval);
                            resendInterval = setInterval(function() {
                                countdown--;
                                $('#countdown').text(countdown);

                                if (countdown <= 0) {
                                    clearInterval(resendInterval);
                                    $('#resend-btn').prop('disabled', false).html(
                                        '<i class="fa fa-refresh"></i> Kirim Ulang');
                                }
                            }, 1000);

                            alert('Kode OTP baru telah dikirim.');
                        } else {
                            alert(response.message || 'Gagal mengirim ulang kode OTP.');
                        }
                    }).fail(function() {
                        alert('Gagal mengirim ulang kode OTP.');
                    });
                });
            });
        </script>
    @endpush
@endsection
