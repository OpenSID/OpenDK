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

namespace Database\Factories;

use App\Models\DataDesa;
use App\Models\TingkatPendidikan;
use Illuminate\Database\Eloquent\Factories\Factory;

class TingkatPendidikanFactory extends Factory
{
    protected $model = TingkatPendidikan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Buat desa jika belum ada
        if (!DataDesa::exists()) {
            DataDesa::factory()->create();
        }

        return [
            'desa_id' => DataDesa::inRandomOrder()->first()->desa_id,
            'tidak_tamat_sekolah' => $this->faker->numberBetween(0, 100),
            'tamat_sd' => $this->faker->numberBetween(0, 200),
            'tamat_smp' => $this->faker->numberBetween(0, 150),
            'tamat_sma' => $this->faker->numberBetween(0, 100),
            'tamat_diploma_sederajat' => $this->faker->numberBetween(0, 50),
            'tahun' => $this->faker->numberBetween(2020, 2024),
            'semester' => $this->faker->randomElement([1, 2]),
        ];
    }
}
