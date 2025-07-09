<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Widget;

class SyncWidgets extends Command
{
    protected $signature = 'widgets:sync';
    protected $description = 'Sinkronisasi widget dari folder resources/views/widgets dan dari semua tema';

    public function handle()
    {
        $this->info("🔍 Memulai sinkronisasi widget...");

        $paths = Widget::listWidgetBaru();
        $count = 0;
        $urutMap = [
            'camat',
            'sinergi_program',
            'komplain',
            'event',
            'pengurus',
            'media_sosial',
            'media_terkait',
            'visitor',
        ];
        $maxUrut = Widget::max('urut') ?? 0; // Kalau null, mulai dari 0

        foreach ($paths as $fullPath) {
            if (!File::exists($fullPath)) {
                $this->warn("⚠️  File tidak ditemukan: $fullPath");
                continue;
            }

            // Ambil nama file: camat.blade.php => camat
            $basename = basename($fullPath, '.blade.php');

            // Judul otomatis: camat => Camat, sinergi_program => Sinergi Program
            $judul = ucwords(str_replace('_', ' ', $basename));
            $isi = basename(bersihkan_xss($fullPath));

            // Cek apakah sudah ada
            $exists = Widget::where('judul', $judul)
                ->where('isi', $isi)
                ->exists();

            if (!$exists) {

                // Cek apakah widget ada di daftar urutan manual
                if (in_array($basename, $urutMap)) {
                    $urut = array_search($basename, $urutMap) + 1; // Urutan berdasarkan posisi di array
                } else {
                    $maxUrut++;
                    $urut = $maxUrut; // Urutan lanjutan
                }

                Widget::create([
                    'judul' => $judul,
                    'isi' => $isi,
                    'jenis_widget' => 2,
                    'enabled' => 1,
                    'urut' => $urut,
                ]);

                $count++;
                $this->line("➕ Widget ditambahkan: {$judul} ({$isi}) [urut: {$urut}]");
            }
        }

        $this->info("✅ Sinkronisasi selesai. Total ditambahkan: {$count}");
    }
}
