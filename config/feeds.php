<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Cache Location
    |--------------------------------------------------------------------------
    |
    | Filesystem path to use for caching, the default should be acceptable in
    | most cases.
    |
    */
    'cache.location'           => storage_path('framework/cache'),

    /*
    |--------------------------------------------------------------------------
    | Cache Life
    |--------------------------------------------------------------------------
    |
    | Life of cache, in seconds
    |
    */
    'cache.life'               => 600,

    /*
    |--------------------------------------------------------------------------
    | Cache Disabled
    |--------------------------------------------------------------------------
    |
    | Whether to disable the cache.
    |
    */
    'cache.disabled'           => false,

    /*
    |--------------------------------------------------------------------------
    | Disable Check for SSL certificates (enable for self signed certificates)
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'ssl_check.disabled'       => false,

    /*
    |--------------------------------------------------------------------------
    | Strip Html Tags Disabled
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'strip_html_tags.disabled' => false,

    /*
    |--------------------------------------------------------------------------
    | Stripped Html Tags
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'strip_html_tags.tags'     => [
        'base', 'blink', 'body', 'doctype', 'embed', 'font', 'form', 'frame', 'frameset', 'html', 'iframe', 'input',
        'marquee', 'meta', 'noscript', 'object', 'param', 'script', 'style',
    ],

    /*
    |--------------------------------------------------------------------------
    | Strip Attributes Disabled
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'strip_attribute.disabled' => false,

    /*
    |--------------------------------------------------------------------------
    | Stripped Attributes Tags
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'strip_attribute.tags'    => [
        'bgsound', 'class', 'expr', 'id', 'style', 'onclick', 'onerror', 'onfinish', 'onmouseover', 'onmouseout',
        'onfocus', 'onblur', 'lowsrc', 'dynsrc',
    ],

    /*
    |--------------------------------------------------------------------------
    | CURL Options
    |--------------------------------------------------------------------------
    |
    | Array of CURL options (see curl_setopt())
    | Set to null to disable
    |
    */
    'curl.options'             => null,

    'curl.timeout' => null,

];
