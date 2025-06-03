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

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\Demo\DemoFaqSeeder;
use Database\Seeders\Demo\DemoEventSeeder;
use Database\Seeders\Demo\DemoPesanSeeder;
use Database\Seeders\Demo\DemoAKIAKBSeeder;
use Database\Seeders\Demo\DemoSliderSeeder;
use Database\Seeders\Demo\DemoAPBDesaSeeder;
use Database\Seeders\Demo\DemoArtikelSeeder;
use Database\Seeders\Demo\DemoDokumenSeeder;
use Database\Seeders\Demo\DemoPotensiSeeder;
use Database\Seeders\Demo\DemoProsedurSeeder;
use Database\Seeders\Demo\DemoRegulasiSeeder;
use Database\Seeders\Demo\DemoImunisasiSeeder;
use Database\Seeders\Demo\DemoMediaSosialSeeder;
use Database\Seeders\Demo\DemoPesanDetailSeeder;
use Database\Seeders\Demo\DemoPutusSekolahSeeder;
use Database\Seeders\Demo\DemoFasilitasPaudSeeder;
use Database\Seeders\Demo\DemoDasProfilTableSeeder;
use Database\Seeders\Demo\DemoProgramBantuanSeeder;
use Database\Seeders\Demo\DemoSinergiProgramSeeder;
use Database\Seeders\Demo\DemoToiletSanitasiSeeder;
use Database\Seeders\Demo\DemoEpidemiPenyakitSeeder;
use Database\Seeders\Demo\DemoDasDataDesaTableSeeder;
use Database\Seeders\Demo\DemoDasDataUmumTableSeeder;
use Database\Seeders\Demo\DemoDasPengurusTableSeeder;
use Database\Seeders\Demo\DemoPendudukKeluargaSeeder;
use Database\Seeders\Demo\DemoAnggaranRealisasiSeeder;
use Database\Seeders\Demo\DemoTingkatPendidikanSeeder;
use Database\Seeders\Demo\DemoDasNavigationTableSeeder;

class DemoDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call(DatabaseSeeder::class);

        $this->call(DemoDasProfilTableSeeder::class);
        $this->call(DemoDasDataUmumTableSeeder::class);
        $this->call(DemoDasDataDesaTableSeeder::class);

        $this->call(DemoAKIAKBSeeder::class);
        $this->call(DemoAnggaranRealisasiSeeder::class);
        $this->call(DemoAPBDesaSeeder::class);
        $this->call(DemoEpidemiPenyakitSeeder::class);
        $this->call(DemoFasilitasPaudSeeder::class);
        $this->call(DemoImunisasiSeeder::class);
        $this->call(DemoPendudukKeluargaSeeder::class);
        $this->call(DemoProgramBantuanSeeder::class);
        $this->call(DemoPutusSekolahSeeder::class);
        $this->call(DemoTingkatPendidikanSeeder::class);
        $this->call(DemoToiletSanitasiSeeder::class);
        $this->call(DemoDasPengurusTableSeeder::class);
        $this->call(DemoArtikelSeeder::class);
        $this->call(DemoPesanSeeder::class);
        $this->call(DemoPesanDetailSeeder::class);
        $this->call(DemoPotensiSeeder::class);
        $this->call(DemoProsedurSeeder::class);
        $this->call(DemoRegulasiSeeder::class);
        $this->call(DemoDokumenSeeder::class);
        $this->call(DemoEventSeeder::class);
        $this->call(DemoFaqSeeder::class);
        $this->call(DemoMediaSosialSeeder::class);
        $this->call(DemoSinergiProgramSeeder::class);
        $this->call(DemoSliderSeeder::class);
        $this->call(DemoDasNavigationTableSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
