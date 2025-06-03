<?php

namespace Tests\Feature;

use App\Exports\ExportPenduduk;
use App\Models\Penduduk;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class PendudukExportTest extends TestCase
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
     * A basic feature test example.
     *
     * @return void
     */
    public function test_export_excel()
    {
        // Act: Export the users
        Excel::fake(); // Fake the Excel facade

        $this->get('/data/penduduk/export-excel'); // Assuming this is the route for exporting users

        // Assert: Check that the export was called
        Excel::assertDownloaded('data-penduduk.xlsx', function (ExportPenduduk $export) {
            $pendudukCount = Penduduk::count();            
            return $export->collection()->count() == $pendudukCount; // Check if the exported data has 3 users
        });
    }
}
