<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $page_title       = 'Pegaturan Beranda';
        $browser_title    = $this->browser_title;

        return view('setting.dashboard.index', compact('page_title', 'browser_title'));
    }

    public function edit_browser_title()
    {
        $page_title               = 'Pegaturan Judul Beranda';
        $default_browser_title    = $this->default_browser_title;
        $custom_browser_title     = $this->getProfilWilayah->browser_title;

        return view('setting.dashboard.edit_browser_title', compact(
            'page_title', 'custom_browser_title', 'default_browser_title'
        ));
    }

    public function update_browser_title(Request $request)
    {
        if($request->input('custom_title')) {
            $use_custom_title = $request->input('use_custom_title');
            $this->getProfilWilayah->browser_title = $use_custom_title ? $request->input('custom_title') : null;
            $this->getProfilWilayah->save();
            $updated_browser_title =  $use_custom_title ? $this->getProfilWilayah->browser_title : $this->default_browser_title;

            return redirect()
                ->route('setting.dashboard.index')
                ->with('success', 'Halaman judul berhasil dirubah menjadi "' . $updated_browser_title . '".');
        }

        return redirect()->route('setting.dashboard.index');
    }
}
