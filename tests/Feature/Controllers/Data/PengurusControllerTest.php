<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

use App\Enums\Status;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\Agama;
use App\Models\Jabatan;
use App\Models\PendidikanKK;
use App\Models\Pengurus;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
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
});

test('index displays pengurus list view', function () {
    // Mock the database query for index
    $mockPengurus = $this->mock(Pengurus::class);
    $mockPengurus->shouldReceive('where->orderBy->get')->andReturn(collect());

    $response = $this->get(route('data.pengurus.index'));

    $response->assertStatus(200);
    $response->assertViewIs('data.pengurus.index');
    $response->assertViewHas(['page_title', 'page_description']);
});

test('index returns datatables json for ajax request', function () {
    // Mock the database query for ajax
    $mockPengurus = $this->mock(Pengurus::class);
    $mockPengurus->shouldReceive('where->orderBy->get')->andReturn(collect());

    $response = $this->getJson(route('data.pengurus.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ]);

    $response->assertStatus(200);
});

test('create displays create form', function () {
    $response = $this->get(route('data.pengurus.create'));

    $response->assertStatus(200);
    $response->assertViewIs('data.pengurus.create');
    $response->assertViewHas(['page_title', 'page_description', 'pendidikan', 'agama', 'jabatan', 'atasan', 'pengurus']);
});

test('store creates new pengurus successfully', function () {
    $jabatan = Jabatan::factory()->create();
    $pendidikan = PendidikanKK::factory()->create();
    $agama = Agama::factory()->create();

    $pengurusData = [
        'nama' => 'John Doe',
        'nik' => '1234567890123456',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '1990-01-01',
        'sex' => 1,
        'masa_jabatan' => '2024-2029',
        'jabatan_id' => $jabatan->id,
        'pendidikan_id' => $pendidikan->id,
        'agama_id' => $agama->id,
    ];

    $response = $this->from(route('data.pengurus.create'))->post(route('data.pengurus.store'), $pengurusData);

    $response->assertRedirect(route('data.pengurus.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('das_pengurus', [
        'nama' => 'John Doe',
        'nik' => '1234567890123456',
    ]);
});

test('store creates pengurus with file upload', function () {
    $jabatan = Jabatan::factory()->create();
    $pendidikan = PendidikanKK::factory()->create();
    $agama = Agama::factory()->create();

    $file = UploadedFile::fake()->image('foto.jpg');

    $pengurusData = [
        'nama' => 'Jane Doe',
        'nik' => '1234567890123457',
        'tempat_lahir' => 'Bandung',
        'tanggal_lahir' => '1992-02-02',
        'sex' => 2,
        'masa_jabatan' => '2024-2029',
        'jabatan_id' => $jabatan->id,
        'pendidikan_id' => $pendidikan->id,
        'agama_id' => $agama->id,
        'foto' => $file,
    ];

    $response = $this->from(route('data.pengurus.create'))->post(route('data.pengurus.store'), $pengurusData);

    $response->assertRedirect(route('data.pengurus.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('das_pengurus', [
        'nama' => 'Jane Doe',
        'nik' => '1234567890123457',
    ]);
});

test('store fails with duplicate nik', function () {
    $existingPengurus = Pengurus::factory()->create(['nik' => '1234567890123456']);
    $jabatan = Jabatan::factory()->create();

    $pengurusData = [
        'nama' => 'Duplicate NIK',
        'nik' => '1234567890123456', // Same NIK
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '1990-01-01',
        'sex' => 1,
        'masa_jabatan' => '2024-2029',
        'jabatan_id' => $jabatan->id,
    ];

    $response = $this->from(route('data.pengurus.create'))
        ->post(route('data.pengurus.store'), $pengurusData);

    $response->assertRedirect(route('data.pengurus.create'));
    $response->assertSessionHasErrors('nik');
});

test('edit displays edit form', function () {
    $pengurus = Pengurus::factory()->create();

    $response = $this->get(route('data.pengurus.edit', $pengurus->id));

    $response->assertStatus(200);
    $response->assertViewIs('data.pengurus.edit');
    $response->assertViewHas(['pengurus', 'page_title', 'page_description', 'pendidikan', 'agama', 'jabatan', 'atasan']);
    expect($response->viewData('pengurus')->id)->toBe($pengurus->id);
});

test('edit fails for non-existent pengurus', function () {
    $response = $this->get(route('data.pengurus.edit', 999999));

    $response->assertStatus(404);
});

test('update modifies pengurus successfully', function () {
    $pengurus = Pengurus::factory()->create();
    $newJabatan = Jabatan::factory()->create();

    $updateData = [
        'nama' => 'Updated Name',
        'nik' => $pengurus->nik, // Keep same NIK for this test
        'tempat_lahir' => 'Updated City',
        'tanggal_lahir' => '1991-03-03',
        'sex' => 1,
        'masa_jabatan' => '2024-2029',
        'jabatan_id' => $newJabatan->id,
        'pendidikan_id' => $pengurus->pendidikan_id,
        'agama_id' => $pengurus->agama_id,
    ];

    $response = $this->put(route('data.pengurus.update', $pengurus->id), $updateData);

    $response->assertRedirect(route('data.pengurus.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('das_pengurus', [
        'id' => $pengurus->id,
        'nama' => 'Updated Name',
        'tempat_lahir' => 'Updated City',
    ]);
});

test('update fails for non-existent pengurus', function () {
    $jabatan = Jabatan::factory()->create();
    $pendidikan = PendidikanKK::factory()->create();
    $agama = Agama::factory()->create();

    $response = $this->put(route('data.pengurus.update', 999999), [
        'nama' => 'Test Name',
        'nik' => '1234567890123456',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '1990-01-01',
        'sex' => 1,
        'masa_jabatan' => '2024-2029',
        'jabatan_id' => $jabatan->id,
        'pendidikan_id' => $pendidikan->id,
        'agama_id' => $agama->id,
    ]);

    $response->assertStatus(404);
});

test('destroy deletes pengurus successfully', function () {
    $pengurus = Pengurus::factory()->create();

    $response = $this->delete(route('data.pengurus.destroy', $pengurus->id));

    $response->assertRedirect(route('data.pengurus.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('das_pengurus', ['id' => $pengurus->id]);
});

test('lock activates pengurus', function () {
    $pengurus = Pengurus::factory()->create(['status' => Status::TidakAktif]);

    $response = $this->post(route('data.pengurus.lock', [$pengurus->id, Status::Aktif]));

    $response->assertRedirect(route('data.pengurus.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('das_pengurus', [
        'id' => $pengurus->id,
        'status' => Status::Aktif,
    ]);
});

test('lock deactivates pengurus', function () {
    $pengurus = Pengurus::factory()->create(['status' => Status::Aktif]);

    $response = $this->post(route('data.pengurus.lock', [$pengurus->id, Status::TidakAktif]));

    $response->assertRedirect(route('data.pengurus.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('das_pengurus', [
        'id' => $pengurus->id,
        'status' => Status::TidakAktif,
    ]);
});

test('lock prevents multiple active camat', function () {
    $camatJabatan = Jabatan::where('jenis', 1)->first() ?? Jabatan::factory()->create(['jenis' => 1]); // JenisJabatan::Camat
    Pengurus::factory()->create(['jabatan_id' => $camatJabatan->id, 'status' => Status::Aktif]);

    $anotherPengurus = Pengurus::factory()->create(['jabatan_id' => $camatJabatan->id, 'status' => Status::TidakAktif]);

    $response = $this->post(route('data.pengurus.lock', [$anotherPengurus->id, Status::Aktif]));

    $response->assertRedirect(route('data.pengurus.index'));
    $response->assertSessionHas('error', 'Camat aktif sudah ditetapkan!');
});

test('lock prevents multiple active sekretaris', function () {
    $sekretarisJabatan = Jabatan::where('jenis', 2)->first() ?? Jabatan::factory()->create(['jenis' => 2]); // JenisJabatan::Sekretaris
    Pengurus::factory()->create(['jabatan_id' => $sekretarisJabatan->id, 'status' => Status::Aktif]);

    $anotherPengurus = Pengurus::factory()->create(['jabatan_id' => $sekretarisJabatan->id, 'status' => Status::TidakAktif]);

    $response = $this->post(route('data.pengurus.lock', [$anotherPengurus->id, Status::Aktif]));

    $response->assertRedirect(route('data.pengurus.index'));
    $response->assertSessionHas('error', 'Sekretaris aktif sudah ditetapkan!');
});

test('bagan displays bagan view', function () {
    $response = $this->get(route('data.pengurus.bagan'));

    $response->assertStatus(200);
    $response->assertViewIs('data.pengurus.bagan');
    $response->assertViewHas(['page_title', 'page_description']);
});

test('ajax bagan returns json data', function () {
    Pengurus::factory()->create();

    $response = $this->getJson(route('data.pengurus.ajax-bagan'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'nodes']);
});
