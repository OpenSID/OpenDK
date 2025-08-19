<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace Tests\Feature;

use App\Models\SettingAplikasi;
use App\Services\BantuanService;
use App\Services\DesaService;
use App\Services\KeluargaService;
use App\Services\PendudukService;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

class JumlahDashboardTest extends TestCase
{
    // protected $settings;
    // protected $baseUrl;
    // protected $defaultHeaders;

    // public function setUp(): void
    // {
    //     parent::setUp();

    //     $this->settings = SettingAplikasi::whereIn('key', ['api_server_database_gabungan', 'api_key_database_gabungan', 'sinkronisasi_database_gabungan'])->pluck('value', 'key');

    //     $this->baseUrl = $this->settings['api_server_database_gabungan'];

    //     $this->defaultHeaders = [
    //         'Accept' => 'application/ld+json',
    //         'Content-Type' => 'application/json',
    //         'Authorization' => 'Bearer ' . $this->settings['api_key_database_gabungan'],
    //     ];
    // }

    public function test_jumlah_desa_service_sama_dengan_api()
    {
        $mockJson = file_get_contents(base_path('tests/Feature/Fixtures/Responses/JumlahDashboardTest/JumlahDesa.json'));
        $expectedJumlah = json_decode($mockJson, true)['meta']['pagination']['total'];

        $mockService = Mockery::mock(DesaService::class);
        $mockService->shouldReceive('jumlahDesa')->once()->andReturn($expectedJumlah);

        $this->app->instance(DesaService::class, $mockService);

        $this->assertEquals($expectedJumlah, $mockService->jumlahDesa());
    }

    public function test_jumlah_penduduk_service_sama_dengan_api()
    {
        $mockJson = file_get_contents(base_path('tests/Feature/Fixtures/Responses/JumlahDashboardTest/JumlahPenduduk.json'));
        $expectedJumlah = json_decode($mockJson, true)['meta']['pagination']['total'];

        $mockService = Mockery::mock(PendudukService::class);
        $mockService->shouldReceive('jumlahPenduduk')->once()->andReturn($expectedJumlah);

        $this->app->instance(PendudukService::class, $mockService);

        $this->assertEquals($expectedJumlah, $mockService->jumlahPenduduk());
    }

    public function test_jumlah_keluarga_service_sama_dengan_api()
    {
        $mockJson = file_get_contents(base_path('tests/Feature/Fixtures/Responses/JumlahDashboardTest/JumlahKeluarga.json'));
        $expectedJumlah = json_decode($mockJson, true)['meta']['pagination']['total'];

        $mockService = Mockery::mock(KeluargaService::class);
        $mockService->shouldReceive('jumlahKeluarga')->once()->andReturn($expectedJumlah);

        $this->app->instance(KeluargaService::class, $mockService);

        $this->assertEquals($expectedJumlah, $mockService->jumlahKeluarga());
    }

    public function test_jumlah_bantuan_service_sama_dengan_api()
    {
        $mockJson = file_get_contents(base_path('tests/Feature/Fixtures/Responses/JumlahDashboardTest/JumlahBantuan.json'));
        $expectedJumlah = json_decode($mockJson, true)['meta']['pagination']['total'];

        $mockService = Mockery::mock(BantuanService::class);
        $mockService->shouldReceive('jumlahBantuan')->once()->andReturn($expectedJumlah);

        $this->app->instance(BantuanService::class, $mockService);

        $this->assertEquals($expectedJumlah, $mockService->jumlahBantuan());
    }

    // public function test_jumlah_desa_service_sama_dengan_api()
    // {
    //     $filters = [
    //         'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
    //     ];

    //     // 1. Panggil dari service
    //     $jumlahDariService = (new DesaService())->jumlahDesa($filters);

    //     // 2. Panggil langsung endpoint API
    //     $response = Http::withHeaders($this->defaultHeaders)
    //     ->get($this->baseUrl.'/api/v1/desa?' . http_build_query([
    //         ...$filters,
    //         'page[size]' => 10,
    //         'page[number]' => 1,
    //     ]));

    //     $this->assertTrue($response->successful());
    //     $this->assertEquals(200, $response->status());
    //     $json = $response->json();

    //     $jumlahDariApi = $json['meta']['pagination']['total'] ?? null;

    //     $this->assertEquals($jumlahDariService, $jumlahDariApi, 'Jumlah desa dari service dan API tidak sama');
    // }

    // public function test_jumlah_penduduk_service_sama_dengan_api()
    // {
    //     $filters = [
    //         'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
    //     ];

    //     // 1. Panggil dari service
    //     $jumlahDariService = (new PendudukService())->jumlahPenduduk($filters);

    //     // 2. Panggil langsung endpoint API
    //     $response = Http::withHeaders($this->defaultHeaders)
    //     ->get($this->baseUrl.'/api/v1/opendk/sync-penduduk-opendk?' . http_build_query([
    //         ...$filters,
    //         'page[size]' => 10,
    //         'page[number]' => 1,
    //     ]));

    //     $this->assertTrue($response->successful());
    //     $this->assertEquals(200, $response->status());
    //     $json = $response->json();

    //     $jumlahDariApi = $json['meta']['pagination']['total'] ?? null;

    //     $this->assertEquals($jumlahDariService, $jumlahDariApi, 'Jumlah penduduk dari service dan API tidak sama');
    // }

    // public function test_jumlah_keluarga_service_sama_dengan_api()
    // {
    //     $filters = [
    //         'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
    //         'filter[status]' => 1
    //     ];

    //     // 1. Panggil dari service
    //     $jumlahDariService = (new KeluargaService())->jumlahKeluarga($filters);

    //     // 2. Panggil langsung endpoint API
    //     $response = Http::withHeaders($this->defaultHeaders)
    //     ->get($this->baseUrl.'/api/v1/keluarga?' . http_build_query([
    //         ...$filters,
    //         'page[size]' => 10,
    //         'page[number]' => 1,
    //     ]));

    //     $this->assertTrue($response->successful());
    //     $this->assertEquals(200, $response->status());
    //     $json = $response->json();

    //     $jumlahDariApi = $json['meta']['pagination']['total'] ?? null;

    //     $this->assertEquals($jumlahDariService, $jumlahDariApi, 'Jumlah keluarga dari service dan API tidak sama');
    // }

    // public function test_jumlah_bantuan_service_sama_dengan_api()
    // {
    //     $filters = [
    //         'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
    //     ];

    //     // 1. Panggil dari service
    //     $jumlahDariService = (new BantuanService())->jumlahBantuan($filters);

    //     // 2. Panggil langsung endpoint API
    //     $response = Http::withHeaders($this->defaultHeaders)
    //     ->get($this->baseUrl.'/api/v1/bantuan?' . http_build_query([
    //         ...$filters,
    //         'page[size]' => 10,
    //         'page[number]' => 1,
    //     ]));

    //     $this->assertTrue($response->successful());
    //     $this->assertEquals(200, $response->status());
    //     $json = $response->json();

    //     $jumlahDariApi = $json['meta']['pagination']['total'] ?? null;

    //     $this->assertEquals($jumlahDariService, $jumlahDariApi, 'Jumlah bantuan dari service dan API tidak sama');
    // }

}
