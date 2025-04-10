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

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengurusRequest extends FormRequest
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
        if ($this->isMethod('put')) {
            $id = ','.$this->penguru;
        } else {
            $id = '';
        }

        return [
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:1024|valid_file',
            'nama' => "required|regex:/^[a-zA-Z '\.,\-]+$/|max:150",
            'gelar_depan' => "nullable|regex:/^[a-zA-Z '\.,\-]+$/|max:150",
            'gelar_belakang' => "nullable|regex:/^[a-zA-Z '\.,\-]+$/|max:150",
            'nik' => 'required|integer|digits:16|unique:das_pengurus,nik'.$id,
            'nip' => 'nullable|integer|digits:18|unique:das_pengurus,nip'.$id,
            'tempat_lahir' => "required|regex:/^[a-zA-Z0-9 '\.,\-\/]+$/",
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'integer',
            'pendidikan' => 'integer',
            'agama' => 'integer',
            'pangkat' => 'nullable|regex:/^[a-zA-Z0-9 \.\-\/]+$/|max:50',
            'no_sk' => 'nullable|regex:/^[a-zA-Z0-9 \.\-\/]+$/|max:50',
            'tanggal_sk' => 'nullable|date',
            'no_henti' => 'nullable|regex:/^[a-zA-Z0-9 \.\-\/]+$/|max:50',
            'tanggal_henti' => 'nullable|date',
            'masa_jabatan' => 'required|regex:/^[a-zA-Z0-9 \.\-\/]+$/|max:50',
            'atasan' => 'nullable',
            'bagan_tingkat' => 'nullable|integer|min:0',
            'bagan_offset' => 'nullable',
            'bagan_layout' => 'nullable',
            'bagan_warna' => 'nullable',
        ];
    }
}
