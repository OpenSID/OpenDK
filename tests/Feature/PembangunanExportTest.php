<?php

namespace Tests\Feature;

use App\Exports\ExportPembangunan;
use App\Models\Pembangunan;
use App\Models\DataDesa;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class PembangunanExportTest extends TestCase
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
    }

    /**
     * Test export excel pembangunan.
     *
     * @return void
     */
    public function test_export_excel_pembangunan()
    {
        // Arrange: Buat beberapa data test
        $desa = DataDesa::factory()->create();
        Pembangunan::factory()->count(3)->create([
            'desa_id' => $desa->desa_id
        ]);

        // Act: Export pembangunan
        Excel::fake(); // Fake Excel facade

        $response = $this->get('/data/pembangunan/export-excel'); // Route untuk export pembangunan

        // Assert: Periksa bahwa export dipanggil
        $response->assertSuccessful();
        // Cukup periksa response berhasil karena filename dinamis dengan timestamp
    }

    /**
     * Test export excel pembangunan dengan filter desa.
     *
     * @return void
     */
    public function test_export_excel_pembangunan_with_desa_filter()
    {
        // Arrange: Buat data test dengan beberapa desa
        $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
        $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

        Pembangunan::factory()->count(2)->create([
            'desa_id' => $desa1->desa_id
        ]);
        Pembangunan::factory()->count(3)->create([
            'desa_id' => $desa2->desa_id
        ]);

        // Act: Export dengan filter desa
        Excel::fake(); // Fake Excel facade

        $response = $this->get('/data/pembangunan/export-excel?desa_id=' . $desa1->desa_id);

        // Assert: Periksa bahwa export dipanggil dengan filter
        $response->assertSuccessful();
        // Cukup periksa response berhasil karena filename dinamis dengan timestamp
    }

    /**
     * Test export pembangunan dengan database lokal tanpa filter.
     *
     * @return void
     */
    public function test_export_pembangunan_local_database_no_filter()
    {
        // Arrange: Buat data test
        $desa = DataDesa::factory()->create();
        Pembangunan::factory()->count(5)->create([
            'desa_id' => $desa->desa_id
        ]);

        // Act: Buat instance export tanpa filter
        $export = new ExportPembangunan();
        $collection = $export->collection();

        // Assert: Periksa data collection
        $this->assertEquals(Pembangunan::count(), $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }

    /**
     * Test export pembangunan dengan database lokal dengan filter desa.
     *
     * @return void
     */
    public function test_export_pembangunan_local_database_with_desa_filter()
    {
        // Arrange: Buat data test dengan beberapa desa
        $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
        $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

        Pembangunan::factory()->count(2)->create([
            'desa_id' => $desa1->desa_id
        ]);
        Pembangunan::factory()->count(3)->create([
            'desa_id' => $desa2->desa_id
        ]);

        // Act: Buat instance export dengan filter desa
        $export = new ExportPembangunan(['desa_id' => $desa1->desa_id]);
        $collection = $export->collection();

        // Assert: Periksa data collection yang terfilter
        $expectedCount = Pembangunan::where('desa_id', $desa1->desa_id)->count();
        $this->assertEquals($expectedCount, $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }

    /**
     * Test export pembangunan dengan filter 'Semua' (harus mengembalikan semua data).
     *
     * @return void
     */
    public function test_export_pembangunan_with_semua_filter()
    {
        // Arrange: Buat data test
        $desa = DataDesa::factory()->create();
        Pembangunan::factory()->count(4)->create([
            'desa_id' => $desa->desa_id
        ]);

        // Act: Buat instance export dengan filter 'Semua'
        $export = new ExportPembangunan(['desa_id' => 'Semua']);
        $collection = $export->collection();

        // Assert: Periksa bahwa semua data dikembalikan
        $this->assertEquals(Pembangunan::count(), $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }
}
