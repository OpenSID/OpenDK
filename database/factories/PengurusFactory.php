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

namespace Database\Factories;

use App\Enums\JenisJabatan;
use App\Models\Agama;
use App\Models\Pendidikan;
use App\Models\Pengurus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengurus>
 */
class PengurusFactory extends Factory
{
    protected $model = Pengurus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->name(),
            'gelar_depan' => null,
            'gelar_belakang' => null,
            'nip' => random_int(100000000, 999999999),
            'nik' => random_int(1000000000000000, 9999999999999999),
            'status' => $this->faker->randomElement([0, 1]),
            'foto' => null,
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'sex' => $this->faker->randomElement([1, 2]),
            'pendidikan_id' => $this->faker->randomElement(Pendidikan::pluck('id')->toArray()) ?? 1,
            'agama_id' => $this->faker->randomElement(Agama::pluck('id')->toArray()),
            'no_sk' => null,
            'tanggal_sk' => $this->faker->date(),
            'masa_jabatan' => 5,
            'pangkat' => 'Camat',
            'no_henti' => null,
            'tanggal_henti' => null,
            'jabatan_id' => JenisJabatan::Camat,
        ];
    }
}
