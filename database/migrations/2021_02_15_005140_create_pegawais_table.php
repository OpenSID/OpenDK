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

class CreatePegawaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_pegawai', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_pegawai');
            $table->string('nip')->nullable();
            $table->enum('jenis_kelamin', ['1','2']);
            $table->char('agama_id', 2);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->char('status_kawin_id', 2);
            $table->string('nomor_karpeg')->nullable();
            $table->char('nik', 16)->unique();
            $table->enum('status_pegawai', ['CPNS','PNS','P3K','PPNPN','Honorer']);
            $table->string('pangkat_cpns')->nullable();
            $table->date('tmt_cpns')->nullable();
            $table->enum('pangkat', ['Juru Muda','Juru Muda Tingkat I','Juru','Juru Tingkat I','Pengatur Muda','Pengatur Muda Tingkat I','Pengatur','Pengatur Tingkat I','Penata Muda','Penata Muda Tingkat I','Penata','Penata Tingkat I','Pembina','Pembina Tingkat I','Pembina Utama Muda','Pembina Utama Madya','Pembina Utama'])->nullable();
            $table->enum('golongan', ['I','II','III','IV'])->nullable();
            $table->enum('ruang', ['a','b','c','d','e'])->nullable();
            $table->date('tmt_pangkat')->nullable();
            $table->date('tmt_jabatan')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('pendidikan');
            $table->date('tamat_pendidikan')->nullable();
            $table->enum('status', ['1', '2'])->default('1');
            $table->longText('foto')->nullable();
            $table->unsignedBigInteger('jabatan_id')->nullable();
            $table->foreign('jabatan_id')->references('id')->on('das_jabatan');
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
        Schema::dropIfExists('das_pegawai');
    }
}
