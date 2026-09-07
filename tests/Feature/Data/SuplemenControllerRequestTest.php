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
use App\Models\Penduduk;
use App\Models\SettingAplikasi;
use App\Models\Suplemen;
use App\Models\SuplemenTerdata;
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

    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );
});

test('store membuat data suplemen dengan data valid', function () {
    $response = $this->post(route('data.data-suplemen.store'), [
        'nama' => 'Suplemen Valid',
        'sasaran' => '1',
        'keterangan' => 'Keterangan valid',
    ]);

    $response->assertRedirect(route('data.data-suplemen.index'));
    $response->assertSessionHas('success', 'Data Suplemen berhasil ditambah!');

    $this->assertDatabaseHas('das_suplemen', [
        'nama' => 'Suplemen Valid',
        'slug' => 'suplemen-valid',
        'sasaran' => '1',
    ]);
});

test('store gagal ketika nama kosong', function () {
    $response = $this->post(route('data.data-suplemen.store'), [
        'nama' => '',
        'sasaran' => '1',
    ]);

    $response->assertSessionHasErrors(['nama']);
});

test('store gagal ketika sasaran tidak valid', function () {
    $response = $this->post(route('data.data-suplemen.store'), [
        'nama' => 'Suplemen Sasaran',
        'sasaran' => '3',
    ]);

    $response->assertSessionHasErrors(['sasaran']);
});

test('update mengubah data suplemen saat data valid', function () {
    $suplemen = Suplemen::create([
        'nama' => 'Suplemen Lama',
        'slug' => 'suplemen-lama',
        'sasaran' => 1,
    ]);

    $response = $this->put(route('data.data-suplemen.update', $suplemen->id), [
        'nama' => 'Suplemen Baru',
        'sasaran' => '2',
        'keterangan' => 'Keterangan baru',
    ]);

    $response->assertRedirect(route('data.data-suplemen.index'));
    $response->assertSessionHas('success', 'Data Suplemen berhasil diubah!');

    $this->assertDatabaseHas('das_suplemen', [
        'id' => $suplemen->id,
        'nama' => 'Suplemen Baru',
        'slug' => 'suplemen-baru',
        'sasaran' => '2',
    ]);
});

test('storeDetail membuat anggota suplemen saat data valid', function () {
    $suplemen = Suplemen::create([
        'nama' => 'Suplemen Anggota',
        'slug' => 'suplemen-anggota',
        'sasaran' => 1,
    ]);
    $penduduk = Penduduk::factory()->create();

    $response = $this->post(route('data.data-suplemen.storedetail'), [
        'suplemen_id' => $suplemen->id,
        'penduduk_id' => $penduduk->id,
        'keterangan' => 'Anggota baru',
    ]);

    $response->assertRedirect(route('data.data-suplemen.show', $suplemen->id));
    $response->assertSessionHas('success', 'Anggota Suplemen berhasil ditambah!');

    expect(SuplemenTerdata::where('suplemen_id', $suplemen->id)->count())->toBe(1);
});

test('storeDetail gagal ketika sumber penduduk kosong dengan pesan kustom', function () {
    $suplemen = Suplemen::create([
        'nama' => 'Suplemen Anggota',
        'slug' => 'suplemen-anggota',
        'sasaran' => 1,
    ]);

    $response = $this->post(route('data.data-suplemen.storedetail'), [
        'suplemen_id' => $suplemen->id,
        'keterangan' => 'Tanpa penduduk',
    ]);

    $response->assertSessionHasErrors(['penduduk_id']);

    $errors = session('errors');
    expect($errors->first('penduduk_id'))->toBe('isian warga atau penduduk wajib diisi');
});

test('updateDetail mengubah anggota suplemen saat data valid', function () {
    $suplemen = Suplemen::create([
        'nama' => 'Suplemen Anggota',
        'slug' => 'suplemen-anggota',
        'sasaran' => 1,
    ]);
    $penduduk = Penduduk::factory()->create();
    $terdata = SuplemenTerdata::create([
        'suplemen_id' => $suplemen->id,
        'penduduk_id' => $penduduk->id,
        'keterangan' => 'Keterangan awal',
    ]);

    $response = $this->put(route('data.data-suplemen.updatedetail', $terdata->id), [
        'suplemen_id' => $suplemen->id,
        'penduduk_id' => $penduduk->id,
        'keterangan' => 'Keterangan diubah',
    ]);

    $response->assertRedirect(route('data.data-suplemen.show', $suplemen->id));
    $response->assertSessionHas('success', 'Anggota Suplemen berhasil diubah!');

    $this->assertDatabaseHas('das_suplemen_terdata', [
        'id' => $terdata->id,
        'keterangan' => 'Keterangan diubah',
    ]);
});
