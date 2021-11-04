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

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\Penduduk;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request as RequestFacade;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class KeluargaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Keluarga';
        $page_description = 'Daftar Keluarga';

        return view('data.keluarga.index', compact('page_title', 'page_description'));
    }

    /**
     * Return datatable Data Keluarga
     */
    public function getKeluarga()
    {
        return DataTables::of(Keluarga::query())
            ->addColumn('action', function ($row) {
                $data['show_url']   = route('data.keluarga.show', $row->id);

                return view('forms.action', $data);
            })
            ->editColumn('nik_kepala', function ($row) {
                if (isset($row->nik_kepala)) {
                    $penduduk = Penduduk::where('nik', $row->nik_kepala)->first();
                    return $penduduk->nama;
                } else {
                    return '';
                }
            })->make();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $page_title       = 'Detail Keluarga';
        $page_description = 'Detail Data Keluarga';
        $penduduk         = Penduduk::select(['nik', 'nama'])->get();
        $keluarga         = Keluarga::findOrFail($id);

        return view('data.keluarga.show', compact('page_title', 'page_description', 'penduduk', 'keluarga'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function import()
    {
        $page_title       = 'Import';
        $page_description = 'Import Data Keluarga';

        return view('data.keluarga.import', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function importExcel()
    {
        ini_set('max_execution_time', 300);
        if (Input::hasFile('data_file')) {
            $path = RequestFacade::file('data_file')->getRealPath();

            Excel::filter('chunk')->load($path)->chunk(1000, function ($results) {
                foreach ($results as $row) {
                    Keluarga::insert($row->toArray());
                }
            });

            $data = Excel::load($path, function ($reader) {
            })->get();

            return redirect()->route('data.keluarga.import')->with('success', 'Data Keluarga berhasil diunggah!');
        }
    }
}
