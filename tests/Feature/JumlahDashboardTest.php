<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use App\Models\SettingAplikasi;
use App\Services\BantuanService;
use App\Services\DesaService;
use App\Services\KeluargaService;
use App\Services\PendudukService;
use Tests\TestCase;

class JumlahDashboardTest extends TestCase
{

    protected $settings;
    protected $baseUrl;
    protected $defaultHeaders;

    public function setUp(): void
    {
        parent::setUp();

        $this->settings = SettingAplikasi::whereIn('key', ['api_server_database_gabungan', 'api_key_database_gabungan', 'sinkronisasi_database_gabungan'])->pluck('value', 'key');        

        $this->baseUrl = $this->settings['api_server_database_gabungan'];

        $this->defaultHeaders = [
            'Accept' => 'application/ld+json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->settings['api_key_database_gabungan'],
        ];
    }

    public function test_jumlah_desa_service_sama_dengan_api()
    {
        $filters = [
            'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
        ];

        // 1. Panggil dari service
        $jumlahDariService = (new DesaService())->jumlahDesa($filters);

        // 2. Panggil langsung endpoint API
        $response = Http::withHeaders($this->defaultHeaders)
        ->get($this->baseUrl.'/api/v1/desa?' . http_build_query([
            ...$filters,
            'page[size]' => 10,
            'page[number]' => 1,
        ]));

        $this->assertTrue($response->successful());
        $this->assertEquals(200, $response->status());
        $json = $response->json();

        $jumlahDariApi = $json['meta']['pagination']['total'] ?? null;

        $this->assertEquals($jumlahDariService, $jumlahDariApi, 'Jumlah desa dari service dan API tidak sama');
    }

    public function test_jumlah_penduduk_service_sama_dengan_api()
    {
        $filters = [
            'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
        ];

        // 1. Panggil dari service
        $jumlahDariService = (new PendudukService())->jumlahPenduduk($filters);

        // 2. Panggil langsung endpoint API
        $response = Http::withHeaders($this->defaultHeaders)
        ->get($this->baseUrl.'/api/v1/opendk/sync-penduduk-opendk?' . http_build_query([
            ...$filters,
            'page[size]' => 10,
            'page[number]' => 1,
        ]));

        $this->assertTrue($response->successful());
        $this->assertEquals(200, $response->status());
        $json = $response->json();

        $jumlahDariApi = $json['meta']['pagination']['total'] ?? null;

        $this->assertEquals($jumlahDariService, $jumlahDariApi, 'Jumlah penduduk dari service dan API tidak sama');
    }

    public function test_jumlah_keluarga_service_sama_dengan_api()
    {
        $filters = [
            'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
            'filter[status]' => 1
        ];

        // 1. Panggil dari service
        $jumlahDariService = (new KeluargaService())->jumlahKeluarga($filters);

        // 2. Panggil langsung endpoint API
        $response = Http::withHeaders($this->defaultHeaders)
        ->get($this->baseUrl.'/api/v1/keluarga?' . http_build_query([
            ...$filters,
            'page[size]' => 10,
            'page[number]' => 1,
        ]));

        $this->assertTrue($response->successful());
        $this->assertEquals(200, $response->status());
        $json = $response->json();

        $jumlahDariApi = $json['meta']['pagination']['total'] ?? null;

        $this->assertEquals($jumlahDariService, $jumlahDariApi, 'Jumlah keluarga dari service dan API tidak sama');
    }

    public function test_jumlah_bantuan_service_sama_dengan_api()
    {
        $filters = [
            'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
        ];

        // 1. Panggil dari service
        $jumlahDariService = (new BantuanService())->jumlahBantuan($filters);

        // 2. Panggil langsung endpoint API
        $response = Http::withHeaders($this->defaultHeaders)
        ->get($this->baseUrl.'/api/v1/bantuan?' . http_build_query([
            ...$filters,
            'page[size]' => 10,
            'page[number]' => 1,
        ]));

        $this->assertTrue($response->successful());
        $this->assertEquals(200, $response->status());
        $json = $response->json();

        $jumlahDariApi = $json['meta']['pagination']['total'] ?? null;

        $this->assertEquals($jumlahDariService, $jumlahDariApi, 'Jumlah bantuan dari service dan API tidak sama');
    }

}
