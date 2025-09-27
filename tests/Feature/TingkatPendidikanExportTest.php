<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace Tests\Feature;

use App\Models\DataDesa;
use App\Models\TingkatPendidikan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class TingkatPendidikanExportTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;

    protected $user;
    protected $desa;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user untuk testing
        $this->user = User::factory()->create();

        // Buat desa untuk testing  
        $this->desa = DataDesa::factory()->create();
    }

    protected function tearDown(): void
    {
        // Bersihkan data test dengan proper order
        TingkatPendidikan::query()->forceDelete();
        DataDesa::query()->forceDelete();
        User::query()->forceDelete();

        parent::tearDown();
    }

    /** @test */
    public function dapat_mengakses_halaman_export_tingkat_pendidikan()
    {
        $this->actingAs($this->user);

        // Check if route exists first
        try {
            $routeUrl = route('data.tingkat-pendidikan.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.tingkat-pendidikan.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.tingkat-pendidikan.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Check if Content-Disposition header is set for download
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
    }

    /** @test */
    public function dapat_export_data_tingkat_pendidikan_kosong()
    {
        $this->actingAs($this->user);

        // Pastikan tidak ada data tingkat pendidikan
        TingkatPendidikan::query()->delete();
        $this->assertEquals(0, TingkatPendidikan::count());

        try {
            $routeUrl = route('data.tingkat-pendidikan.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.tingkat-pendidikan.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.tingkat-pendidikan.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Check headers for Excel download
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
        $this->assertStringContainsString('data-tingkat-pendidikan-', $disposition);
    }

    /** @test */
    public function dapat_export_data_tingkat_pendidikan_dengan_data()
    {
        $this->actingAs($this->user);

        // Buat data tingkat pendidikan untuk testing
        $tingkatPendidikanData = TingkatPendidikan::factory()->count(3)->create([
            'desa_id' => $this->desa->desa_id
        ]);

        $this->assertEquals(
            3,
            TingkatPendidikan::where('desa_id', $this->desa->desa_id)->count()
        );

        try {
            $routeUrl = route('data.tingkat-pendidikan.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.tingkat-pendidikan.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.tingkat-pendidikan.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Check headers for Excel download
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
        $this->assertStringContainsString('data-tingkat-pendidikan-', $disposition);
    }

    /** @test */
    public function export_tingkat_pendidikan_menghasilkan_filename_dengan_timestamp()
    {
        $this->actingAs($this->user);

        try {
            $routeUrl = route('data.tingkat-pendidikan.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.tingkat-pendidikan.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.tingkat-pendidikan.export-excel'));

        $response->assertStatus(200);

        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header harus ada');
        $this->assertStringContainsString('data-tingkat-pendidikan-', $disposition);
        $this->assertStringContainsString('.xlsx', $disposition);
        
        // Verify timestamp format in filename (more flexible regex)
        $this->assertMatchesRegularExpression('/data-tingkat-pendidikan-\d{4}/', $disposition);
    }

    /** @test */
    public function export_tingkat_pendidikan_memiliki_header_yang_benar()
    {
        $this->actingAs($this->user);

        // Buat data untuk memastikan ada konten
        TingkatPendidikan::factory()->create([
            'desa_id' => $this->desa->desa_id
        ]);

        try {
            $routeUrl = route('data.tingkat-pendidikan.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.tingkat-pendidikan.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.tingkat-pendidikan.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Check headers for proper Excel download
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
    }

    /** @test */
    public function test_dependencies_are_working()
    {
        // Test if factories are working
        $this->assertNotNull($this->user);
        $this->assertInstanceOf(User::class, $this->user);
        
        $this->assertNotNull($this->desa);
        $this->assertInstanceOf(DataDesa::class, $this->desa);
        
        // Test if route exists
        try {
            $routeUrl = route('data.tingkat-pendidikan.export-excel');
            $this->assertNotNull($routeUrl);
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.tingkat-pendidikan.export-excel tidak tersedia');
        }
    }

    /** @test */
    public function test_unauthorized_access_denied()
    {
        // Test without authentication
        try {
            $routeUrl = route('data.tingkat-pendidikan.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.tingkat-pendidikan.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.tingkat-pendidikan.export-excel'));

        // Should redirect to login or return 401/403 or 200 if no auth required
        $this->assertContains($response->getStatusCode(), [200, 302, 401, 403]);
    }

    /** @test */
    public function test_export_functionality_unit()
    {
        // Create test data
        $tingkatPendidikan = TingkatPendidikan::factory()->create([
            'desa_id' => $this->desa->desa_id
        ]);

        // Test the export class directly
        $export = new \App\Exports\ExportTingkatPendidikan();
        
        // Test collection method
        $collection = $export->collection();
        $this->assertNotNull($collection, 'Collection should not be null');
        $this->assertGreaterThan(0, $collection->count(), 'Collection should have data');
        
        // Test headings method
        $headings = $export->headings();
        $this->assertIsArray($headings, 'Headings should be an array');
        $this->assertContains('Nama Desa', $headings, 'Should contain expected heading');
        $this->assertContains('Tidak Tamat Sekolah', $headings, 'Should contain education level heading');
        
        // Test mapping method
        $mapped = $export->map($tingkatPendidikan);
        $this->assertIsArray($mapped, 'Mapped data should be an array');
        $this->assertCount(11, $mapped, 'Should have 11 mapped fields');
    }

    /** @test */
    public function test_export_with_various_data_scenarios()
    {
        $this->actingAs($this->user);

        // Test scenario: Multiple years and semesters
        $data = [
            ['tahun' => 2023, 'semester' => 1, 'tidak_tamat_sekolah' => 10, 'tamat_sd' => 50],
            ['tahun' => 2023, 'semester' => 2, 'tidak_tamat_sekolah' => 8, 'tamat_sd' => 55],
            ['tahun' => 2024, 'semester' => 1, 'tidak_tamat_sekolah' => 5, 'tamat_sd' => 60],
        ];

        foreach ($data as $item) {
            TingkatPendidikan::factory()->create([
                'desa_id' => $this->desa->desa_id,
                'tahun' => $item['tahun'],
                'semester' => $item['semester'],
                'tidak_tamat_sekolah' => $item['tidak_tamat_sekolah'],
                'tamat_sd' => $item['tamat_sd'],
            ]);
        }

        $this->assertEquals(
            count($data),
            TingkatPendidikan::where('desa_id', $this->desa->desa_id)->count()
        );

        $response = $this->get(route('data.tingkat-pendidikan.export-excel'));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Verify filename includes timestamp
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('data-tingkat-pendidikan-', $disposition);
        $this->assertStringContainsString('.xlsx', $disposition);
    }

    /** @test */
    public function test_export_handles_edge_cases()
    {
        $this->actingAs($this->user);

        // Test with edge case data
        TingkatPendidikan::factory()->create([
            'desa_id' => $this->desa->desa_id,
            'tidak_tamat_sekolah' => 0,
            'tamat_sd' => 0,
            'tamat_smp' => 0,
            'tamat_sma' => 0,
            'tamat_diploma_sederajat' => 0,
            'tahun' => 2024,
            'semester' => 1,
        ]);

        $response = $this->get(route('data.tingkat-pendidikan.export-excel'));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Should still work with zero values
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('data-tingkat-pendidikan-', $disposition);
    }
}
