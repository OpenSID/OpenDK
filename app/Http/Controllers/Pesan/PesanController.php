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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Pesan;

use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use App\Models\Pesan;
use App\Models\PesanDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Stevebauman\Purify\Facades\Purify;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PesanController extends Controller
{
    public const PESAN_MASUK = "Pesan Masuk";
    public const PESAN_KELUAR = "Pesan Keluar";
    public const BELUM_DIBACA = 0;
    public const SUDAH_DIBACA = 1;
    public const MASUK_ARSIP = 1;
    public const NON_ARSIP = 0;
    public const PER_PAGE = 10;

    public function index(Request $request)
    {
        $flag_include_arsip = false;
        $data = collect([]);
        $data->put('page_title', 'Pesan');
        $data->put('desa_id', null);
        $data->put('search_query', '');
        $data->put('page_description', 'Managemen Pesan');
        $data = $data->merge($this->loadCounter());
        $pesan = Pesan::with(['dataDesa', 'detailPesan'])
            ->where('jenis', self::PESAN_MASUK)
            ->orderBy('sudah_dibaca', 'ASC')
            ->orderBy('created_at', 'DESC');

        if (!empty($request->get('desa_id'))) {
            $flag_include_arsip = true;
            $pesan->where('das_data_desa_id', $request->get('desa_id'));
            $data->put('desa_id', $request->get('desa_id'));
        }

        if (!empty($request->get('q'))) {
            $flag_include_arsip = true;
            $query = $request->get('q');
            $pesan->where('judul', 'LIKE', "%{$query}%");
            $data->put('search_query', $request->get('q'));
        }

        if (!$flag_include_arsip) {
            $pesan->where('diarsipkan', self::NON_ARSIP);
        }

        $pesan = $pesan->paginate(self::PER_PAGE);
        $list_desa = DataDesa::get();
        $data = $data->merge($this->getPaginationAttribute($pesan));
        $data->put('list_pesan', $pesan);
        $data->put('list_desa', $list_desa);
        return view('pesan.masuk.index', $data->all());
    }

    public function getPaginationAttribute(LengthAwarePaginator $pesan): array
    {
        $first_data = (($pesan->currentPage() * $pesan->perPage()) - $pesan->perPage()) + 1;
        $last_data = $pesan->perPage() * $pesan->currentPage();
        $last_data = $pesan->total() < $last_data ? $pesan->total() : $last_data;
        return [
            'first_data' => $first_data,
            'last_data'=> $last_data
        ];
    }

    protected function loadCounter()
    {
        $counter_unread =  Pesan::where([
            'jenis' => self::PESAN_MASUK,
            'diarsipkan' => self::NON_ARSIP,
            'sudah_dibaca' => self::BELUM_DIBACA])->count();
        $counter_pesan_keluar =  Pesan::where([
            'jenis' => self::PESAN_KELUAR,
            'diarsipkan' => self::NON_ARSIP])->count();
        $counter_arsip =  Pesan::where([
            'diarsipkan' => self::MASUK_ARSIP])->count();

        return [
            'counter_unread' => $counter_unread,
            'counter_pesan_keluar' => $counter_pesan_keluar,
            'counter_arsip' => $counter_arsip
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
        $data = $data->merge($this->loadCounter());
        $pesan = Pesan::with(['dataDesa', 'detailPesan'])
            ->where('jenis', self::PESAN_KELUAR)
            ->orderBy('created_at', 'DESC');

        if (!empty($request->get('desa_id'))) {
            $flag_include_arsip = true;
            $pesan->where('das_data_desa_id', $request->get('desa_id'));
            $data->put('desa_id', $request->get('desa_id'));
        }

        if (!empty($request->get('q'))) {
            $flag_include_arsip = true;
            $query = $request->get('q');
            $pesan->where('judul', 'LIKE', "%{$query}%");
            $data->put('search_query', $request->get('q'));
        }

        if (!$flag_include_arsip) {
            $pesan->where('diarsipkan', self::NON_ARSIP);
        }

        $list_desa = DataDesa::get();
        $pesan = $pesan->paginate(self::PER_PAGE);
        $data = $data->merge($this->getPaginationAttribute($pesan));
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
        $data = $data->merge($this->loadCounter());
        $pesan = Pesan::with(['dataDesa', 'detailPesan'])
            ->where('diarsipkan', self::MASUK_ARSIP)
            ->orderBy('created_at', 'DESC');

        if (!empty($request->get('desa_id'))) {
            $flag_include_arsip = true;
            $pesan->where('das_data_desa_id', $request->get('desa_id'));
            $data->put('desa_id', $request->get('desa_id'));
        }

        if (!empty($request->get('q'))) {
            $flag_include_arsip = true;
            $query = $request->get('q');
            $pesan->where('judul', 'LIKE', "%{$query}%");
            $data->put('search_query', $request->get('q'));
        }

        $list_desa = DataDesa::get();
        $pesan = $pesan->paginate(self::PER_PAGE);
        $data = $data->merge($this->getPaginationAttribute($pesan));
        $data->put('list_pesan', $pesan);
        $data->put('list_desa', $list_desa);
        return view('pesan.arsip.index', $data->all());
    }

    public function readPesan($id_pesan)
    {
        $pesan  = Pesan::with(['dataDesa', 'detailPesan'])->findOrFail($id_pesan);
        if ($pesan->sudah_dibaca === self::BELUM_DIBACA) {
            $pesan->sudah_dibaca = self::SUDAH_DIBACA;
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
            $status = $this->validate($request, [
                'judul'    => 'required',
                'das_data_desa_id' => 'required',
                'text' => 'required'
            ]);

            if (!$status) {
                throw new \Exception(
                    collect($this->errors()->all())->implode(", "),
                    ResponseAlias::HTTP_BAD_REQUEST
                );
            }

            DB::transaction(function () use ($request) {
                $id = Pesan::insertGetId([
                    'das_data_desa_id' => $request->get('das_data_desa_id'),
                    'judul' => $request->get('judul'),
                    'jenis' => self::PESAN_KELUAR,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                PesanDetail::insert([
                    'pesan_id' => $id,
                    'text' => Purify::clean($request->get('text')),
                    'desa_id' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
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
        $pesan->diarsipkan = self::MASUK_ARSIP;
        if ($pesan->save()) {
            return redirect()->route('pesan.arsip')->with('success', 'Pesan berhasil diarsipkan!');
        } else {
            return back()->withInput()->with('error', 'Pesan gagal diarsipkan!');
        }
    }

    public function setMultipleReadPesanStatus(Request $request)
    {
        $array = json_decode($request->get('array_id'));
        $pesan = Pesan::whereIn('id', $array)->update([
            'sudah_dibaca' => self::SUDAH_DIBACA
        ]);

        if ($pesan > 0) {
            return back()->with('success', 'Pesan berhasil ditandai!');
        } else {
            return back()->withInput()->with('error', 'Pesan gagal ditandai!');
        }
    }

    public function setMultipleArsipPesanStatus(Request $request)
    {
        $array = json_decode($request->get('array_id'));
        $pesan = Pesan::whereIn('id', $array)->update([
            'diarsipkan' => self::MASUK_ARSIP
        ]);

        if ($pesan > 0) {
            return back()->with('success', 'Pesan berhasil ditandai!');
        } else {
            return back()->withInput()->with('error', 'Pesan gagal diarsipkan!');
        }
    }

    public function replyPesan(Request $request)
    {
        $pesan = PesanDetail::insert([
            'pesan_id' => $request->get('id'),
            'text' => Purify::clean($request->get('text')),
            'desa_id' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if ($pesan) {
            return back()->with('success', 'Pesan berhasil dibalas!');
        } else {
            return back()->withInput()->with('error', 'Pesan gagal disimpan!');
        }
    }
}
