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

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\DataDesa;
use App\Models\Penduduk;
use App\Models\SettingAplikasi;
use App\Models\Suplemen;
use App\Models\SuplemenTerdata;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

uses(DatabaseTransactions::class);

function aktifkanDatabaseGabungan(bool $aktif = true): void
{
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => $aktif ? '1' : '0']
    );

    if ($aktif) {
        SettingAplikasi::updateOrCreate(
            ['key' => 'api_server_database_gabungan'],
            ['value' => 'https://api.example.com']
        );
        SettingAplikasi::updateOrCreate(
            ['key' => 'api_key_database_gabungan'],
            ['value' => 'test-api-key']
        );
    }
}

/**
 * Fake seluruh panggilan API database gabungan.
 *
 * Constructor controller memanggil DesaService::listDesa() sehingga setiap
 * request saat mode gabungan aktif wajib memiliki stub catch-all.
 */
function fakeGabunganApi(array $routes = []): void
{
    Http::fake(array_merge($routes, [
        '*' => Http::response([
            'data' => [],
            'meta' => ['pagination' => ['total' => 0]],
        ], 200),
    ]));
}

beforeEach(function () {
    $this->withViewErrors([]);
    $this->withoutMiddleware([
        Authenticate::class,
        RoleMiddleware::class,
        PermissionMiddleware::class,
        CompleteProfile::class,
        GlobalShareMiddleware::class,
    ]);

    aktifkanDatabaseGabungan(false);

    $this->suplemen = Suplemen::create([
        'nama' => 'Suplemen Gabungan Test',
        'slug' => 'suplemen-gabungan-test',
        'sasaran' => 1,
        'keterangan' => 'Testing database gabungan',
    ]);
});

test('show menampilkan view gabungan saat database gabungan aktif', function () {
    aktifkanDatabaseGabungan(true);
    fakeGabunganApi();

    $response = $this->get(route('data.data-suplemen.show', $this->suplemen->id));

    $response->assertStatus(200);
    $response->assertViewIs('data.data_suplemen.gabungan.show');
    $response->assertViewHas('suplemen', fn (Suplemen $suplemen) => $suplemen->id === $this->suplemen->id);
});

test('show menampilkan view lokal saat database gabungan tidak aktif', function () {
    $response = $this->get(route('data.data-suplemen.show', $this->suplemen->id));

    $response->assertStatus(200);
    $response->assertViewIs('data.data_suplemen.show');
    $response->assertViewHas('suplemen', fn (Suplemen $suplemen) => $suplemen->id === $this->suplemen->id);
});

test('createDetail menampilkan view gabungan saat database gabungan aktif', function () {
    aktifkanDatabaseGabungan(true);
    fakeGabunganApi();

    $response = $this->get(route('data.data-suplemen.createdetail', $this->suplemen->id));

    $response->assertStatus(200);
    $response->assertViewIs('data.data_suplemen.gabungan.create_detail');
    $response->assertViewHas('isDatabaseGabungan', true);
    $response->assertSee('api.example.com/api/v1/opendk/sync-penduduk-opendk');
    $response->assertSee('filter[kode_desa]');
    $response->assertSee('select2({');
    $response->assertSee('minimumInputLength: 0');
    $response->assertSee("$('#penduduk_id_gabungan')", false);
});

test('createDetail menampilkan view lokal saat database gabungan tidak aktif', function () {
    $response = $this->get(route('data.data-suplemen.createdetail', $this->suplemen->id));

    $response->assertStatus(200);
    $response->assertViewIs('data.data_suplemen.create_detail');
    $response->assertViewHas('isDatabaseGabungan', false);
});

test('storeDetail menyimpan penduduk dari database gabungan saat gabungan aktif', function () {
    aktifkanDatabaseGabungan(true);
    fakeGabunganApi();

    $response = $this->post(route('data.data-suplemen.storedetail'), [
        'suplemen_id' => $this->suplemen->id,
        'desa_id' => '3301010001',
        'penduduk_id_gabungan' => 99,
        'keterangan' => 'Anggota dari gabungan',
    ]);

    $response->assertRedirect(route('data.data-suplemen.show', $this->suplemen->id));
    $response->assertSessionHas('success', 'Anggota Suplemen berhasil ditambah!');

    $this->assertDatabaseHas('das_suplemen_terdata', [
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id' => null,
        'penduduk_id_gabungan' => 99,
        'desa_id' => '3301010001',
        'keterangan' => 'Anggota dari gabungan',
    ]);
});

test('storeDetail menyimpan penduduk lokal saat database gabungan tidak aktif', function () {
    $penduduk = Penduduk::factory()->create();

    $response = $this->post(route('data.data-suplemen.storedetail'), [
        'suplemen_id' => $this->suplemen->id,
        'desa_id' => $penduduk->desa_id,
        'penduduk_id' => $penduduk->id,
        'keterangan' => 'Anggota lokal',
    ]);

    $response->assertRedirect(route('data.data-suplemen.show', $this->suplemen->id));
    $response->assertSessionHas('success', 'Anggota Suplemen berhasil ditambah!');

    $this->assertDatabaseHas('das_suplemen_terdata', [
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id' => $penduduk->id,
        'penduduk_id_gabungan' => null,
        'desa_id' => $penduduk->desa_id,
    ]);
});

test('storeDetail gagal ketika penduduk lokal dan gabungan keduanya diisi', function () {
    $penduduk = Penduduk::factory()->create();

    $response = $this->post(route('data.data-suplemen.storedetail'), [
        'suplemen_id' => $this->suplemen->id,
        'desa_id' => $penduduk->desa_id,
        'penduduk_id' => $penduduk->id,
        'penduduk_id_gabungan' => 99,
    ]);

    $response->assertSessionHasErrors(['penduduk_id_gabungan']);
});

test('storeDetail gagal ketika desa_id tidak diisi', function () {
    $penduduk = Penduduk::factory()->create();

    $response = $this->post(route('data.data-suplemen.storedetail'), [
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id' => $penduduk->id,
    ]);

    $response->assertSessionHasErrors(['desa_id']);
});

test('getDataSuplemenTerdata tidak menggabungkan anggota dari API database gabungan', function () {
    aktifkanDatabaseGabungan(true);
    fakeGabunganApi([
        'https://api.example.com/api/v1/opendk/suplemen-terdata-datatable/*' => Http::response([
            'data' => [
                [
                    'id' => 100,
                    'attributes' => [
                        'nama_desa' => 'Desa API',
                        'no_kk' => '1234567890123456',
                        'nik' => '1234567890123457',
                        'nama' => 'Anggota Dari API',
                        'tempat_lahir' => 'Jakarta',
                        'tanggal_lahir' => '1990-01-01',
                        'sex' => 1,
                        'alamat' => 'Alamat API',
                        'keterangan' => 'Terdata desa',
                    ],
                ],
            ],
            'meta' => ['pagination' => ['total' => 1]],
        ], 200),
    ]);

    $penduduk = Penduduk::factory()->create(['nama' => 'Anggota Lokal']);
    SuplemenTerdata::create([
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id' => $penduduk->id,
        'desa_id' => $penduduk->desa_id,
        'keterangan' => 'Anggota lokal',
    ]);

    $response = $this->postJson(
        route('data.data-suplemen.getsuplementerdata', $this->suplemen->id),
        [],
        ['X-Requested-With' => 'XMLHttpRequest']
    );

    $response->assertStatus(200);
    $data = collect($response->json('data'));

    expect($data->pluck('keterangan'))->toContain('Anggota lokal')
        ->not->toContain('Terdata desa')
        ->and($data->pluck('penduduk.nama'))->toContain('Anggota Lokal')
        ->not->toContain('Anggota Dari API');
});

test('getDataSuplemenTerdata menyelesaikan penduduk gabungan lokal dalam satu request batch', function () {
    aktifkanDatabaseGabungan(true);

    $batchCalls = 0;
    $sentIds = null;

    Http::fake([
        'https://api.example.com/api/v1/opendk/sync-penduduk-opendk*' => function (\Illuminate\Http\Client\Request $request) use (&$batchCalls, &$sentIds) {
            $batchCalls++;
            parse_str((string) parse_url($request->url(), PHP_URL_QUERY), $query);
            $sentIds = $query['filter']['id_penduduk'] ?? null;

            return Http::response([
                'data' => [
                    ['id' => 500, 'attributes' => ['nama' => 'Penduduk Gabungan 1', 'nik' => '123', 'sex' => 1]],
                    ['id' => 501, 'attributes' => ['nama' => 'Penduduk Gabungan 2', 'nik' => '124', 'sex' => 2]],
                ],
                'meta' => ['pagination' => ['total' => 2]],
            ], 200);
        },
        'https://api.example.com/api/v1/opendk/suplemen-terdata-datatable/*' => Http::response([
            'data' => [],
            'meta' => ['pagination' => ['total' => 0]],
        ], 200),
        '*' => Http::response([
            'data' => [],
            'meta' => ['pagination' => ['total' => 0]],
        ], 200),
    ]);

    SuplemenTerdata::create([
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id_gabungan' => 500,
        'keterangan' => 'A1',
    ]);
    SuplemenTerdata::create([
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id_gabungan' => 501,
        'keterangan' => 'A2',
    ]);

    $response = $this->postJson(
        route('data.data-suplemen.getsuplementerdata', $this->suplemen->id),
        [],
        ['X-Requested-With' => 'XMLHttpRequest']
    );

    $response->assertStatus(200);
    $data = collect($response->json('data'));

    expect($batchCalls)->toBe(1)
        ->and($sentIds)->toContain('500')
        ->and($sentIds)->toContain('501')
        ->and($data->pluck('penduduk.nama'))->toContain('Penduduk Gabungan 1')
        ->and($data->pluck('penduduk.nama'))->toContain('Penduduk Gabungan 2');
});

test('getDataSuplemenTerdata mengabaikan item non-array pada respons batch', function () {
    aktifkanDatabaseGabungan(true);

    Http::fake([
        'https://api.example.com/api/v1/opendk/sync-penduduk-opendk*' => Http::response([
            'data' => [
                ['id' => 500, 'attributes' => ['nama' => 'Penduduk Gabungan 1', 'nik' => '123', 'sex' => 1]],
                false,
                null,
            ],
            'meta' => ['pagination' => ['total' => 3]],
        ], 200),
        'https://api.example.com/api/v1/opendk/suplemen-terdata-datatable/*' => Http::response([
            'data' => [],
            'meta' => ['pagination' => ['total' => 0]],
        ], 200),
        '*' => Http::response([
            'data' => [],
            'meta' => ['pagination' => ['total' => 0]],
        ], 200),
    ]);

    SuplemenTerdata::create([
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id_gabungan' => 500,
        'keterangan' => 'A1',
    ]);

    $response = $this->postJson(
        route('data.data-suplemen.getsuplementerdata', $this->suplemen->id),
        [],
        ['X-Requested-With' => 'XMLHttpRequest']
    );

    $response->assertStatus(200);
    $data = collect($response->json('data'));

    expect($data->pluck('penduduk.nama'))->toContain('Penduduk Gabungan 1');
});

test('getDataSuplemenTerdata memfilter anggota lokal berdasarkan desa', function () {
    aktifkanDatabaseGabungan(true);
    fakeGabunganApi();

    $desaA = DataDesa::create(['desa_id' => '3301010001', 'nama' => 'Desa A']);
    $desaB = DataDesa::create(['desa_id' => '3301010002', 'nama' => 'Desa B']);

    $pendudukA = Penduduk::factory()->create(['desa_id' => $desaA->desa_id, 'nama' => 'Warga Desa A']);
    $pendudukB = Penduduk::factory()->create(['desa_id' => $desaB->desa_id, 'nama' => 'Warga Desa B']);

    SuplemenTerdata::create([
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id' => $pendudukA->id,
        'desa_id' => $pendudukA->desa_id,
        'keterangan' => 'A',
    ]);
    SuplemenTerdata::create([
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id' => $pendudukB->id,
        'desa_id' => $pendudukB->desa_id,
        'keterangan' => 'B',
    ]);

    $response = $this->postJson(
        route('data.data-suplemen.getsuplementerdata', $this->suplemen->id),
        ['desa' => $desaA->desa_id],
        ['X-Requested-With' => 'XMLHttpRequest']
    );

    $response->assertStatus(200);
    $data = collect($response->json('data'));

    expect($data->pluck('penduduk.nama'))->toContain('Warga Desa A')
        ->not->toContain('Warga Desa B');
});

test('getDataSuplemenTerdata tanpa filter desa menampilkan semua anggota lokal', function () {
    $desaA = DataDesa::create(['desa_id' => '3301010001', 'nama' => 'Desa A']);
    $desaB = DataDesa::create(['desa_id' => '3301010002', 'nama' => 'Desa B']);

    $pendudukA = Penduduk::factory()->create(['desa_id' => $desaA->desa_id, 'nama' => 'Warga Desa A']);
    $pendudukB = Penduduk::factory()->create(['desa_id' => $desaB->desa_id, 'nama' => 'Warga Desa B']);

    SuplemenTerdata::create([
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id' => $pendudukA->id,
        'desa_id' => $pendudukA->desa_id,
    ]);
    SuplemenTerdata::create([
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id' => $pendudukB->id,
        'desa_id' => $pendudukB->desa_id,
    ]);

    $response = $this->postJson(
        route('data.data-suplemen.getsuplementerdata', $this->suplemen->id),
        ['desa' => 'Semua'],
        ['X-Requested-With' => 'XMLHttpRequest']
    );

    $response->assertStatus(200);
    $data = collect($response->json('data'));

    expect($data->pluck('penduduk.nama'))->toContain('Warga Desa A')
        ->toContain('Warga Desa B');
});

test('storeDetail menyimpan desa_id dari penduduk database gabungan', function () {
    aktifkanDatabaseGabungan(true);
    fakeGabunganApi([
        'https://api.example.com/api/v1/opendk/sync-penduduk-opendk*' => Http::response([
            'data' => [
                [
                    'id' => 99,
                    'attributes' => [
                        'nama' => 'Penduduk Gabungan',
                        'nik' => '123',
                        'sex' => 1,
                        'config' => ['kode_desa' => '3301010001', 'nama_desa' => 'Desa API'],
                    ],
                ],
            ],
            'meta' => ['pagination' => ['total' => 1]],
        ], 200),
    ]);

    $response = $this->post(route('data.data-suplemen.storedetail'), [
        'suplemen_id' => $this->suplemen->id,
        'desa_id' => '3301010001',
        'penduduk_id_gabungan' => 99,
        'keterangan' => 'Anggota dari gabungan',
    ]);

    $response->assertRedirect(route('data.data-suplemen.show', $this->suplemen->id));

    $this->assertDatabaseHas('das_suplemen_terdata', [
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id_gabungan' => 99,
        'desa_id' => '3301010001',
    ]);
});

test('storeDetail menyimpan desa_id dari penduduk lokal', function () {
    $penduduk = Penduduk::factory()->create(['desa_id' => '3301010003', 'nama' => 'Warga Lokal']);

    $response = $this->post(route('data.data-suplemen.storedetail'), [
        'suplemen_id' => $this->suplemen->id,
        'desa_id' => $penduduk->desa_id,
        'penduduk_id' => $penduduk->id,
        'keterangan' => 'Anggota lokal',
    ]);

    $response->assertRedirect(route('data.data-suplemen.show', $this->suplemen->id));

    $this->assertDatabaseHas('das_suplemen_terdata', [
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id' => $penduduk->id,
        'desa_id' => '3301010003',
    ]);
});

test('editDetail menampilkan view gabungan saat database gabungan aktif', function () {
    aktifkanDatabaseGabungan(true);
    fakeGabunganApi([
        'https://api.example.com/api/v1/opendk/sync-penduduk-opendk*' => Http::response([
            'data' => [
                [
                    'id' => 99,
                    'attributes' => [
                        'nama' => 'Penduduk Gabungan Edit',
                        'nik' => '1234567890123456',
                        'sex' => 1,
                        'config' => ['nama_desa' => 'Desa Edit'],
                    ],
                ],
            ],
            'meta' => ['pagination' => ['total' => 1]],
        ], 200),
    ]);

    $terdata = SuplemenTerdata::create([
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id_gabungan' => 99,
        'keterangan' => 'Anggota gabungan',
    ]);

    $response = $this->get(route('data.data-suplemen.editdetail', [$terdata->id, $this->suplemen->id]));

    $response->assertStatus(200);
    $response->assertViewIs('data.data_suplemen.gabungan.edit_detail');
    $response->assertViewHas('isDatabaseGabungan', true);
    $response->assertSee('Penduduk Gabungan Edit');
    $response->assertSee('sync-penduduk-opendk');
    $response->assertSee('filter[kode_desa]');
});

test('editDetail menampilkan view lokal saat database gabungan tidak aktif', function () {
    $penduduk = Penduduk::factory()->create(['nama' => 'Anggota Lokal Edit']);
    $terdata = SuplemenTerdata::create([
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id' => $penduduk->id,
        'keterangan' => 'Anggota lokal',
    ]);

    $response = $this->get(route('data.data-suplemen.editdetail', [$terdata->id, $this->suplemen->id]));

    $response->assertStatus(200);
    $response->assertViewIs('data.data_suplemen.edit_detail');
    $response->assertViewHas('isDatabaseGabungan', false);
});

test('updateDetail memperbarui anggota gabungan saat gabungan aktif', function () {
    aktifkanDatabaseGabungan(true);
    fakeGabunganApi();

    $terdata = SuplemenTerdata::create([
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id_gabungan' => 99,
        'keterangan' => 'Keterangan awal',
    ]);

    $response = $this->put(route('data.data-suplemen.updatedetail', $terdata->id), [
        'suplemen_id' => $this->suplemen->id,
        'desa_id' => '3301010001',
        'penduduk_id_gabungan' => 100,
        'keterangan' => 'Keterangan diubah',
    ]);

    $response->assertRedirect(route('data.data-suplemen.show', $this->suplemen->id));
    $response->assertSessionHas('success', 'Anggota Suplemen berhasil diubah!');

    $this->assertDatabaseHas('das_suplemen_terdata', [
        'id' => $terdata->id,
        'penduduk_id' => null,
        'penduduk_id_gabungan' => 100,
        'keterangan' => 'Keterangan diubah',
    ]);
});

test('destroyDetail menghapus anggota saat gabungan aktif', function () {
    aktifkanDatabaseGabungan(true);
    fakeGabunganApi();

    $terdata = SuplemenTerdata::create([
        'suplemen_id' => $this->suplemen->id,
        'penduduk_id_gabungan' => 99,
        'keterangan' => 'Anggota gabungan',
    ]);

    $response = $this->delete(route('data.data-suplemen.destroydetail', [$terdata->id, $this->suplemen->id]));

    $response->assertRedirect(route('data.data-suplemen.show', $this->suplemen->id));
    $response->assertSessionHas('success', 'Anggota Suplemen berhasil dihapus!');

    $this->assertDatabaseMissing('das_suplemen_terdata', ['id' => $terdata->id]);
});
