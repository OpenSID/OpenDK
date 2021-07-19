<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Imports\ImporPenduduk;
use App\Models\DataDesa;
use App\Models\Penduduk;
use Doctrine\DBAL\Query\QueryException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Barryvdh\Debugbar\Facade as Debugbar;

use ZipArchive;

use function back;
use function compact;
use function config;
use function convert_born_date_to_age;
use function redirect;
use function request;
use function route;
use function strtolower;
use function substr;
use function ucwords;
use function view;

class PendudukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Penduduk $penduduk)
    {
        $page_title       = 'Penduduk';
        $page_description = 'Data Penduduk';
        $list_desa        = DataDesa::get();

        return view('data.penduduk.index', compact('page_title', 'page_description', 'list_desa'));
    }

    /**
     * Return datatable Data Penduduk.
     *
     * @param Request $request
     * @return DataTables
     */
    public function getPenduduk(Request $request)
    {
        $desa = $request->input('desa');

        $query = DB::table('das_penduduk')
            ->leftJoin('das_data_desa', 'das_penduduk.desa_id', '=', 'das_data_desa.desa_id')
            ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
            ->leftJoin('ref_kawin', 'das_penduduk.status_kawin', '=', 'ref_kawin.id')
            ->leftJoin('ref_pekerjaan', 'das_penduduk.pekerjaan_id', '=', 'ref_pekerjaan.id')
            ->select([
                'das_penduduk.id',
                'das_penduduk.foto',
                'das_penduduk.nik',
                'das_penduduk.nama',
                'das_penduduk.no_kk',
                'das_penduduk.alamat',
                'das_data_desa.nama as nama_desa',
                'ref_pendidikan_kk.nama as pendidikan',
                'das_penduduk.tanggal_lahir',
                'ref_kawin.nama as status_kawin',
                'ref_pekerjaan.nama as pekerjaan',
            ])
            ->when($desa, function ($query) use ($desa) {
                return $desa === 'ALL'
                    ? $query
                    : $query->where('das_data_desa.desa_id', $desa);
            })
            ->where('status_dasar', 1);

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                $show_url   = route('data.penduduk.show', $row->id);

                $data['show_url']   = $show_url;

                return view('forms.action', $data);
            })
            ->addColumn('tanggal_lahir', function ($row) {
                return convert_born_date_to_age($row->tanggal_lahir);
            })->make();
    }

    /**
     * Show the specified resource.
     *
     * @param Penduduk $penduduk
     * @return Response
     */
    public function show($id)
    {
        $penduduk = Penduduk::findOrFail($id);
        if ($penduduk->foto == '') {
            $penduduk->file_struktur_organisasi = 'http://placehold.it/120x150';
        }
        $page_title       = 'Detail Penduduk';
        $page_description = 'Detail Data Penduduk: ' . ucwords(strtolower($penduduk->nama));

        return view('data.penduduk.show', compact('page_title', 'page_description', 'penduduk'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function import()
    {
        $page_title       = 'Impor';
        $page_description = 'Impor Data Penduduk';

        $list_desa = DB::table('das_data_desa')->select('*')->where('kecamatan_id', '=', config('app.default_profile'))->get();
        return view('data.penduduk.import', compact('page_title', 'page_description', 'list_desa'));
    }

    /**
     * Impor data penduduk dari file Excel.
     * Kalau penduduk sudah ada (berdasarkan NIK), update dengan data yg diimpor
     *
     * @return Response
     */
    public function importExcel(Request $request)
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
            $extract = storage_path('app/public/penduduk/foto/');

            // Ekstrak file
            $zip = new ZipArchive;
            $zip->open($path);
            $zip->extractTo($extract);
            $zip->close();

            // Proses impor excell
            (new ImporPenduduk())
                ->queue($extract . $excellName = Str::replaceLast('zip', 'xlsx', $name));
        } catch (Exception $e) {
            return back()->with('error', 'Import data gagal. ' . $e->getMessage());
        }

        // Hapus folder temp ketika sudah selesai
        Storage::deleteDirectory('temp');
        // Hapus file excell temp ketika sudah selesai
        Storage::disk('public')->delete('penduduk/foto/' . $excellName);

        return back()->with('success', 'Import data sukses.');
    }
}
