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
     * Default view used to render Javascript validation code
     */
    'view' => 'jsvalidation::bootstrap',

    /*
     * Default JQuery selector find the form to be validated.
     * By default, the validations are applied to all forms.
     */
    'form_selector' => 'form',

    /*
     * If you change the focus on detect some error then active
     * this parameter to move the focus to the first error found.
     */
    'focus_on_error' => false,

    /*
     * Duration time for the animation when We are moving the focus
     * to the first error, http://api.jquery.com/animate/ for more information.
     */
    'duration_animate' => 1000,

    /*
     * Enable or disable Ajax validations of Database and custom rules.
     * By default Unique, ActiveURL, Exists and custom validations are validated via AJAX
     */
    'disable_remote_validation' => false,

    /*
     * Field name used in the remote validation Ajax request
     * You can change this value to avoid conflicts wth your field names
     */
    'remote_validation_field' => '_jsvalidation',

];
