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
    <link rel="icon" type="image/png" href="{{ is_logo($profil->file_logo) }}" />
    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/iCheck/square/blue.css') }}">
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
                    <img class="" src="{{ is_logo($profil->file_logo) }}" style="max-width:80px;white-space:normal" alt="" width="70px">
                    <h3>PEMERINTAH KAB. {{ strtoupper($profil->nama_kabupaten) }}<br /><b>{{ strtoupper($sebutan_wilayah . ' ' . $profil->nama_kecamatan) }}</b></h3>
                </a>
            </div>
            <hr />

            @include('partials.flash_message')
            <form class="form-horizontal" role="form" method="POST" action="{{ route('auth.token') }}">
                @csrf

                <div class="form-group row">
                    <label for="token" class="col-md-4 col-form-label text-md-right">Kode Token</label>

                    <div class="col-md-6">
                        <input
                            id="token"
                            type="text"
                            autocomplete="one-time-code"
                            class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}"
                            name="token"
                            value="{{ old('token') }}"
                            required
                            autofocus
                        >

                        @if ($errors->has('token'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('token') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Verifikasi</button>
                    </div>
                </div>
            </form>

            <hr />
            <div class="text-center">
                <small>Hak Cipta &copy; 2017 <a href="http://www.kompak.or.id">KOMPAK</a>, 2018-{{ date('Y') }} <a href="http://opendesa.id">OpenDesa</a>
                    <br />
                    <b><a href="https://github.com/openSID/openDK" target="_blank">OpenDK</a></b> {{ config('app.version') }}
                </small>
            </div>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->
    <!-- jQuery 3 -->
    <script src="{{ asset('/bower_components/jquery/dist/jquery.min.js') }}"></script>

</body>

</html>
