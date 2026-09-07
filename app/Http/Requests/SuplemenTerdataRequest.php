<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuplemenTerdataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'suplemen_id' => ['required', 'exists:das_suplemen,id'],
            'penduduk_id' => ['nullable', 'integer'],
            'penduduk_id_gabungan' => ['nullable', 'integer'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    /**
     * Pastikan hanya salah satu sumber penduduk yang diisi:
     * penduduk lokal (penduduk_id) atau penduduk dari database gabungan (penduduk_id_gabungan).
     */
    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $validator->after(function ($validator) {
            $hasLocal = $this->filled('penduduk_id');
            $hasGabungan = $this->filled('penduduk_id_gabungan');

            if (! $hasLocal && ! $hasGabungan) {
                $validator->errors()->add('penduduk_id', 'isian warga atau penduduk wajib diisi');
            }

            if ($hasLocal && $hasGabungan) {
                $validator->errors()->add('penduduk_id_gabungan', 'hanya boleh memilih salah satu, penduduk lokal atau penduduk database gabungan');
            }
        });
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'penduduk_id.required' => 'isian warga atau penduduk wajib diisi',
        ];
    }
}
