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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendudukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_penduduk', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 100);
            $table->char('nik', 16)->unique();
            $table->integer('id_kk')->nullable(true);
            $table->tinyInteger('kk_level')->nullable(true);
            $table->integer('id_rtm')->nullable(true);
            $table->integer('rtm_level')->nullable(true);
            $table->tinyInteger('sex')->nullable(true);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->integer('agama_id')->nullable();
            $table->integer('pendidikan_kk_id')->nullable(true);
            $table->integer('pendidikan_id')->nullable(true);
            $table->integer('pendidikan_sedang_id')->nullable(true);
            $table->integer('pekerjaan_id')->nullable(true);
            $table->tinyInteger('status_kawin')->nullable(true);
            $table->integer('warga_negara_id')->nullable(true);
            $table->string('dokumen_pasport', 45)->nullable(true);
            $table->string('dokumen_kitas', 45)->nullable(true);
            $table->string('ayah_nik', 16)->nullable(true);
            $table->string('ibu_nik', 16)->nullable(true);
            $table->string('nama_ayah', 100)->nullable(true);
            $table->string('nama_ibu', 100)->nullable(true);
            $table->string('foto', 255)->nullable(true);
            $table->integer('golongan_darah_id')->nullable(true);
            $table->integer('id_cluster')->nullable(true);
            $table->integer('status')->nullable(true);
            $table->string('alamat_sebelumnya', 255)->nullable(true);
            $table->string('alamat_sekarang', 255)->nullable(true);
            $table->tinyInteger('status_dasar');
            $table->integer('hamil')->nullable(true);
            $table->integer('cacat_id')->nullable(true);
            $table->integer('sakit_menahun_id')->nullable(true);
            $table->string('akta_lahir', 40)->nullable(true);
            $table->string('akta_perkawinan', 40)->nullable(true);
            $table->date('tanggal_perkawinan')->nullable(true);
            $table->string('akta_perceraian', 40)->nullable(true);
            $table->date('tanggal_perceraian')->nullable(true);
            $table->tinyInteger('cara_kb_id')->nullable(true);
            $table->string('telepon', 20)->nullable(true);
            $table->date('tanggal_akhir_pasport')->nullable(true);
            $table->string('no_kk', 30)->nullable(true);
            $table->string('no_kk_sebelumnya', 30)->nullable(true);
            $table->tinyInteger('ktp_el')->nullable(true);
            $table->tinyInteger('status_rekam')->nullable(true);
            $table->string('alamat', 255)->nullable();
            $table->string('dusun', 255)->nullable();
            $table->string('rw', 10)->nullable();
            $table->string('rt', 10)->nullable();
            $table->char('desa_id', 10)->nullable(true);
            $table->char('kecamatan_id', 7)->nullable(true);
            $table->char('kabupaten_id', 4)->nullable(true);
            $table->char('provinsi_id', 2)->nullable(true);
            $table->integer('tahun')->nullable();
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
        Schema::dropIfExists('das_penduduk');
    }
}
