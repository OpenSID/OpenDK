<?php

namespace App\Traits;

use App\Models\DataDesa;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Support\OutboundHttp;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Pengiriman data statistik ke server pantau OpenDK.
 * Dipakai oleh App\Http\Controllers\Controller dan App\Http\Middleware\GlobalShareMiddleware
 * agar logika tracking hanya ada di satu tempat.
 *
 * Kelas pemakai harus menyediakan properti: $profil, $umum, $sebutan_wilayah, $nama_camat.
 */
trait KirimTrackPantau
{
    protected function kirimTrack()
    {
        if (config('app.demo') == true) { // jika posisi demo, matikan tracking
            return;
        }

        if (cache()->get('track') != null && cache()->get('track') == date('Y m d')) {
            return;
        }

        $host_pantau = config('app.host_pantau');
        $data = [
            'url' => url('/'),
            'versi' => config('app.version'),
            'jml_desa' => DataDesa::count(),
            'desa' => json_encode(DataDesa::select(['desa_id', 'nama', 'sebutan_desa', 'path', 'website'])->get()),
            'jumlahdesa_sinkronisasi' => DataDesa::count(),
            'jumlah_penduduk' => Penduduk::where('status_dasar', 1)->count(),
            'jumlah_keluarga' => Keluarga::count(),
            'peta_wilayah' => $this->umum->path ?? '[[[[]]]]',
            'batas_wilayah' => json_encode([
                'bts_wil_utara' => $this->umum->bts_wil_utara,
                'bts_wil_timur' => $this->umum->bts_wil_timur,
                'bts_wil_selatan' => $this->umum->bts_wil_selatan,
                'bts_wil_barat' => $this->umum->bts_wil_barat,
            ]),
            'sebutan_wilayah' => $this->sebutan_wilayah,
            'alamat' => $this->profil->alamat,
            'jumlah_bantuan' => Program::count(),
            'kode_kecamatan' => $this->profil->kecamatan_id,
            'kode_kabupaten' => $this->profil->kabupaten_id,
            'kode_provinsi' => $this->profil->provinsi_id,
            'nama_kecamatan' => $this->profil->nama_kecamatan,
            'nama_kabupaten' => $this->profil->nama_kabupaten,
            'nama_provinsi' => $this->profil->nama_provinsi,
            'nama_camat' => $this->nama_camat,
            'lat' => $this->umum->lat,
            'lng' => $this->umum->lng,
        ];

        try {
            $response = OutboundHttp::client()->withHeaders([
                'token' => config('app.token_pantau'),
            ])->post($host_pantau.'track/opendk?token='.config('app.token_pantau'), $data);
            cache()->put('track', date('Y m d'), 60 * 60 * 24);

            return;
        } catch (Exception $e) {
            Log::notice($e);

            return;
        }
    }
}
