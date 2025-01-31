<?php

namespace App\Services;

use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Http;

class LaporanPendudukService
{
    protected $settings;

    public function __construct()
    {
        $this->settings = SettingAplikasi::pluck('value', 'key');
    }

    /**
     * General API Call Method
     */
    protected function apiRequest(string $endpoint, array $params = [])
    {
        // Base URL
        $baseUrl = $this->settings['api_server_database_gabungan'];

        // Buat permintaan API dengan Header dan Parameter
        $response = Http::withHeaders([
            'Accept' => 'application/ld+json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->settings['api_key_database_gabungan'],
        ])->get($baseUrl . $endpoint, $params);

        // Return JSON hasil
        return $response->json('data') ?? [];
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
    public function exportLaporanPenduduk(array $filters = [])
    {
        // Default parameter
        $defaultParams = [
            'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
        ];

        // Gabungkan parameter default dengan filter dinamis
        $params = array_merge($defaultParams, $filters);

        // Panggil API dan ambil data
        $data = $this->apiRequest('/api/v1/opendk/laporan-penduduk', $params);

        // Format ulang data jika diperlukan
        return collect($data)->map(function ($item) {
            return [
                'id' => $item['id'],
                'nama_desa' => $item['attributes']['config']['nama_desa'] ?? '',
                'judul' => $item['attributes']['judul'] ?? '',
                'bulan' => $item['attributes']['bulan'] ?? '',
                'tahun' => $item['attributes']['tahun'] ?? '',
                'tanggal_lapor' => $item['attributes']['tanggal_lapor'] ?? '',
            ];
        });
    }
}