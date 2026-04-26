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

namespace App\Http\Controllers\Ppid;

use App\Models\PpidPengaturan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $page_title = 'Pengaturan PPID';
        $page_description = 'Pengaturan Layanan PPID';

        $pengaturan = [
            'nama_ppid' => PpidPengaturan::getValue('nama_ppid', ''),
            'alamat_ppid' => PpidPengaturan::getValue('alamat_ppid', ''),
            'nomor_telepon' => PpidPengaturan::getValue('nomor_telepon', ''),
            'email_ppid' => PpidPengaturan::getValue('email_ppid', ''),
            'jam_operasional' => PpidPengaturan::getValue('jam_operasional', ''),
            'nama_pejabat' => PpidPengaturan::getValue('nama_pejabat', ''),
            'nip_pejabat' => PpidPengaturan::getValue('nip_pejabat', ''),
            'jabatan_pejabat' => PpidPengaturan::getValue('jabatan_pejabat', ''),
        ];

        return view('ppid.pengaturan.index', compact('page_title', 'page_description', 'pengaturan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $pengaturanList = [
                'nama_ppid' => ['value' => $request->nama_ppid, 'keterangan' => 'Nama Instansi PPID'],
                'alamat_ppid' => ['value' => $request->alamat_ppid, 'keterangan' => 'Alamat PPID'],
                'nomor_telepon' => ['value' => $request->nomor_telepon, 'keterangan' => 'Nomor Telepon PPID'],
                'email_ppid' => ['value' => $request->email_ppid, 'keterangan' => 'Email PPID'],
                'jam_operasional' => ['value' => $request->jam_operasional, 'keterangan' => 'Jam Operasional Pelayanan'],
                'nama_pejabat' => ['value' => $request->nama_pejabat, 'keterangan' => 'Nama Pejabat PPID'],
                'nip_pejabat' => ['value' => $request->nip_pejabat, 'keterangan' => 'NIP Pejabat PPID'],
                'jabatan_pejabat' => ['value' => $request->jabatan_pejabat, 'keterangan' => 'Jabatan Pejabat PPID'],
            ];

            foreach ($pengaturanList as $key => $data) {
                PpidPengaturan::setValue($key, $data['value'], $data['keterangan']);
            }

            return back()->with('success', 'Pengaturan PPID berhasil disimpan!');
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Pengaturan PPID gagal disimpan!');
        }
    }
}
