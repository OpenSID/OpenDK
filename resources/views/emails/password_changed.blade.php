<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Berhasil Diubah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .header h1 {
            color: #333;
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 0;
        }
        .info {
            color: #555;
            line-height: 1.6;
            margin: 15px 0;
            font-size: 14px;
        }
        .success-box {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 14px;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #007bff;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="icon">🔐</div>
            <h1>{{ config('app.name') }}</h1>
            <p><strong>Password Berhasil Diubah</strong></p>
        </div>

        <div class="content">
            <p class="info">
                Halo <strong>{{ $name }}</strong>,
            </p>

            <div class="success-box">
                Kami menginformasikan bahwa password akun Anda telah <strong>berhasil diperbarui</strong>.
            </div>

            <p class="info">
                Jika Anda melakukan perubahan password ini, maka tidak ada tindakan lanjutan yang perlu dilakukan.
            </p>

            <div class="warning">
                <strong>Perhatian:</strong><br>
                Jika Anda <strong>tidak merasa melakukan perubahan password</strong>, segera hubungi administrator
                atau lakukan pengamanan akun untuk mencegah akses tidak sah.
            </div>

            <p style="text-align: center;">
                <a href="{{ url('/login') }}" class="button">Masuk ke Aplikasi</a>
            </p>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem {{ config('app.name') }}.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Seluruh hak cipta dilindungi.</p>
        </div>
    </div>
</body>
</html>
