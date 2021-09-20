<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSetingAplikasiRequest;
use App\Models\SettingAplikasi;
use Exception;

class AplikasiController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $settings = SettingAplikasi::all();
        if ($settings->isEmpty()) {
            SettingAplikasi::insert([
                'key'         => SettingAplikasi::KEY_BROWSER_TITLE,
                'value'       => $this->default_browser_title,
                'type'        => "input",
                'description' => "Judul halaman aplikasi.",
                'kategori'    => "-",
                'option'      => '{}'
            ]);
            $settings = SettingAplikasi::all();
        }

        return view('setting.aplikasi.index', [
            'page_title'    => 'Pegaturan Aplikasi',
            'settings'      => $settings,
        ]);
    }

    public function edit(SettingAplikasi $aplikasi)
    {
        $page_title             = 'Update Aplikasi';
        $page_description       = 'Edit Pengaturan Aplikasi Lainnya';
        $default_browser_title  = $this->default_browser_title;

        return view('setting.aplikasi.edit', compact(
            'page_title',
            'aplikasi',
            'default_browser_title',
            'page_description'
        ));
    }

    public function update(UpdateSetingAplikasiRequest $request, SettingAplikasi $aplikasi)
    {
        try {
            $data = $request->validated();
            if ($aplikasi->isBrowserTitle() && !$request->input('value')) {
                $data['value'] = $this->default_browser_title;
            }

            $aplikasi->update($data);

            return redirect()
                ->route('setting.aplikasi.index')
                ->with('success', 'Pengaturan aplikasi "' . $aplikasi->description . '" berhasil diupdate.');
        } catch (Exception $e) {
            return redirect()
                ->route('setting.aplikasi.edit', $aplikasi->id)
                ->with('error', 'Gagal mengupdate pengaturan ' . $aplikasi->description . ', error: "' . $e->getMessage() . '".');
        }
    }
}
