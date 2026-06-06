<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Application Service Providers
    |--------------------------------------------------------------------------
    |
    | Provider aplikasi yang didaftarkan secara eksplisit. Pada Laravel 11+,
    | provider inti Illuminate sudah di-auto-register sehingga tidak perlu
    | dicantumkan di sini. Hanya provider kustom & paket yang perlu didaftarkan.
    |
    */

    // Provider kustom aplikasi
    App\Providers\AppServiceProvider::class,
    App\Providers\KDServiceProvider::class,
    App\Providers\SmtpServiceProvider::class,

    // Paket — hanya yang tidak auto-discover via composer atau perlu eksplisit
    UniSharp\LaravelFilemanager\LaravelFilemanagerServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    Spatie\Html\HtmlServiceProvider::class,
    Maatwebsite\Excel\ExcelServiceProvider::class,
    Mews\Captcha\CaptchaServiceProvider::class,
    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
    Rap2hpoutre\LaravelLogViewer\LaravelLogViewerServiceProvider::class,
];
