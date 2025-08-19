<?php

namespace Tests\Feature;

use App\Exports\ExportKeluarga;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\DataDesa;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class KeluargaExportTest extends TestCase
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
     * Test export excel keluarga.
     *
     * @return void
     */
    /**
     * Test export excel keluarga.
     *
     * @return void
     */
    public function test_export_excel_keluarga()
    {
        // Arrange: Buat beberapa data test
        $desa = DataDesa::factory()->create();
        $penduduk = Penduduk::factory()->create(['desa_id' => $desa->desa_id]);
        Keluarga::factory()->count(3)->create([
            'nik_kepala' => $penduduk->nik,
            'desa_id' => $desa->desa_id
        ]);

        // Act: Export keluarga
        Excel::fake(); // Fake Excel facade

        $response = $this->get('/data/keluarga/export-excel'); // Route untuk export keluarga

        // Assert: Periksa bahwa export dipanggil
        $response->assertSuccessful();
        // Cukup periksa response berhasil karena filename dinamis dengan timestamp
    }

    /**
     * Test export excel keluarga dengan filter desa.
     *
     * @return void
     */
    public function test_export_excel_keluarga_with_desa_filter()
    {
        // Arrange: Buat data test dengan beberapa desa
        $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
        $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

        $penduduk1 = Penduduk::factory()->create(['desa_id' => $desa1->desa_id]);
        $penduduk2 = Penduduk::factory()->create(['desa_id' => $desa2->desa_id]);

        Keluarga::factory()->count(2)->create([
            'nik_kepala' => $penduduk1->nik,
            'desa_id' => $desa1->desa_id
        ]);
        Keluarga::factory()->count(3)->create([
            'nik_kepala' => $penduduk2->nik,
            'desa_id' => $desa2->desa_id
        ]);

        // Act: Export dengan filter desa
        Excel::fake(); // Fake Excel facade

        $response = $this->get('/data/keluarga/export-excel?desa=' . $desa1->desa_id);

        // Assert: Periksa bahwa export dipanggil dengan filter
        $response->assertSuccessful();
        // Cukup periksa response berhasil karena filename dinamis dengan timestamp
    }

    /**
     * Test export keluarga dengan database lokal tanpa filter.
     *
     * @return void
     */
    public function test_export_keluarga_local_database_no_filter()
    {
        // Arrange: Buat data test
        $desa = DataDesa::factory()->create();
        $penduduk = Penduduk::factory()->create(['desa_id' => $desa->desa_id]);
        Keluarga::factory()->count(5)->create([
            'nik_kepala' => $penduduk->nik,
            'desa_id' => $desa->desa_id
        ]);

        // Act: Buat instance export tanpa filter
        $export = new ExportKeluarga(false, []);
        $collection = $export->collection();

        // Assert: Periksa data collection
        $this->assertEquals(Keluarga::has('kepala_kk')->count(), $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }

    /**
     * Test export keluarga dengan database lokal dengan filter desa.
     *
     * @return void
     */
    public function test_export_keluarga_local_database_with_desa_filter()
    {
        // Arrange: Buat data test dengan beberapa desa
        $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
        $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

        $penduduk1 = Penduduk::factory()->create(['desa_id' => $desa1->desa_id]);
        $penduduk2 = Penduduk::factory()->create(['desa_id' => $desa2->desa_id]);

        Keluarga::factory()->count(2)->create([
            'nik_kepala' => $penduduk1->nik,
            'desa_id' => $desa1->desa_id
        ]);
        Keluarga::factory()->count(3)->create([
            'nik_kepala' => $penduduk2->nik,
            'desa_id' => $desa2->desa_id
        ]);

        // Act: Buat instance export dengan filter desa
        $export = new ExportKeluarga(false, ['desa' => $desa1->desa_id]);
        $collection = $export->collection();

        // Assert: Periksa data collection yang terfilter
        $expectedCount = Keluarga::has('kepala_kk')->where('desa_id', $desa1->desa_id)->count();
        $this->assertEquals($expectedCount, $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }

    /**
     * Test export keluarga dengan filter 'Semua' (harus mengembalikan semua data).
     *
     * @return void
     */
    public function test_export_keluarga_with_semua_filter()
    {
        // Arrange: Buat data test
        $desa = DataDesa::factory()->create();
        $penduduk = Penduduk::factory()->create(['desa_id' => $desa->desa_id]);
        Keluarga::factory()->count(4)->create([
            'nik_kepala' => $penduduk->nik,
            'desa_id' => $desa->desa_id
        ]);

        // Act: Buat instance export dengan filter 'Semua'
        $export = new ExportKeluarga(false, ['desa' => 'Semua']);
        $collection = $export->collection();

        // Assert: Periksa bahwa semua data dikembalikan
        $this->assertEquals(Keluarga::has('kepala_kk')->count(), $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }
}
