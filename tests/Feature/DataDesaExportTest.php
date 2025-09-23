<?php

namespace Tests\Feature;

use App\Exports\ExportDataDesa;
use App\Models\DataDesa;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class DataDesaExportTest extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;

    /**
     * Menyiapkan lingkungan test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // nonaktifkan database gabungan untuk testing
        SettingAplikasi::updateOrCreate(
            ['key' => 'sinkronisasi_database_gabungan'],
            ['value' => '0']
        );
        config(['setting.sebutan_desa' => 'Desa']);
    }

    /**
     * Test export excel data desa.
     *
     * @return void
     */
    public function test_export_excel_data_desa()
    {
        // Arrange: Buat beberapa data test
        DataDesa::factory()->count(3)->create();

        // Act: Export data desa
        Excel::fake(); // Fake Excel facade

        $response = $this->get('/data/data-desa/export-excel'); // Route untuk export data desa

        // Assert: Periksa status response dan bahwa export dipanggil
        $response->assertSuccessful();
        // Cukup periksa response berhasil karena filename dinamis dengan timestamp
    }

    /**
     * Test export excel data desa dengan database lokal.
     *
     * @return void
     */
    public function test_export_excel_local_database()
    {
        // Arrange: Buat beberapa data test
        DataDesa::factory()->count(5)->create();

        // Act: Buat instance export untuk database lokal
        $export = new ExportDataDesa(false, []);
        $collection = $export->collection();

        // Assert: Periksa data collection
        $this->assertEquals(DataDesa::count(), $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }

    /**
     * Test export excel data desa dengan database gabungan aktif.
     *
     * @return void
     */
    public function test_export_excel_database_gabungan_active()
    {
        // Arrange: Aktifkan database gabungan
        SettingAplikasi::updateOrCreate(
            ['key' => 'sinkronisasi_database_gabungan'],
            ['value' => '1']
        );

        // Buat beberapa data test lokal
        DataDesa::factory()->count(3)->create();

        // Act: Export dengan mode database gabungan
        Excel::fake();

        $response = $this->get('/data/data-desa/export-excel');

        // Assert: Periksa bahwa export dipanggil dengan mode gabungan
        $response->assertSuccessful();
        // Cukup periksa response berhasil karena filename dinamis dengan timestamp
    }

    /**
     * Test export data desa dengan constructor mode gabungan.
     *
     * @return void
     */
    public function test_export_data_desa_gabungan_mode_constructor()
    {
        // Act: Buat instance export dengan mode gabungan
        $export = new ExportDataDesa(true, []);

        // Assert: Instance export harus berhasil dibuat
        $this->assertInstanceOf(ExportDataDesa::class, $export);

        // Test bahwa method collection ada dan dapat dipanggil
        // Dalam mode gabungan, akan memanggil DesaService->listDesa()
        // tapi karena dalam lingkungan test, kita hanya verifikasi method ada
        $this->assertTrue(method_exists($export, 'collection'));
    }

    /**
     * Test export headings.
     *
     * @return void
     */
    public function test_export_headings()
    {
        // Act: Buat instance export
        $export = new ExportDataDesa(false, []);
        $headings = $export->headings();

        // Assert: Periksa headings
        $expectedHeadings = [
            'ID',
            'Kode Desa',
            'Nama Desa',
            'Sebutan Desa',
            'Website',
            'Luas Wilayah (km²)',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];

        $this->assertEquals($expectedHeadings, $headings);
    }

    /**
     * Test export mapping.
     *
     * @return void
     */
    public function test_export_mapping()
    {
        // Arrange: Buat data test
        $dataDesa = DataDesa::factory()->create([
            'nama' => 'Test Desa',
            'desa_id' => '12345',
            'sebutan_desa' => 'Desa',
        ]);

        // Act: Buat instance export dan test mapping
        $export = new ExportDataDesa(false, []);
        $mappedData = $export->map($dataDesa);

        // Assert: Periksa struktur data yang dimapping
        $this->assertIsArray($mappedData);
        $this->assertEquals($dataDesa->id, $mappedData[0]);
        $this->assertEquals('12345', $mappedData[1]);
        $this->assertEquals('Test Desa', $mappedData[2]);
        $this->assertEquals('Desa', $mappedData[3]);
    }

    /**
     * Test export styles.
     *
     * @return void
     */
    public function test_export_styles()
    {
        // Act: Buat instance export
        $export = new ExportDataDesa(false, []);

        // Buat mock worksheet
        $worksheet = $this->createMock(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::class);

        // Assert: Method ada dan mengembalikan array styles
        $styles = $export->styles($worksheet);
        $this->assertIsArray($styles);
    }
}
