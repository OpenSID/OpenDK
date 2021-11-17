<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'Laravel') }} | Login</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ is_logo($profil->file_logo) }}"/>
    <link rel="stylesheet" href="{{ asset("/bower_components/bootstrap/dist/css/bootstrap.min.css") }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset("/bower_components/font-awesome/css/font-awesome.min.css") }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset("/bower_components/Ionicons/css/ionicons.min.css") }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css") }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/iCheck/square/blue.css") }}">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        html {
            height: auto;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box" style="background-color: white;">
    <!-- /.login-logo -->
    <div class="login-box-body">
        <div class="login-logo" style="padding-top: 10px;">
            <a href="{{ route('beranda') }}">
                <img class="" src="{{ is_logo($profil->file_logo) }}" style="max-width:80px;white-space:normal" alt=""  width="70px">
                <h3>PEMERINTAH {{ strtoupper($profil->nama_kabupaten) }}<br/><b>{{ strtoupper($sebutan_wilayah.' '.$profil->nama_kecamatan) }}</b></h3>
            </a>
        </div>
        <hr/>
        
        @include('partials.flash_message')
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="Email">
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                <input id="password" type="password" class="form-control" name="password" required placeholder="Password">
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input class="iCheck" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <hr/>
        <div class="text-center">
            <small>Hak Cipta &copy; 2017 <a href="http://www.kompak.or.id">KOMPAK</a>, 2018-{{ date('Y') }} <a href="http://opendesa.id">OpenDesa</a>
            <br/>
            <b><a href="https://github.com/openSID/openDK" target="_blank">OpenDK</a></b> {{ config('app.version') }}
            </small>
        </div>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<!-- jQuery 3 -->
<script src="{{ asset ("/bower_components/jquery/dist/jquery.min.js") }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset ("/bower_components/bootstrap/dist/js/bootstrap.min.js") }}"></script>
<!-- iCheck -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}"></script>
<script>
    $(document).ready(function() {
        $('.iCheck').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        window.setTimeout(function() {
            $("#notifikasi").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 5000);
    });
</script>
</body>
</html>