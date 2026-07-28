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

namespace Tests;

use App\Models\Profil;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\WithSettingAplikasi;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions, WithSettingAplikasi;    

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure a Profil record with valid kecamatan_id exists so the
        // CompleteProfile middleware does not redirect to data.profil.index.
        Profil::firstOrCreate(
            ['id' => 1],
            [
                'nama' => 'Kecamatan Test',
                'kecamatan_id' => '33010100',
                'provinsi_id' => '33',
                'kabupaten_id' => '33010',
                'nama_provinsi' => 'Jawa Tengah',
                'nama_kabupaten' => 'Banjarnegara',
                'nama_kecamatan' => 'Pagentan',
                'alamat' => 'Alamat Test',
                'kode_pos' => '53471',
                'telepon' => '0123456789',
                'email' => 'test@example.com',
                'tahun_pembentukan' => '2024',
                'dasar_pembentukan' => 'Dasar Pembentukan Test',
            ]
        );

        // Authenticate a user for all tests to prevent 403 errors
        // This is necessary for Laravel 11 where authorization is stricter
        $user = \App\Models\User::first();
        if (!$user) {
            $user = \App\Models\User::factory()->create();
        }
        $this->actingAs($user);
        $this->setDefaultApplicationConfig();
    }
}
