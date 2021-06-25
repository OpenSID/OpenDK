<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AplikasiController extends Controller
{
    public function index()
    {
        $page_title       = 'Pegaturan Aplikasi';
        $browser_title    = $this->browser_title;

        return view('setting.aplikasi.index', compact('page_title', 'browser_title'));
    }

    public function edit_browser_title()
    {
        $page_title               = 'Pegaturan Judul Aplikasi';
        $default_browser_title    = $this->default_browser_title;
        $browser_title            = $this->getProfilWilayah->browser_title ?? $this->browser_title;

        return view('setting.aplikasi.edit_browser_title', compact(
            'page_title', 'browser_title', 'default_browser_title'
        ));
    }

    public function update_browser_title(Request $request)
    {
        $this->getProfilWilayah->browser_title = $request->input('title') ?? $this->default_browser_title;
        $this->getProfilWilayah->save();
        $updated_browser_title =  $this->getProfilWilayah->browser_title ?? $this->default_browser_title;

        return redirect()
            ->route('setting.aplikasi.index')
            ->with('success', 'Halaman aplikasi berhasil dirubah menjadi "' . $updated_browser_title . '".');
    }
}
