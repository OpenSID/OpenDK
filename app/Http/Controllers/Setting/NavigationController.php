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

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\NavigationRequest;
use App\Models\Navigation;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class NavigationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($parent_id = 0)
    {
        $page_title = 'Navigasi';
        $page_description = 'Daftar Navigasi';
        $prev_parent = null;
        
        if (!empty($parent_id)) {
            $navigation = Navigation::findOrFail($parent_id);
            $page_description = 'Daftar Navigasi '. $navigation->name;
            $prev_parent = $navigation->parent_id;
        }

        return view('setting.navigation.index', compact('page_title', 'page_description', 'parent_id', 'prev_parent'));
    }

    // Get Data Navigasi
    public function getData($parent_id = null)
    {
        $query = Navigation::when($parent_id, function ($query) use ($parent_id) {
            return $query->where('parent_id', $parent_id);
        })->orderBy('order', 'asc');

        return DataTables::of($query)
            ->addColumn('aksi', function ($row) {
                if (! auth()->guest()) {
                    $data['edit_url'] = route('setting.navigation.edit', $row->id);
                    $data['delete_url'] = route('setting.navigation.destroy', $row->id);
                    $data['detail_url'] = route('setting.navigation.index', $row->id);
                    $data['naik'] = route('setting.navigation.order', [$row->id, 'up']);
                    $data['turun'] = route('setting.navigation.order', [$row->id, 'down']);
                }

                return view('forms.aksi', $data);
            })
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($parent_id = 0)
    {
        $page_title = 'Navigasi';
        $page_description = 'Tambah Navigasi';
        $navigation = null;

        return view('setting.navigation.create', compact('page_title', 'page_description', 'parent_id', 'navigation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(NavigationRequest $request)
    {
        $parent_id = $request->input('parent_id');

        try {
            
            $data = $request->all();
            
            // generate slug
            $parent = Navigation::where('id', $parent_id)->first();
            $data['slug'] = (!empty($parent)) ? $parent->slug . "-" . Str::slug($data['name']) : Str::slug($data['name']);

            // set latest order
            $data['order'] = Navigation::lastOrder($parent_id) + 1;
            $navigation = new Navigation($data);
            $navigation->save();
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Navigasi gagal dikirim!');
        }

        return redirect()->route('setting.navigation.index', $parent_id)->with('success', 'Navigasi berhasil dikirim!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $navigation = Navigation::findOrFail($id);
        $page_title = 'Navigasi';
        $page_description = 'Ubah  Navigasi : '.$navigation->name;
        $parent_id = $navigation->parent_id;

        return view('setting.navigation.edit', compact('page_title', 'page_description', 'navigation', 'parent_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(NavigationRequest $request, $id)
    {
        $parent_id = $request->input('parent_id');
        
        try {
            $data = $request->all();
            
            // generate slug
            $parent = Navigation::where('id', $parent_id)->first();            
            $data['slug'] = (!empty($parent)) ? $parent->slug . "-" . Str::slug($data['name']) : Str::slug($data['name']);

            $navigation = Navigation::findOrFail($id);
            $navigation->fill($data);
            $navigation->save();
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Navigasi gagal diupdate!');
        }

        return redirect()->route('setting.navigation.index', $parent_id)->with('success', 'Navigasi berhasil diupdate!');
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
            // delete childs
            Navigation::where('parent_id', $id)->delete();
            
            $navigation = Navigation::findOrFail($id);
            $navigation->delete();

            $parent_id = $navigation->parent_id;
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Navigasi gagal dihapus!');
        }

        return redirect()->route('setting.navigation.index', $parent_id)->with('success', 'Navigasi berhasil dihapus!');
    }

    /**
     * Order the specified resource from storage.
     *
     * @param  int  $id
     * @param  string  $direction
     * @return Response
     */
    public function order($id, $direction)
    {
        try {
            $navigation = Navigation::findOrFail($id);
            $parent_id = $navigation->parent_id;
            
            $current_order = $navigation->order;
            if ($direction === 'up') {
                $new_order = $current_order - 1;

                $lower_navigation = Navigation::where('parent_id', $parent_id)->where('order', $new_order)->first();
                if (!empty($lower_navigation)) {
                    $lower_navigation->order = $current_order;
                    $lower_navigation->save();
                    
                    $navigation->order = $new_order;
                    $navigation->save();
                }                
            } else {
                $new_order = $current_order + 1;

                $higher_navigation = Navigation::where('parent_id', $parent_id)->where('order', $new_order)->first();
                if (!empty($higher_navigation)) {
                    $higher_navigation->order = $current_order;
                    $higher_navigation->save();
                    
                    $navigation->order = $new_order;
                    $navigation->save();
                }
            }

        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Navigasi gagal diurutkan!');
        }

        return redirect()->route('setting.navigation.index', $parent_id)->with('success', 'Navigasi berhasil diurutkan!');
    }
}
