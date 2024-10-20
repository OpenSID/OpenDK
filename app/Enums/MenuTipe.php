<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Enums;

use App\Models\Potensi;
use BenSampo\Enum\Enum;
use App\Models\DataDesa;
use App\Models\TipePotensi;

/**
 * Jenis atau tipe jabatan untuk pengurus
 */
final class MenuTipe extends Enum
{
    public const PROFIL    = 1;
    public const DESA      = 2;
    public const STATISTIK = 3;
    public const POTENSI   = 4;
    public const UNDUHAN   = 5;
    public const PUBLIKASI   = 6;
    public const EKSTERNAL = 0;


    public static function all(): array
    {
        return [
            self::PROFIL => 'Profil',
            self::DESA => 'Desa',
            self::STATISTIK => 'Statistik',
            self::POTENSI => 'Potensi',
            self::UNDUHAN => 'Unduhan',
            self::PUBLIKASI => 'Publikasi',
            self::EKSTERNAL => 'Eksternal',
        ];
    }

    public static function getProfil(): array
    {
        return [
            'sejarah' => 'Sejarah',
            'visi-dan-misi' => 'Visi dan Misi',
            'letak-geografis' => 'Letak Geografis',
            'struktur-pemerintahan' => 'Struktur Pemerintahan',
            'sambutan' => 'Sambutan'
        ];
    }

    public static function getDesa(): array
    {
        return DataDesa::get()->pluck('nama', 'sebutan_desa')->toArray();
    }

    public static function getPotensi(): array
    {
        return TipePotensi::get()->pluck('nama_kategori', 'slug')->toArray();
    }

    public static function getStatistik(): array
    {
        return [
            'kependudukan' => 'Kependudukan',
            'pendidikan' => 'Pendidikan',
            'kesehatan' => 'Kesehatan',
            'program-dan-bantuan' => 'Program dan Bantuan',
            'anggaran-dan-realisasi' => 'Anggaran dan Realisasi',
            'anggaran-desa' => 'Anggaran Desa',
        ];
    }

    public static function getUnduhan(): array
    {
        return [
            'peraturan' => 'Peraturan',
            'formulir' => 'Formulir',
            'laporan' => 'Laporan',
            'dokumen' => 'Dokumen',
        ];
    }

    public static function getPublikasi(): array
    {
        return [
            'galeri' => 'Galeri',
        ];
    }
}
