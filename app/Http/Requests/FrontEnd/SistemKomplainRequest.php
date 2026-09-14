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

namespace App\Http\Requests\FrontEnd;

use App\Models\Penduduk;
use App\Models\SettingAplikasi;
use App\Services\PendudukService;
use Illuminate\Foundation\Http\FormRequest;

class SistemKomplainRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $maxRule = \App\Services\FileUploadService::isLimitEnabled() ? '|max:1024' : '';

        return [
            'nik' => ['required', 'numeric', $this->nikRule()],
            'judul' => 'required|string|max:255',
            'kategori' => 'required',
            'laporan' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'captcha' => 'required|captcha',
            'lampiran1' => 'file|mimes:jpeg,png,jpg,gif,svg' . $maxRule . '|valid_file',
            'lampiran2' => 'file|mimes:jpeg,png,jpg,gif,svg' . $maxRule . '|valid_file',
            'lampiran3' => 'file|mimes:jpeg,png,jpg,gif,svg' . $maxRule . '|valid_file',
            'lampiran4' => 'file|mimes:jpeg,png,jpg,gif,svg' . $maxRule . '|valid_file',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'captcha.captcha' => 'Invalid captcha code.',
        ];
    }

    /**
     * Determine if penduduk database gabungan is active.
     *
     * @return bool
     */
    protected function isDatabaseGabungan()
    {
        return SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->value('value') === '1';
    }

    /**
     * NIK validation rule.
     *
     * When database gabungan is active, validate against the penduduk service,
     * otherwise validate against the local penduduk table.
     */
    protected function nikRule()
    {
        return function ($attribute, $value, $fail) {
            $tanggalLahir = $this->input('tanggal_lahir');

            if ($this->isDatabaseGabungan()) {
                $valid = (new PendudukService)->cekPendudukNikTanggalLahir($value, $tanggalLahir);
            } else {
                $valid = Penduduk::where('nik', $value)->where('tanggal_lahir', $tanggalLahir)->exists();
            }

            if (!$valid) {
                $fail('NIK tidak ditemukan atau tidak sesuai dengan tanggal lahir.');
            }
        };
    }
}
