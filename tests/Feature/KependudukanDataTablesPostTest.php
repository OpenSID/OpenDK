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

/**
 * Test untuk memastikan endpoint DataTables pada menu Data → Kependudukan
 * dapat diakses menggunakan metode POST (antisipasi WAF blocking URL panjang).
 */

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\DataDesa;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Models\Suplemen;

uses(DatabaseTransactions::class);

const AJAX_HEADERS_KEPENDUDUKAN = ['X-Requested-With' => 'XMLHttpRequest'];

function datatablePostPayloadKependudukan(array $extra = []): array
{
    return array_merge([
        'draw'                      => 1,
        'start'                     => 0,
        'length'                    => 10,
        'search'                    => ['value' => '', 'regex' => 'false'],
        'columns[0][data]'          => 'aksi',
        'columns[0][name]'          => 'aksi',
        'columns[0][searchable]'    => 'false',
        'columns[0][orderable]'     => 'false',
        'columns[0][search][value]' => '',
        'order[0][column]'          => '1',
        'order[0][dir]'             => 'asc',
    ], $extra);
}

beforeEach(function () {
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

describe('DataTables Menu Kependudukan via POST', function () {

    test('Penduduk endpoint menerima POST dan mengembalikan struktur DataTables', function () {
        $response = $this->postJson(
            route('data.penduduk.getdata'),
            datatablePostPayloadKependudukan(),
            AJAX_HEADERS_KEPENDUDUKAN
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    });

    test('Keluarga endpoint menerima POST dan mengembalikan struktur DataTables', function () {
        $response = $this->postJson(
            route('data.keluarga.getdata'),
            datatablePostPayloadKependudukan(),
            AJAX_HEADERS_KEPENDUDUKAN
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    });

    test('Data Suplemen getdata endpoint menerima POST dan mengembalikan struktur DataTables', function () {
        $response = $this->postJson(
            route('data.data-suplemen.getdata'),
            datatablePostPayloadKependudukan(),
            AJAX_HEADERS_KEPENDUDUKAN
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    });

    test('Data Suplemen getsuplementerdata endpoint menerima POST', function () {
        $suplemen = Suplemen::create([
            'nama' => 'Suplemen Test POST',
            'slug' => 'suplemen-test-post',
            'sasaran' => 1,
            'keterangan' => 'Testing POST method'
        ]);

        $response = $this->postJson(
            route('data.data-suplemen.getsuplementerdata', $suplemen->id),
            datatablePostPayloadKependudukan(),
            AJAX_HEADERS_KEPENDUDUKAN
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    });

    test('Laporan Penduduk endpoint menerima POST dan mengembalikan struktur DataTables', function () {
        $response = $this->postJson(
            route('data.laporan-penduduk.getdata'),
            datatablePostPayloadKependudukan(),
            AJAX_HEADERS_KEPENDUDUKAN
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    });

    test('Semua endpoint getdata Kependudukan tetap backward-compatible dengan GET', function () {
        $endpoints = [
            route('data.penduduk.getdata'),
            route('data.keluarga.getdata'),
            route('data.data-suplemen.getdata'),
            route('data.laporan-penduduk.getdata'),
        ];

        foreach ($endpoints as $url) {
            $response = $this->getJson($url, AJAX_HEADERS_KEPENDUDUKAN);
            $response->assertStatus(200);
            $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
        }
    });
});
