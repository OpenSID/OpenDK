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

class PpidDokumenRequest extends FormRequest
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
     * BUG-006: Conditional validation based on tipe_dokumen value
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'judul' => 'required|string|max:255',
            'jenis_dokumen_id' => 'required|exists:das_ppid_jenis_dokumen,id',
            'tipe_dokumen' => 'required|in:file,url',
            'ringkasan' => 'nullable|string',
            'status' => 'required|in:terbit,tidak_terbit',
            'tanggal_publikasi' => 'nullable|date',
        ];

        // BUG-006: Get the tipe_dokumen value from request or existing model
        $tipeDokumen = $this->input('tipe_dokumen');
        if ($this->ppid_dokumen && empty($tipeDokumen)) {
            $tipeDokumen = $this->ppid_dokumen->tipe_dokumen;
        }

        // Conditional validation based on tipe_dokumen
        if ($tipeDokumen === 'file') {
            // For FILE type: file required if creating, nullable if updating
            if ($this->ppid_dokumen) {
                // Updating: file is nullable (keep existing if not changed)
                $rules['file_path'] = 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240';
            } else {
                // Creating: file is required
                $rules['file_path'] = 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240';
            }
            $rules['url'] = 'nullable|max:255';
        } elseif ($tipeDokumen === 'url') {
            // For URL type: url required, file nullable
            $rules['file_path'] = 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240';
            $rules['url'] = 'required|url|max:255';
        } else {
            // Default: both nullable (when tipe_dokumen not yet selected)
            $rules['file_path'] = 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240';
            $rules['url'] = 'nullable|url|max:255';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul maksimal 255 karakter.',
            'jenis_dokumen_id.required' => 'Jenis dokumen wajib dipilih.',
            'jenis_dokumen_id.exists' => 'Jenis dokumen tidak valid.',
            'tipe_dokumen.required' => 'Tipe dokumen wajib dipilih.',
            'tipe_dokumen.in' => 'Tipe dokumen harus file atau url.',
            'file_path.required' => 'File wajib diunggah saat tipe dokumen adalah file.',
            'file_path.mimes' => 'Format file harus: pdf, doc, docx, xls, xlsx, ppt, pptx, jpg, jpeg, png.',
            'file_path.max' => 'Ukuran file maksimal 10MB.',
            'url.required' => 'URL wajib diisi saat tipe dokumen adalah url.',
            'url.url' => 'Format URL tidak valid.',
            'url.max' => 'URL maksimal 255 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus terbit atau tidak_terbit.',
            'tanggal_publikasi.date' => 'Format tanggal publikasi tidak valid.',
        ];
    }
}
