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

namespace App\Http\Controllers\Pesan;

use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use App\Models\Pesan;
use App\Models\PesanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stevebauman\Purify\Facades\Purify;

class PesanController extends Controller
{
    public function index(Request $request)
    {
        $flag_include_arsip = false;
        $data = collect([]);
        $data->put('page_title', 'Pesan');
        $data->put('desa_id', null);
        $data->put('search_query', '');
        $data->put('sudah_dibaca', '');
        $data->put('page_description', 'Managemen Pesan');
        $data = $data->merge($this->loadCounter());
        $pesan = Pesan::with(['dataDesa', 'detailPesan'])
            ->where('jenis', Pesan::PESAN_MASUK)
            ->where('diarsipkan', Pesan::NON_ARSIP)
            ->orderBy('sudah_dibaca', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->when(!empty($request->get('desa_id')), function ($q) use ($request, &$data) {
                $data->put('desa_id', $request->get('desa_id'));
                return  $q->where('das_data_desa_id', $request->get('desa_id'));
            })
            ->when(!empty($request->get('q')), function ($q) use ($request, &$data) {
                $data->put('search_query', $request->get('q'));
                return  $q->where('judul', 'LIKE', "%{$request->get('q')}%");
            })
            ->when($request->get('sudahdibaca') !== null, function ($q) use ($request, &$data) {
                $data->put('sudah_dibaca', $request->get('sudahdibaca'));
                return $q->where('sudah_dibaca', (int) $request->get('sudahdibaca'));
            })
            ->paginate(Pesan::PER_PAGE);

        $list_desa = DataDesa::get();
        $data->put('list_pesan', $pesan);
        $data->put('list_desa', $list_desa);

        return view('pesan.masuk.index', $data->all());
    }

    protected function loadCounter()
    {
        $counter_unread =  Pesan::where([
            'jenis' => Pesan::PESAN_MASUK,
            'diarsipkan' => Pesan::NON_ARSIP,
            'sudah_dibaca' => Pesan::BELUM_DIBACA])->count();
        $counter_unread_keluar =  Pesan::where([
            'jenis' => Pesan::PESAN_KELUAR,
            'diarsipkan' => Pesan::NON_ARSIP,
            'sudah_dibaca' => Pesan::BELUM_DIBACA])->count();

        return [
            'counter_unread' => $counter_unread,
            'counter_unread_keluar' => $counter_unread_keluar,
         ];
    }

    public function loadPesanKeluar(Request $request)
    {
        $flag_include_arsip = false;
        $data = collect([]);
        $data->put('desa_id', null);
        $data->put('search_query', '');
        $data->put('page_title', 'Pesan Keluar');
        $data->put('page_description', 'Managemen Pesan');
        $data->put('sudah_dibaca', null);
        $data = $data->merge($this->loadCounter());
        $pesan = Pesan::with(['dataDesa', 'detailPesan'])
            ->where('jenis', Pesan::PESAN_KELUAR)
            ->where('diarsipkan', Pesan::NON_ARSIP)
            ->orderBy('created_at', 'DESC')
            ->when(!empty($request->get('desa_id')), function ($q) use ($request, &$data) {
                $data->put('desa_id', $request->get('desa_id'));
                return  $q->where('das_data_desa_id', $request->get('desa_id'));
            })
            ->when(!empty($request->get('q')), function ($q) use ($request, &$data) {
                $data->put('search_query', $request->get('q'));
                return  $q->where('judul', 'LIKE', "%{$request->get('q')}%");
            })
            ->paginate(Pesan::PER_PAGE);

        $list_desa = DataDesa::get();
        $data->put('list_pesan', $pesan);
        $data->put('list_desa', $list_desa);

        return view('pesan.keluar.index', $data->all());
    }

    public function loadPesanArsip(Request $request)
    {
        $data = collect([]);
        $data->put('desa_id', null);
        $data->put('search_query', '');
        $data->put('page_title', 'Pesan Arsip');
        $data->put('page_description', 'Managemen Pesan');
        $data->put('sudah_dibaca', null);
        $data = $data->merge($this->loadCounter());
        $pesan = Pesan::with(['dataDesa', 'detailPesan'])
            ->where('diarsipkan', Pesan::MASUK_ARSIP)
            ->orderBy('created_at', 'DESC')
            ->when(!empty($request->get('desa_id')), function ($q) use ($request, &$data) {
                $data->put('desa_id', $request->get('desa_id'));
                return  $q->where('das_data_desa_id', $request->get('desa_id'));
            })
            ->when(!empty($request->get('q')), function ($q) use ($request, &$data) {
                $data->put('search_query', $request->get('q'));
                return  $q->where('judul', 'LIKE', "%{$request->get('q')}%");
            })
            ->paginate(Pesan::PER_PAGE);

        $list_desa = DataDesa::get();
        $data->put('list_pesan', $pesan);
        $data->put('list_desa', $list_desa);
        return view('pesan.arsip.index', $data->all());
    }

    public function readPesan($id_pesan)
    {
        $pesan  = Pesan::findOrFail($id_pesan);
        if ($pesan->sudah_dibaca === Pesan::BELUM_DIBACA) {
            $pesan->sudah_dibaca = Pesan::SUDAH_DIBACA;
            $pesan->save();
        }

        $data = collect([]);
        $data->put('page_title', 'Pesan');
        $data->put('page_description', 'Managemen Pesan');
        $data->put('pesan', $pesan);
        $data = $data->merge($this->loadCounter());
        return view('pesan.read_pesan', $data->all());
    }

    public function composePesan()
    {
        $data = collect([]);
        $data->put('page_title', 'Buat Pesan');
        $data->put('page_description', 'Managemen Pesan');
        $list_desa = DataDesa::get();
        $data = $data->merge($this->loadCounter());
        $data->put('list_desa', $list_desa);
        return view('pesan.compose_pesan', $data->all());
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeComposePesan(Request $request)
    {
        try {
            $this->validate($request, [
                'judul'    => 'required',
                'das_data_desa_id' => 'required|exists:das_data_desa,id',
                'text' => 'required'
            ]);

            DB::transaction(function () use ($request) {
                $id = Pesan::create([
                    'das_data_desa_id' => $request->get('das_data_desa_id'),
                    'judul' => $request->get('judul'),
                    'jenis' => Pesan::PESAN_KELUAR,
                    'sudah_dibaca' => 1,
                ])->id;

                PesanDetail::create([
                    'pesan_id' => $id,
                    'text' => Purify::clean($request->get('text')),
                    'pengirim' => 'kecamatan',
                    'nama_pengirim' => 'kecamatan - '. auth()->user()->name,
                ]);
            });

            return redirect()->route('pesan.keluar')->with('success', 'Pesan berhasil dikirim!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Pesan gagal dikirim!. Detail: ' . $e->getMessage());
        }
    }

    public function setArsipPesan(Request $request)
    {
        $pesan = Pesan::findOrFail($request->get('id'));
        $pesan->diarsipkan = Pesan::MASUK_ARSIP;
        if ($pesan->save()) {
            return redirect()->route('pesan.arsip')->with('success', 'Pesan berhasil diarsipkan!');
        }
        return back()->withInput()->with('error', 'Pesan gagal diarsipkan!');
    }

    public function setMultipleReadPesanStatus(Request $request)
    {
        $array = json_decode($request->get('array_id'));
        $pesan = Pesan::whereIn('id', $array)->update([
            'sudah_dibaca' => Pesan::SUDAH_DIBACA
        ]);

        if ($pesan > 0) {
            return back()->with('success', 'Pesan berhasil ditandai!');
        }
        return back()->withInput()->with('error', 'Pesan gagal ditandai!');
    }

    public function setMultipleArsipPesanStatus(Request $request)
    {
        $array = json_decode($request->get('array_id'));
        $pesan = Pesan::whereIn('id', $array)->update([
            'diarsipkan' => Pesan::MASUK_ARSIP
        ]);

        if ($pesan > 0) {
            return back()->with('success', 'Pesan berhasil ditandai!');
        }
        return back()->withInput()->with('error', 'Pesan gagal diarsipkan!');
    }

    public function replyPesan(Request $request)
    {
        $pesan = PesanDetail::create([
            'pesan_id' => $request->get('id'),
            'text' => Purify::clean($request->get('text')),
            'pengirim' => 'kecamatan',
            'nama_pengirim' => 'kecamatan - '. auth()->user()->name
        ]);

        if ($pesan) {
            return back()->with('success', 'Pesan berhasil dikirim!');
        }
        return back()->withInput()->with('error', 'Pesan gagal dikirim!');
    }
}
