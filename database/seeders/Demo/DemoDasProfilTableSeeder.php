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

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDasProfilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('das_profil')->truncate();

        DB::table('das_profil')->insert([
            'id'                             => 1,
            'provinsi_id'                    => '53',
            'nama_provinsi'                  => 'Nusa Tenggara Timur',
            'kabupaten_id'                   => '53.06',
            'nama_kabupaten'                 => 'FLORES TIMUR',
            'kecamatan_id'                   => '53.06.13',
            'nama_kecamatan'                 => 'Ile Boleng',
            'alamat'                         => 'Jl. Trans Flores No. 01, Kecamatan Ile Boleng, Kabupaten Flores Timur',
            'kode_pos'                       => '86251',
            'telepon'                        => '(0383) 2123456',
            'email'                          => 'kecamatan.ileboleng@flotim.go.id',
            'tahun_pembentukan'              => 1990,
            'dasar_pembentukan'              => 'Perda No. 4 Tahun 1990',
            'nama_camat'                     => 'H. Hadi Fathurrahman, S.Sos, M.AP',
            'sekretaris_camat'               => 'Drs. Zaenal Abidin, M.Si',
            'kepsek_pemerintahan_umum'       => 'Musyayad, S.Sos',
            'kepsek_kesejahteraan_masyarakat'=> 'Suhartono, S.Sos',
            'kepsek_pemberdayaan_masyarakat' => 'Asrarudin, SE',
            'kepsek_pelayanan_umum'          => 'Masturi, ST',
            'kepsek_trantib'                 => 'Mastur Idris, SH',
            'file_struktur_organisasi'       => null,
            'file_logo'                      => null,
            'visi'                           => '<p><strong>Visi:</strong></p><p>"Terwujudnya Kecamatan Ile Boleng yang Maju, Sejahtera, dan Berbudaya Berlandaskan Nilai-nilai Kearifan Lokal"</p>',
            'misi'                           => '<p><strong>Misi:</strong></p><ol><li>Meningkatkan kualitas pelayanan publik yang cepat, transparan, dan akuntabel kepada seluruh masyarakat kecamatan.</li><li>Mengoptimalkan pembangunan infrastruktur dasar untuk mendukung pertumbuhan ekonomi dan peningkatan kualitas hidup masyarakat.</li><li>Mendorong pengembangan potensi unggulan daerah meliputi sektor pertanian, pariwisata, dan ekonomi kreatif.</li><li>Memperkuat tata kelola pemerintahan desa yang demokratis, partisipatif, dan berbasis data.</li><li>Melestarikan nilai-nilai budaya dan kearifan lokal sebagai identitas dan kebanggaan masyarakat Ile Boleng.</li></ol>',
            'sambutan'                       => '<h4>Sambutan Camat Ile Boleng</h4><p>Assalamu\'alaikum Warrahmatullahi Wabarakatuh,<br>Salam Sejahtera bagi kita semua.</p><p>Puji syukur kehadirat Tuhan Yang Maha Esa atas segala rahmat dan karunia-Nya sehingga website resmi Kecamatan Ile Boleng dapat hadir sebagai sarana informasi dan komunikasi antara pemerintah dan masyarakat.</p><p>Kecamatan Ile Boleng merupakan salah satu kecamatan di Kabupaten Flores Timur, Provinsi Nusa Tenggara Timur. Kecamatan ini memiliki potensi alam yang luar biasa, mulai dari keindahan pantai, perkebunan kopi arabika, hingga kekayaan budaya tenun ikat tradisional yang telah dikenal hingga mancanegara.</p><p>Melalui website ini, kami berkomitmen untuk menyajikan informasi yang akurat, transparan, dan terkini mengenai berbagai program dan kegiatan pemerintahan, data kependudukan, potensi desa, serta berbagai layanan publik yang tersedia di Kecamatan Ile Boleng.</p><p>Kami mengundang seluruh masyarakat untuk berpartisipasi aktif dalam proses pembangunan dan memberikan masukan yang konstruktif demi kemajuan kecamatan yang kita cintai bersama.</p><p>Wassalamu\'alaikum Warrahmatullahi Wabarakatuh.</p><p><strong>H. Hadi Fathurrahman, S.Sos, M.AP</strong><br><em>Camat Ile Boleng</em></p>',
            'created_at'                     => now(),
            'updated_at'                     => now(),
        ]);
    }
}
