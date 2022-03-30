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

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Imports\ImporLaporanApbdes;
use App\Models\DataDesa;
use App\Models\LaporanApbdes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use ZipArchive;

class LaporanApbdesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(LaporanApbdes $apbdes)
    {
        $page_title       = 'Laporan APBDes';
        $page_description = 'Daftar Laporan APBDes';
        $list_desa        = DataDesa::get();

        return view('data.laporan-apbdes.index', compact('page_title', 'page_description', 'list_desa'));
    }

    /**
     * Return datatable Data Apbdes.
     *
     * @param Request $request
     * @return DataTables
     */
    public function getApbdes(Request $request)
    {
        $desa = $request->input('desa');

        $query = DB::table('das_apbdes')
            ->leftJoin('das_data_desa', 'das_apbdes.desa_id', '=', 'das_data_desa.desa_id')
            ->select([
                'das_apbdes.id',
                'das_data_desa.nama as nama_desa',
                'das_apbdes.judul',
                'das_apbdes.tahun',
                'das_apbdes.semester',
                'das_apbdes.nama_file',
                'das_apbdes.imported_at',
            ])
            ->when($desa, function ($query) use ($desa) {
                return $desa === 'Semua'
                    ? $query
                    : $query->where('das_data_desa.desa_id', $desa);
            });

        return DataTables::of($query)
            ->addColumn('aksi', function ($row) {
                $data['delete_url'] = route('data.laporan-apbdes.destroy', $row->id);
                $data['download_url'] = route('data.laporan-apbdes.download', $row->id);

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
            $apbdes = LaporanApbdes::findOrFail($id);

            // Hapus file apbdes
            Storage::disk('public')->delete('apbdes/' . $apbdes->nama_file);

            $apbdes->delete();
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('data.laporan-apbdes.index')->with('error', 'Data gagal dihapus!');
        }

        return redirect()->route('data.laporan-apbdes.index')->with('success', 'Data sukses dihapus!');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function import()
    {
        $page_title       = 'Laporan APBDes';
        $page_description = 'Import Laporan APBDes';

        return view('data.laporan-apbdes.import', compact('page_title', 'page_description'));
    }

    /**
     * Impor data apbdes dari file Excel.
     * Kalau apbdes sudah ada (berdasarkan NIK), update dengan data yg diimpor
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
            $extract = storage_path('app/temp/apbdes/');

            // Ekstrak file
            $zip = new ZipArchive();
            $zip->open($path);
            $zip->extractTo($extract);
            $zip->close();

            $fileExtracted = glob($extract.'*.xlsx');

            // Proses impor excell
            (new ImporLaporanApbdes())
                ->queue($extract . basename($fileExtracted[0]));
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Import data gagal.');
        }

        return redirect()->route('data.laporan-apbdes.index')->with('success', 'Import data sukses.');
    }

    /**
     * Download the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function download($id)
    {
        try {
            $getFile = LaporanApbdes::findOrFail($id);

            return Storage::download('public/apbdes/' . $getFile->nama_file);
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Dokumen tidak ditemukan');
        }
    }
}
