<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\SettingAplikasi;
use App\Models\SinergiProgram;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
});

test('index', function () {
    $response = $this->get(route('informasi.sinergi-program.index'));

    $response->assertStatus(200);
    $response->assertViewIs('informasi.sinergi_program.index');
});

test('get data sinergi program', function () {
    SinergiProgram::factory()->count(2)->create();
    $response = $this->getJson(route('informasi.sinergi-program.getdata'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['data']);
});

test('create', function () {
    $response = $this->get(route('informasi.sinergi-program.create'));

    $response->assertStatus(200);
    $response->assertViewIs('informasi.sinergi_program.create');
});

test('store success', function () {
    Storage::fake('public');
    $data = [
        'nama' => 'Program Baru',
        'deskripsi' => 'Deskripsi program',
        'status' => 1,
        'urutan' => 1,
        'gambar' => UploadedFile::fake()->image('gambar.jpg'),
        'url' => 'https://example.com',
    ];

    $response = $this->post(route('informasi.sinergi-program.store'), $data);

    $response->assertRedirect(route('informasi.sinergi-program.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas((new SinergiProgram())->getTable(), [
        'nama' => 'Program Baru',
        'status' => 1,
    ]);
});

test('store failed', function () {
    $response = $this->post(route('informasi.sinergi-program.store'), []);

    $response->assertStatus(302); // Laravel 11: validation redirects back
});

test('edit', function () {
    $sinergi = SinergiProgram::factory()->create();
    $response = $this->get(route('informasi.sinergi-program.edit', $sinergi->id));

    $response->assertStatus(200);
    $response->assertViewIs('informasi.sinergi_program.edit');
});

test('update success', function () {
    Storage::fake('public');
    $sinergi = SinergiProgram::factory()->create(['nama' => 'Lama']);
    $data = [
        'nama' => 'Baru',
        'deskripsi' => 'Deskripsi update',
        'status' => 1,
        'urutan' => 2,
        'gambar' => UploadedFile::fake()->image('gambar2.jpg'),
        'url' => 'https://example.com',
    ];

    $response = $this->put(route('informasi.sinergi-program.update', $sinergi->id), $data);

    $response->assertRedirect(route('informasi.sinergi-program.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas($sinergi->getTable(), [
        'id' => $sinergi->id,
        'nama' => 'Baru',
    ]);
});

test('update failed', function () {
    $sinergi = SinergiProgram::factory()->create();
    $response = $this->put(route('informasi.sinergi-program.update', $sinergi->id), []);

    $response->assertStatus(302); // Laravel 11: validation redirects back
});

test('destroy success', function () {
    $sinergi = SinergiProgram::factory()->create();
    $response = $this->delete(route('informasi.sinergi-program.destroy', $sinergi->id));

    $response->assertRedirect(route('informasi.sinergi-program.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseMissing($sinergi->getTable(), [
        'id' => $sinergi->id,
    ]);
});
