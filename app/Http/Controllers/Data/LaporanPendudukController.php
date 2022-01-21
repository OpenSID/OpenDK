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

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Imports\ImporLaporanPenduduk;
use App\Models\DataDesa;
use App\Models\LaporanPenduduk;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use ZipArchive;

class LaporanPendudukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(LaporanPenduduk $penduduk)
    {
        $page_title       = 'Laporan Penduduk';
        $page_description = 'Daftar Laporan Penduduk';
        $list_desa        = DataDesa::get();

        return view('data.laporan-penduduk.index', compact('page_title', 'page_description', 'list_desa'));
    }

    /**
     * Return datatable Data Laporan Penduduk.
     *
     * @param Request $request
     * @return DataTables
     */
    public function getData(Request $request)
    {
        $desa = $request->input('desa');

        $query = DB::table('das_laporan_penduduk')
            ->leftJoin('das_data_desa', 'das_laporan_penduduk.desa_id', '=', 'das_data_desa.desa_id')
            ->select([
                'das_laporan_penduduk.id',
                'das_data_desa.nama as nama_desa',
                'das_laporan_penduduk.judul',
                'das_laporan_penduduk.bulan',
                'das_laporan_penduduk.tahun',
                'das_laporan_penduduk.nama_file',
                'das_laporan_penduduk.imported_at',
            ])
            ->when($desa, function ($query) use ($desa) {
                return $desa === 'Semua'
                    ? $query
                    : $query->where('das_data_desa.desa_id', $desa);
            });

        return DataTables::of($query)
            ->addColumn('aksi', function ($row) {
                $data['delete_url'] = route('data.laporan-penduduk.destroy', $row->id);
                $data['download_url'] = asset('storage/laporan_penduduk/' . $row->nama_file);

                return view('forms.aksi', $data);
            })->make();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $penduduk = LaporanPenduduk::findOrFail($id);

            // Hapus file penduduk
            Storage::disk('public')->delete('laporan_penduduk/' . $penduduk->nama_file);

            $penduduk->delete();
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('data.laporan-penduduk.index')->with('error', 'Data gagal dihapus!');
        }

        return redirect()->route('data.laporan-penduduk.index')->with('success', 'Data sukses dihapus!');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function import()
    {
        $page_title       = 'Laporan Penduduk';
        $page_description = 'Import Laporan Penduduk';

        return view('data.laporan-penduduk.import', compact('page_title', 'page_description'));
    }

    /**
     * Impor data penduduk dari file Excel.
     *
     * @return Response
     */
    public function do_import(Request $request)
    {
        $this->validate($request, [
            'file' => 'file|mimes:zip|max:51200',
        ]);

        try {
            // Upload file zip temporary.
            $file = $request->file('file');
            $file->storeAs('temp', $name = $file->getClientOriginalName());

            // Temporary path file
            $path = storage_path("app/temp/{$name}");
            $extract = storage_path('app/temp/laporan_penduduk/');

            // Ekstrak file
            $zip = new ZipArchive();
            $zip->open($path);
            $zip->extractTo($extract);
            $zip->close();

            $fileExtracted = glob($extract.'*.xlsx');

            // Proses impor excell
            (new ImporLaporanPenduduk())
                ->queue($extract . basename($fileExtracted[0]));
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Import data gagal.');
        }

        return redirect()->route('data.laporan-penduduk.index')->with('success', 'Import data sukses');
    }
}
