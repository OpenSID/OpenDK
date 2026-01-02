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
use App\Models\KategoriLembaga;
use App\Models\Lembaga;
use App\Models\LembagaAnggota;
use App\Models\Penduduk;
use App\Models\SettingAplikasi;
use Faker\Generator;
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

function getLembagaPenduduk()
{
    $penduduk = Penduduk::inRandomOrder()->first();
    if (!$penduduk) {
        Penduduk::factory()->create();
        $penduduk = Penduduk::inRandomOrder()->first();
    }
    return $penduduk;
}

function getLembagaKategori()
{
    $kategori = KategoriLembaga::inRandomOrder()->first();
    if (!$kategori) {
        KategoriLembaga::factory()->create();
        $kategori = KategoriLembaga::inRandomOrder()->first();
    }
    return $kategori;
}

test('it can create lembaga anggota', function () {
    $penduduk = getLembagaPenduduk();
    $kategori = getLembagaKategori();
    $lembaga = Lembaga::factory()->create([
        'lembaga_kategori_id' => $kategori->id,
        'penduduk_id' => $penduduk->id,
    ]);

    $response = $this->post(route('data.lembaga_anggota.store', $lembaga->slug), [
        'penduduk_id' => $penduduk->id,
        'no_anggota' => '001',
        'jabatan_id' => 1,
        'no_sk_jabatan' => 'SK001',
        'no_sk_pengangkatan' => 'SKP001',
        'tgl_sk_pengangkatan' => now()->toDateString(),
        'no_sk_pemberhentian' => null,
        'tgl_sk_pemberhentian' => null,
        'periode' => '2024-2025',
        'keterangan' => 'Keterangan test',
    ]);

    $response->assertRedirect(route('data.lembaga_anggota.index', $lembaga->slug));
    $this->assertDatabaseHas('das_lembaga_anggota', [
        'lembaga_id' => $lembaga->id,
        'penduduk_id' => $penduduk->id,
        'no_anggota' => '001',
    ]);
});

test('it can update lembaga anggota', function () {
    $penduduk = getLembagaPenduduk();
    $kategori = getLembagaKategori();
    $lembaga = Lembaga::factory()->create([
        'lembaga_kategori_id' => $kategori->id,
        'penduduk_id' => $penduduk->id,
    ]);
    $anggota = LembagaAnggota::factory()->create([
        'lembaga_id' => $lembaga->id,
        'penduduk_id' => $penduduk->id,
    ]);

    $jabatanBaru = (new Generator())->numberBetween(1, 5);
    $response = $this->put(route('data.lembaga_anggota.update', [$lembaga->slug, $anggota->id]), [
        'no_anggota' => '002',
        'jabatan_id' => $jabatanBaru,
    ]);

    $response->assertRedirect(route('data.lembaga_anggota.index', $lembaga->slug));
    $this->assertDatabaseHas('das_lembaga_anggota', [
        'id' => $anggota->id,
        'no_anggota' => '002',
        'jabatan' => $jabatanBaru,
    ]);
});

test('it can delete lembaga anggota', function () {
    $penduduk = getLembagaPenduduk();
    $kategori = getLembagaKategori();
    $lembaga = Lembaga::factory()->create([
        'lembaga_kategori_id' => $kategori->id,
        'penduduk_id' => $penduduk->id,
    ]);
    $anggota = LembagaAnggota::factory()->create([
        'lembaga_id' => $lembaga->id,
        'penduduk_id' => $penduduk->id,
    ]);

    $response = $this->delete(route('data.lembaga_anggota.destroy', [$lembaga->slug, $anggota->id]));

    $response->assertRedirect(route('data.lembaga_anggota.index', $lembaga->slug));
    $this->assertDatabaseMissing('das_lembaga_anggota', [
        'id' => $anggota->id,
    ]);
});
