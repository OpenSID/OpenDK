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
use App\Models\DataDesa;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

class DataDesaController extends Controller
{
    protected $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Desa';
        $page_description = 'Daftar Desa';

        return view('data.data_desa.index', compact('page_title', 'page_description'));
    }

    public function getDataDesa()
    {
        return DataTables::of(DataDesa::all())
            ->addColumn('action', function ($row) {
                if ($this->profil->kecamatan_id) {
                    $data['edit_url']   = route('data.data-desa.edit', $row->id);
                }
                $data['delete_url'] = route('data.data-desa.destroy', $row->id);

                return view('forms.action', $data);
            })
            ->editColumn('website', function ($row) {
                return '<a href="' . $row->website . '" target="_blank">' . $row->website . '</a>';
            })
            ->rawColumns(['website', 'action'])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (! $this->profil->kecamatan_id) {
            return redirect()->route('data.data-desa.index');
        }

        $page_title       = 'Desa';
        $page_description = 'Tambah Desa';
        $profil           = $this->profil;

        return view('data.data_desa.create', compact('page_title', 'page_description', 'profil'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'desa_id'      => 'required|regex:/^[0-9.]+$/|min:13|max:13|unique:das_data_desa,desa_id',
            'nama'         => 'required',
            'luas_wilayah' => 'required|numeric',
        ]);

        try {
            $desa = new DataDesa();
            $desa->fill($request->all());
            $desa->profil_id = $this->profil->id;
            $desa->save();
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data Desa gagal disimpan!');
        }

        return redirect()->route('data.data-desa.index')->with('success', 'Data Desa berhasil disimpan!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        if (! $this->profil->kecamatan_id) {
            return redirect()->route('data.data-desa.index');
        }

        $desa             = DataDesa::findOrFail($id);
        $page_title       = 'Desa';
        $page_description = 'Ubah Desa : ' . $desa->nama;
        $profil           = $this->profil;

        return view('data.data_desa.edit', compact('page_title', 'page_description', 'desa', 'profil'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'desa_id'      => "required|unique:das_data_desa,desa_id,{$id}|regex:/^[0-9.]+$/|min:13|max:13",
            'nama'         => 'required',
            'luas_wilayah' => 'required|numeric',
        ]);

        try {
            $desa = DataDesa::findOrFail($id);
            $desa->fill($request->all());
            $desa->profil_id = $this->profil->id;
            $desa->save();
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data Desa gagal disimpan!');
        }

        return redirect()->route('data.data-desa.index')->with('success', 'Data Desa berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            DataDesa::findOrFail($id)->delete();
        } catch (Exception $e) {
            return redirect()->route('data.data-desa.index')->with('error', 'Data Desa gagal dihapus!');
        }

        return redirect()->route('data.data-desa.index')->with('success', 'Data Desa sukses dihapus!');
    }

    public function getDesaKecamatan()
    {
        $host = config('app.host_pantau');
        $token = config('app.token_pantau');

        try {
            $response = $this->client->get("{$host}wilayah/list_wilayah", [
                'query' => [
                    'token' => $token,
                    'kode' => $this->profil->kecamatan_id,
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $daftar_desa = collect(json_decode($response->getBody(), true));

                foreach ($daftar_desa['results'] as $value) {
                    $insert = [
                        'profil_id' => $this->profil->id,
                        'desa_id' => $value['kode_desa'],
                        'nama' => $value['nama_desa'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    DataDesa::query()->updateOrInsert([
                        'desa_id' => $value['kode_desa']
                    ], $insert);
                }
            }
        } catch (Exception $e) {
            return redirect()->route('data.data-desa.index')->with('error', 'Data Desa gagal ditambahkan!' . $e);
        }

        return redirect()->route('data.data-desa.index')->with('success', 'Data Desa berhasil ditambahkan!');
    }
}
