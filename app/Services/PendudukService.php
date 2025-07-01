<?php

namespace App\Services;

use App\Models\Penduduk;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PendudukService extends BaseApiService
{    
    /**
     * Get Unique Desa
     */
    public function jumlahPenduduk(array $filters = [])
    {
        // Default parameter
        $defaultParams = [
            'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
        ];

        // Gabungkan parameter default dengan filter dinamis
        $params = array_merge($defaultParams, $filters);

        // Panggil API dan ambil data
        $data = $this->apiRequestLengkap('/api/v1/opendk/sync-penduduk-opendk', $params);
        if (!isset($data['meta']['pagination']['total'])) {
            return 0; // Jika tidak ada total, kembalikan 0
        }
        // Jika total tersedia, kembalikan nilainya
        return $data['meta']['pagination']['total'];
    }

    /**
     * Get Unique Desa
     */
    public function desa(array $filters = [])
    {
        // Default parameter
        $defaultParams = [
            'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
        ];

        // Gabungkan parameter default dengan filter dinamis
        $params = array_merge($defaultParams, $filters);

        // Panggil API dan ambil data
        $data = $this->apiRequest('/api/v1/desa', $params);

        return collect($data)
        ->map(function ($item) {
            return (object)[
                'id' => $item['id'],
                'kode_desa' => $item['attributes']['kode_desa'] ?? null, // Ambil kode desa
                'nama_desa' => $item['attributes']['nama_desa'] ?? null, // Ambil nama desa
            ];
        });
    }

    /**
     * Export Data Penduduk
     */
    public function exportPenduduk($size, $number, $search)
    {
        // Default parameter
        $defaultParams = [
            'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
            'page[size]' => $size,
            'page[number]' => $number,
            'filter[search]' => $search,
        ];

        // Gabungkan parameter default dengan filter dinamis
        $params = $defaultParams;

        // Panggil API dan ambil data
        $data = $this->apiRequest('/api/v1/opendk/sync-penduduk-opendk', $params);

        // Format ulang data jika diperlukan
        return collect($data)->map(function ($item) {
            return [
                'ID' => $item['id'],
                'nama' => $item['attributes']['nama'] ?? '',
                'nik' => '`' . $item['attributes']['nik'],
                'no_kk' => '`' .$item['attributes']['keluarga']['no_kk'] ?? '',
                'nama_desa' => $item['attributes']['config']['nama_desa'] ?? '',
                'alamat' => $item['attributes']['alamat_sekarang'] ?? '',
                'pendidikan' => $item['attributes']['pendidikan_k_k']['nama'] ?? '',
                'tanggal_lahir' => $item['attributes']['tanggallahir'] ?? '',
                'umur' => $item['attributes']['umur'] ?? '',
                'pekerjaan' => $item['attributes']['pekerjaan']['nama'] ?? '',
                'status_kawin' => $item['attributes']['status_kawin']['nama'] ?? '',
            ];
        });
    }

    /**
     * Export Data Penduduk
     */
    public function cekPendudukNikTanggalLahir($nik, $tgl_lhr = null)
    {
        try {
            $baseUrl = $this->settings['api_server_database_gabungan'];
        
            $response = Http::post($baseUrl . '/api/v1/opendk/penduduk-nik-tanggalahir', [
                'kode_kecamatan' => str_replace('.', '', config('profil.kecamatan_id')),
                'nik' => $nik,
                'tanggallahir' => $tgl_lhr,
            ]);
        
            if ($response->successful() && $response->json('data')) {
                return new Penduduk($response->json('data'));
            }
        
            return null;
        } catch (\Exception $e) {
            Log::error('Unexpected Error', ['message' => $e->getMessage()]);
            return null;
        }

    }
}
