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
        $this->browser_title = SettingAplikasi::query()
            ->where('key', 'browser_title')
            ->first()
            ->value ?? $this->default_browser_title;
    }

    public function index()
    {
        return view('setting.aplikasi.index', [
            'page_title'    => 'Pegaturan Aplikasi', 
            'browser_title' => $this->browser_title
        ]);
    }

    public function edit_browser_title()
    {
        $setting                = SettingAplikasi::query()->where('key', 'browser_title')->first();
        $page_title             = 'Pegaturan Judul Aplikasi';
        $default_browser_title  = $this->default_browser_title;
        $browser_title          = $setting->value ?? null;

        return view('setting.aplikasi.edit_browser_title', compact(
            'page_title', 'browser_title', 'default_browser_title'
        ));
    }

    public function update_browser_title(Request $request)
    {
        try {
            $setting = SettingAplikasi::firstOrNew([
                'key'         => 'browser_title',
                'value'       => $this->default_browser_title,
                'type'        => "input",
                'description' => "Judul halaman aplikasi.",
                'option'      => '{}'
            ]);
            $setting->save();
            
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
