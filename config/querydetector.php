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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

return [
    /*
     * Enable or disable the query detection.
     * If this is set to "null", the app.debug config value will be used.
     */
    'enabled' => env('QUERY_DETECTOR_ENABLED', null),

    /*
     * Threshold level for the N+1 query detection. If a relation query will be
     * executed more then this amount, the detector will notify you about it.
     */
    'threshold' => (int) env('QUERY_DETECTOR_THRESHOLD', 1),

    /*
     * Here you can whitelist model relations.
     *
     * Right now, you need to define the model relation both as the class name and the attribute name on the model.
     * So if an "Author" model would have a "posts" relation that points to a "Post" class, you need to add both
     * the "posts" attribute and the "Post::class", since the relation can get resolved in multiple ways.
     */
    'except' => [
        //Author::class => [
        //    Post::class,
        //    'posts',
        //]
    ],

    /*
     * Here you can set a specific log channel to write to
     * in case you are trying to isolate queries or have a lot
     * going on in the laravel.log. Defaults to laravel.log though.
     */
    'log_channel' => env('QUERY_DETECTOR_LOG_CHANNEL', 'daily'),

    /*
     * Define the output format that you want to use. Multiple classes are supported.
     * Available options are:
     *
     * Alert:
     * Displays an alert on the website
     * \BeyondCode\QueryDetector\Outputs\Alert::class
     *
     * Console:
     * Writes the N+1 queries into your browsers console log
     * \BeyondCode\QueryDetector\Outputs\Console::class
     *
     * Clockwork: (make sure you have the itsgoingd/clockwork package installed)
     * Writes the N+1 queries warnings to Clockwork log
     * \BeyondCode\QueryDetector\Outputs\Clockwork::class
     *
     * Debugbar: (make sure you have the barryvdh/laravel-debugbar package installed)
     * Writes the N+1 queries into a custom messages collector of Debugbar
     * \BeyondCode\QueryDetector\Outputs\Debugbar::class
     *
     * JSON:
     * Writes the N+1 queries into the response body of your JSON responses
     * \BeyondCode\QueryDetector\Outputs\Json::class
     *
     * Log:
     * Writes the N+1 queries into the Laravel.log file
     * \BeyondCode\QueryDetector\Outputs\Log::class
     */
    'output' => [
        \BeyondCode\QueryDetector\Outputs\Debugbar::class
    ]
];
