<?php

namespace Tests\Feature;

use App\Exports\ExportAKIAKB;
use App\Models\AkiAkb;
use App\Models\DataDesa;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class AKIAKBExportTest extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;

    /**
     * Set up test environment untuk testing export AKI AKB.
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
     * Test export Excel AKI AKB.
     *
     * @return void
     */
    public function test_export_excel_aki_akb()
    {
        // Arrange: Bersihkan data dan buat data test baru
        AkiAkb::query()->delete();

        $desa = DataDesa::factory()->create([
            'nama' => 'Desa Test AKI AKB'
        ]);

        AkiAkb::factory()->create([
            'desa_id' => $desa->desa_id,
            'aki' => 5,
            'akb' => 3,
            'bulan' => 1,
            'tahun' => 2024,
        ]);

        AkiAkb::factory()->create([
            'desa_id' => $desa->desa_id,
            'aki' => 2,
            'akb' => 1,
            'bulan' => 2,
            'tahun' => 2024,
        ]);

        // Act: Export AKI AKB
        Excel::fake();

        $response = $this->get('/data/aki-akb/export-excel');

        // Assert: Periksa bahwa export berhasil 
        $response->assertSuccessful();

        // Periksa bahwa data tersedia
        $akiAkbCount = AkiAkb::count();
        $this->assertEquals(2, $akiAkbCount);
    }

    /**
     * Test struktur heading export AKI AKB.
     *
     * @return void
     */
    public function test_export_aki_akb_headings()
    {
        // Arrange: Buat instance export
        $export = new ExportAKIAKB();

        // Act: Ambil headings
        $headings = $export->headings();

        // Assert: Periksa struktur headings
        $expectedHeadings = [
            'ID',
            'Nama Desa',
            'Kode Desa',
            'Jumlah AKI',
            'Jumlah AKB',
            'Bulan',
            'Tahun',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];

        $this->assertEquals($expectedHeadings, $headings);
    }

    /**
     * Test export data AKI AKB dari database lokal.
     *
     * @return void
     */
    public function test_export_aki_akb_data()
    {
        // Arrange: Bersihkan data dan buat data test baru
        AkiAkb::query()->delete();

        $desa = DataDesa::factory()->create([
            'nama' => 'Desa Test Export'
        ]);

        $akiAkb = AkiAkb::factory()->create([
            'desa_id' => $desa->desa_id,
            'aki' => 7,
            'akb' => 4,
            'bulan' => 3,
            'tahun' => 2024,
        ]);

        // Act: Buat export
        $export = new ExportAKIAKB();
        $collection = $export->collection();

        // Assert: Periksa data yang diekspor
        $this->assertCount(1, $collection);

        $exportedData = $collection->first();
        $this->assertEquals($akiAkb->id, $exportedData->id);
        $this->assertEquals('Desa Test Export', $exportedData->desa->nama);
        $this->assertEquals(7, $exportedData->aki);
        $this->assertEquals(4, $exportedData->akb);
        $this->assertEquals(3, $exportedData->bulan);
        $this->assertEquals(2024, $exportedData->tahun);
    }

    /**
     * Test format mapping data export AKI AKB.
     *
     * @return void
     */
    public function test_export_aki_akb_mapping()
    {
        // Arrange: Bersihkan data dan buat data test
        AkiAkb::query()->delete();

        $desa = DataDesa::factory()->create([
            'nama' => 'Desa Test Mapping'
        ]);

        $akiAkb = AkiAkb::factory()->create([
            'desa_id' => $desa->desa_id,
            'aki' => 6,
            'akb' => 2,
            'bulan' => 4,
            'tahun' => 2024,
        ]);

        // Act: Test mapping
        $export = new ExportAKIAKB();
        $mappedData = $export->map($akiAkb);

        // Assert: Periksa format mapping
        $this->assertEquals($akiAkb->id, $mappedData[0]);
        $this->assertEquals('Desa Test Mapping', $mappedData[1]);
        $this->assertEquals($desa->desa_id, $mappedData[2]);
        $this->assertEquals(6, $mappedData[3]);
        $this->assertEquals(2, $mappedData[4]);
        $this->assertEquals('April', $mappedData[5]); // months_list()[4] = 'April'
        $this->assertEquals(2024, $mappedData[6]);
    }

    /**
     * Test export ketika tidak ada data AKI AKB.
     *
     * @return void
     */
    public function test_export_aki_akb_no_data()
    {
        // Arrange: Pastikan tidak ada data AKI AKB
        AkiAkb::query()->delete();

        // Act: Export ketika tidak ada data
        $export = new ExportAKIAKB();
        $collection = $export->collection();

        // Assert: Periksa bahwa collection kosong
        $this->assertCount(0, $collection);
    }
}
