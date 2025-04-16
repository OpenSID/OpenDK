<?php

namespace App\Services;

use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class BaseApiService
{
    protected $settings;
    protected $useDatabaseGabungan;
    protected $header;
    protected $baseUrl;
    protected $kodeKecamatan;
    public function __construct()
    {
        $this->settings = SettingAplikasi::whereIn('key', ['api_server_database_gabungan', 'api_key_database_gabungan', 'sinkronisasi_database_gabungan'])->pluck('value', 'key');        
        $this->useDatabaseGabungan = $this->useDatabaseGabungan();
        $this->header = [
            'Accept' => 'application/ld+json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->settings['api_key_database_gabungan'],
        ];
        $this->baseUrl = $this->settings['api_server_database_gabungan'];
        $this->kodeKecamatan = str_replace('.','',config('profil.kecamatan_id'));        
    }

    /**
     * General API Call Method
     */
    protected function apiRequest(string $endpoint, array $params = [])
    {        
        try {
            // Buat permintaan API dengan Header dan Parameter
            $response = Http::withHeaders($this->header)->get($this->baseUrl . $endpoint, $params);
            session()->forget('error_api');
            // Return JSON hasil            
            return $response->json('data') ?? [];
        } catch (\Exception $e) {
            session()->flash('error_api', 'Gagal mendapatkan data'. $e->getMessage());
            \Log::error('Failed get data in '.__FILE__.' function '.__METHOD__.' '. $e->getMessage());
        }
        return [];
    }    

    protected function useDatabaseGabungan()
    {
        return ($this->settings['sinkronisasi_database_gabungan'] ?? null) === '1';
    }
}
