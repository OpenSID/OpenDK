<?php

namespace Tests\Feature;

use App\Exports\ExportSuplemen;
use App\Exports\ExportSuplemenTerdata;
use App\Models\DataDesa;
use App\Models\Penduduk;
use App\Models\Suplemen;
use App\Models\SuplemenTerdata;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class SuplemenExportTest extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Suplemen::query()->delete();
    }

    /**
     * Test export excel suplemen.
     *
     * @return void
     */
    public function test_export_excel_suplemen()
    {
        // Arrange: Buat beberapa data test
        Suplemen::factory()->count(3)->create();

        // Act: Export suplemen
        Excel::fake(); // Fake Excel facade

        $response = $this->get('/data/data-suplemen/export-excel'); // Route untuk export suplemen

        // Assert: Periksa bahwa export dipanggil
        $response->assertSuccessful();
    }

    /**
     * Test export excel suplemen dengan filter.
     *
     * @return void
     */
    public function test_export_excel_suplemen_with_filters()
    {
        // Arrange: Buat data test dengan nama dan sasaran berbeda
        Suplemen::factory()->create(['nama' => 'Suplemen Test 1', 'sasaran' => 1]);
        Suplemen::factory()->create(['nama' => 'Suplemen Test 2', 'sasaran' => 2]);

        // Act: Export dengan filter
        Excel::fake(); // Fake Excel facade

        $response = $this->get('/data/data-suplemen/export-excel?nama=Test&sasaran=1');

        // Assert: Periksa bahwa export dipanggil dengan filter
        $response->assertSuccessful();
    }

    /**
     * Test export suplemen tanpa filter.
     *
     * @return void
     */
    public function test_export_suplemen_no_filter()
    {
        // Arrange: Buat data test
        Suplemen::factory()->count(5)->create();

        // Act: Buat instance export tanpa filter
        $export = new ExportSuplemen([]);
        $collection = $export->collection();

        // Assert: Periksa data collection
        $this->assertEquals(Suplemen::count(), $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }

    /**
     * Test export suplemen dengan filter nama.
     *
     * @return void
     */
    public function test_export_suplemen_with_nama_filter()
    {
        // Arrange: Buat data test dengan nama berbeda
        Suplemen::factory()->create(['nama' => 'BLT Dana Desa']);
        Suplemen::factory()->create(['nama' => 'PKH Keluarga']);
        Suplemen::factory()->create(['nama' => 'Bantuan Pangan']);

        // Act: Buat instance export dengan filter nama
        $export = new ExportSuplemen(['nama' => 'BLT']);
        $collection = $export->collection();

        // Assert: Periksa data collection yang terfilter
        $this->assertEquals(1, $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }

    /**
     * Test export suplemen dengan filter sasaran.
     *
     * @return void
     */
    public function test_export_suplemen_with_sasaran_filter()
    {
        // Arrange: Buat data test dengan sasaran berbeda secara manual
        Suplemen::create(['nama' => 'Suplemen Penduduk 1', 'slug' => 'suplemen-penduduk-1', 'sasaran' => 1, 'keterangan' => 'Test']);
        Suplemen::create(['nama' => 'Suplemen Penduduk 2', 'slug' => 'suplemen-penduduk-2', 'sasaran' => 1, 'keterangan' => 'Test']);
        Suplemen::create(['nama' => 'Suplemen Keluarga 1', 'slug' => 'suplemen-keluarga-1', 'sasaran' => 2, 'keterangan' => 'Test']);
        Suplemen::create(['nama' => 'Suplemen Keluarga 2', 'slug' => 'suplemen-keluarga-2', 'sasaran' => 2, 'keterangan' => 'Test']);
        Suplemen::create(['nama' => 'Suplemen Keluarga 3', 'slug' => 'suplemen-keluarga-3', 'sasaran' => 2, 'keterangan' => 'Test']);

        // Act: Buat instance export dengan filter sasaran
        $export = new ExportSuplemen(['sasaran' => 1]);
        $collection = $export->collection();

        // Assert: Periksa data collection yang terfilter
        $this->assertEquals(2, $collection->count());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);
    }

    /**
     * Test export excel suplemen terdata.
     *
     * @return void
     */
    public function test_export_excel_suplemen_terdata()
    {
        // Arrange: Buat data test secara manual untuk menghindari factory issue
        $suplemen = Suplemen::create([
            'nama' => 'Test Suplemen',
            'slug' => 'test-suplemen',
            'sasaran' => 1,
            'keterangan' => 'Test keterangan'
        ]);

        $desa = DataDesa::factory()->create();

        // Create penduduk dengan ID eksplisit untuk mengatasi masalah incrementing
        $pendudukId = 999999; // Use a large ID to avoid conflicts
        $penduduk = Penduduk::create([
            'id' => $pendudukId,
            'nama' => 'Test Penduduk',
            'nik' => '1234567890123456',
            'desa_id' => $desa->desa_id,
            'status_dasar' => 1,
            'sex' => 1,
            'alamat' => 'Test Alamat',
            'tempat_lahir' => 'Test Tempat Lahir',
            'tanggal_lahir' => '1990-01-01',
        ]);

        SuplemenTerdata::create([
            'suplemen_id' => $suplemen->id,
            'penduduk_id' => $pendudukId,
            'keterangan' => 'Test keterangan terdata'
        ]);

        // Act: Export suplemen terdata
        Excel::fake(); // Fake Excel facade

        $response = $this->get("/data/data-suplemen/export-terdata-excel/{$suplemen->id}");

        // Assert: Periksa bahwa export dipanggil
        $response->assertSuccessful();
    }

    /**
     * Test export suplemen terdata dengan filter desa.
     *
     * @return void
     */
    public function test_export_suplemen_terdata_with_desa_filter()
    {
        // Arrange: Buat data test dengan beberapa desa
        $suplemen = Suplemen::create([
            'nama' => 'Test Suplemen Filter',
            'slug' => 'test-suplemen-filter',
            'sasaran' => 1,
            'keterangan' => 'Test keterangan filter'
        ]);

        $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
        $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

        // Create penduduk dengan ID eksplisit untuk mengatasi masalah incrementing
        $pendudukId1 = 999998;
        $pendudukId2 = 999997;

        $penduduk1 = Penduduk::create([
            'id' => $pendudukId1,
            'nama' => 'Test Penduduk 1',
            'nik' => '1111567890123456',
            'desa_id' => $desa1->desa_id,
            'status_dasar' => 1,
            'sex' => 1,
            'alamat' => 'Test Alamat 1',
            'tempat_lahir' => 'Test Tempat Lahir 1',
            'tanggal_lahir' => '1990-01-01',
        ]);

        $penduduk2 = Penduduk::create([
            'id' => $pendudukId2,
            'nama' => 'Test Penduduk 2',
            'nik' => '2222567890123456',
            'desa_id' => $desa2->desa_id,
            'status_dasar' => 1,
            'sex' => 2,
            'alamat' => 'Test Alamat 2',
            'tempat_lahir' => 'Test Tempat Lahir 2',
            'tanggal_lahir' => '1991-01-01',
        ]);

        SuplemenTerdata::create([
            'suplemen_id' => $suplemen->id,
            'penduduk_id' => $pendudukId1,
            'keterangan' => 'Test terdata 1'
        ]);
        SuplemenTerdata::create([
            'suplemen_id' => $suplemen->id,
            'penduduk_id' => $pendudukId2,
            'keterangan' => 'Test terdata 2'
        ]);

        // Act: Export dengan filter desa
        Excel::fake(); // Fake Excel facade

        $response = $this->get("/data/data-suplemen/export-terdata-excel/{$suplemen->id}?desa={$desa1->desa_id}");

        // Assert: Periksa bahwa export dipanggil dengan filter
        $response->assertSuccessful();
    }

    /**
     * Test export headings suplemen.
     *
     * @return void
     */
    public function test_export_suplemen_headings()
    {
        // Act: Buat instance export
        $export = new ExportSuplemen([]);
        $headings = $export->headings();

        // Assert: Periksa headings
        $expectedHeadings = [
            'ID',
            'Nama Suplemen',
            'Slug',
            'Sasaran',
            'Keterangan',
            'Jumlah Terdata',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];

        $this->assertEquals($expectedHeadings, $headings);
    }

    /**
     * Test export mapping suplemen.
     *
     * @return void
     */
    public function test_export_suplemen_mapping()
    {
        // Arrange: Buat data test
        $suplemen = Suplemen::factory()->create([
            'nama' => 'Test Suplemen',
            'slug' => 'test-suplemen',
            'sasaran' => 1,
            'keterangan' => 'Test Keterangan'
        ]);

        // Act: Buat instance export dan test mapping
        $export = new ExportSuplemen([]);
        $mappedData = $export->map($suplemen);

        // Assert: Periksa struktur data yang dimapping
        $this->assertIsArray($mappedData);
        $this->assertEquals($suplemen->id, $mappedData[0]);
        $this->assertEquals('Test Suplemen', $mappedData[1]);
        $this->assertEquals('test-suplemen', $mappedData[2]);
        $this->assertEquals('Penduduk', $mappedData[3]);
        $this->assertEquals('Test Keterangan', $mappedData[4]);
    }

    /**
     * Test export headings suplemen terdata.
     *
     * @return void
     */
    public function test_export_suplemen_terdata_headings()
    {
        // Act: Buat instance export
        $export = new ExportSuplemenTerdata();
        $headings = $export->headings();

        // Assert: Periksa headings
        $expectedHeadings = [
            'ID',
            'Nama Suplemen',
            'NIK',
            'Nama Penduduk',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Umur',
            'Alamat',
            'Desa',
            'Keterangan Suplemen',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];

        $this->assertEquals($expectedHeadings, $headings);
    }

    /**
     * Test export styles suplemen.
     *
     * @return void
     */
    public function test_export_suplemen_styles()
    {
        // Act: Buat instance export
        $export = new ExportSuplemen([]);

        // Buat mock worksheet
        $worksheet = $this->createMock(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::class);

        // Assert: Method ada dan mengembalikan array styles
        $styles = $export->styles($worksheet);
        $this->assertIsArray($styles);
    }
}
