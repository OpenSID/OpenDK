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
 * KEWAJIBAN ATAUPUN PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Support;

/**
 * Content Security Policy untuk seluruh halaman.
 *
 * Dipisahkan dari middleware agar tiap directive bisa diuji secara terpisah
 * dan mudah ditambahkan ketika ada sumber daya baru yang diizinkan.
 */
class CspPolicy
{
    /**
     * Directive CSP beserta sumber daya yang diizinkan.
     *
     * media-src mengizinkan https: karena galeri dapat memutar video dari
     * hosting situs desa mana pun, dan frame-src mengizinkan penyedia video
     * yang hanya menyediakan preview lewat iframe (Google Drive, YouTube, Vimeo).
     *
     * @var array<string, array<int, string>>
     */
    protected const DIRECTIVES = [
        'default-src' => ["'self'"],
        'script-src' => [
            "'self'",
            'https://pantau.opensid.my.id/',
            'https://cdnjs.cloudflare.com/ajax/libs/tinymce/',
            'https://cdn.jsdelivr.net/npm/',
            'https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js',
            'https://website-widgets.pages.dev/dist/sienna.min.js',
            'platform.twitter.com',
            'unpkg.com',
            "'unsafe-inline'",
            "'unsafe-eval'",
        ],
        'style-src' => [
            "'self'",
            'https://www.tiny.cloud/',
            'http://www.tinymce.com/css/codepen.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/tinymce/',
            'https://cdn.jsdelivr.net/npm/',
            'fonts.googleapis.com',
            'unpkg.com',
            "'unsafe-inline'",
        ],
        'img-src' => ["'self'", '*', 'data:', 'blob:'],
        'font-src' => [
            "'self'",
            'https://cdnjs.cloudflare.com/ajax/libs/tinymce/',
            'https://cdn.jsdelivr.net/npm/',
            'fonts.gstatic.com',
            'data:',
        ],
        'media-src' => ["'self'", 'https:', 'blob:'],
        'frame-src' => [
            "'self'",
            'blob:',
            'data:',
            'platform.twitter.com',
            'github.com',
            '*.youtube.com',
            '*.vimeo.com',
            'https://drive.google.com',
            '*.opensid.my.id',
        ],
        'object-src' => ["'self'", 'blob:'],
        'frame-ancestors' => ["'self'"],
        'base-uri' => ["'self'"],
    ];

    /**
     * Nilai seluruh directive dalam format header CSP.
     */
    public function toHeader(): string
    {
        $directives = self::DIRECTIVES;

        $directives['connect-src'] = array_values(array_filter(array_merge(
            ["'self'"],
            [config('setting.api_server_database_gabungan')],
            ['https://pantau.opensid.my.id/']
        )));

        $parts = [];

        foreach ($directives as $name => $sources) {
            $parts[] = $name . ' ' . implode(' ', $sources);
        }

        return implode(';', $parts) . ';';
    }

    /**
     * Nilai satu directive, mis. media-src.
     */
    public function directive(string $name): string
    {
        return implode(' ', self::DIRECTIVES[$name] ?? []);
    }
}
