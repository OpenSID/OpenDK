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

class ProfilRequest extends FormRequest
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
            'provinsi_id'                     => 'required|string|min:2|max:2',
            'nama_provinsi'                   => 'required|string',
            'kabupaten_id'                    => 'required|string|min:5|max:5',
            'nama_kabupaten'                  => 'required|string',
            'kecamatan_id'                    => 'required|string|min:8|max:8',
            'nama_kecamatan'                  => 'required|string',
            'alamat'                          => 'required',
            'kode_pos'                        => 'required|string|max:12',
            'telepon'                         => 'required|string|max:15',
            'email'                           => 'required|email',
            'tahun_pembentukan'               => 'required|integer',
            'dasar_pembentukan'               => 'required|string|max:50',
            'nama_camat'                      => 'required|string|max:150',
            'sekretaris_camat'                => 'required|string|max:150',
            'kepsek_pemerintahan_umum'        => 'required|string|max:150',
            'kepsek_kesejahteraan_masyarakat' => 'required|string|max:150',
            'kepsek_pemberdayaan_masyarakat'  => 'required|string|max:150',
            'kepsek_pelayanan_umum'           => 'required|string|max:150',
            'kepsek_trantib'                  => 'required|string|max:150',
            'file_logo'                       => 'image|mimes:jpg,jpeg,bmp,png,gif|max:1024',
            'file_struktur_organisasi'        => 'image|mimes:jpg,jpeg,png,bmp,gif|max:1024',
            'foto_kepala_wilayah'             => 'image|mimes:jpg,jpeg,png,bmp,gif|max:1024',
            // 'visi'                            => 'required',
            // 'misi'                            => 'required',
        ];
    }
}
