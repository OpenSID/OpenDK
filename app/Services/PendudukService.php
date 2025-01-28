<?php

namespace App\Services;

use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Http;

class PendudukService
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
            'kode_kecamatan' => str_replace('.', '', config('profil.kecamatan_id')),
        ];

        // Gabungkan parameter default dengan filter dinamis
        $params = array_merge($defaultParams, $filters);

        // Panggil API dan ambil data
        $data = $this->apiRequest('/api/v1/opendk/sync-penduduk-opendk', $params);

        return collect($data)
        ->map(function ($item) {
            return (object)[
                'kode_desa' => $item['attributes']['config']['kode_desa'] ?? null, // Ambil kode desa
                'nama_desa' => $item['attributes']['config']['nama_desa'] ?? null, // Ambil nama desa
            ];
        })
        ->filter(function ($item) {
            // Hapus item yang memiliki kode desa atau nama desa null
            return $item->kode_desa && $item->nama_desa;
        })
        ->unique('kode_desa') // Hapus duplikat berdasarkan kode desa
        ->values() // Reset indeks koleksi
        ->all(); // Konversi ke array of objects

    }

    /**
     * Export Data Penduduk
     */
    public function exportPenduduk(array $filters = [])
    {
        // Default parameter
        $defaultParams = [
            'kode_kecamatan' => str_replace('.', '', config('profil.kecamatan_id')),
        ];

        // Gabungkan parameter default dengan filter dinamis
        $params = array_merge($defaultParams, $filters);

        // Panggil API dan ambil data
        $data = $this->apiRequest('/api/v1/opendk/sync-penduduk-opendk', $params);

        // Format ulang data jika diperlukan
        return collect($data)->map(function ($item) {
            return [
                'ID' => $item['id'],
                'nama' => $item['attributes']['nama'] ?? '',
                'nik' => '`' . $item['attributes']['nik'],
                'no_kk' => $item['attributes']['keluarga']['no_kk'] ?? '',
                'nama_desa' => $item['attributes']['config']['nama_desa'] ?? '',
                'alamat' => $item['attributes']['alamat_sekarang'] ?? '',
                'pendidikan' => $item['attributes']['pendidikan_k_k']['nama'] ?? '',
                'tanggal_lahir' => $item['attributes']['tanggallahir'] ?? '',
                'pekerjaan' => $item['attributes']['pekerjaan']['nama'] ?? '',
                'status_kawin' => $item['attributes']['status_kawin']['nama'] ?? '',
            ];
        });
    }
}
