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

use App\Models\KategoriLembaga;
use App\Models\Lembaga;
use App\Models\Penduduk;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withViewErrors([]);
    $this->withoutMiddleware([
        \App\Http\Middleware\Authenticate::class,
        \Spatie\Permission\Middleware\PermissionMiddleware::class,
        \Spatie\Permission\Middleware\RoleMiddleware::class,
        \App\Http\Middleware\CompleteProfile::class,
        \App\Http\Middleware\GlobalShareMiddleware::class,
    ]);
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

test('it can show create lembaga page (local mode)', function () {
    $penduduk = getLembagaPenduduk();
    $kategori = getLembagaKategori();

    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );

    $response = get(route('data.lembaga.create'));

    $response->assertStatus(200);
    $response->assertSee('Tambah Lembaga');
    $response->assertSee('penduduk_id');
});

test('it can show create lembaga page (gabungan mode)', function () {
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'http://localhost:8000']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'test-key']
    );

    $response = get(route('data.lembaga.create'));

    $response->assertStatus(200);
    $response->assertSee('Tambah Lembaga');
    $response->assertSee('penduduk_id_gabungan');
});

test('it can show edit lembaga page (gabungan mode)', function () {
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'http://localhost:8000']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'test-key']
    );

    Http::fake([
        'http://localhost:8000/*' => Http::response([
            'data' => [
                [
                    'id' => 1,
                    'attributes' => [
                        'nama' => 'John Doe',
                        'nik' => '1234567890123456',
                    ],
                ],
            ],
        ], 200),
    ]);

    $penduduk = getLembagaPenduduk();
    $kategori = getLembagaKategori();
    $lembaga = Lembaga::factory()->create([
        'lembaga_kategori_id' => $kategori->id,
        'penduduk_id' => $penduduk->id,
    ]);

    $response = get(route('data.lembaga.edit', $lembaga->id));

    $response->assertStatus(200);
    $response->assertSee('Ubah Lembaga');
    $response->assertSee('penduduk_id_gabungan');
});

test('it can create lembaga in gabungan mode with integer penduduk_id', function () {
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );

    $penduduk = getLembagaPenduduk();
    $kategori = getLembagaKategori();

    $response = post(route('data.lembaga.store'), [
        'nama' => 'Lembaga Test Gabungan',
        'kode' => 'LTG-' . uniqid(),
        'lembaga_kategori_id' => $kategori->id,
        'penduduk_id_gabungan' => $penduduk->id,
    ]);

    $response->assertRedirect(route('data.lembaga.index'));
    $this->assertDatabaseHas('das_lembaga', [
        'nama' => 'Lembaga Test Gabungan',
        'lembaga_kategori_id' => $kategori->id,
        'penduduk_id_gabungan' => $penduduk->id,
    ]);
});

test('it can update lembaga in gabungan mode with integer penduduk_id', function () {
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );

    $penduduk = getLembagaPenduduk();
    $kategori = getLembagaKategori();
    $lembaga = Lembaga::factory()->create([
        'lembaga_kategori_id' => $kategori->id,
        'penduduk_id_gabungan' => $penduduk->id,
    ]);

    $newPenduduk = getLembagaPenduduk();

    $response = put(route('data.lembaga.update', $lembaga->id), [
        'nama' => 'Lembaga Updated Gabungan',
        'kode' => $lembaga->kode,
        'lembaga_kategori_id' => $kategori->id,
        'penduduk_id_gabungan' => $newPenduduk->id,
    ]);

    $response->assertRedirect(route('data.lembaga.index'));
    $this->assertDatabaseHas('das_lembaga', [
        'id' => $lembaga->id,
        'nama' => 'Lembaga Updated Gabungan',
        'penduduk_id_gabungan' => $newPenduduk->id,
    ]);
});
