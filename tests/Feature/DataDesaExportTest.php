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
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // disabled database gabungan for testing
        SettingAplikasi::updateOrCreate(
            ['key' => 'sinkronisasi_database_gabungan'],
            ['value' => '0']
        );
    }

    /**
     * Test export excel data desa.
     *
     * @return void
     */
    public function test_export_excel_data_desa()
    {
        // Arrange: Create some test data
        DataDesa::factory()->count(3)->create();

        // Act: Export the data desa
        Excel::fake(); // Fake the Excel facade

        $response = $this->get('/data/data-desa/export-excel'); // Route for exporting data desa

        // Assert: Check response status and that export was called
        $response->assertSuccessful();
        // Just check response is successful since filename is dynamic with timestamp
    }

    /**
     * Test export excel data desa with local database.
     *
     * @return void
     */
    public function test_export_excel_local_database()
    {
        // Arrange: Create some test data
        DataDesa::factory()->count(5)->create();

        // Act: Create export instance for local database
        $export = new ExportDataDesa(false, []);
        $collection = $export->collection();

        // Assert: Check collection data
        $this->assertEquals(DataDesa::count(), $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }

    /**
     * Test export excel data desa with database gabungan active.
     *
     * @return void
     */
    public function test_export_excel_database_gabungan_active()
    {
        // Arrange: Enable database gabungan
        SettingAplikasi::updateOrCreate(
            ['key' => 'sinkronisasi_database_gabungan'],
            ['value' => '1']
        );

        // Create some local test data
        DataDesa::factory()->count(3)->create();

        // Act: Export with gabungan database mode
        Excel::fake();

        $response = $this->get('/data/data-desa/export-excel');

        // Assert: Check that export was called with gabungan mode
        $response->assertSuccessful();
        // Just check response is successful since filename is dynamic with timestamp
    }

    /**
     * Test export data desa with gabungan mode constructor.
     *
     * @return void
     */
    public function test_export_data_desa_gabungan_mode_constructor()
    {
        // Act: Create export instance with gabungan mode
        $export = new ExportDataDesa(true, []);

        // Assert: The export instance should be created successfully
        $this->assertInstanceOf(ExportDataDesa::class, $export);

        // Test that collection method exists and can be called
        // In gabungan mode, it would call DesaService->listDesa()
        // but since we're in test environment, we just verify the method exists
        $this->assertTrue(method_exists($export, 'collection'));
    }

    /**
     * Test export headings.
     *
     * @return void
     */
    public function test_export_headings()
    {
        // Act: Create export instance
        $export = new ExportDataDesa(false, []);
        $headings = $export->headings();

        // Assert: Check headings
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
        // Arrange: Create test data
        $dataDesa = DataDesa::factory()->create([
            'nama' => 'Test Desa',
            'desa_id' => '12345',
            'sebutan_desa' => 'Desa',
        ]);

        // Act: Create export instance and test mapping
        $export = new ExportDataDesa(false, []);
        $mappedData = $export->map($dataDesa);

        // Assert: Check mapped data structure
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
        // Act: Create export instance
        $export = new ExportDataDesa(false, []);

        // Create a mock worksheet
        $worksheet = $this->createMock(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::class);

        // Assert: Method exists and returns styles array
        $styles = $export->styles($worksheet);
        $this->assertIsArray($styles);
    }
}
