<?php

namespace App\Http\Controllers\SistemKomplain;

use App\Http\Controllers\Controller;
use App\Models\JawabKomplain;
use App\Models\Komplain;
use App\Models\Penduduk;
use App\Models\Wilayah;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use function back;
use function compact;
use function config;
use function mt_rand;
use function redirect;
use function request;
use function response;
use function str_slug;
use function view;

class SistemKomplainController extends Controller
{
    public function index()
    {
        $page_title       = 'SIKEMA';
        $page_description = 'Sistem Keluhan Masyarakat';

        $komplains = Komplain::with('kategori_komplain')
            ->where('status', '<>', 'DITOLAK')
            ->where('status', '<>', 'REVIEW')
            ->orderBy('created_at', 'desc')->paginate(10);
        return view('sistem_komplain.komplain.index', compact('page_title', 'page_description', 'komplains'));
    }

    public function indexKategori($slug)
    {
        $page_title       = 'SIKEMA';
        $page_description = 'Sistem Keluhan Masyarakat';

        $komplains = Komplain::where('kategori', '=', $slug)->orderBy('created_at', 'desc')->paginate(10);
        return view('sistem_komplain.komplain.index', compact('page_title', 'page_description', 'komplains'));
    }

    public function indexSukses()
    {
        $page_title       = 'SIKEMA';
        $page_description = 'Sistem Keluhan Masyarakat';

        $komplains = Komplain::where('status', '=', 'Belum')->orderBy('created_at', 'desc')->paginate(10);
        return view('sistem_komplain.komplain.index', compact('page_title', 'page_description', 'komplains'));
    }

    public function kirim()
    {
        $page_title       = 'Kirim Keluhan';
        $page_description = 'Kirim Keluhan Baru';

        return view('sistem_komplain.komplain.kirim', compact('page_title', 'page_description'));
    }

    public function tracking(Request $request)
    {
        try {
            $komplain = Komplain::where('komplain_id', '=', $request->post('q'))->firstOrFail();
            return redirect()->route('sistem-komplain.komplain', $komplain->slug);
        } catch (Exception $e) {
            return back()->with('warning', 'Komplain tidak ditemukan!');
        }
    }

    protected function generateID()
    {
        $id  = mt_rand(100000, 999999);
        $kid = '';

        if (! Komplain::where('komplain_id', '=', $id)->exists()) {
            $kid = $id;
        } else {
            $this->generateID();
        }

        return $kid;
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nik'           => 'required|numeric|nik_exists:' . $request->input('tanggal_lahir'),
                'judul'         => 'required',
                'kategori'      => 'required',
                'laporan'       => 'required',
                'captcha'       => 'required|captcha',
                'tanggal_lahir' => 'password_exists:' . $request->input('nik'),
                'lampiran1'     => 'file|mimes:jpeg,png,jpg,gif,svg|max:1024',
                'lampiran2'     => 'file|mimes:jpeg,png,jpg,gif,svg|max:1024',
                'lampiran3'     => 'file|mimes:jpeg,png,jpg,gif,svg|max:1024',
                'lampiran4'     => 'file|mimes:jpeg,png,jpg,gif,svg|max:1024',
            ], [
                'captcha.captcha' => 'Invalid captcha code.',
                'nik_exists'      => 'NIK tidak ditemukan atau NIK dan Tanggal Lahir tidak sesuai.',
                'password_exists' => 'NIK dan Tanggal Lahir tidak sesuai.',
            ]);

            if ($validator->fails()) {
                return back()->withInput()->with('error', 'Komplain gagal dikirim!')->withErrors($validator);
            }
            $komplain = new Komplain($request->all());

            $komplain->komplain_id = $this->generateID();
            $komplain->slug        = str_slug($komplain->judul) . '-' . $komplain->komplain_id;
            $komplain->status      = 'REVIEW';
            $komplain->dilihat     = 0;
            $komplain->nama        = Penduduk::where('nik', $komplain->nik)->first()->nama;

            // Save if lampiran available
            if ($request->hasFile('lampiran1')) {
                $lampiran1 = $request->file('lampiran1');
                $fileName1 = $lampiran1->getClientOriginalName();
                $path      = "storage/komplain/" . $komplain->komplain_id . '/';
                $request->file('lampiran1')->move($path, $fileName1);
                $komplain->lampiran1 = $path . $fileName1;
            }

            if ($request->hasFile('lampiran2')) {
                $lampiran2 = $request->file('lampiran2');
                $fileName2 = $lampiran2->getClientOriginalName();
                $path      = "storage/komplain/" . $komplain->komplain_id . '/';
                $request->file('lampiran2')->move($path, $fileName2);
                $komplain->lampiran2 = $path . $fileName2;
            }

            if ($request->hasFile('lampiran3')) {
                $lampiran3 = $request->file('lampiran3');
                $fileName3 = $lampiran3->getClientOriginalName();
                $path      = "storage/komplain/" . $komplain->komplain_id . '/';
                $request->file('lampiran3')->move($path, $fileName3);
                $komplain->lampiran3 = $path . $fileName3;
            }

            if ($request->hasFile('lampiran4')) {
                $lampiran4 = $request->file('lampiran4');
                $fileName4 = $lampiran4->getClientOriginalName();
                $path      = "storage/komplain/" . $komplain->komplain_id . '/';
                $request->file('lampiran3')->move($path, $fileName4);
                $komplain->lampiran4 = $path . $fileName4;
            }

            $komplain->save();
            return redirect()->route('sistem-komplain.index')->with('success', 'Komplain berhasil dikirim. Tunggu Admin untuk di review terlebih dahulu!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Komplain gagal dikirim!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $komplain         = Komplain::where('komplain_id', '=', $id)->first();
        $page_title       = 'Edit Komplain';
        $page_description = 'Komplain ' . $komplain->komplain_id;
        return view('sistem_komplain.komplain.edit', compact('page_title', 'page_description', 'komplain'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // Save Request
        try {
            request()->validate([
                'nik'      => 'required|numeric',
                'judul'    => 'required',
                'kategori' => 'required',
                'laporan'  => 'required',
            ], ['captcha.captcha' => 'Invalid captcha code.']);

            $komplain = Komplain::findOrFail($id);
            $komplain->fill($request->all());
            $komplain->nama = Penduduk::where('nik', $komplain->nik)->first()->nama;

            // Save if lampiran available
            if ($request->hasFile('lampiran1')) {
                $lampiran1 = $request->file('lampiran1');
                $fileName1 = $lampiran1->getClientOriginalName();
                $path      = "storage/komplain/" . $komplain->komplain_id . '/';
                $request->file('lampiran1')->move($path, $fileName1);
                $komplain->lampiran1 = $path . $fileName1;
            }

            if ($request->hasFile('lampiran2')) {
                $lampiran2 = $request->file('lampiran2');
                $fileName2 = $lampiran2->getClientOriginalName();
                $path      = "storage/komplain/" . $komplain->komplain_id . '/';
                $request->file('lampiran2')->move($path, $fileName2);
                $komplain->lampiran2 = $path . $fileName2;
            }

            if ($request->hasFile('lampiran3')) {
                $lampiran3 = $request->file('lampiran3');
                $fileName3 = $lampiran3->getClientOriginalName();
                $path      = "storage/komplain/" . $komplain->komplain_id . '/';
                $request->file('lampiran3')->move($path, $fileName3);
                $komplain->lampiran3 = $path . $fileName3;
            }

            if ($request->hasFile('lampiran4')) {
                $lampiran4 = $request->file('lampiran4');
                $fileName4 = $lampiran4->getClientOriginalName();
                $path      = "storage/komplain/" . $komplain->komplain_id . '/';
                $request->file('lampiran4')->move($path, $fileName4);
                $komplain->lampiran4 = $path . $fileName4;
            }

            $komplain->save();
            return redirect()->route('sistem-komplain.index')->with('success', 'Komplain berhasil dikirim!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Komplain gagal dikirim!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            Komplain::findOrFail($id)->delete();

            return redirect()->route('sistem-komplain.index')->with('success', 'Keluhan sukses dihapus!');
        } catch (Exception $e) {
            return redirect()->route('sistem-komplain.index')->with('error', 'Keluhan gagal dihapus!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  slug
     * @return Response
     */
    public function show($slug)
    {
        try {
            $komplain = Komplain::where('slug', '=', $slug)->first();
        } catch (Exception $ex) {
            return back()->withInput()->with('error', $ex);
        }
        $camat            = Wilayah::where('kode', '=', config('app.default_profile'))->first();
        $page_title       = 'Detail Laporan';
        $page_description = $komplain->judul;

        return view('sistem_komplain.komplain.show', compact('page_title', 'page_description', 'komplain', 'camat'));
    }

    public function reply(Request $request, $id)
    {
        if (request()->ajax()) {
            try {
                $jawab = new JawabKomplain();

                if ($request->input('nik') == '999') {
                    request()->validate([
                        'jawaban' => 'required',
                    ]);
                    $jawab->fill($request->all());
                    $jawab->penjawab = 'Admin';
                } else {
                    request()->validate([
                        'jawaban'       => 'required',
                        'nik'           => 'required|numeric|nik_exists:' . $request->input('tanggal_lahir'),
                        'tanggal_lahir' => 'password_exists:' . $request->input('nik'),
                    ], [
                        'nik_exists'      => 'NIK tidak ditemukan atau NIK dan Tanggal Lahir tidak sesuai.',
                        'password_exists' => 'NIK dan Tanggal Lahir tidak sesuai.',
                    ]);

                    $jawab->fill($request->all());
                    $jawab->penjawab = $request->input('nik');
                }

                $jawab->komplain_id = $id;

                $jawab->save();
                $response = [
                    'status' => 'success',
                    'msg'    => 'Jawaban  berhasil disimpan!',
                ];
                return response()->json($response);
            } catch (Exception $ex) {
                $response = [
                    'status' => 'error',
                    'msg'    => 'Jawaban  gagal disimpan!',
                ];
                return response()->json($response);
            }
        } else {
            return response('You not allowed!', 304);
        }
    }

    public function getJawabans(Request $request)
    {
        $jawabans = JawabKomplain::where('komplain_id', $request->input('id'))->orderBy('id', 'desc')->get();
        return view('sistem_komplain.komplain.jawabans', compact('jawabans'))->render();
    }
}
