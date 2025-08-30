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

namespace Tests\Feature;

use App\Models\DataUmum;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CrudTestCase;

class DataUmumControllerTest extends CrudTestCase
{
    use DatabaseTransactions;

    public function test_index_displays_edit_view()
    {
        $response = $this->get(route('data.data-umum.index'));

        $response->assertStatus(200);
        $response->assertViewIs('data.data_umum.edit');
    }

    public function test_update_success()
    {

        $dataUmum = DataUmum::first();
        $updateData = [
            'tipologi' => 'Desa',
            'sejarah' => 'Sejarah desa ini bermula dari...',
            'sumber_luas_wilayah' => 1,
            'luas_wilayah' => 200,
            'bts_wil_utara' => 'Batas Utara',
            'bts_wil_timur' => 'Batas Timur',
            'bts_wil_selatan' => 'Batas Selatan',
            'bts_wil_barat' => 'Batas Barat',
            'jml_puskesmas' => 2,
            'jml_puskesmas_pembantu' => 3,
            'jml_posyandu' => 5,
            'jml_pondok_bersalin' => 1,
            'jml_paud' => 4,
            'jml_sd' => 6,
            'jml_smp' => 3,
            'jml_sma' => 2,
            'jml_masjid_besar' => 1,
            'jml_mushola' => 10,
            'jml_gereja' => 0,
            'jml_pasar' => 1,
            'jml_balai_pertemuan' => 2,
            'lat' => '-7.123456',
            'lng' => '110.123456',
        ];
        $response = $this->put(route('data.data-umum.update', $dataUmum->id), $updateData);

        $response->assertRedirect(route('data.data-umum.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas($dataUmum->getTable(), [
            'id' => $dataUmum->id,
            'luas_wilayah' => 200,
        ]);
    }

    public function test_update_failed()
    {

        $response = $this->from(route('data.data-umum.index'))
            ->put(route('data.data-umum.update', 999999), [
                'luas_wilayah' => 200,
                'luas_wilayah_value' => 200,
                'sumber_luas_wilayah' => 1,
            ]);

        $response->assertRedirect(route('data.data-umum.index'));
    }

    public function test_get_data_umum_ajax()
    {
        // test using ajax request
        $response = $this->getJson(route('data.data-umum.getdataajax'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }
}
