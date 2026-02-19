<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace Tests\Feature\MasterData;

use App\Models\Keluarga;
use Tests\CrudTestCase;

beforeEach(function () {
    // Test setup if needed
});

describe('Keluarga CRUD', function () {
    test('index displays keluarga list view', function () {
        $response = $this->get(route('data.keluarga.index'));

        $response->assertStatus(200);
        $response->assertViewIs('data.keluarga.index');
        $response->assertViewHas('page_title', 'Keluarga');
    });

    test('show displays keluarga detail', function () {
        $keluarga = Keluarga::factory()->create();

        $response = $this->get(route('data.keluarga.show', $keluarga->id));

        $response->assertStatus(200);
        $response->assertViewIs('data.keluarga.show');
        $response->assertViewHas('keluarga', $keluarga);
    });

    test('export-excel displays export form', function () {
        $response = $this->get(route('data.keluarga.export-excel'));

        $response->assertStatus(200);
    });
});
