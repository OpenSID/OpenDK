<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSetingAplikasiRequest;
use App\Models\SettingAplikasi;
use Exception;

class AplikasiController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $settings = SettingAplikasi::all();
        if ($settings->isEmpty()) {
            SettingAplikasi::insert([
                'key'         => SettingAplikasi::KEY_BROWSER_TITLE,
                'value'       => $this->default_browser_title,
                'type'        => "input",
                'description' => "Judul halaman aplikasi.",
                'kategori'    => "-",
                'option'      => '{}'
            ]);
            $settings = SettingAplikasi::all();
        }

        return view('setting.aplikasi.index', [
            'page_title'    => 'Pegaturan Aplikasi',
            'settings'      => $settings,
        ]);
    }

    public function edit(SettingAplikasi $aplikasi)
    {
        $page_title             = 'Update Aplikasi';
        $page_description       = 'Edit Pengaturan Aplikasi Lainnya';
        $default_browser_title  = $this->default_browser_title;

        return view('setting.aplikasi.edit', compact(
            'page_title',
            'aplikasi',
            'default_browser_title',
            'page_description'
        ));
    }

    public function update(UpdateSetingAplikasiRequest $request, SettingAplikasi $aplikasi)
    {
        try {
            $data = $request->validated();
            if ($aplikasi->isBrowserTitle() && !$request->input('value')) {
                $data['value'] = $this->default_browser_title;
            }

            $aplikasi->update($data);

            return redirect()
                ->route('setting.aplikasi.index')
                ->with('success', 'Pengaturan aplikasi "' . $aplikasi->description . '" berhasil diupdate.');
        } catch (Exception $e) {
            return redirect()
                ->route('setting.aplikasi.edit', $aplikasi->id)
                ->with('error', 'Gagal mengupdate pengaturan ' . $aplikasi->description . ', error: "' . $e->getMessage() . '".');
        }
    }
}
