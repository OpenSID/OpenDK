<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $page_title ?? 'Verifikasi Surat' }} | {{ optional($profil)->nama_kecamatan ?? config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/AdminLTE.min.css') }}">

    <style>
        body {
            background: #d2d6de;
        }

        .verifikasi-wrapper {
            max-width: 720px;
            margin: 40px auto 20px;
        }

        .verifikasi-kop {
            background: #fff;
            padding: 20px;
            text-align: center;
            border-bottom: 3px solid #3c8dbc;
        }

        .verifikasi-kop img {
            max-width: 70px;
            margin-bottom: 8px;
        }

        .verifikasi-footer {
            text-align: center;
            color: #7a7a7a;
            font-size: 12px;
            padding: 10px 0 30px;
        }
    </style>
</head>

<body>
    <div class="verifikasi-wrapper">
        <div class="verifikasi-kop">
            <img src="{{ is_logo(optional($profil)->file_logo) }}" alt="logo kecamatan">
            <h4>
                <b>
                    {{ strtoupper('Pemerintah Kabupaten ' . (optional($profil)->nama_kabupaten ?? '-')) }}<br>
                    {{ strtoupper('Kecamatan ' . (optional($profil)->nama_kecamatan ?? '-')) }}<br>
                </b>
            </h4>
        </div>

        @yield('content')

        <div class="verifikasi-footer">
            Layanan verifikasi surat digital {{ config('app.name') }}
        </div>
    </div>
</body>

</html>
