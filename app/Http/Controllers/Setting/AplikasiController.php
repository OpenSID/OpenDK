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

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\SettingAplikasi;
use Illuminate\Http\Request;

class AplikasiController extends Controller
{
    public function index()
    {
        $settings = SettingAplikasi::all();
        if ($settings->isEmpty()) {
            SettingAplikasi::insert([
                'key'         => SettingAplikasi::KEY_BROWSER_TITLE,
                'value'       => $this->browser_title,
                'type'        => "input",
                'description' => "Judul halaman aplikasi.",
                'kategori'    => "-",
                'option'      => '{}'
            ]);
            $settings = SettingAplikasi::all();
        }

        $page_title       = 'Pegaturan Aplikasi';
        $page_description = 'Daftar Pegaturan Aplikasi';

        return view('setting.aplikasi.index', compact('page_title', 'page_description', 'settings'));
    }

    public function edit(SettingAplikasi $aplikasi)
    {
        $page_title             = 'Pengaturan Aplikasi';
        $page_description       = 'Ubah Pengaturan Aplikasi';

        return view('setting.aplikasi.edit', compact('page_title', 'page_description', 'aplikasi'));
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'value' => 'required',
        ]);

        try {
            $penyakit = SettingAplikasi::findOrFail($id);
            $penyakit->fill($request->only(['value']));
            $penyakit->save();

            $this->browser_title = $request->input('value');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Pengaturan aplikasi gagal diubah!');
        }

        return redirect()->route('setting.aplikasi.index')->with('success', 'Pengaturan aplikasi berhasil diubah!.');
    }
}
