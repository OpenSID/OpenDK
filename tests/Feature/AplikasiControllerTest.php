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
use Illuminate\Foundation\Testing\DatabaseTransactions;
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

test('index displays settings', function () {
    $response = $this->get(route('setting.aplikasi.index'));

    $response->assertStatus(200);
    $response->assertViewIs('setting.aplikasi.index');
    $response->assertViewHas('page_title', 'Pengaturan Aplikasi');
});

test('edit displays edit form', function () {
    $setting = SettingAplikasi::factory()->create();

    $response = $this->get(route('setting.aplikasi.edit', $setting->id));

    $response->assertStatus(200);
    $response->assertViewIs('setting.aplikasi.edit');
    $response->assertViewHas('aplikasi', $setting);
});

test('update success', function () {
    $setting = SettingAplikasi::factory()->create(['value' => 'lama']);

    $response = $this->put(route('setting.aplikasi.update', $setting->id), [
        'value' => 'baru',
    ]);

    $response->assertRedirect(route('setting.aplikasi.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas($setting->getTable(), [
        'id' => $setting->id,
        'value' => 'baru',
    ]);
});

test('update validation error', function () {
    $setting = SettingAplikasi::factory()->create();

    $response = $this->from(route('setting.aplikasi.edit', $setting->id))
        ->put(route('setting.aplikasi.update', $setting->id), [
            'value' => '',
        ]);

    $response->assertRedirect(route('setting.aplikasi.edit', $setting->id));
    $response->assertInvalid(['value']); // Laravel 11 way
});
