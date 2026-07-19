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
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

const MOCK_API_SERVER = 'https://api.example.com';

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
});

test('index shows upload button when database gabungan is inactive', function () {
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );

    $response = $this->get(route('setting.themes.index'));

    $response->assertStatus(200);
    $response->assertSee('Unggah');
    $response->assertSee('modal-upload');
});

test('index hides upload button when database gabungan is active', function () {
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );

    config(['api_server_database_gabungan' => MOCK_API_SERVER]);
    Http::fake([
        MOCK_API_SERVER . '/api/v1/wilayah/desa*' => Http::response([
            'data' => [],
            'meta' => ['pagination' => ['total' => 0]],
        ], 200),
        MOCK_API_SERVER . '/api/v1/opendk/*' => Http::response([
            'data' => [],
            'meta' => ['pagination' => ['total' => 0]],
        ], 200),
    ]);

    $response = $this->get(route('setting.themes.index'));

    $response->assertStatus(200);
    $response->assertDontSee('data-toggle="modal" data-target="#modal-upload"');
    $response->assertDontSee('id="modal-upload"');
});

test('upload returns 403 when database gabungan is active', function () {
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );

    config(['api_server_database_gabungan' => MOCK_API_SERVER]);
    Http::fake([
        MOCK_API_SERVER . '/api/v1/wilayah/desa*' => Http::response([
            'data' => [],
            'meta' => ['pagination' => ['total' => 0]],
        ], 200),
        MOCK_API_SERVER . '/api/v1/opendk/*' => Http::response([
            'data' => [],
            'meta' => ['pagination' => ['total' => 0]],
        ], 200),
    ]);

    $response = $this->postJson(route('setting.themes.upload'));

    $response->assertStatus(403);
    $response->assertJson([
        'status' => 'error',
        'message' => 'Unggah tema tidak diijinkan pada database gabungan',
    ]);
});

test('upload returns 400 when file is missing', function () {
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );

    $response = $this->postJson(route('setting.themes.upload'));

    $response->assertStatus(400);
    $response->assertJson([
        'status' => 'error',
        'message' => 'File tema tidak ditemukan',
    ]);
});

test('upload allows valid zip file when database gabungan is inactive', function () {
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );

    $zipPath = sys_get_temp_dir() . '/' . uniqid() . '.zip';
    $z = new ZipArchive();
    $z->open($zipPath, ZipArchive::CREATE);
    $z->addFromString('style.css', 'body{}');
    $z->close();

    $file = new \Illuminate\Http\UploadedFile($zipPath, 'theme.zip', 'application/zip', null, true);

    $response = $this->postJson(route('setting.themes.upload'), [
        'file' => $file,
    ]);

    @unlink($zipPath);

    $response->assertStatus(200);
});
