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
use App\Models\JawabKomplain;
use App\Models\KategoriKomplain;
use App\Models\Komplain;
use App\Models\Penduduk;
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

function getPenduduk()
{
    $penduduk = Penduduk::inRandomOrder()->first();
    return $penduduk ?: Penduduk::factory()->create();
}

test('index', function () {
    $response = $this->get(route('admin-komplain.index'));

    $response->assertStatus(200);
    $response->assertViewIs('sistem_komplain.index');
});

test('get data komplain', function () {
    KategoriKomplain::factory()->create();
    Komplain::factory()->create();
    $response = $this->getJson(route('admin-komplain.getdata'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data']);
});

test('disetujui', function () {
    $komplain = Komplain::factory()->create(['status' => 'REVIEW']);
    $response = $this->put(route('admin-komplain.setuju', $komplain->id), [
        'status' => 'SELESAI'
    ]);

    $response->assertRedirect(route('admin-komplain.index'));
    $this->assertDatabaseHas('das_komplain', [
        'id' => $komplain->id,
        'status' => 'SELESAI'
    ]);
});

test('anonim', function () {
    $komplain = Komplain::factory()->create(['anonim' => 0]);
    $response = $this->put(route('admin-komplain.anonim', $komplain->id), [
        'anonim' => 1
    ]);

    $response->assertRedirect(route('admin-komplain.index'));
    $this->assertDatabaseHas('das_komplain', [
        'id' => $komplain->id,
        'anonim' => 1
    ]);
});

test('show', function () {
    $komplain = Komplain::factory()->create();
    $response = $this->get(route('admin-komplain.show', $komplain->id));

    $response->assertStatus(200);
    $response->assertViewIs('sistem_komplain.show');
});

test('edit', function () {
    $komplain = Komplain::factory()->create();
    $response = $this->get(route('admin-komplain.edit', $komplain->id));

    $response->assertStatus(200);
    $response->assertViewIs('sistem_komplain.edit');
});

test('update', function () {
    $kategori = KategoriKomplain::factory()->create();
    $penduduk = getPenduduk();
    $komplain = Komplain::factory()->create(['kategori' => $kategori->id, 'nik' => $penduduk->nik]);
    $response = $this->put(route('admin-komplain.update', $komplain->id), [
        'nik' => $komplain->nik,
        'judul' => 'Judul Baru',
        'kategori' => $kategori->id,
        'laporan' => 'Isi laporan baru',
    ]);

    $response->assertRedirect(route('admin-komplain.index'));
    $this->assertDatabaseHas('das_komplain', [
        'id' => $komplain->id,
        'judul' => 'Judul Baru'
    ]);
});

test('update komentar', function () {
    $penduduk = getPenduduk();
    $jawab = JawabKomplain::factory()->create(['penjawab' => $penduduk->nik]);
    $response = $this->put(route('admin-komplain.updatekomentar', $jawab->id), [
        'jawaban' => 'Jawaban baru'
    ]);

    $response->assertJson(['status' => 'success']);
    $this->assertDatabaseHas('das_jawab_komplain', [
        'id' => $jawab->id,
        'jawaban' => 'Jawaban baru'
    ]);
});

test('statistik', function () {
    $response = $this->get(route('admin-komplain.statistik'));

    $response->assertStatus(200);
    $response->assertViewIs('sistem_komplain.statistik');
});

test('get komentar', function () {
    $jawab = JawabKomplain::factory()->create();
    $response = $this->get(route('admin-komplain.getkomentar', $jawab->id));

    $response->assertJson(['status' => 'success']);
});

test('destroy', function () {
    $komplain = Komplain::factory()->create();
    $response = $this->delete(route('admin-komplain.destroy', $komplain->id));

    $response->assertRedirect(route('admin-komplain.index'));
    $this->assertDatabaseMissing('das_komplain', [
        'id' => $komplain->id
    ]);
});

test('delete komentar', function () {
    $jawab = JawabKomplain::factory()->create();
    $response = $this->delete(route('admin-komplain.deletekomentar', $jawab->id));

    $this->assertDatabaseMissing('das_jawab_komplain', [
        'id' => $jawab->id
    ]);
});
