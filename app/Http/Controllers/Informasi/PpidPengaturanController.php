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

namespace App\Http\Controllers\Informasi;

use App\Http\Controllers\Controller;
use App\Http\Requests\PpidPengaturanRequest;
use App\Models\PpidPengaturan;

class PpidPengaturanController extends Controller
{
    public function index()
    {
        $page_title = 'Pengaturan PPID';
        $page_description = 'Kelola pengaturan PPID';

        $pengaturan = [
            'banner' => PpidPengaturan::getValue('banner', 'default-ppid-banner.jpg'),
            'memperoleh_informasi' => PpidPengaturan::getValue('memperoleh_informasi', [
                'Mengambil informasi di kantor desa (hardcopy)',
                'Dikirim melalui email',
                'Melalui Whatsapp pengguna (Softcopy)',
            ]),
            'alasan_keberatan' => PpidPengaturan::getValue('alasan_keberatan', [
                'Permohonan Informasi ditolak',
                'Informasi berkala tidak tersedia',
                'Permintaan informasi ditanggapi tidak sebagaimana yang diminta',
                'Permintaan informasi tidak dipenuhi',
                'Biaya yang dikenakan tidak wajar',
                'Informasi disampaikan melebihi jangka waktu yang ditentukan',
            ]),
            'salinan_informasi' => PpidPengaturan::getValue('salinan_informasi', [
                'Mengambil Langsung',
                'Email',
                'Faksimili',
                'Kurir',
                'Pos',
            ]),
        ];

        return view('ppid.pengaturan.index', compact('page_title', 'page_description', 'pengaturan'));
    }

    public function update(PpidPengaturanRequest $request)
    {
        try {
            $input = $request->validated();

            PpidPengaturan::setValue('banner', $input['banner'] ?? 'default-ppid-banner.jpg', 'Banner PPID');
            PpidPengaturan::setValue('memperoleh_informasi', $input['memperoleh_informasi'] ?? [], 'Opsi memperoleh informasi');
            PpidPengaturan::setValue('alasan_keberatan', $input['alasan_keberatan'] ?? [], 'Opsi pemohonan keberatan');
            PpidPengaturan::setValue('salinan_informasi', $input['salinan_informasi'] ?? [], 'Opsi salinan informasi');

            return redirect()->route('ppid.pengaturan.index')
                ->with('success', 'Pengaturan PPID berhasil diperbarui!');
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Pengaturan PPID gagal diperbarui!');
        }
    }
}
