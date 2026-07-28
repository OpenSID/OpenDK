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

use App\Enums\Status;
use App\Models\Faq;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DemoFaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Faq::truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            [
                'question' => 'Apa itu OpenDK?',
                'answer'   => '<p>OpenDK (Dashboard Kecamatan Terbuka) adalah aplikasi manajemen informasi kecamatan berbasis web yang dikembangkan oleh Perkumpulan Desa Digital Terbuka (OpenDesa). Aplikasi ini dirancang untuk membantu pemerintah kecamatan dalam mengelola dan mempublikasikan data kependudukan, informasi pembangunan, potensi desa, serta layanan publik secara transparan dan mudah diakses oleh seluruh masyarakat.</p>',
                'status'   => Status::Aktif,
            ],
            [
                'question' => 'Bagaimana cara mengakses informasi publik kecamatan?',
                'answer'   => '<p>Seluruh informasi publik kecamatan dapat diakses melalui website resmi ini tanpa perlu registrasi atau login. Anda dapat melihat data statistik penduduk, informasi kegiatan kecamatan, artikel berita, regulasi, prosedur pelayanan, dan berbagai informasi lainnya secara gratis.</p><p>Untuk informasi lebih detail atau dokumen tertentu, Anda dapat mengunduh dokumen yang tersedia di menu <strong>Dokumen</strong> atau datang langsung ke kantor kecamatan pada jam kerja.</p>',
                'status'   => Status::Aktif,
            ],
            [
                'question' => 'Apa saja layanan administrasi yang tersedia di Kecamatan Ile Boleng?',
                'answer'   => '<p>Kecamatan Ile Boleng menyediakan berbagai layanan administrasi kependudukan dan pemerintahan, antara lain:</p><ul><li>Penerbitan dan pembaruan Kartu Tanda Penduduk Elektronik (KTP-el)</li><li>Pengurusan Kartu Keluarga (KK)</li><li>Surat keterangan pindah domisili</li><li>Rekomendasi berbagai keperluan administrasi</li><li>Legalisir dokumen</li><li>Layanan pengaduan masyarakat</li></ul><p>Seluruh prosedur layanan dapat dilihat di menu <strong>Prosedur</strong> pada website ini.</p>',
                'status'   => Status::Aktif,
            ],
            [
                'question' => 'Bagaimana cara menyampaikan pengaduan atau saran kepada kecamatan?',
                'answer'   => '<p>Masyarakat dapat menyampaikan pengaduan, saran, dan masukan melalui beberapa cara:</p><ul><li><strong>Online:</strong> Melalui menu <strong>Pengaduan</strong> di website ini</li><li><strong>Email:</strong> Kirim ke kecamatan.ileboleng@flotim.go.id</li><li><strong>Telepon:</strong> Hubungi (0383) 2123456 pada jam kerja (Senin–Jumat, 08.00–16.00 WITA)</li><li><strong>Langsung:</strong> Datang ke kantor kecamatan dan menemui petugas pelayanan</li></ul><p>Setiap pengaduan akan ditanggapi dalam waktu maksimal 3 hari kerja.</p>',
                'status'   => Status::Aktif,
            ],
            [
                'question' => 'Di mana lokasi dan jam operasional Kantor Kecamatan Ile Boleng?',
                'answer'   => '<p>Kantor Kecamatan Ile Boleng berlokasi di:</p><p><strong>Alamat:</strong> Jl. Trans Flores No. 01, Kecamatan Ile Boleng, Kabupaten Flores Timur, Nusa Tenggara Timur 86251</p><p><strong>Jam Operasional:</strong></p><ul><li>Senin – Kamis: 08.00 – 16.00 WITA</li><li>Jumat: 08.00 – 11.30 WITA</li><li>Sabtu, Minggu, dan hari libur nasional: Tutup</li></ul><p><strong>Telepon:</strong> (0383) 2123456<br><strong>Email:</strong> kecamatan.ileboleng@flotim.go.id</p>',
                'status'   => Status::Aktif,
            ],
        ];

        foreach ($data as $faq) {
            Faq::create($faq);
        }
    }
}
