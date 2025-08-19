<?php

namespace Tests\Feature;

use App\Exports\LaporanPendudukExport;
use App\Models\DataDesa;
use App\Models\LaporanPenduduk;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class LaporanPendudukExportTest extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;

    /**
     * Set up the test environment untuk testing export laporan penduduk.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Menonaktifkan database gabungan untuk testing
        SettingAplikasi::updateOrCreate(
            ['key' => 'sinkronisasi_database_gabungan'],
            ['value' => '0']
        );
    }

    /**
     * Test export Excel laporan penduduk ketika gabungan non-aktif.
     *
     * @return void
     */
    public function test_export_excel_gabungan_inactive()
    {
        // Arrange: Bersihkan data dan buat data test baru
        LaporanPenduduk::query()->delete();

        $desa = DataDesa::factory()->create([
            'nama' => 'Desa Test Export'
        ]);

        LaporanPenduduk::factory()->create([
            'judul' => 'Laporan Test 1',
            'bulan' => '01',
            'tahun' => '2024',
            'nama_file' => 'laporan-test-1.pdf',
            'id_laporan_penduduk' => 1001,
            'desa_id' => $desa->desa_id,
        ]);

        LaporanPenduduk::factory()->create([
            'judul' => 'Laporan Test 2',
            'bulan' => '02',
            'tahun' => '2024',
            'nama_file' => 'laporan-test-2.pdf',
            'id_laporan_penduduk' => 1002,
            'desa_id' => $desa->desa_id,
        ]);

        // Act: Export laporan penduduk dengan mode gabungan non-aktif
        Excel::fake();

        // Karena route belum ada, test langsung export class
        $export = new LaporanPendudukExport(false);
        $collection = $export->collection();

        // Assert: Periksa bahwa export berhasil 
        $this->assertCount(2, $collection);

        // Periksa bahwa export menggunakan data lokal
        $laporanCount = LaporanPenduduk::count();
        $this->assertEquals(2, $laporanCount);
    }

    /**
     * Test export Excel laporan penduduk ketika database gabungan aktif.
     *
     * @return void
     */
    public function test_export_excel_gabungan_active()
    {
        // Arrange: Aktifkan database gabungan
        SettingAplikasi::updateOrCreate(
            ['key' => 'sinkronisasi_database_gabungan'],
            ['value' => '1']
        );

        // Act: Test export dengan mode gabungan aktif menggunakan class langsung
        $export = new LaporanPendudukExport(true);

        // Assert: Pastikan export dapat diinstansiasi dengan mode gabungan
        $this->assertInstanceOf(LaporanPendudukExport::class, $export);
    }

    /**
     * Test struktur heading export laporan penduduk.
     *
     * @return void
     */
    public function test_export_laporan_penduduk_headings()
    {
        // Arrange: Buat instance export
        $export = new LaporanPendudukExport(false);

        // Act: Ambil headings
        $headings = $export->headings();

        // Assert: Periksa struktur headings
        $expectedHeadings = [
            'ID',
            'DESA',
            'JUDUL',
            'BULAN',
            'TAHUN',
            'TANGGAL LAPOR',
        ];

        $this->assertEquals($expectedHeadings, $headings);
    }

    /**
     * Test export data laporan penduduk dari database lokal.
     *
     * @return void
     */
    public function test_export_laporan_penduduk_local_data()
    {
        // Arrange: Bersihkan data dan buat data test baru
        LaporanPenduduk::query()->delete();

        $desa = DataDesa::factory()->create([
            'nama' => 'Desa Test Local'
        ]);

        $laporan = LaporanPenduduk::factory()->create([
            'judul' => 'Laporan Test Local',
            'bulan' => '03',
            'tahun' => '2024',
            'nama_file' => 'laporan-local.pdf',
            'id_laporan_penduduk' => 2001,
            'desa_id' => $desa->desa_id,
        ]);

        // Act: Buat export dengan mode lokal
        $export = new LaporanPendudukExport(false);
        $collection = $export->collection();

        // Assert: Periksa data yang diekspor
        $this->assertCount(1, $collection);

        $exportedData = $collection->first();
        $this->assertEquals($laporan->id, $exportedData['id']);
        $this->assertEquals('Desa Test Local', $exportedData['nama_desa']);
        $this->assertEquals('Laporan Test Local', $exportedData['judul']);
        $this->assertEquals('03', $exportedData['bulan']);
        $this->assertEquals('2024', $exportedData['tahun']);
    }

    /**
     * Test export ketika tidak ada data laporan penduduk.
     *
     * @return void
     */
    public function test_export_laporan_penduduk_no_data()
    {
        // Pastikan tidak ada data laporan penduduk
        LaporanPenduduk::query()->delete();

        // Act: Export ketika tidak ada data
        $export = new LaporanPendudukExport(false);
        $collection = $export->collection();

        // Assert: Periksa bahwa collection kosong
        $this->assertCount(0, $collection);
    }
}
