<?php

namespace Tests\Feature;

use App\Exports\ExportImunisasi;
use App\Models\Imunisasi;
use App\Models\DataDesa;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ImunisasiExportTest extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;

    /**
     * Set up test environment untuk testing export Imunisasi.
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
     * Test export Excel Imunisasi.
     *
     * @return void
     */
    public function test_export_excel_imunisasi()
    {
        // Arrange: Bersihkan data dan buat data test baru
        Imunisasi::query()->delete();

        $desa = DataDesa::factory()->create([
            'nama' => 'Desa Test Imunisasi'
        ]);

        Imunisasi::factory()->create([
            'desa_id' => $desa->desa_id,
            'cakupan_imunisasi' => 85,
            'bulan' => 1,
            'tahun' => 2024,
        ]);

        Imunisasi::factory()->create([
            'desa_id' => $desa->desa_id,
            'cakupan_imunisasi' => 92,
            'bulan' => 2,
            'tahun' => 2024,
        ]);

        // Act: Export Imunisasi
        Excel::fake();

        $response = $this->get('/data/imunisasi/export-excel');

        // Assert: Periksa bahwa export berhasil 
        $response->assertSuccessful();

        // Periksa bahwa data tersedia
        $imunisasiCount = Imunisasi::count();
        $this->assertEquals(2, $imunisasiCount);
    }

    /**
     * Test struktur heading export Imunisasi.
     *
     * @return void
     */
    public function test_export_imunisasi_headings()
    {
        // Arrange: Buat instance export
        $export = new ExportImunisasi();

        // Act: Ambil headings
        $headings = $export->headings();

        // Assert: Periksa struktur headings
        $expectedHeadings = [
            'ID',
            'Nama Desa',
            'Kode Desa',
            'Cakupan Imunisasi',
            'Bulan',
            'Tahun',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];

        $this->assertEquals($expectedHeadings, $headings);
    }

    /**
     * Test export data Imunisasi dari database lokal.
     *
     * @return void
     */
    public function test_export_imunisasi_data()
    {
        // Arrange: Bersihkan data dan buat data test baru
        Imunisasi::query()->delete();

        $desa = DataDesa::factory()->create([
            'nama' => 'Desa Test Export'
        ]);

        $imunisasi = Imunisasi::factory()->create([
            'desa_id' => $desa->desa_id,
            'cakupan_imunisasi' => 90,
            'bulan' => 3,
            'tahun' => 2024,
        ]);

        // Act: Buat export
        $export = new ExportImunisasi();
        $collection = $export->collection();

        // Assert: Periksa data yang diekspor
        $this->assertCount(1, $collection);

        $exportedData = $collection->first();
        $this->assertEquals($imunisasi->id, $exportedData->id);
        $this->assertEquals('Desa Test Export', $exportedData->desa->nama);
        $this->assertEquals(90, $exportedData->cakupan_imunisasi);
        $this->assertEquals(3, $exportedData->bulan);
        $this->assertEquals(2024, $exportedData->tahun);
    }

    /**
     * Test format mapping data export Imunisasi.
     *
     * @return void
     */
    public function test_export_imunisasi_mapping()
    {
        // Arrange: Bersihkan data dan buat data test
        Imunisasi::query()->delete();

        $desa = DataDesa::factory()->create([
            'nama' => 'Desa Test Mapping'
        ]);

        $imunisasi = Imunisasi::factory()->create([
            'desa_id' => $desa->desa_id,
            'cakupan_imunisasi' => 88,
            'bulan' => 4,
            'tahun' => 2024,
        ]);

        // Act: Test mapping
        $export = new ExportImunisasi();
        $mappedData = $export->map($imunisasi);

        // Assert: Periksa format mapping
        $this->assertEquals($imunisasi->id, $mappedData[0]);
        $this->assertEquals('Desa Test Mapping', $mappedData[1]);
        $this->assertEquals($desa->desa_id, $mappedData[2]);
        $this->assertEquals('88', $mappedData[3]);
        $this->assertEquals('April', $mappedData[4]); // months_list()[4] = 'April'
        $this->assertEquals(2024, $mappedData[5]);
    }

    /**
     * Test export ketika tidak ada data Imunisasi.
     *
     * @return void
     */
    public function test_export_imunisasi_no_data()
    {
        // Arrange: Pastikan tidak ada data Imunisasi
        Imunisasi::query()->delete();

        // Act: Export ketika tidak ada data
        $export = new ExportImunisasi();
        $collection = $export->collection();

        // Assert: Periksa bahwa collection kosong
        $this->assertCount(0, $collection);
    }
}
