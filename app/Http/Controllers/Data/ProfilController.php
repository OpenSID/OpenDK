<?php

namespace App\Http\Controllers\Data;

use App\Models\DataUmum;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\DataDesa;
use App\Models\Profil;
use App\Models\VisiMisi;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use FIle;
use Image;
use Illuminate\Support\Facades\Input;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$page_title = 'Profil';
        $page_description= 'Data Profil Kecamatan';
        return view('data.profil.index', compact('page_title', 'page_description'));*/
        $profil = Profil::where('kecamatan_id', config('app.default_profile'))->first();
        if($profil->file_struktur_organisasi == ''){
            $profil->file_struktur_organisasi = 'http://placehold.it/600x400';
        }
        $page_title = 'Ubah Profil';
        $page_description = 'Kecamatan: ' . ucwords(strtolower($profil->kecamatan->nama));


        return view('data.profil.edit', compact('page_title', 'page_description', 'profil'));
    }


    public function getDataProfil()
    {
        return DataTables::of(Profil::with(['Kecamatan'])->get())
            ->addColumn('action', function ($data) {
                $edit_url = route('data.profil.edit', $data->id);
                $delete_url = route('data.profil.destroy', $data->id);

                $data['edit_url'] = $edit_url;
                //$data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Tambah';
        $page_description = 'Tambah Profil Kecamatan';
        $profil = new Profil();


        return view('data.profil.create', compact('page_title', 'page_description', 'profil'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Save Request
        try {
            request()->validate([
                'kecamatan_id' => 'required',
                'alamat' => 'required',
                'kode_pos' => 'required',
                'email' => 'email',
                'nama_camat' => 'required',
            ]);

            $profil = new Profil();
            $profil->fill($request->all());
            $profil->kabupaten_id = substr($profil->kecamatan_id,0,5);
            $profil->provinsi_id = substr($profil->kecamatan_id,0,2);



            if ($request->hasFile('file_struktur_organisasi')) {
                $file       = $request->file('file_struktur_organisasi');
                $fileName   = $file->getClientOriginalName();
                $request->file('file_struktur_organisasi')->move("storage/profil/struktur_organisasi/", $fileName);
                $profil->file_struktur_organisasi = 'storage/profil/struktur_organisasi/'.$fileName;
            }


            if ($request->hasFile('file_logo')) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["file_logo"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $profil->file_logo = $target_file.$imageFileType;
            }

            if($profil->save())
                $id = DataUmum::create(['profil_id'=> $profil->id, 'kecamatan_id'=>$profil->kecamatan_id, 'embed_peta' => 'Edit Peta Pada Menu Data Umum.'])->id;
                $desa = Desa::where('kecamatan_id', '=', $profil->kecamatan_id)->get();
                $data_desa= array();
                foreach($desa as $val)
                {
                    $data_desa[] = array(
                        'desa_id' => $val->id,
                        'kecamatan_id' => strval($profil->kecamatan_id),
                        'nama' => $val->nama
                    );
                }

                DataDesa::insert($data_desa);
            return redirect()->route('data.profil.success', $id)->with('success', 'Profil berhasil disimpan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Profil gagal disimpan!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
      $desa = Desa::where('kecamatan_id', '=', '1107062')->get();
                $data_desa= array();
                foreach($desa as $val)
                {
                    $data_desa[] = array(
                        'desa_id' => strval($val->id),
                        'kecamatan_id' => strval('1107062'),
                        'nama' => $val->nama
                    );
                }

                DataDesa::insert($data_desa);
      return $data_desa;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $profil = Profil::findOrFail($id);
        if($profil->file_struktur_organisasi == ''){
            $profil->file_struktur_organisasi = 'http://placehold.it/600x400';
        }
        $page_title = 'Ubah';
        $page_description = 'Ubah Profil Kecamatan: ' . ucwords(strtolower($profil->kecamatan->nama));


        return view('data.profil.edit', compact('page_title', 'page_description', 'profil'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      request()->validate([
          'kecamatan_id' => 'required',
          'alamat' => 'required',
          'kode_pos' => 'required',
          'email' => 'email',
          'nama_camat' => 'required',
          'file_logo' => 'image|mimes:jpg,jpeg,bmp,png,gif|max:1024',
          'file_struktur_organisasi' => 'image|mimes:jpg,jpeg,png,bmp,gif|max:1024',
      ],[

      ]);

        try {
            $profil = Profil::find($id);
            $profil->fill($request->all());
            $profil->kabupaten_id = substr($profil->kecamatan_id,0,5);
            $profil->provinsi_id = substr($profil->kecamatan_id,0,2);

            $dataumum = DataUmum::where('profil_id', $id)->first();
            $dataumum->kecamatan_id = $profil->kecamatan_id;

            if($request->file('file_struktur_organisasi') == "")
            {
                $profil->file_struktur_organisasi = $profil->file_struktur_organisasi;
            }
            else
            {
                $file       = $request->file('file_struktur_organisasi');
                $fileName   = $file->getClientOriginalName();
                $request->file('file_struktur_organisasi')->move("storage/profil/struktur_organisasi/", $fileName);
                $profil->file_struktur_organisasi = 'storage/profil/struktur_organisasi/'.$fileName;
            }

            if($request->file('file_logo') == "")
            {
                $profil->file_logo = $profil->file_logo;
            }
            else
            {
                $fileLogo       = $request->file('file_logo');
                $fileLogoName   = $fileLogo->getClientOriginalName();
                $request->file('file_logo')->move("storage/profil/file_logo/", $fileLogoName);
                $profil->file_logo = 'storage/profil/file_logo/'.$fileLogoName;
            }



            $profil->update();
            $dataumum->update();

            return redirect()->route('data.profil.success', $profil->dataumum->id)->with('success', 'Update Profil sukses!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Update Profil gagal!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $profil = Profil::findOrFail($id);
            $profil->dataUmum()->delete();
            $profil->dataDesa()->delete();
            $profil->delete();

            return redirect()->route('data.profil.index')->with('success', 'Profil sukses dihapus!');

        } catch (Exception $e) {
            return redirect()->route('data.profil.index')->with('error', 'Profil gagal dihapus!');
        }
    }

    /**
     * Redirect to edit Data Umum if success
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function success($id)
    {
        $page_title = 'Konfirmasi?';
        $page_description = '';
        return view('data.profil.save_success', compact('id', 'page_title', 'page_description'));
    }
}
