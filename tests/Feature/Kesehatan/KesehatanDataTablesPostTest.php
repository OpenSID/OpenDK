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

use App\Models\AkiAkb;
use App\Models\DataDesa;
use App\Models\EpidemiPenyakit;
use App\Models\Imunisasi;
use App\Models\JenisPenyakit;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

const AJAX_HEADERS_KESEHATAN = ['X-Requested-With' => 'XMLHttpRequest'];

function datatablePostPayloadKesehatan(array $columns, string $direction = 'asc', array $extra = []): array
{
    return array_merge([
        'draw' => 1,
        'start' => 0,
        'length' => 10,
        'search' => ['value' => '', 'regex' => 'false'],
        'columns' => $columns,
        'order' => [['column' => 1, 'dir' => $direction]],
    ], $extra);
}

describe('DataTables Menu Kesehatan via POST', function () {
    test('AKI AKB getdata menerima POST dan mengembalikan struktur DataTables', function () {
        $desa = DataDesa::factory()->create();
        AkiAkb::factory()->create(['desa_id' => $desa->desa_id]);

        $response = $this->postJson(route('data.aki-akb.getdata'), datatablePostPayloadKesehatan([
            ['data' => 'aksi', 'name' => 'aksi', 'searchable' => 'false', 'orderable' => 'false', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'nama_desa', 'name' => 'desa_id', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'aki', 'name' => 'aki', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'akb', 'name' => 'akb', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'bulan', 'name' => 'bulan', 'searchable' => 'false', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'tahun', 'name' => 'tahun', 'searchable' => 'false', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
        ], 'desc'), AJAX_HEADERS_KESEHATAN);

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });

    test('Imunisasi getdata menerima POST dan mengembalikan struktur DataTables', function () {
        $desa = DataDesa::factory()->create();
        Imunisasi::factory()->create(['desa_id' => $desa->desa_id]);

        $response = $this->postJson(route('data.imunisasi.getdata'), datatablePostPayloadKesehatan([
            ['data' => 'aksi', 'name' => 'aksi', 'searchable' => 'false', 'orderable' => 'false', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'nama_desa', 'name' => 'desa_id', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'cakupan_imunisasi', 'name' => 'cakupan_imunisasi', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'bulan', 'name' => 'bulan', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'tahun', 'name' => 'tahun', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
        ]), AJAX_HEADERS_KESEHATAN);

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });

    test('Epidemi Penyakit getdata menerima POST dan mengembalikan struktur DataTables', function () {
        $desa = DataDesa::factory()->create();
        $jenisPenyakit = JenisPenyakit::factory()->create();
        EpidemiPenyakit::factory()->create([
            'desa_id' => $desa->desa_id,
            'penyakit_id' => $jenisPenyakit->id,
        ]);

        $response = $this->postJson(route('data.epidemi-penyakit.getdata'), datatablePostPayloadKesehatan([
            ['data' => 'aksi', 'name' => 'aksi', 'searchable' => 'false', 'orderable' => 'false', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'nama_desa', 'name' => 'desa_id', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'penyakit.nama', 'name' => 'penyakit.nama', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'jumlah_penderita', 'name' => 'jumlah_penderita', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'bulan', 'name' => 'bulan', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'tahun', 'name' => 'tahun', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
        ]), AJAX_HEADERS_KESEHATAN);

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });

    test('endpoint getdata kesehatan tidak lagi menerima GET', function () {
        $endpoints = [
            route('data.aki-akb.getdata'),
            route('data.imunisasi.getdata'),
            route('data.epidemi-penyakit.getdata'),
        ];

        foreach ($endpoints as $url) {
            $this->getJson($url, AJAX_HEADERS_KESEHATAN)->assertStatus(405);
        }
    });
});
