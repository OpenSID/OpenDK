<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SettingAplikasi;
use Exception;

class AplikasiController extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function index()
    {
        $settings = SettingAplikasi::all();
        if ($settings->isEmpty()) {
            $setting = SettingAplikasi::insert([
                'key'         => SettingAplikasi::KEY_BROWSER_TITLE,
                'value'       => $this->default_browser_title,
                'type'        => "input",
                'description' => "Judul halaman aplikasi.",
                'option'      => '{"edit_route":"setting.aplikasi.edit_browser_title"}'
            ]);
            $setting->save();
            $settings = SettingAplikasi::all();
        }

        return view('setting.aplikasi.index', [
            'page_title'    => 'Pegaturan Aplikasi', 
            'settings'      => $settings,
        ]);
    }

    public function edit(SettingAplikasi $setting)
    {
        $page_title             = 'Pegaturan Judul Aplikasi';
        $default_browser_title  = $this->default_browser_title;

        return view('setting.aplikasi.edit', compact(
            'page_title', 'setting', 'default_browser_title'
        ));
    }

    public function update(Request $request, SettingAplikasi $setting)
    {
        try {
            $browser_title = $request->input('title') ?? $this->default_browser_title;
            $setting->update([
                'value'       => $browser_title,
                'type'        => "input",
                'description' => "Judul halaman aplikasi.",
                'option'      => '{}'
            ]);
    
            return redirect()
                ->route('setting.aplikasi.index')
                ->with('success', 'Halaman aplikasi berhasil dirubah menjadi "' . $browser_title . '".');
        } catch (Exception $e) {
            return redirect()
                ->route('setting.aplikasi.index')
                ->with('error', 'Gagal mengupdate halaman judul "' . $e->getMessage() . '".');
        }
    }
}
