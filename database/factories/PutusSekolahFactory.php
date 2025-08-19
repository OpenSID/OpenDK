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
use App\Models\PutusSekolah;
use Illuminate\Database\Eloquent\Factories\Factory;

class PutusSekolahFactory extends Factory
{
    protected $model = PutusSekolah::class;

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
            'siswa_paud' => $this->faker->numberBetween(0, 50),
            'anak_usia_paud' => $this->faker->numberBetween(0, 100),
            'siswa_sd' => $this->faker->numberBetween(0, 100),
            'anak_usia_sd' => $this->faker->numberBetween(0, 200),
            'siswa_smp' => $this->faker->numberBetween(0, 80),
            'anak_usia_smp' => $this->faker->numberBetween(0, 150),
            'siswa_sma' => $this->faker->numberBetween(0, 60),
            'anak_usia_sma' => $this->faker->numberBetween(0, 120),
            'semester' => $this->faker->randomElement([1, 2]),
            'tahun' => $this->faker->numberBetween(2020, 2024),
        ];
    }
}
