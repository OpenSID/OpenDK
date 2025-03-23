<?php

namespace App\Services;

use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Http;

class KeluargaService
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

    public function keluarga(int $id)
    {
        // Default parameter
        $defaultParams = [
            'filter[id]' => $id,
        ];

        // Gabungkan parameter default dengan filter dinamis
        $params = array_merge($defaultParams);

        // Panggil API dan ambil data
        $data = $this->apiRequest('/api/v1/keluarga', $params);

        $result = collect($data)
        ->map(function ($item) {
            return (object)[
                'id' => $item['id'],
                'no_kk' => $item['attributes']['no_kk'] ?? null,
                'nik_kepala' => $item['attributes']['nik_kepala'] ?? null,
                'nama_kk' => $item['attributes']['nama_kk'] ?? null,
                'tgl_daftar' => $item['attributes']['tgl_daftar'] ?? null,
                'tgl_cetak_kk' => $item['attributes']['tgl_cetak_kk'] ?? null,
                'desa' => $item['attributes']['desa'] ?? null,
                'alamat' => $item['attributes']['alamat_plus_dusun'] ?? null,
                'rt' => $item['attributes']['rt'] ?? null,
                'rw' => $item['attributes']['rw'] ?? null,
                'anggota' => $item['attributes']['anggota'] ?? null
            ];
        });

        return $result[0];
    }


}
