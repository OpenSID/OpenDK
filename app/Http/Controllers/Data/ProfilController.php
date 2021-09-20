<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use App\Models\DataUmum;
use App\Models\Desa;
use App\Models\Profil;
use function back;
use function basename;
use function compact;

use function config;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function is_img;
use function pathinfo;
use const PATHINFO_EXTENSION;
use function redirect;
use function request;
use function strtolower;
use function strval;
use function substr;
use function ucwords;

use function view;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $profil = Profil::where('kecamatan_id', config('app.default_profile'))->first();

        $profil->file_struktur_organisasi = is_img($profil->file_struktur_organisasi);
        $profil->file_logo                = is_img($profil->file_logo);
        $profil->foto_kepala_wilayah                = is_img($profil->foto_kepala_wilayah);

        $page_title       = 'Ubah Profil';
        $page_description =   ucwords(strtolower($this->sebutan_wilayah).' : ' . $profil->kecamatan->nama);
        // dd($profil);
        return view('data.profil.edit', compact('page_title', 'page_description', 'profil'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title       = 'Tambah';
        $page_description = 'Tambah Profil ' .$this->sebutan_wilayah;
        $profil           = new Profil();

        return view('data.profil.create', compact('page_title', 'page_description', 'profil'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // Save Request
        try {
            request()->validate([
                'kecamatan_id' => 'required',
                'alamat'       => 'required',
                'kode_pos'     => 'required',
                'email'        => 'email',
                'nama_camat'   => 'required',
            ]);

            $profil = new Profil();
            $profil->fill($request->all());
            $profil->kabupaten_id = substr($profil->kecamatan_id, 0, 5);
            $profil->provinsi_id  = substr($profil->kecamatan_id, 0, 2);

            if ($request->hasFile('file_struktur_organisasi')) {
                $file     = $request->file('file_struktur_organisasi');
                $fileName = $file->getClientOriginalName();
                $request->file('file_struktur_organisasi')->move("storage/profil/struktur_organisasi/", $fileName);
                $profil->file_struktur_organisasi = 'storage/profil/struktur_organisasi/' . $fileName;
            }

            if ($request->hasFile('file_logo')) {
                $target_dir        = "uploads/";
                $target_file       = $target_dir . basename($_FILES["file_logo"]["name"]);
                $imageFileType     = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $profil->file_logo = $target_file . $imageFileType;
            }

            if ($profil->save()) {
                $id = DataUmum::create(['profil_id' => $profil->id, 'kecamatan_id' => $profil->kecamatan_id, 'embed_peta' => 'Edit Peta Pada Menu Data Umum.'])->id;
            }
            $desa      = Desa::where('kecamatan_id', '=', $profil->kecamatan_id)->get();
            $data_desa = [];
            foreach ($desa as $val) {
                $data_desa[] = [
                    'desa_id'      => $val->id,
                    'kecamatan_id' => strval($profil->kecamatan_id),
                    'nama'         => $val->nama,
                ];
            }

            DataDesa::insert($data_desa);
            return redirect()->route('data.profil.success', $id)->with('success', 'Profil berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Profil gagal disimpan!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show()
    {
        $desa              = Desa::where('kecamatan_id', '=', '1107062')->get();
        $data_desa = [];
        foreach ($desa as $val) {
            $data_desa[] = [
                'desa_id'      => strval($val->id),
                'kecamatan_id' => strval('1107062'),
                'nama'         => $val->nama,
            ];
        }

        DataDesa::insert($data_desa);
        return $data_desa;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $profil = Profil::findOrFail($id);
        if ($profil->file_struktur_organisasi == '') {
            $profil->file_struktur_organisasi = 'http://placehold.it/600x400';
        }
        $page_title       = 'Ubah';
        $page_description = 'Ubah Profil Kecamatan: ' . ucwords(strtolower($profil->kecamatan->nama));

        return view('data.profil.edit', compact('page_title', 'page_description', 'profil'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'kecamatan_id'             => 'required',
                'alamat'                   => 'required',
                'kode_pos'                 => 'required',
                'email'                    => 'email',
                'nama_camat'               => 'required',
                'file_logo'                => 'image|mimes:jpg,jpeg,bmp,png,gif|max:1024',
                'file_struktur_organisasi' => 'image|mimes:jpg,jpeg,png,bmp,gif|max:1024',
                'foto_kepala_wilayah'      => 'image|mimes:jpg,jpeg,png,bmp,gif|max:1024',
            ], []);

        try {
            $profil = Profil::find($id);
            $profil->fill($request->all());
            $profil->kabupaten_id = substr($profil->kecamatan_id, 0, 5);
            $profil->provinsi_id  = substr($profil->kecamatan_id, 0, 2);

            $dataumum               = DataUmum::where('profil_id', $id)->first();
            $dataumum->kecamatan_id = $profil->kecamatan_id;

            if ($request->file('file_struktur_organisasi') == "") {
                $profil->file_struktur_organisasi = $profil->file_struktur_organisasi;
            } else {
                $file     = $request->file('file_struktur_organisasi');
                $fileName = $file->getClientOriginalName();
                $request->file('file_struktur_organisasi')->move("storage/profil/struktur_organisasi/", $fileName);
                $profil->file_struktur_organisasi = 'storage/profil/struktur_organisasi/' . $fileName;
            }

            if ($request->file('file_logo') == "") {
                $profil->file_logo = $profil->file_logo;
            } else {
                $fileLogo     = $request->file('file_logo');
                $fileLogoName = $fileLogo->getClientOriginalName();
                $request->file('file_logo')->move("storage/profil/file_logo/", $fileLogoName);
                $profil->file_logo = 'storage/profil/file_logo/' . $fileLogoName;
            }
            if ($request->file('foto_kepala_wilayah') == "") {
                $profil->foto_kepala_wilayah = $profil->foto_kepala_wilayah;
            } else {
                $fileFoto     = $request->file('foto_kepala_wilayah');
                $fileFotoName = $fileFoto->getClientOriginalName();
                $request->file('foto_kepala_wilayah')->move("storage/profil/pegawai/", $fileFotoName);
                $profil->foto_kepala_wilayah = 'storage/profil/pegawai/' . $fileFotoName;
            }

            $profil->update();
            $dataumum->update();
            return redirect()->route('data.profil.success', $profil->dataumum->id)->with('success', 'Update Profil sukses!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Update Profil gagal!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
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
     * @return Response
     */
    public function success($id)
    {
        $page_title       = 'Konfirmasi?';
        $page_description = '';
        return view('data.profil.save_success', compact('id', 'page_title', 'page_description'));
    }
}
