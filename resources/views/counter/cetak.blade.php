<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Statistik Pengunjung</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        /* ukuran font untuk tampilan data */
        body div.data {
            font-size: 14px;
        }

        .container {
            width: 100%;
            margin: 20px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
        }

        .header p {
            font-size: 16px;
        }

        /* new line */
        div.header .info span {
            display: block;
        }

        hr {
            display: block;
            height: 1px;
            border: 0;
            border-top: 1px solid #ccc;
            margin: 1em 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img class="user-image" src="{{ is_logo($profil->file_logo) }}" alt="OpenDK" width="60px" style="">
            <h2 class="info">
                <span>{{ strtoupper('Pemerintah Kab. ' . $profil->nama_kabupaten) }}</span>
                <span>{{ strtoupper('Kecamatan ' . $profil->nama_kecamatan) }}</span>
            </h2>
            <p style="font-style: italic; font-size: 12px">Tanggal Cetak:
                {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        </div>

        <hr />

        <div class="data">
            <h3>LAPORAN DATA STATISTIK PENGUNJUNG WEBSITE SETIAP TAHUN</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 1%">No</th>
                        <th>Tanggal</th>
                        <th>Page Views</th>
                        <th>Unique visitors</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($yearlyVisitors as $index => $stat)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::createFromFormat('Y', $stat->date)->translatedFormat('Y') }}
                            </td>
                            <td>{{ $stat->page_views }}</td>
                            <td>{{ $stat->unique_visitors }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h3><?= strtoupper('Halaman Populer') ?></h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 1%">No</th>
                        <th>URL</th>
                        <th>Page Views</th>
                        <th>Unique Visitors</th>
                        <th>Bounce Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($top_pages_visited as $index => $page)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><code>{{ $page->url }}</code></td>
                            <td>{{ $page->total_views }}</td>
                            <td>{{ $page->unique_visitors }}</td>
                            <td>
                                @if (isset($page->bounces))
                                    {{ round(($page->bounces / $page->total_views) * 100, 1) }}%
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
