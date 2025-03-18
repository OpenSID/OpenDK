<?php

namespace App\Services;

use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Http;

class GeografisService
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
        return (object) $response->json('data') ?? [];
    }

    /**
     * Get Unique Desa
     */
    public function wilayah_desa()
    {
        $data = $this->apiRequest('/api/v1/opendk/desa/'.str_replace('.', '', config('profil.kecamatan_id')));

        return collect($data)
        ->map(function ($item) {
            return (object)[
                'id' => $item['id'],
                'kode_desa' => $item['attributes']['kode_desa'] ?? null, // Ambil kode desa
                'nama_desa' => $item['attributes']['nama_desa'] ?? null, // Ambil nama desa
                'path' => $item['attributes']['path'] ?? null, // Ambil nama desa
            ];
        });
    }

    /**
     * Get Unique Desa
     */
    public function profile()
    {
        return $this->apiRequest('/api/v1/opendk/profile/'.str_replace('.', '', config('profil.kecamatan_id')));
    }
    
}
