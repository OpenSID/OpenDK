<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>2FA Otentikasi Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>

    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tbody>
                        <tr>

                            <td colspan="3" height="20px" bgcolor="#1c459d">
                            </td>

                        </tr>
                        <tr>
                            <td colspan="3">
                                <p>
                                    Kode token 2FA untuk login adalah <br>
                                <h2>{{ $token }}</h2> <br>
                                Kode akan kadaluarsa dalam {{ config('twofactor-auth.expiry', 2) }} menit dari email ini dikirimkan.<br>
                                Jika anda tidak mencoba login, abaikan email ini<br>
                                <a href="{{ route('auth.token') }}">Klik disini</a> untuk verifikasi.
                                </p>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="3">
                                <br> <br>Terima kasih dan salam hangat.
                            </td>

                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
