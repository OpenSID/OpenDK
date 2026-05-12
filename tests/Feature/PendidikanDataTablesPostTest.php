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

use App\Models\DataDesa;
use App\Models\FasilitasPAUD;
use App\Models\PutusSekolah;
use App\Models\TingkatPendidikan;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

const AJAX_HEADERS_PENDIDIKAN = ['X-Requested-With' => 'XMLHttpRequest'];

function datatablePostPayloadPendidikan(array $columns, array $extra = []): array
{
    return array_merge([
        'draw' => 1,
        'start' => 0,
        'length' => 10,
        'search' => ['value' => '', 'regex' => 'false'],
        'columns' => $columns,
        'order' => [['column' => 1, 'dir' => 'asc']],
    ], $extra);
}

describe('DataTables Menu Pendidikan via POST', function () {
    test('Tingkat Pendidikan getdata menerima POST dan mengembalikan struktur DataTables', function () {
        $desa = DataDesa::factory()->create();
        TingkatPendidikan::factory()->create(['desa_id' => $desa->desa_id]);

        $response = $this->postJson(route('data.tingkat-pendidikan.getdata'), datatablePostPayloadPendidikan([
            ['data' => 'aksi', 'name' => 'aksi', 'searchable' => 'false', 'orderable' => 'false', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'desa.nama', 'name' => 'desa.nama', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'tidak_tamat_sekolah', 'name' => 'tidak_tamat_sekolah', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'tamat_sd', 'name' => 'tamat_sd', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'tamat_smp', 'name' => 'tamat_smp', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'tamat_sma', 'name' => 'tamat_sma', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'tamat_diploma_sederajat', 'name' => 'tamat_diploma_sederajat', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'semester', 'name' => 'semester', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'tahun', 'name' => 'tahun', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
        ]), AJAX_HEADERS_PENDIDIKAN);

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });

    test('Putus Sekolah getdata menerima POST dan mengembalikan struktur DataTables', function () {
        $desa = DataDesa::factory()->create();
        PutusSekolah::factory()->create(['desa_id' => $desa->desa_id]);

        $response = $this->postJson(route('data.putus-sekolah.getdata'), datatablePostPayloadPendidikan([
            ['data' => 'aksi', 'name' => 'aksi', 'searchable' => 'false', 'orderable' => 'false', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'desa.nama', 'name' => 'desa.nama', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'siswa_paud', 'name' => 'siswa_paud', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'anak_usia_paud', 'name' => 'anak_usia_paud', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'siswa_sd', 'name' => 'siswa_sd', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'anak_usia_sd', 'name' => 'anak_usia_sd', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'siswa_smp', 'name' => 'siswa_smp', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'anak_usia_smp', 'name' => 'anak_usia_smp', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'siswa_sma', 'name' => 'siswa_sma', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'anak_usia_sma', 'name' => 'anak_usia_sma', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'semester', 'name' => 'semester', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'tahun', 'name' => 'tahun', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
        ]), AJAX_HEADERS_PENDIDIKAN);

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });

    test('Fasilitas PAUD getdata menerima POST dan mengembalikan struktur DataTables', function () {
        $desa = DataDesa::factory()->create();
        FasilitasPAUD::factory()->create(['desa_id' => $desa->desa_id]);

        $response = $this->postJson(route('data.fasilitas-paud.getdata'), datatablePostPayloadPendidikan([
            ['data' => 'aksi', 'name' => 'aksi', 'searchable' => 'false', 'orderable' => 'false', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'desa.nama', 'name' => 'desa.nama', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'jumlah_paud', 'name' => 'jumlah_paud', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'jumlah_guru_paud', 'name' => 'jumlah_guru_paud', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'jumlah_siswa_paud', 'name' => 'jumlah_siswa_paud', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'semester', 'name' => 'semester', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'tahun', 'name' => 'tahun', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
        ]), AJAX_HEADERS_PENDIDIKAN);

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });

    test('endpoint getdata pendidikan tidak lagi menerima GET', function () {
        $endpoints = [
            route('data.tingkat-pendidikan.getdata'),
            route('data.putus-sekolah.getdata'),
            route('data.fasilitas-paud.getdata'),
        ];

        foreach ($endpoints as $url) {
            $this->getJson($url, AJAX_HEADERS_PENDIDIKAN)->assertStatus(405);
        }
    });
});
