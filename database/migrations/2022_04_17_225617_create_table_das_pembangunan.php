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

class CreateTableDasPembangunan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_pembangunan', function (Blueprint $table) {
            $table->integer('id', false);
            $table->char('desa_id', 13);
            $table->string('lokasi', 255)->nullable();
            $table->string('sumber_dana', 255)->nullable();
            $table->string('judul', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->string('volume', 255)->nullable();
            $table->year('tahun_anggaran')->nullable();
            $table->string('pelaksana_kegiatan', 255)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('foto', 255)->nullable();
            $table->float('anggaran', 65, 2)->nullable();
            $table->float('perubahan_anggaran', 65, 2)->nullable();
            $table->float('sumber_biaya_pemerintah', 65, 2)->nullable();
            $table->float('sumber_biaya_provinsi', 65, 2)->nullable();
            $table->float('sumber_biaya_kab_kota', 65, 2)->nullable();
            $table->float('sumber_biaya_swadaya', 65, 2)->nullable();
            $table->float('sumber_biaya_jumlah', 65, 2)->nullable();
            $table->string('manfaat', 100)->nullable();
            $table->integer('waktu')->nullable();
            $table->timestamps();
            $table->unique(['id', 'desa_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('das_pembangunan');
    }
}
