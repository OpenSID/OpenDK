<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $page_title ?? config('app.name', 'Laravel') }} | {{ $browser_title }}</title>
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/AdminLTE.min.css') }}">

    <style>
        .verifikasi-page {
            background: #d2d6de;
        }

        .verifikasi-box {
            width: 40%;
            margin: 7% auto;
        }

        .verifikasi-box-body {
            background: #fff;
            padding: 20px;
            border-top: 0;
            color: #666;
        }

        .row {
            height: 100%;
            display: table-row;
        }

        .row .no-float {
            display: table-cell;
            float: none;
        }

        td {
            padding: 8px 0;
        }
    </style>
</head>

<body class="hold-transition verifikasi-page">
    <div class="verifikasi-box">
        <div class="verifikasi-box-body">
            <center>
                <img src="{{ is_logo($profil->file_logo) }}" style="max-width:80px;white-space:normal" alt="logo kecamatan" width="70px">
                <h4>
                    <b>
                        {{ strtoupper('Pemerintah Kabupaten ' . $profil->nama_kabupaten) }}<br>
                        {{ strtoupper('Kecamatan ' . $profil->nama_kecamatan) }}<br>
                    </b>
                </h4>
                <hr style="border-bottom: 2px solid #000000; height:0px;">
                <table>
                    <tbody>
                        <tr>
                            <td colspan="3"><u><b>Menyatakan Bahwa :</b></u></td>
                        </tr>
                        <tr>
                            <td width="30%">Nomor Surat</td>
                            <td width="1%">:</td>
                            <td>{{ $surat->nomor }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Surat</td>
                            <td>:</td>
                            <td>{{ format_date($surat->tanggal) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><?= 'a/n ' . $surat->penduduk->nama ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"><u><b>Ditandatangani oleh :</b></u></td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>{{ $surat->pengurus->nama }}</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{ $surat->pengurus->jabatan->nama }}</td>
                        </tr>
                    </tbody>
                </table>
                <br />
                <div class="callout callout-success row">
                    <div class="col-md-8 no-float">
                        <h5><b>Telah ditandatangani secara elektronik</b></h5>
                    </div>
                    <div class="col-md-4 no-float">
                        <img src="{{ asset('img/bsre.png') }}" alt="logo bsre" width="120px" height="auto">
                    </div>
                </div>
            </center>
        </div>
    </div>
</body>

</html>
