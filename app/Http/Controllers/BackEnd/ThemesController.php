<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\BackEndController;
use App\Models\Themes;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ThemesController extends BackEndController
{
    public function index()
    {
        $page_title = 'Tema';
        $page_description = 'Daftar Tema';
        $themes = Themes::orderBy('active', 'desc')->get();

        return view('backend.themes.index', compact('page_title', 'page_description', 'themes'));
    }

    public function activate(Themes $themes)
    {
        Themes::where('active', 1)->update(['active' => 0]);
        $themes->update(['active' => 1]);

        return redirect()->route('setting.themes.index')->with('success', 'Tema berhasil diaktifkan');
    }

    public function reScan()
    {
        scan_themes();

        return redirect()->route('setting.themes.index')->with('success', 'Tema berhasil pindai ulang');
    }

    public function upload()
    {
        try {
            $file = request()->file('file');

            if ($file->getClientOriginalExtension() !== 'zip') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File harus berformat .zip',
                ]);
            }

            $fileName = $file->getClientOriginalName();
            $filePath = storage_path('framework/themes');
            $file->move($filePath, $fileName);

            $zip = new \ZipArchive;
            if ($zip->open("$filePath/$fileName") === true) {
                $extractedPath = base_path('themes/extracted');
                $zip->extractTo($extractedPath);
                $zip->close();

                $folderTheme = explode('.', $fileName)[0];
                $composerPath = "$extractedPath/$folderTheme/composer.json";

                if (file_exists($composerPath)) {
                    $composerData = json_decode(file_get_contents($composerPath), true);
                    $newFolder = base_path('themes/'.$composerData['name']);

                    if (File::move("$extractedPath/$folderTheme", $newFolder)) {
                        File::deleteDirectory($extractedPath);
                        File::deleteDirectory($filePath);

                        scan_themes();

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Tema berhasil diunggah',
                        ]);
                    } else {
                        File::deleteDirectory($extractedPath);
                        File::deleteDirectory($filePath);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('File upload failed: '.$e->getMessage());
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Tema gagal diunggah',
        ]);
    }

    // destroy
    public function destroy(Themes $themes)
    {
        $themes->delete();

        return redirect()->route('setting.themes.index')->with('success', 'Theme berhasil dihapus');
    }
}
