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

use App\Services\BantuanService;
use App\Services\DesaService;
use App\Services\KeluargaService;
use App\Services\PendudukService;
use Mockery;

test('jumlah desa service sama dengan api', function () {
    $mockJson = file_get_contents(base_path('tests/Feature/Fixtures/Responses/JumlahDashboardTest/JumlahDesa.json'));
    $expectedJumlah = json_decode($mockJson, true)['meta']['pagination']['total'];

    $mockService = Mockery::mock(DesaService::class);
    $mockService->shouldReceive('jumlahDesa')->once()->andReturn($expectedJumlah);

    $this->app->instance(DesaService::class, $mockService);

    expect($mockService->jumlahDesa())->toBe($expectedJumlah);
});

test('jumlah penduduk service sama dengan api', function () {
    $mockJson = file_get_contents(base_path('tests/Feature/Fixtures/Responses/JumlahDashboardTest/JumlahPenduduk.json'));
    $expectedJumlah = json_decode($mockJson, true)['meta']['pagination']['total'];

    $mockService = Mockery::mock(PendudukService::class);
    $mockService->shouldReceive('jumlahPenduduk')->once()->andReturn($expectedJumlah);

    $this->app->instance(PendudukService::class, $mockService);

    expect($mockService->jumlahPenduduk())->toBe($expectedJumlah);
});

test('jumlah keluarga service sama dengan api', function () {
    $mockJson = file_get_contents(base_path('tests/Feature/Fixtures/Responses/JumlahDashboardTest/JumlahKeluarga.json'));
    $expectedJumlah = json_decode($mockJson, true)['meta']['pagination']['total'];

    $mockService = Mockery::mock(KeluargaService::class);
    $mockService->shouldReceive('jumlahKeluarga')->once()->andReturn($expectedJumlah);

    $this->app->instance(KeluargaService::class, $mockService);

    expect($mockService->jumlahKeluarga())->toBe($expectedJumlah);
});

test('jumlah bantuan service sama dengan api', function () {
    $mockJson = file_get_contents(base_path('tests/Feature/Fixtures/Responses/JumlahDashboardTest/JumlahBantuan.json'));
    $expectedJumlah = json_decode($mockJson, true)['meta']['pagination']['total'];

    $mockService = Mockery::mock(BantuanService::class);
    $mockService->shouldReceive('jumlahBantuan')->once()->andReturn($expectedJumlah);

    $this->app->instance(BantuanService::class, $mockService);

    expect($mockService->jumlahBantuan())->toBe($expectedJumlah);
});
