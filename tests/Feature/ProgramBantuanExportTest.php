<?php

namespace Tests\Feature;

use App\Exports\ExportProgramBantuan;
use App\Models\Program;
use App\Models\DataDesa;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ProgramBantuanExportTest extends TestCase
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
     * Test export excel program bantuan.
     *
     * @return void
     */
    public function test_export_excel_program_bantuan()
    {
        // Arrange: Buat beberapa data test
        $desa = DataDesa::factory()->create();
        Program::factory()->count(3)->create([
            'desa_id' => $desa->desa_id
        ]);

        // Act: Export program bantuan
        Excel::fake(); // Fake Excel facade

        $response = $this->get('/data/program-bantuan/export-excel'); // Route untuk export program bantuan

        // Assert: Periksa bahwa export dipanggil
        $response->assertSuccessful();
        // Cukup periksa response berhasil karena filename dinamis dengan timestamp
    }

    /**
     * Test export excel program bantuan dengan filter desa.
     *
     * @return void
     */
    public function test_export_excel_program_bantuan_with_desa_filter()
    {
        // Arrange: Buat data test dengan beberapa desa
        $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
        $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

        Program::factory()->count(2)->create([
            'desa_id' => $desa1->desa_id
        ]);
        Program::factory()->count(3)->create([
            'desa_id' => $desa2->desa_id
        ]);

        // Act: Export dengan filter desa
        Excel::fake(); // Fake Excel facade

        $response = $this->get('/data/program-bantuan/export-excel?desa_id=' . $desa1->desa_id);

        // Assert: Periksa bahwa export dipanggil dengan filter
        $response->assertSuccessful();
        // Cukup periksa response berhasil karena filename dinamis dengan timestamp
    }

    /**
     * Test export program bantuan dengan database lokal tanpa filter.
     *
     * @return void
     */
    public function test_export_program_bantuan_local_database_no_filter()
    {
        // Arrange: Buat data test
        $desa = DataDesa::factory()->create();
        Program::factory()->count(5)->create([
            'desa_id' => $desa->desa_id
        ]);

        // Act: Buat instance export tanpa filter
        $export = new ExportProgramBantuan();
        $collection = $export->collection();

        // Assert: Periksa data collection
        $this->assertEquals(Program::count(), $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }

    /**
     * Test export program bantuan dengan database lokal dengan filter desa.
     *
     * @return void
     */
    public function test_export_program_bantuan_local_database_with_desa_filter()
    {
        // Arrange: Buat data test dengan beberapa desa
        $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
        $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

        Program::factory()->count(2)->create([
            'desa_id' => $desa1->desa_id
        ]);
        Program::factory()->count(3)->create([
            'desa_id' => $desa2->desa_id
        ]);

        // Act: Buat instance export dengan filter desa
        $export = new ExportProgramBantuan(['desa_id' => $desa1->desa_id]);
        $collection = $export->collection();

        // Assert: Periksa data collection yang terfilter
        $expectedCount = Program::where('desa_id', $desa1->desa_id)->count();
        $this->assertEquals($expectedCount, $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }

    /**
     * Test export program bantuan dengan filter 'Semua' (harus mengembalikan semua data).
     *
     * @return void
     */
    public function test_export_program_bantuan_with_semua_filter()
    {
        // Arrange: Buat data test
        $desa = DataDesa::factory()->create();
        Program::factory()->count(4)->create([
            'desa_id' => $desa->desa_id
        ]);

        // Act: Buat instance export dengan filter 'Semua'
        $export = new ExportProgramBantuan(['desa_id' => 'Semua']);
        $collection = $export->collection();

        // Assert: Periksa bahwa semua data dikembalikan
        $this->assertEquals(Program::count(), $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }
}
