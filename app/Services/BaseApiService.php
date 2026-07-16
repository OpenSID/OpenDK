<?php

namespace App\Services;

use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class BaseApiService
{
    protected $settings;
    protected $useDatabaseGabungan;
    protected $header;
    protected $baseUrl;
    protected $kodeKecamatan;
    private $fullResponse = false;
    public function __construct()
    {
        $this->settings = SettingAplikasi::whereIn('key', ['api_server_database_gabungan', 'api_key_database_gabungan', 'sinkronisasi_database_gabungan'])->pluck('value', 'key');        
        
        // Allow mock via config for testing
        if (config()->has('api_server_database_gabungan')) {
            $this->settings['api_server_database_gabungan'] = config('api_server_database_gabungan');
        }
        if (config()->has('api_key_database_gabungan')) {
            $this->settings['api_key_database_gabungan'] = config('api_key_database_gabungan');
        }
        if (config()->has('sinkronisasi_database_gabungan')) {
            $this->settings['sinkronisasi_database_gabungan'] = config('sinkronisasi_database_gabungan');
        }

        $this->useDatabaseGabungan = $this->useDatabaseGabungan();
        $this->header = [
            'Accept' => 'application/ld+json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . ($this->settings['api_key_database_gabungan'] ?? ''),
        ];
        $this->baseUrl = !empty($this->settings['api_server_database_gabungan']) ? $this->settings['api_server_database_gabungan'] : 'http://localhost';
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
            $jsonResponse = $response->json();
            
            if($this->isFullResponse()) {
                // Jika full response, kembalikan seluruh response
                return $jsonResponse;
            }
            
            // Return JSON hasil, cek apakah ada key 'data', jika tidak ada kembalikan seluruh response
            if (isset($jsonResponse['data'])) {
                return $jsonResponse['data'];
            }
            
            return $jsonResponse;
        } catch (\Exception $e) {
            if (app()->environment('testing')) {
                throw $e;
            }
            session()->flash('error_api', 'Gagal mendapatkan data'. $e->getMessage());
            Log::error('Failed get data in '.__FILE__.' function '.__METHOD__.' '. $e->getMessage());
        }
        return [];
    }    

    /**
     * General API Call Method
     */
    protected function apiRequestLengkap(string $endpoint, array $params = [])
    {
        $this->setFullResponse(true);
        return $this->apiRequest($endpoint, $params);
    }

    protected function useDatabaseGabungan()
    {
        return ($this->settings['sinkronisasi_database_gabungan'] ?? null) === '1';
    }

    private function setFullResponse(bool $fullResponse)
    {
        $this->fullResponse = $fullResponse;
    }

    private function isFullResponse()
    {
        return $this->fullResponse;
    }
}
