<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'Laravel') }} | Login OTP</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ is_logo($profil->file_logo) }}" />
    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        html {
            height: auto;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box" style="background-color: white;">
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
                <h4><i class="fa fa-mobile"></i> Login dengan OTP</h4>
                <p class="text-muted">Masukkan email atau username Anda</p>
            </div>

            @include('partials.flash_message')

            <form method="POST" action="{{ route('otp.request-login') }}">
                @csrf
                <div class="form-group has-feedback {{ $errors->has('identifier') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <input id="identifier" type="text" class="form-control" name="identifier"
                            value="{{ old('identifier') }}" required autofocus placeholder="Email atau Username">
                        @if ($errors->has('identifier'))
                            <span class="help-block">
                                <strong>{{ $errors->first('identifier') }}</strong>
                            </span>
                        @endif
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">
                            <i class="fa fa-send"></i> Kirim Kode OTP
                        </button>
                    </div>
                </div>
            </form>

            <hr />

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-center">
                    <i class="fa fa-arrow-left"></i> Kembali ke Login Password
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
            window.setTimeout(function() {
                $("#notifikasi").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>
</body>

</html>
