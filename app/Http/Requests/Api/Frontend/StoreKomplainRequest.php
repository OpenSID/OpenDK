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

namespace App\Http\Requests\Api\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidasiNikRule;

class StoreKomplainRequest extends FormRequest
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
        return [
            'nik' => ['required', 'numeric', new ValidasiNikRule($this->input('tanggal_lahir'))],
            'judul' => 'required|string|max:255',
            'kategori' => 'required',
            'laporan' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'anonim' => 'boolean',
            'lampiran1' => 'file|mimes:jpeg,png,jpg,gif,svg|max:1024|valid_file',
            'lampiran2' => 'file|mimes:jpeg,png,jpg,gif,svg|max:1024|valid_file',
            'lampiran3' => 'file|mimes:jpeg,png,jpg,gif,svg|max:1024|valid_file',
            'lampiran4' => 'file|mimes:jpeg,png,jpg,gif,svg|max:1024|valid_file',
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
            'nik.required' => 'NIK wajib diisi',
            'nik.numeric' => 'NIK harus berupa angka',
            'judul.required' => 'Judul komplain wajib diisi',
            'judul.max' => 'Judul komplain maksimal 255 karakter',
            'kategori.required' => 'Kategori wajib dipilih',
            'laporan.required' => 'Isi laporan wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'lampiran1.file' => 'Lampiran 1 harus berupa file',
            'lampiran1.mimes' => 'Lampiran 1 harus berformat: jpeg, png, jpg, gif, svg',
            'lampiran1.max' => 'Ukuran Lampiran 1 maksimal 1MB',
            'lampiran2.file' => 'Lampiran 2 harus berupa file',
            'lampiran2.mimes' => 'Lampiran 2 harus berformat: jpeg, png, jpg, gif, svg',
            'lampiran2.max' => 'Ukuran Lampiran 2 maksimal 1MB',
            'lampiran3.file' => 'Lampiran 3 harus berupa file',
            'lampiran3.mimes' => 'Lampiran 3 harus berformat: jpeg, png, jpg, gif, svg',
            'lampiran3.max' => 'Ukuran Lampiran 3 maksimal 1MB',
            'lampiran4.file' => 'Lampiran 4 harus berupa file',
            'lampiran4.mimes' => 'Lampiran 4 harus berformat: jpeg, png, jpg, gif, svg',
            'lampiran4.max' => 'Ukuran Lampiran 4 maksimal 1MB',
        ];
    }
}