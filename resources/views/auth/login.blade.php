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
    <link rel="icon" type="image/png" href="{{ asset("/favicon.png")}}"/>

    <link rel="stylesheet" href="{{ asset("/bower_components/bootstrap/dist/css/bootstrap.min.css") }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset("/bower_components/font-awesome/css/font-awesome.min.css") }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset("/bower_components/Ionicons/css/ionicons.min.css") }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css") }}">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        html{
            height: auto;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <img class="" src="@if(isset($profil_wilayah->file_logo)) {{  asset($profil_wilayah->file_logo) }} @else {{   asset('img/logo.png')}}@endif" style="max-width:80px;white-space:normal" alt=""  width="70px">
        <h3 href="{{$app->make('url')->to('/')}}">PEMERINTAH {{ strtoupper($nama_wilayah_kab) }}<br><b>{{ strtoupper($sebutan_wilayah.' '.$nama_wilayah) }}</b></h3>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        @include( 'flash::message' )
        <form method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}  
            <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required
                    autofocus placeholder="Email">
                @if ($errors->has('email'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                @endif
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                <input id="password" type="password" class="form-control" name="password" required fplaceholder="Password">
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
                            <input class="iCheck" type="checkbox"    name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
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
    </div>
    <!-- /.login-box-body -->
</div>
<div class="login-box-footer" style="align-items: center; text-align: center">
    <small>Hak Cipta &copy; 2017 <a href="http://www.kompak.or.id">KOMPAK</a>, 2018-{{ date('Y') }} <a href="http://opendesa.id">OpenDesa</a> <i class="fa fa-github"></i>  <a href="https://github.com/openSID/openDK" target="_blank"> openDK </a> .</small>
</div>
<!-- /.login-box -->
<!-- jQuery 3 -->
<script src="{{ asset ("/bower_components/jquery/dist/jquery.min.js") }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset ("/bower_components/bootstrap/dist/js/bootstrap.min.js") }}"></script>
<!-- iCheck -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}"></script>
<script>
    $(function () {
        $('.iCheck').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>