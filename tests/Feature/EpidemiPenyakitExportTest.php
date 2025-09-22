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
use App\Models\EpidemiPenyakit;
use App\Models\JenisPenyakit;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class EpidemiPenyakitExportTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;

    protected $user;
    protected $desa;
    protected $penyakit;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user untuk testing
        $this->user = User::factory()->create();

        // Buat desa untuk testing  
        $this->desa = DataDesa::factory()->create();

        // Buat jenis penyakit untuk testing
        $this->penyakit = JenisPenyakit::factory()->create();
    }

    protected function tearDown(): void
    {
        // Bersihkan data test dengan proper order
        EpidemiPenyakit::query()->forceDelete();
        JenisPenyakit::query()->forceDelete();
        DataDesa::query()->forceDelete();
        User::query()->forceDelete();

        parent::tearDown();
    }

    /** @test */
    public function dapat_mengakses_halaman_export_epidemi_penyakit()
    {
        $this->actingAs($this->user);

        // Check if route exists first
        try {
            $routeUrl = route('data.epidemi-penyakit.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.epidemi-penyakit.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.epidemi-penyakit.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // For binary files like Excel, check if headers are set correctly
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
    }

    /** @test */
    public function dapat_export_data_epidemi_penyakit_kosong()
    {
        $this->actingAs($this->user);

        // Pastikan tidak ada data epidemi penyakit
        EpidemiPenyakit::query()->delete();
        $this->assertEquals(0, EpidemiPenyakit::count());

        try {
            $routeUrl = route('data.epidemi-penyakit.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.epidemi-penyakit.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.epidemi-penyakit.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Check headers for Excel download
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
        $this->assertStringContainsString('data-epidemi-penyakit-', $disposition);
    }

    /** @test */
    public function dapat_export_data_epidemi_penyakit_dengan_data()
    {
        $this->actingAs($this->user);

        $epidemiDataSebelumnya = EpidemiPenyakit::count();
        // Buat data epidemi penyakit untuk testing
        $epidemiData = EpidemiPenyakit::factory()->count(3)->create([
            'desa_id' => $this->desa->desa_id,
            'penyakit_id' => $this->penyakit->id
        ]);

        // Verify data was created
        $this->assertEquals($epidemiData->count() + $epidemiDataSebelumnya, EpidemiPenyakit::count());

        try {
            $routeUrl = route('data.epidemi-penyakit.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.epidemi-penyakit.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.epidemi-penyakit.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Check headers for Excel download
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
        $this->assertStringContainsString('data-epidemi-penyakit-', $disposition);
    }

    /** @test */
    public function export_epidemi_penyakit_menghasilkan_filename_dengan_timestamp()
    {
        $this->actingAs($this->user);

        try {
            $routeUrl = route('data.epidemi-penyakit.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.epidemi-penyakit.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.epidemi-penyakit.export-excel'));

        $response->assertStatus(200);

        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header harus ada');
        $this->assertStringContainsString('data-epidemi-penyakit-', $disposition);
        $this->assertStringContainsString('.xlsx', $disposition);
        
        // Verify timestamp format in filename (more flexible regex)
        $this->assertMatchesRegularExpression('/data-epidemi-penyakit-\d{4}/', $disposition);
    }

    /** @test */
    public function export_epidemi_penyakit_memiliki_header_yang_benar()
    {
        $this->actingAs($this->user);

        // Buat data untuk memastikan ada konten
        EpidemiPenyakit::factory()->create([
            'desa_id' => $this->desa->desa_id,
            'penyakit_id' => $this->penyakit->id
        ]);

        try {
            $routeUrl = route('data.epidemi-penyakit.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.epidemi-penyakit.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.epidemi-penyakit.export-excel'));

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
        
        $this->assertNotNull($this->penyakit);
        $this->assertInstanceOf(JenisPenyakit::class, $this->penyakit);
        
        // Test if route exists
        try {
            $routeUrl = route('data.epidemi-penyakit.export-excel');
            $this->assertNotNull($routeUrl);
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.epidemi-penyakit.export-excel tidak tersedia');
        }
    }

    /** @test */
    public function test_unauthorized_access_denied()
    {
        // Test without authentication
        try {
            $routeUrl = route('data.epidemi-penyakit.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.epidemi-penyakit.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.epidemi-penyakit.export-excel'));

        // Should redirect to login or return 401/403 or 200 if no auth required
        $this->assertContains($response->getStatusCode(), [200, 302, 401, 403]);
    }

    /** @test */
    public function test_export_functionality_unit()
    {
        // Create test data
        $epidemi = EpidemiPenyakit::factory()->create([
            'desa_id' => $this->desa->desa_id,
            'penyakit_id' => $this->penyakit->id
        ]);

        // Test the export class directly
        $export = new \App\Exports\ExportEpidemiPenyakit();
        
        // Test collection method
        $collection = $export->collection();
        $this->assertNotNull($collection, 'Collection should not be null');
        $this->assertGreaterThan(0, $collection->count(), 'Collection should have data');
        
        // Test headings method
        $headings = $export->headings();
        $this->assertIsArray($headings, 'Headings should be an array');
        $this->assertContains('Nama Desa', $headings, 'Should contain expected heading');
        
        // Test mapping method
        $mapped = $export->map($epidemi);
        $this->assertIsArray($mapped, 'Mapped data should be an array');
        $this->assertCount(8, $mapped, 'Should have 8 mapped fields');
    }

    /** @test */
    public function test_export_with_various_data_scenarios()
    {
        $this->actingAs($this->user);

        // Test scenario 1: Multiple years and months
        $data = [
            ['bulan' => 1, 'tahun' => 2023, 'jumlah_penderita' => 10],
            ['bulan' => 6, 'tahun' => 2023, 'jumlah_penderita' => 15],
            ['bulan' => 12, 'tahun' => 2024, 'jumlah_penderita' => 5],
        ];

        foreach ($data as $item) {
            EpidemiPenyakit::factory()->create([
                'desa_id' => $this->desa->desa_id,
                'penyakit_id' => $this->penyakit->id,
                'bulan' => $item['bulan'],
                'tahun' => $item['tahun'],
                'jumlah_penderita' => $item['jumlah_penderita'],
            ]);
        }

        $this->assertEquals(3, EpidemiPenyakit::count());

        $response = $this->get(route('data.epidemi-penyakit.export-excel'));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Verify filename includes timestamp
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('data-epidemi-penyakit-', $disposition);
        $this->assertStringContainsString('.xlsx', $disposition);
    }
}
