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
use App\Models\DataDesa;
use App\Models\Pesan;
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

test('index', function () {
    Pesan::factory()->count(2)->create(['jenis' => Pesan::PESAN_MASUK]);
    $response = $this->get(route('pesan.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pesan.masuk.index');
});

test('load pesan keluar', function () {
    Pesan::factory()->count(2)->create(['jenis' => Pesan::PESAN_KELUAR]);
    $response = $this->get(route('pesan.keluar'));

    $response->assertStatus(200);
    $response->assertViewIs('pesan.keluar.index');
});

test('load pesan arsip', function () {
    Pesan::factory()->count(2)->create(['diarsipkan' => Pesan::MASUK_ARSIP]);
    $response = $this->get(route('pesan.arsip'));

    $response->assertStatus(200);
    $response->assertViewIs('pesan.arsip.index');
});

test('read pesan', function () {
    $pesan = Pesan::factory()->create(['sudah_dibaca' => Pesan::BELUM_DIBACA]);
    $response = $this->get(route('pesan.read', $pesan->id));

    $response->assertStatus(200);
    $response->assertViewIs('pesan.read_pesan');
    $this->assertDatabaseHas((new Pesan())->getTable(), [
        'id' => $pesan->id,
        'sudah_dibaca' => Pesan::SUDAH_DIBACA,
    ]);
});

test('compose pesan', function () {
    $response = $this->get(route('pesan.compose'));

    $response->assertStatus(200);
    $response->assertViewIs('pesan.compose_pesan');
});

test('store compose pesan success', function () {
    $desa = DataDesa::factory()->create();
    $response = $this->post(route('pesan.compose.post'), [
        'judul' => 'Judul Pesan',
        'das_data_desa_id' => $desa->desa_id,
        'text' => 'Isi pesan'
    ]);

    $response->assertRedirect(route('pesan.keluar'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas((new Pesan())->getTable(), [
        'judul' => 'Judul Pesan',
        'das_data_desa_id' => $desa->desa_id,
    ]);
});

test('store compose pesan failed', function () {
    $response = $this->post(route('pesan.compose.post'), []);

    $response->assertRedirect(url('/'));
});

test('set arsip pesan', function () {
    $pesan = Pesan::factory()->create(['diarsipkan' => Pesan::NON_ARSIP]);
    $response = $this->post(route('pesan.arsip.post'), ['id' => $pesan->id]);

    $response->assertRedirect(route('pesan.arsip'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas($pesan->getTable(), [
        'id' => $pesan->id,
        'diarsipkan' => Pesan::MASUK_ARSIP,
    ]);
});

test('set multiple read pesan status', function () {
    $pesan = Pesan::factory()->count(2)->create(['sudah_dibaca' => Pesan::BELUM_DIBACA]);
    $ids = $pesan->pluck('id')->toArray();
    $response = $this->post(route('pesan.read.multiple'), [
        'array_id' => json_encode($ids),
    ]);

    $response->assertSessionHas('success');
    foreach ($ids as $id) {
        $this->assertDatabaseHas((new Pesan())->getTable(), [
            'id' => $id,
            'sudah_dibaca' => Pesan::SUDAH_DIBACA,
        ]);
    }
});

test('set multiple arsip pesan status', function () {
    $pesan = Pesan::factory()->count(2)->create(['diarsipkan' => Pesan::NON_ARSIP]);
    $ids = $pesan->pluck('id')->toArray();
    $response = $this->post(route('pesan.arsip.multiple'), [
        'array_id' => json_encode($ids),
    ]);

    $response->assertSessionHas('success');
    foreach ($ids as $id) {
        $this->assertDatabaseHas((new Pesan())->getTable(), [
            'id' => $id,
            'diarsipkan' => Pesan::MASUK_ARSIP,
        ]);
    }
});
