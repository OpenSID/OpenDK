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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataUmumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_data_umum', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('profil_id')->nullable();
            $table->char('kecamatan_id', 7);
            $table->string('tipologi', 255)->nullable(true);
            $table->integer('ketinggian')->nullable(true);
            $table->double('luas_wilayah')->nullable(true);
            $table->integer('jumlah_penduduk')->nullable(true);
            $table->integer('jml_laki_laki')->nullable(true);
            $table->integer('jml_perempuan')->nullable(true);
            $table->string('bts_wil_utara', 255)->nullable(true);
            $table->string('bts_wil_timur', 255)->nullable(true);
            $table->string('bts_wil_selatan', 255)->nullable(true);
            $table->string('bts_wil_barat', 255)->nullable(true);
            $table->integer('jml_puskesmas')->nullable(true);
            $table->integer('jml_puskesmas_pembantu')->nullable(true);
            $table->integer('jml_posyandu')->nullable(true);
            $table->integer('jml_pondok_bersalin')->nullable(true);
            $table->integer('jml_paud')->nullable(true);
            $table->integer('jml_sd')->nullable(true);
            $table->integer('jml_smp')->nullable(true);
            $table->integer('jml_sma')->nullable(true);
            $table->integer('jml_masjid_besar')->nullable(true);
            $table->integer('jml_mushola')->nullable(true);
            $table->integer('jml_gereja')->nullable(true);
            $table->integer('jml_pasar')->nullable(true);
            $table->integer('jml_balai_pertemuan')->nullable(true);
            $table->integer('kepadatan_penduduk')->nullable(true);
            $table->longText('embed_peta')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('das_data_umum');
    }
}
