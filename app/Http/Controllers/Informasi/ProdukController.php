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

namespace App\Http\Controllers\Informasi;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProdukRequest;
use App\Models\Produk;
use Yajra\DataTables\DataTables;

class ProdukController extends Controller
{
    public function index()
    {
        $page_title       = 'Produk';
        $page_description = 'Daftar Produk';

        return view('informasi.produk.index', compact('page_title', 'page_description'));
    }

    public function getDataProduk()
    {
        return DataTables::of(Produk::select(
            'id',
            'pelapak',
            'produk',
            'kategori_produk',
            'harga',
            'satuan',
            'stok',
            'potongan',
            'deskripsi_produk'
        ))
            ->addColumn('aksi', function ($row) {
                $data['show_url'] = route('informasi.produk.show', $row->id);
                $data['edit_url']   = route('informasi.produk.edit', $row->id);
                $data['delete_url'] = route('informasi.produk.destroy', $row->id);
                return view('forms.aksi', $data);
            })
            ->editColumn('produk', function ($row) {
                return $row->produk;
            })->make();
    }

    public function create()
    {
        $page_title       = 'Produk';
        $page_description = 'Tambah Produk';

        return view('informasi.produk.create', compact('page_title', 'page_description'));
    }


    public function store(ProdukRequest $request)
    {
        try {
            $input = $request->all();
            if ($request->hasFile('foto')) {
                $file     = $request->file('foto');
                $original_name = strtolower(trim($file->getClientOriginalName()));
                $file_name = time() . rand(100, 999) . '_' . $original_name;
                $path     = "storage/produk/";
                $file->move($path, $file_name);

                $input['foto'] = $path . $file_name;
                $input['mime_type'] = $file->getClientOriginalExtension();
            }
            // dd($request);
            Produk::create($input);
        } catch (\Exception $e) {
            return back()->with('error', 'Produk gagal disimpan!' . $e->getMessage());
        }
        return redirect()->route('informasi.produk.index')->with('success', 'Produk berhasil disimpan!');
    }

    public function show(Produk $produk)
    {
        $page_title       = 'Produk';
        $page_description = 'Detail Barang Jualan : ' . $produk->produk;

        return view('informasi.produk.show', compact('page_title', 'page_description', 'produk'));
    }

    public function edit(Produk $produk)
    {
        $page_title       = 'Produk';
        $page_description = 'Ubah Produk : ' . $produk->produk;

        return view('informasi.produk.edit', compact('page_title', 'page_description', 'produk'));
    }

    public function update(Produk $produk, ProdukRequest $request)
    {
        try {
            $input = $request->all();

            if ($request->hasFile('foto')) {
                $file     = $request->file('foto');
                $original_name = strtolower(trim($file->getClientOriginalName()));
                $file_name = time() . rand(100, 999) . '_' . $original_name;
                $path     = "storage/produk/";
                $file->move($path, $file_name);
                unlink(base_path('public/' . $produk->foto));

                $input['foto'] = $path . $file_name;
                $input['mime_type'] = $file->getClientOriginalExtension();
            }

            $produk->update($input);
        } catch (\Exception $e) {
            return back()->with('error', 'Produk gagal disimpan!' . $e->getMessage());
        }

        return redirect()->route('informasi.produk.index')->with('success', 'Produk berhasil disimpan!');
    }

    public function destroy(Produk $produk)
    {
        try {
            if ($produk->delete()) {
                unlink(base_path('public/' . $produk->foto));
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Produk gagal dihapus!');
        }

        return redirect()->route('informasi.produk.index')->with('success', 'Produk berhasil disimpan!');
    }
}
