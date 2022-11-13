<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
        return [
            'foto'              => 'image|mimes:jpg,jpeg,png|max:1024',
            'nama'              =>  "required|regex:/^[a-zA-Z '\.,\-]+$/|max:150",
            'gelar_depan'       =>  "regex:/^[a-zA-Z '\.,\-]+$/|max:150",
            'gelar_belakang'    =>  "regex:/^[a-zA-Z '\.,\-]+$/|max:150",
            'nik'               => 'required|integer|digits:16',
            'nip'               => 'integer|digits:18',
            'tempat_lahir'      => "required|regex:/^[a-zA-Z0-9 '\.,\-\/]+$/",
            'tanggal_lahir'     => "required|date",
            'jenis_kelamin'     => 'integer',
            'pendidikan'        => 'integer',
            'agama'             => 'integer',
            'pangkat'           => 'regex:/^[a-zA-Z0-9 \.\-\/]+$/|max:50',
            'no_sk'             => 'regex:/^[a-zA-Z0-9 \.\-\/]+$/|max:50',
            'tanggal_sk'        => 'date',
            'no_henti'          => 'regex:/^[a-zA-Z0-9 \.\-\/]+$/|max:50',
            'tanggal_henti'     => 'date',
            'masa_jabatan'      => 'required|regex:/^[a-zA-Z0-9 \.\-\/]+$/|max:50',
        ];
    }
}
