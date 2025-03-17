<?php

namespace App\Services;

use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Http;

class BaseApiService
{
    protected $settings;
    protected $useDatabaseGabungan;

    public function __construct()
    {
        $this->settings = SettingAplikasi::whereIn('key', ['api_server_database_gabungan', 'api_key_database_gabungan', 'sinkronisasi_database_gabungan'])->pluck('value', 'key');        
        $this->useDatabaseGabungan = $this->useDatabaseGabungan();
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

    protected function useDatabaseGabungan()
    {
        return ($this->settings['sinkronisasi_database_gabungan'] ?? null) === '1';
    }
}
