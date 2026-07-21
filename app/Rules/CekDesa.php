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

namespace App\Rules;

use App\Models\DataDesa;
use App\Models\Profil;
use Illuminate\Contracts\Validation\Rule;

class CekDesa implements Rule
{
    protected ?string $nama_kecamatan = null;

    protected ?string $value = null;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     */
    public function passes($attribute, $value): bool
    {
        $this->value = $value;

        return (bool) DataDesa::where($attribute, $value)->first();
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        if ($this->nama_kecamatan === null) {
            $this->nama_kecamatan = optional(Profil::first())->nama_kecamatan ?? 'Unknown';
        }

        return 'Kode desa '.$this->value.' tidak dikenal di OpenDK Kecamatan '.$this->nama_kecamatan;
    }
}
