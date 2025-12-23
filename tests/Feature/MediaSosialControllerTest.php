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
use App\Models\MediaSosial;
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
    ]);
    $this->tableName = (new MediaSosial())->getTable();
});

test('display the media sosial index page', function () {
    $response = $this->get(route('informasi.media-sosial.index'));

    $response->assertStatus(200);
    $response->assertViewIs('informasi.media_sosial.index');
});

test('create a media sosial', function () {
    // Membuat file gambar fake menggunakan UploadedFile::fake()
    $file = UploadedFile::fake()->image('logo.png', 320, 240);

    $content = @file_get_contents($file);
    if ($content === false) {
        throw new \Exception("Gagal mengunduh gambar dari URL.");
    }
    $tempPath = storage_path('app/temp-image.png');
    file_put_contents($tempPath, $content);

    $file = new UploadedFile(
        $tempPath,
        'temp-image.png',
        mime_content_type($tempPath),
        null,
        true // true artinya ini file "test", bukan hasil upload asli dari browser
    );

    $data = [
        'logo' => $file,
        'url' => 'https://example.com',
        'nama' => 'Instagram',
        'status' => 1,
    ];

    $response = $this->post(route('informasi.media-sosial.store'), $data);

    $this->assertDatabaseHas($this->tableName, [
        'nama' => $data['nama'],
    ]);

    $response->assertRedirect(route('informasi.media-sosial.index'));
    $response->assertSessionHas('success', 'Media Sosial berhasil disimpan!');
});

test('update a media sosial', function () {
    $media = MediaSosial::factory()->create();

    $data = [
        'nama' => 'Updated Media Sosial',
        'url' => 'https://example.com',
        'status' => 1,
    ];

    $response = $this->put(route('informasi.media-sosial.update', $media->id), $data);

    $this->assertDatabaseHas($this->tableName, [
        'nama' => $data['nama'],
    ]);

    $response->assertRedirect(route('informasi.media-sosial.index'));
    $response->assertSessionHas('success', 'Media Sosial berhasil diubah!');
});

test('delete a media sosial', function () {
    $media = MediaSosial::factory()->create();

    $response = $this->delete(route('informasi.media-sosial.destroy', $media->id));

    $this->assertDatabaseMissing($this->tableName, [
        'id' => $media->id,
    ]);

    $response->assertRedirect(route('informasi.media-sosial.index'));
    $response->assertSessionHas('success', 'Media Sosial berhasil dihapus!');
});
