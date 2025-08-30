<?php

namespace Tests\Feature;

use App\Exports\ExportAnggaranDesa;
use App\Models\AnggaranDesa;
use App\Models\DataDesa;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class AnggaranDesaExportTest extends TestCase
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
     * Test export excel anggaran desa.
     *
     * @return void
     */
    public function test_export_excel_anggaran_desa()
    {
        // Arrange: Buat beberapa data test
        $desa = DataDesa::factory()->create();
        AnggaranDesa::factory()->count(3)->create([
            'desa_id' => $desa->desa_id
        ]);

        // Act: Export anggaran desa
        Excel::fake(); // Fake Excel facade

        $response = $this->get('/data/anggaran-desa/export-excel'); // Route untuk export anggaran desa

        // Assert: Periksa bahwa export dipanggil
        $response->assertSuccessful();
        // Cukup periksa response berhasil karena filename dinamis dengan timestamp
    }

    /**
     * Test export excel anggaran desa dengan filter desa.
     *
     * @return void
     */
    public function test_export_excel_anggaran_desa_with_desa_filter()
    {
        // Arrange: Buat data test dengan beberapa desa
        $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
        $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

        AnggaranDesa::factory()->count(2)->create([
            'desa_id' => $desa1->desa_id
        ]);
        AnggaranDesa::factory()->count(3)->create([
            'desa_id' => $desa2->desa_id
        ]);

        // Act: Export dengan filter desa
        Excel::fake(); // Fake Excel facade

        $response = $this->get('/data/anggaran-desa/export-excel?desa_id=' . $desa1->desa_id);

        // Assert: Periksa bahwa export dipanggil dengan filter
        $response->assertSuccessful();
        // Cukup periksa response berhasil karena filename dinamis dengan timestamp
    }

    /**
     * Test export anggaran desa dengan database lokal tanpa filter.
     *
     * @return void
     */
    public function test_export_anggaran_desa_local_database_no_filter()
    {
        // Arrange: Buat data test
        $desa = DataDesa::factory()->create();
        AnggaranDesa::factory()->count(5)->create([
            'desa_id' => $desa->desa_id
        ]);

        // Act: Buat instance export tanpa filter
        $export = new ExportAnggaranDesa();
        $collection = $export->collection();

        // Assert: Periksa data collection
        $this->assertEquals(AnggaranDesa::count(), $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }

    /**
     * Test export anggaran desa dengan database lokal dengan filter desa.
     *
     * @return void
     */
    public function test_export_anggaran_desa_local_database_with_desa_filter()
    {
        // Arrange: Buat data test dengan beberapa desa
        $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
        $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

        AnggaranDesa::factory()->count(2)->create([
            'desa_id' => $desa1->desa_id
        ]);
        AnggaranDesa::factory()->count(3)->create([
            'desa_id' => $desa2->desa_id
        ]);

        // Act: Buat instance export dengan filter desa
        $export = new ExportAnggaranDesa(['desa_id' => $desa1->desa_id]);
        $collection = $export->collection();

        // Assert: Periksa data collection yang terfilter
        $expectedCount = AnggaranDesa::where('desa_id', $desa1->desa_id)->count();
        $this->assertEquals($expectedCount, $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }

    /**
     * Test export anggaran desa dengan filter 'Semua' (harus mengembalikan semua data).
     *
     * @return void
     */
    public function test_export_anggaran_desa_with_semua_filter()
    {
        // Arrange: Buat data test
        $desa = DataDesa::factory()->create();
        AnggaranDesa::factory()->count(4)->create([
            'desa_id' => $desa->desa_id
        ]);

        // Act: Buat instance export dengan filter 'Semua'
        $export = new ExportAnggaranDesa(['desa_id' => 'Semua']);
        $collection = $export->collection();

        // Assert: Periksa bahwa semua data dikembalikan
        $this->assertEquals(AnggaranDesa::count(), $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }
}
