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
 * Test untuk memastikan endpoint DataTables pada menu Admin SIKEMA → Daftar Keluhan
 * dapat diakses menggunakan metode POST (antisipasi WAF blocking URL panjang).
 */

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\KategoriKomplain;
use App\Models\Komplain;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

uses(DatabaseTransactions::class);

const SIKEMA_AJAX_HEADERS = ['X-Requested-With' => 'XMLHttpRequest'];

function sikemaDatatablePostPayload(array $extra = []): array
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
});

describe('DataTables Admin SIKEMA Daftar Keluhan via POST', function () {

    test('endpoint getdata menerima POST dan mengembalikan struktur DataTables', function () {
        $kategori = KategoriKomplain::create(['nama' => 'Infrastruktur']);
        Komplain::factory()->create([
            'kategori' => $kategori->id,
            'judul'    => 'Keluhan Test POST',
        ]);

        $response = $this->postJson(
            route('admin-komplain.getdata'),
            sikemaDatatablePostPayload(),
            SIKEMA_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    });

    test('POST ke getdata mengembalikan kolom yang diharapkan', function () {
        $kategori = KategoriKomplain::create(['nama' => 'Infrastruktur']);
        Komplain::factory()->create([
            'kategori' => $kategori->id,
            'judul'    => 'Keluhan Kolom Test',
        ]);

        $response = $this->postJson(
            route('admin-komplain.getdata'),
            sikemaDatatablePostPayload(),
            SIKEMA_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $firstRow = $data[0];
        $this->assertArrayHasKey('judul', $firstRow);
        $this->assertArrayHasKey('nama', $firstRow);
        $this->assertArrayHasKey('kategori', $firstRow);
        $this->assertArrayHasKey('status', $firstRow);
        $this->assertArrayHasKey('anonim', $firstRow);
        $this->assertArrayHasKey('aksi', $firstRow);
    });

    test('GET pada getdata masih berfungsi (backward-compatible)', function () {
        $response = $this->getJson(
            route('admin-komplain.getdata'),
            SIKEMA_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });

    test('POST dengan draw parameter dikembalikan kembali dalam response', function () {
        $response = $this->postJson(
            route('admin-komplain.getdata'),
            sikemaDatatablePostPayload(['draw' => 5]),
            SIKEMA_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonPath('draw', 5);
    });
});
