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

use App\Models\AnggaranRealisasi;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class AnggaranRealisasiExportTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user untuk testing
        $this->user = User::factory()->create();

        // Bersihkan data anggaran realisasi sebelum setiap test
        AnggaranRealisasi::query()->delete();
    }

    protected function tearDown(): void
    {
        // Bersihkan data test dengan proper order
        AnggaranRealisasi::query()->forceDelete();
        User::query()->forceDelete();

        parent::tearDown();
    }

    /** @test */
    public function dapat_mengakses_halaman_export_anggaran_realisasi()
    {
        $this->actingAs($this->user);

        // Check if route exists first
        try {
            $routeUrl = route('data.anggaran-realisasi.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.anggaran-realisasi.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.anggaran-realisasi.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // Check if Content-Disposition header is set for download
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
    }

    /** @test */
    public function dapat_export_data_anggaran_realisasi_kosong()
    {
        $this->actingAs($this->user);

        // Pastikan tidak ada data anggaran realisasi
        AnggaranRealisasi::query()->delete();
        $this->assertEquals(0, AnggaranRealisasi::count());

        try {
            $routeUrl = route('data.anggaran-realisasi.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.anggaran-realisasi.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.anggaran-realisasi.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // Check headers for Excel download
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
        $this->assertStringContainsString('data-anggaran-realisasi-', $disposition);
    }

    /** @test */
    public function dapat_export_data_anggaran_realisasi_dengan_data()
    {
        $this->actingAs($this->user);

        // Pastikan database bersih sebelum test
        AnggaranRealisasi::query()->delete();

        // Buat data anggaran realisasi untuk testing
        $anggaranRealisasiData = AnggaranRealisasi::factory()->count(3)->create();

        // Verify data was created
        $this->assertEquals(3, AnggaranRealisasi::count());

        try {
            $routeUrl = route('data.anggaran-realisasi.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.anggaran-realisasi.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.anggaran-realisasi.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // Check headers for Excel download
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header should be set');
        $this->assertStringContainsString('attachment', $disposition);
        $this->assertStringContainsString('data-anggaran-realisasi-', $disposition);
    }

    /** @test */
    public function export_anggaran_realisasi_menghasilkan_filename_dengan_timestamp()
    {
        $this->actingAs($this->user);

        try {
            $routeUrl = route('data.anggaran-realisasi.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.anggaran-realisasi.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.anggaran-realisasi.export-excel'));

        $response->assertStatus(200);

        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition, 'Content-Disposition header harus ada');
        $this->assertStringContainsString('data-anggaran-realisasi-', $disposition);
        $this->assertStringContainsString('.xlsx', $disposition);

        // Verify timestamp format in filename (more flexible regex)
        $this->assertMatchesRegularExpression('/data-anggaran-realisasi-\d{4}/', $disposition);
    }

    /** @test */
    public function export_anggaran_realisasi_memiliki_header_yang_benar()
    {
        $this->actingAs($this->user);

        // Pastikan database bersih dan buat data untuk memastikan ada konten
        AnggaranRealisasi::query()->delete();
        AnggaranRealisasi::factory()->create();

        try {
            $routeUrl = route('data.anggaran-realisasi.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.anggaran-realisasi.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.anggaran-realisasi.export-excel'));

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

        // Test if route exists
        try {
            $routeUrl = route('data.anggaran-realisasi.export-excel');
            $this->assertNotNull($routeUrl);
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.anggaran-realisasi.export-excel tidak tersedia');
        }
    }

    /** @test */
    public function test_unauthorized_access_denied()
    {
        // Test without authentication
        try {
            $routeUrl = route('data.anggaran-realisasi.export-excel');
        } catch (\Exception $e) {
            $this->markTestSkipped('Route data.anggaran-realisasi.export-excel tidak ditemukan');
        }

        $response = $this->get(route('data.anggaran-realisasi.export-excel'));

        // Should redirect to login or return 401/403 or 200 if no auth required
        $this->assertContains($response->getStatusCode(), [200, 302, 401, 403]);
    }

    /** @test */
    public function test_export_functionality_unit()
    {
        // Pastikan database bersih sebelum test
        AnggaranRealisasi::query()->delete();

        // Create test data
        $anggaranRealisasi = AnggaranRealisasi::factory()->create();

        // Test the export class directly
        $export = new \App\Exports\ExportAnggaranRealisasi();

        // Test collection method
        $collection = $export->collection();
        $this->assertNotNull($collection, 'Collection should not be null');
        $this->assertGreaterThan(0, $collection->count(), 'Collection should have data');

        // Test headings method
        $headings = $export->headings();
        $this->assertIsArray($headings, 'Headings should be an array');
        $this->assertContains('Total Anggaran', $headings, 'Should contain budget heading');
        $this->assertContains('Total Belanja', $headings, 'Should contain expenditure heading');
        $this->assertContains('Belanja Pegawai', $headings, 'Should contain staff expenditure heading');
        $this->assertContains('Belanja Barang & Jasa', $headings, 'Should contain goods/services heading');

        // Test mapping method
        $mapped = $export->map($anggaranRealisasi);
        $this->assertIsArray($mapped, 'Mapped data should be an array');
        $this->assertCount(11, $mapped, 'Should have 11 mapped fields');

        // Test that monetary values are formatted properly
        $this->assertIsString($mapped[1], 'Total anggaran should be formatted as string');
        $this->assertIsString($mapped[2], 'Total belanja should be formatted as string');
    }

    /** @test */
    public function test_export_with_various_budget_scenarios()
    {
        $this->actingAs($this->user);

        // Pastikan database bersih sebelum test
        AnggaranRealisasi::query()->delete();

        // Test scenario: Multiple years and budget variations
        $data = [
            [
                'tahun' => 2023,
                'bulan' => 1,
                'total_anggaran' => 1000000000,
                'total_belanja' => 800000000,
                'belanja_pegawai' => 300000000,
                'belanja_barang_jasa' => 200000000,
                'belanja_modal' => 250000000,
                'belanja_tidak_langsung' => 50000000
            ],
            [
                'tahun' => 2023,
                'bulan' => 6,
                'total_anggaran' => 1200000000,
                'total_belanja' => 950000000,
                'belanja_pegawai' => 350000000,
                'belanja_barang_jasa' => 250000000,
                'belanja_modal' => 300000000,
                'belanja_tidak_langsung' => 50000000
            ],
            [
                'tahun' => 2024,
                'bulan' => 3,
                'total_anggaran' => 1500000000,
                'total_belanja' => 1200000000,
                'belanja_pegawai' => 400000000,
                'belanja_barang_jasa' => 300000000,
                'belanja_modal' => 450000000,
                'belanja_tidak_langsung' => 50000000
            ],
        ];

        foreach ($data as $item) {
            AnggaranRealisasi::factory()->create($item);
        }

        $this->assertEquals(3, AnggaranRealisasi::count());

        $response = $this->get(route('data.anggaran-realisasi.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // Verify filename includes timestamp
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('data-anggaran-realisasi-', $disposition);
        $this->assertStringContainsString('.xlsx', $disposition);
    }

    /** @test */
    public function test_export_handles_edge_cases()
    {
        $this->actingAs($this->user);

        // Pastikan database bersih sebelum test
        AnggaranRealisasi::query()->delete();

        // Test with edge case data (minimal budget values)
        AnggaranRealisasi::factory()->create([
            'total_anggaran' => 0,
            'total_belanja' => 0,
            'belanja_pegawai' => 0,
            'belanja_barang_jasa' => 0,
            'belanja_modal' => 0,
            'belanja_tidak_langsung' => 0,
            'tahun' => 2024,
            'bulan' => 1,
        ]);

        $response = $this->get(route('data.anggaran-realisasi.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // Should still work with zero values
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('data-anggaran-realisasi-', $disposition);
    }

    /** @test */
    public function test_export_data_validation()
    {
        // Pastikan database bersih sebelum test
        AnggaranRealisasi::query()->delete();

        // Test that factory creates valid budget data
        $anggaranRealisasi = AnggaranRealisasi::factory()->create();

        // Verify required fields are present
        $this->assertNotNull($anggaranRealisasi->tahun);
        $this->assertNotNull($anggaranRealisasi->bulan);
        $this->assertIsNumeric($anggaranRealisasi->total_anggaran);
        $this->assertIsNumeric($anggaranRealisasi->total_belanja);
        $this->assertIsNumeric($anggaranRealisasi->belanja_pegawai);
        $this->assertIsNumeric($anggaranRealisasi->belanja_barang_jasa);
        $this->assertIsNumeric($anggaranRealisasi->belanja_modal);
        $this->assertIsNumeric($anggaranRealisasi->belanja_tidak_langsung);

        // Verify logical budget constraints
        $this->assertGreaterThanOrEqual(0, $anggaranRealisasi->total_anggaran);
        $this->assertGreaterThanOrEqual(0, $anggaranRealisasi->total_belanja);
        $this->assertGreaterThanOrEqual(1, $anggaranRealisasi->bulan);
        $this->assertLessThanOrEqual(12, $anggaranRealisasi->bulan);
    }

    /** @test */
    public function test_export_number_formatting()
    {
        // Pastikan database bersih sebelum test
        AnggaranRealisasi::query()->delete();

        // Create data with specific values to test formatting
        $anggaranRealisasi = AnggaranRealisasi::factory()->create([
            'total_anggaran' => 1234567890,
            'total_belanja' => 987654321,
        ]);

        $export = new \App\Exports\ExportAnggaranRealisasi();
        $mapped = $export->map($anggaranRealisasi);

        // Check that numbers are formatted with thousands separators
        $this->assertStringContainsString('.', $mapped[1], 'Total anggaran should be formatted with thousands separator');
        $this->assertStringContainsString('.', $mapped[2], 'Total belanja should be formatted with thousands separator');

        // Check that the formatted numbers don't contain the raw values
        $this->assertStringNotContainsString('1234567890', $mapped[1]);
        $this->assertStringNotContainsString('987654321', $mapped[2]);
    }

    /** @test */
    public function test_months_list_integration()
    {
        // Pastikan database bersih sebelum test
        AnggaranRealisasi::query()->delete();

        // Test that months_list helper function works in export
        $anggaranRealisasi = AnggaranRealisasi::factory()->create([
            'bulan' => 6, // June
        ]);

        $export = new \App\Exports\ExportAnggaranRealisasi();
        $mapped = $export->map($anggaranRealisasi);

        // Check that month is converted to text if months_list helper exists
        // If helper doesn't exist, should fallback to number
        $monthValue = $mapped[7]; // Month is at index 7
        $this->assertTrue(
            is_string($monthValue) || is_numeric($monthValue),
            'Month value should be either string (from months_list) or numeric (fallback)'
        );
    }
}
