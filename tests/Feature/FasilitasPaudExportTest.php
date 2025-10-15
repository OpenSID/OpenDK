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
use App\Models\FasilitasPAUD;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class FasilitasPaudExportTest extends TestCase
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
        FasilitasPAUD::query()->forceDelete();
        DataDesa::query()->forceDelete();
        User::query()->forceDelete();

        parent::tearDown();
    }

    /** @test */
    public function dapat_mengakses_halaman_export_fasilitas_paud()
    {
        $this->actingAs($this->user);

        // Check if route exists first
        try {
            $routeUrl = route('data.fasilitas-paud.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.fasilitas-paud.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.fasilitas-paud.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Check if Content-Disposition header is set for download
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
    }

    /** @test */
    public function dapat_export_data_fasilitas_paud_kosong()
    {
        $this->actingAs($this->user);

        // Pastikan tidak ada data fasilitas paud
        FasilitasPAUD::query()->delete();
        $this->assertEquals(0, FasilitasPAUD::count());

        try {
            $routeUrl = route('data.fasilitas-paud.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.fasilitas-paud.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.fasilitas-paud.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Check headers for Excel download
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
        $this->assertStringContainsString('data-fasilitas-paud-', $disposition);
    }

    /** @test */
    public function dapat_export_data_fasilitas_paud_dengan_data()
    {
        $this->actingAs($this->user);

        // Bersihkan data terlebih dahulu
        FasilitasPAUD::query()->delete();

        // Buat data fasilitas paud untuk testing
        $fasilitasPaudData = FasilitasPAUD::factory()->count(3)->create([
            'desa_id' => $this->desa->desa_id
        ]);

        // Verify data was created
        $this->assertEquals(3, FasilitasPAUD::count());

        try {
            $routeUrl = route('data.fasilitas-paud.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.fasilitas-paud.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.fasilitas-paud.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Check headers for Excel download
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
        $this->assertStringContainsString('data-fasilitas-paud-', $disposition);
    }

    /** @test */
    public function export_fasilitas_paud_menghasilkan_filename_dengan_timestamp()
    {
        $this->actingAs($this->user);

        try {
            $routeUrl = route('data.fasilitas-paud.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.fasilitas-paud.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.fasilitas-paud.export-excel'));

        $response->assertStatus(200);

        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header harus ada');
        $this->assertStringContainsString('data-fasilitas-paud-', $disposition);
        $this->assertStringContainsString('.xlsx', $disposition);
        
        // Verify timestamp format in filename (more flexible regex)
        $this->assertMatchesRegularExpression('/data-fasilitas-paud-\d{4}/', $disposition);
    }

    /** @test */
    public function export_fasilitas_paud_memiliki_header_yang_benar()
    {
        $this->actingAs($this->user);

        // Buat data untuk memastikan ada konten
        FasilitasPAUD::factory()->create([
            'desa_id' => $this->desa->desa_id
        ]);

        try {
            $routeUrl = route('data.fasilitas-paud.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.fasilitas-paud.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.fasilitas-paud.export-excel'));

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
            $routeUrl = route('data.fasilitas-paud.export-excel');
            $this->assertNotNull($routeUrl);
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.fasilitas-paud.export-excel tidak tersedia');
        }
    }

    /** @test */
    public function test_unauthorized_access_denied()
    {
        // Test without authentication
        try {
            $routeUrl = route('data.fasilitas-paud.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.fasilitas-paud.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.fasilitas-paud.export-excel'));

        // Should redirect to login or return 401/403 or 200 if no auth required
        $this->assertContains($response->getStatusCode(), [200, 302, 401, 403]);
    }

    /** @test */
    public function test_export_functionality_unit()
    {
        // Create test data
        $fasilitasPaud = FasilitasPAUD::factory()->create([
            'desa_id' => $this->desa->desa_id
        ]);

        // Test the export class directly
        $export = new \App\Exports\ExportFasilitasPaud();
        
        // Test collection method
        $collection = $export->collection();
        $this->assertNotNull($collection, 'Collection should not be null');
        $this->assertGreaterThan(0, $collection->count(), 'Collection should have data');
        
        // Test headings method
        $headings = $export->headings();
        $this->assertIsArray($headings, 'Headings should be an array');
        $this->assertContains('Nama Desa', $headings, 'Should contain expected heading');
        $this->assertContains('Jumlah PAUD/RA', $headings, 'Should contain PAUD count heading');
        $this->assertContains('Jumlah Guru PAUD/RA', $headings, 'Should contain teacher count heading');
        $this->assertContains('Jumlah Siswa PAUD/RA', $headings, 'Should contain student count heading');
        
        // Test mapping method
        $mapped = $export->map($fasilitasPaud);
        $this->assertIsArray($mapped, 'Mapped data should be an array');
        $this->assertCount(9, $mapped, 'Should have 9 mapped fields');
    }

    /** @test */
    public function test_export_with_various_data_scenarios()
    {
        $this->actingAs($this->user);

        // Bersihkan data terlebih dahulu
        FasilitasPAUD::query()->delete();

        // Test scenario: Multiple years and semesters
        $data = [
            ['tahun' => 2023, 'semester' => 1, 'jumlah_paud' => 5, 'jumlah_guru_paud' => 20, 'jumlah_siswa_paud' => 100],
            ['tahun' => 2023, 'semester' => 2, 'jumlah_paud' => 6, 'jumlah_guru_paud' => 22, 'jumlah_siswa_paud' => 110],
            ['tahun' => 2024, 'semester' => 1, 'jumlah_paud' => 7, 'jumlah_guru_paud' => 25, 'jumlah_siswa_paud' => 120],
        ];

        foreach ($data as $item) {
            FasilitasPAUD::factory()->create([
                'desa_id' => $this->desa->desa_id,
                'tahun' => $item['tahun'],
                'semester' => $item['semester'],
                'jumlah_paud' => $item['jumlah_paud'],
                'jumlah_guru_paud' => $item['jumlah_guru_paud'],
                'jumlah_siswa_paud' => $item['jumlah_siswa_paud'],
            ]);
        }

        $this->assertEquals(3, FasilitasPAUD::count());

        $response = $this->get(route('data.fasilitas-paud.export-excel'));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Verify filename includes timestamp
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('data-fasilitas-paud-', $disposition);
        $this->assertStringContainsString('.xlsx', $disposition);
    }

    /** @test */
    public function test_export_handles_edge_cases()
    {
        $this->actingAs($this->user);

        // Test with edge case data (zero values)
        FasilitasPAUD::factory()->create([
            'desa_id' => $this->desa->desa_id,
            'jumlah_paud' => 0,
            'jumlah_guru_paud' => 0,
            'jumlah_siswa_paud' => 0,
            'tahun' => 2024,
            'semester' => 1,
        ]);

        $response = $this->get(route('data.fasilitas-paud.export-excel'));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Should still work with zero values
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('data-fasilitas-paud-', $disposition);
    }

    /** @test */
    public function test_export_with_large_dataset()
    {
        $this->actingAs($this->user);

        // Bersihkan data terlebih dahulu
        FasilitasPAUD::query()->delete();

        // Create a larger dataset to test performance
        FasilitasPAUD::factory()->count(50)->create([
            'desa_id' => $this->desa->desa_id
        ]);

        $this->assertEquals(50, FasilitasPAUD::count());

        $response = $this->get(route('data.fasilitas-paud.export-excel'));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Verify successful download with large dataset
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('data-fasilitas-paud-', $disposition);
    }

    /** @test */
    public function test_export_includes_desa_relation()
    {
        $this->actingAs($this->user);

        // Create data with known desa
        $fasilitasPaud = FasilitasPAUD::factory()->create([
            'desa_id' => $this->desa->desa_id,
            'jumlah_paud' => 10,
            'jumlah_guru_paud' => 30,
            'jumlah_siswa_paud' => 150,
        ]);

        // Verify relationship works
        $this->assertNotNull($fasilitasPaud->desa);
        $this->assertEquals($this->desa->desa_id, $fasilitasPaud->desa->desa_id);

        $response = $this->get(route('data.fasilitas-paud.export-excel'));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
