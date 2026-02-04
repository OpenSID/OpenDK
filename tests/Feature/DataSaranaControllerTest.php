<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

use App\Enums\KategoriSarana;
use App\Http\Controllers\Data\DataSaranaController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Imports\ImportDataSarana;
use App\Models\DataDesa;
use App\Models\DataSarana;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withViewErrors([]);
    $this->withoutMiddleware([
        Authenticate::class,
        RoleMiddleware::class,
        PermissionMiddleware::class,
        CompleteProfile::class,
        GlobalShareMiddleware::class,
    ]);
    
    // disabled database gabungan for testing
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );
    
    // Create test data
    $this->desa = DataDesa::factory()->create();
    $this->sarana = DataSarana::factory()->create([
        'desa_id' => $this->desa->desa_id,
        'kategori' => KategoriSarana::PUSKESMAS,
    ]);
});

test('index displays the data sarana index view', function () {
    $response = $this->get(route('data.data-sarana.index'));

    $response->assertStatus(200);
    $response->assertViewIs('data.data_sarana.index');
    $response->assertViewHas('page_title', 'Data Sarana');
    $response->assertViewHas('page_description', 'Daftar Sarana Desa');
    $response->assertViewHas('desaSelect');
});

test('getData returns json response for datatable', function () {
    $response = $this->getJson(route('data.data-sarana.getdata'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'draw',
        'recordsTotal',
        'recordsFiltered',
        'data',
    ]);
});

test('getData with excel action triggers download', function () {
    Excel::fake();
    
    $params = json_encode([
        'search' => ['value' => ''],
        'length' => 10,
        'start' => 0,
    ]);
    
    $response = $this->get(route('data.data-sarana.getdata') . '?action=excel&params=' . urlencode($params));
    
    Excel::assertDownloaded('Data-sarana.xlsx');
});

test('create displays the create form', function () {
    $response = $this->get(route('data.data-sarana.create'));

    $response->assertStatus(200);
    $response->assertViewIs('data.data_sarana.create');
    $response->assertViewHas('page_title', 'Tambah Sarana');
    $response->assertViewHas('page_description', 'Form tambah data sarana');
    $response->assertViewHas('desas');
});

test('store saves new data sarana with valid data', function () {
    $data = [
        'desa_id' => $this->desa->desa_id,
        'nama' => 'Puskesmas Test',
        'jumlah' => 1,
        'kategori' => KategoriSarana::PUSKESMAS,
        'keterangan' => 'Test keterangan',
    ];

    $response = $this->post(route('data.data-sarana.store'), $data);

    $response->assertRedirect(route('data.data-sarana.index'));
    $response->assertSessionHas('success', 'Data Sarana berhasil disimpan!');
    
    $this->assertDatabaseHas('das_data_sarana', [
        'nama' => 'Puskesmas Test',
        'jumlah' => 1,
        'kategori' => KategoriSarana::PUSKESMAS,
    ]);
});

test('store fails with invalid data', function () {
    $data = [
        'desa_id' => '',
        'nama' => '',
        'jumlah' => -1,
        'kategori' => '',
        'keterangan' => '',
    ];

    $response = $this->post(route('data.data-sarana.store'), $data);

    $response->assertSessionHasErrors(['desa_id', 'nama', 'jumlah', 'kategori', 'keterangan']);
});

test('edit displays the edit form with existing data', function () {
    $response = $this->get(route('data.data-sarana.edit', $this->sarana->id));

    $response->assertStatus(200);
    $response->assertViewIs('data.data_sarana.edit');
    $response->assertViewHas('page_title', 'Edit Data Sarana');
    $response->assertViewHas('page_description', 'Ubah informasi sarana desa');
    $response->assertViewHas('sarana');
    $response->assertViewHas('desas');
});

test('update updates existing data sarana with valid data', function () {
    $data = [
        'desa_id' => $this->desa->desa_id,
        'nama' => 'Puskesmas Updated',
        'jumlah' => 2,
        'kategori' => KategoriSarana::POSYANDU,
        'keterangan' => 'Updated keterangan',
    ];

    $response = $this->put(route('data.data-sarana.update', $this->sarana->id), $data);

    $response->assertRedirect(route('data.data-sarana.index'));
    $response->assertSessionHas('success', 'Data Sarana berhasil diperbarui');
    
    $this->assertDatabaseHas('das_data_sarana', [
        'id' => $this->sarana->id,
        'nama' => 'Puskesmas Updated',
        'jumlah' => 2,
        'kategori' => KategoriSarana::POSYANDU,
    ]);
});

test('update fails with invalid data', function () {
    $data = [
        'desa_id' => '',
        'nama' => '',
        'jumlah' => -1,
        'kategori' => '',
        'keterangan' => '',
    ];

    $response = $this->put(route('data.data-sarana.update', $this->sarana->id), $data);

    $response->assertSessionHasErrors(['desa_id', 'nama', 'jumlah', 'kategori', 'keterangan']);
});

test('update fails for non-existent sarana', function () {
    $data = [
        'desa_id' => $this->desa->desa_id,
        'nama' => 'Puskesmas Updated',
        'jumlah' => 2,
        'kategori' => KategoriSarana::POSYANDU,
        'keterangan' => 'Updated keterangan',
    ];

    $response = $this->put(route('data.data-sarana.update', 999999), $data);

    $response->assertStatus(404);
});

test('destroy deletes existing data sarana', function () {
    $response = $this->delete(route('data.data-sarana.destroy', $this->sarana->id));

    $response->assertRedirect(route('data.data-sarana.index'));
    $response->assertSessionHas('success', 'Data Sarana berhasil dihapus');
    
    $this->assertDatabaseMissing('das_data_sarana', [
        'id' => $this->sarana->id,
    ]);
});

test('destroy fails for non-existent sarana', function () {
    $response = $this->delete(route('data.data-sarana.destroy', 999999));

    $response->assertStatus(404);
});

test('import displays the import form', function () {
    $response = $this->get(route('data.data-sarana.import'));

    $response->assertStatus(200);
    $response->assertViewIs('data.data_sarana.import');
    $response->assertViewHas('page_title', 'Import Sarana');
    $response->assertViewHas('page_description', 'Upload data sarana');
});

test('importExcel processes valid file', function () {
    Excel::fake();
    
    Storage::fake('local');
    $file = UploadedFile::fake()->create('data_sarana.xlsx', 100, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    
    $response = $this->post(route('data.data-sarana.import-excel'), [
        'file' => $file,
    ]);
    
    $response->assertRedirect(route('data.data-sarana.index'));
    $response->assertSessionHas('success', 'Data Sarana berhasil diimport');
    
    Excel::assertImported('data_sarana.xlsx', function (ImportDataSarana $import) {
        return $import->type === 'local';
    });
});

test('importExcel fails with invalid file', function () {
    $response = $this->post(route('data.data-sarana.import-excel'), [
        'file' => null,
    ]);
    
    $response->assertSessionHasErrors(['file']);
});

test('importExcel fails with wrong file type', function () {
    Storage::fake('local');
    $file = UploadedFile::fake()->create('data_sarana.txt', 100, 'text/plain');
    
    $response = $this->post(route('data.data-sarana.import-excel'), [
        'file' => $file,
    ]);
    
    $response->assertSessionHasErrors(['file']);
});

test('getData includes desa relationship', function () {
    $response = $this->getJson(route('data.data-sarana.getdata'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ]);

    $response->assertStatus(200);
    $data = $response->json('data');
    
    // Check if the first item has desa information
    if (!empty($data)) {
        $this->assertArrayHasKey('desa', $data[0]);
    }
});

test('getData includes formatted kategori', function () {
    $response = $this->getJson(route('data.data-sarana.getdata'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ]);

    $response->assertStatus(200);
    $data = $response->json('data');
    
    // Check if the first item has formatted kategori
    if (!empty($data)) {
        $this->assertEquals('Puskesmas', $data[0]['kategori']);
    }
});

test('getData includes action buttons', function () {
    $response = $this->getJson(route('data.data-sarana.getdata'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ]);

    $response->assertStatus(200);
    $data = $response->json('data');
    
    // Check if the first item has action buttons
    if (!empty($data)) {
        $this->assertArrayHasKey('aksi', $data[0]);
        $this->assertStringContains('data.data-sarana.edit', $data[0]['aksi']);
        $this->assertStringContains('data.data-sarana.destroy', $data[0]['aksi']);
    }
});