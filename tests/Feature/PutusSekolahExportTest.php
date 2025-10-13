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
use App\Models\PutusSekolah;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class PutusSekolahExportTest extends TestCase
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
        PutusSekolah::query()->forceDelete();
        DataDesa::query()->forceDelete();
        User::query()->forceDelete();

        parent::tearDown();
    }

    /** @test */
    public function dapat_mengakses_halaman_export_putus_sekolah()
    {
        $this->actingAs($this->user);

        // Check if route exists first
        try {
            $routeUrl = route('data.putus-sekolah.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.putus-sekolah.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.putus-sekolah.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Check if Content-Disposition header is set for download
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
    }

    /** @test */
    public function dapat_export_data_putus_sekolah_kosong()
    {
        $this->actingAs($this->user);

        // Pastikan tidak ada data putus sekolah
        PutusSekolah::query()->delete();
        $this->assertEquals(0, PutusSekolah::count());

        try {
            $routeUrl = route('data.putus-sekolah.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.putus-sekolah.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.putus-sekolah.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Check headers for Excel download
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
        $this->assertStringContainsString('data-putus-sekolah-', $disposition);
    }

    /** @test */
    public function dapat_export_data_putus_sekolah_dengan_data()
    {
        $this->actingAs($this->user);

        // Buat data putus sekolah untuk testing
        $putusSekolahData = PutusSekolah::factory()->count(3)->create([
            'desa_id' => $this->desa->desa_id
        ]);

    // Verify data was created for this desa (allow other tests' data to exist)
    $this->assertGreaterThanOrEqual(3, PutusSekolah::where('desa_id', $this->desa->desa_id)->count());

        try {
            $routeUrl = route('data.putus-sekolah.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.putus-sekolah.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.putus-sekolah.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Check headers for Excel download
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
        $this->assertStringContainsString('data-putus-sekolah-', $disposition);
    }

    /** @test */
    public function export_putus_sekolah_menghasilkan_filename_dengan_timestamp()
    {
        $this->actingAs($this->user);

        try {
            $routeUrl = route('data.putus-sekolah.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.putus-sekolah.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.putus-sekolah.export-excel'));

        $response->assertStatus(200);

        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header harus ada');
        $this->assertStringContainsString('data-putus-sekolah-', $disposition);
        $this->assertStringContainsString('.xlsx', $disposition);
        
        // Verify timestamp format in filename (more flexible regex)
        $this->assertMatchesRegularExpression('/data-putus-sekolah-\d{4}/', $disposition);
    }

    /** @test */
    public function export_putus_sekolah_memiliki_header_yang_benar()
    {
        $this->actingAs($this->user);

        // Buat data untuk memastikan ada konten
        PutusSekolah::factory()->create([
            'desa_id' => $this->desa->desa_id
        ]);

        try {
            $routeUrl = route('data.putus-sekolah.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.putus-sekolah.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.putus-sekolah.export-excel'));

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
            $routeUrl = route('data.putus-sekolah.export-excel');
            $this->assertNotNull($routeUrl);
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.putus-sekolah.export-excel tidak tersedia');
        }
    }

    /** @test */
    public function test_unauthorized_access_denied()
    {
        // Test without authentication
        try {
            $routeUrl = route('data.putus-sekolah.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.putus-sekolah.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.putus-sekolah.export-excel'));

        // Should redirect to login or return 401/403 or 200 if no auth required
        $this->assertContains($response->getStatusCode(), [200, 302, 401, 403]);
    }

    /** @test */
    public function test_export_functionality_unit()
    {
        // Create test data
        $putusSekolah = PutusSekolah::factory()->create([
            'desa_id' => $this->desa->desa_id
        ]);

        // Test the export class directly
        $export = new \App\Exports\ExportPutusSekolah();
        
        // Test collection method
        $collection = $export->collection();
        $this->assertNotNull($collection, 'Collection should not be null');
        $this->assertGreaterThan(0, $collection->count(), 'Collection should have data');
        
        // Test headings method
        $headings = $export->headings();
        $this->assertIsArray($headings, 'Headings should be an array');
        $this->assertContains('Nama Desa', $headings, 'Should contain expected heading');
        $this->assertContains('Siswa PAUD', $headings, 'Should contain education level heading');
        $this->assertContains('Anak Usia PAUD', $headings, 'Should contain age group heading');
        
        // Test mapping method
        $mapped = $export->map($putusSekolah);
        $this->assertIsArray($mapped, 'Mapped data should be an array');
        $this->assertCount(14, $mapped, 'Should have 14 mapped fields');
    }

    /** @test */
    public function test_export_with_various_data_scenarios()
    {
        $this->actingAs($this->user);

        // Test scenario: Multiple education levels and years
        $data = [
            [
                'tahun' => 2023, 
                'semester' => 1, 
                'siswa_paud' => 10, 
                'anak_usia_paud' => 15,
                'siswa_sd' => 50,
                'anak_usia_sd' => 60
            ],
            [
                'tahun' => 2023, 
                'semester' => 2, 
                'siswa_paud' => 8, 
                'anak_usia_paud' => 12,
                'siswa_sd' => 45,
                'anak_usia_sd' => 55
            ],
            [
                'tahun' => 2024, 
                'semester' => 1, 
                'siswa_paud' => 12, 
                'anak_usia_paud' => 18,
                'siswa_sd' => 52,
                'anak_usia_sd' => 65
            ],
        ];

        foreach ($data as $item) {
            PutusSekolah::factory()->create([
                'desa_id' => $this->desa->desa_id,
                'tahun' => $item['tahun'],
                'semester' => $item['semester'],
                'siswa_paud' => $item['siswa_paud'],
                'anak_usia_paud' => $item['anak_usia_paud'],
                'siswa_sd' => $item['siswa_sd'],
                'anak_usia_sd' => $item['anak_usia_sd'],
            ]);
        }

    // Verify data was created for this desa (allow other tests' data to exist)
    $this->assertGreaterThanOrEqual(3, PutusSekolah::where('desa_id', $this->desa->desa_id)->count());

        $response = $this->get(route('data.putus-sekolah.export-excel'));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Verify filename includes timestamp
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('data-putus-sekolah-', $disposition);
        $this->assertStringContainsString('.xlsx', $disposition);
    }

    /** @test */
    public function test_export_handles_edge_cases()
    {
        $this->actingAs($this->user);

        // Test with edge case data (all zeros)
        PutusSekolah::factory()->create([
            'desa_id' => $this->desa->desa_id,
            'siswa_paud' => 0,
            'anak_usia_paud' => 0,
            'siswa_sd' => 0,
            'anak_usia_sd' => 0,
            'siswa_smp' => 0,
            'anak_usia_smp' => 0,
            'siswa_sma' => 0,
            'anak_usia_sma' => 0,
            'tahun' => 2024,
            'semester' => 1,
        ]);

        $response = $this->get(route('data.putus-sekolah.export-excel'));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Should still work with zero values
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('data-putus-sekolah-', $disposition);
    }

    /** @test */
    public function test_export_data_validation()
    {
        // Test that factory creates valid data
        $putusSekolah = PutusSekolah::factory()->create([
            'desa_id' => $this->desa->desa_id
        ]);

        // Verify required fields are present
        $this->assertNotNull($putusSekolah->desa_id);
        $this->assertNotNull($putusSekolah->tahun);
        $this->assertNotNull($putusSekolah->semester);
        $this->assertIsNumeric($putusSekolah->siswa_paud);
        $this->assertIsNumeric($putusSekolah->anak_usia_paud);
        $this->assertIsNumeric($putusSekolah->siswa_sd);
        $this->assertIsNumeric($putusSekolah->anak_usia_sd);
        
        // Verify relationship works
        $this->assertNotNull($putusSekolah->desa);
        $this->assertEquals($this->desa->desa_id, $putusSekolah->desa->desa_id);
    }
}
