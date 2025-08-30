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

use App\Models\AnggaranRealisasi;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnggaranRealisasiFactory extends Factory
{
    protected $model = AnggaranRealisasi::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'profil_id' => $this->faker->numberBetween(1, 10),
            'total_anggaran' => $this->faker->numberBetween(100000000, 5000000000),
            'total_belanja' => $this->faker->numberBetween(50000000, 4000000000),
            'belanja_pegawai' => $this->faker->numberBetween(10000000, 1000000000),
            'belanja_barang_jasa' => $this->faker->numberBetween(5000000, 500000000),
            'belanja_modal' => $this->faker->numberBetween(20000000, 2000000000),
            'belanja_tidak_langsung' => $this->faker->numberBetween(5000000, 200000000),
            'bulan' => $this->faker->numberBetween(1, 12),
            'tahun' => $this->faker->numberBetween(2020, 2024),
        ];
    }
}
