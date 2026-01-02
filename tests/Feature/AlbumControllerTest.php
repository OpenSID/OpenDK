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
use App\Models\Album;
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
    ]);
});

test('display the album index page', function () {
    $response = $this->get(route('publikasi.album.index'));

    $response->assertStatus(200);
    $response->assertViewIs('publikasi.album.index');
});

test('create an album', function () {
    $data = [
        'judul' => 'Test Album',
        'status' => true,
        'gambar' => null, // Assuming no image upload for this test
    ];

    $response = $this->post(route('publikasi.album.store'), $data);

    $this->assertDatabaseHas('albums', [
        'judul' => 'Test Album',
    ]);

    $response->assertRedirect(route('publikasi.album.index'));
    $response->assertSessionHas('success', 'Album berhasil disimpan!');
});

test('update an album', function () {
    $album = Album::factory()->create();

    $data = [
        'judul' => 'Updated Album',
    ];

    $response = $this->put(route('publikasi.album.update', $album->id), $data);

    $this->assertDatabaseHas('albums', [
        'judul' => 'Updated Album',
    ]);

    $response->assertRedirect(route('publikasi.album.index'));
    $response->assertSessionHas('success', 'Album berhasil diubah!');
});

test('delete an album', function () {
    $album = Album::factory()->create();

    $response = $this->delete(route('publikasi.album.destroy', $album->id));

    $this->assertDatabaseMissing('albums', [
        'id' => $album->id,
    ]);

    $response->assertRedirect(route('publikasi.album.index'));
    $response->assertSessionHas('success', 'Album sukses dihapus!');
});

test('toggle album status', function () {
    $album = Album::factory()->create(['status' => 0]);

    $response = $this->put(route('publikasi.album.status', $album->id));

    $album->refresh();

    expect($album->status)->toBeTrue();

    $response->assertRedirect(route('publikasi.album.index'));
});
