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

use App\Models\Artikel;
use App\Models\ArtikelKategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DemoArtikelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Artikel::truncate();
        ArtikelKategori::truncate();
        Schema::enableForeignKeyConstraints();

        // Buat kategori artikel
        $kategoriData = [
            ['nama_kategori' => 'Berita Kecamatan'],
            ['nama_kategori' => 'Pengumuman'],
            ['nama_kategori' => 'Kegiatan'],
            ['nama_kategori' => 'Informasi Publik'],
        ];

        $kategoriIds = [];
        foreach ($kategoriData as $k) {
            $kategori       = ArtikelKategori::create($k);
            $kategoriIds[]  = $kategori->id_kategori;
        }

        $artikelData = [
            [
                'judul'       => 'Musyawarah Perencanaan Pembangunan Kecamatan Tahun 2024',
                'isi'         => '<p>Kecamatan Ile Boleng telah melaksanakan Musyawarah Perencanaan Pembangunan (Musrenbang) Tingkat Kecamatan Tahun 2024. Kegiatan ini dihadiri oleh seluruh kepala desa, tokoh masyarakat, perwakilan perempuan, dan pemuda se-kecamatan.</p><p>Musrenbang membahas berbagai program prioritas pembangunan yang akan diusulkan ke tingkat kabupaten, meliputi perbaikan infrastruktur jalan, peningkatan layanan kesehatan, dan pengembangan potensi ekonomi desa.</p><p>Camat Ile Boleng, H. Hadi Fathurrahman, S.Sos, M.AP menegaskan bahwa seluruh usulan akan diperjuangkan agar dapat terakomodir dalam APBD Kabupaten Flores Timur.</p>',
                'gambar_seed' => 'musrenbang',
                'kategori'    => 0,
                'tanggal'     => now()->subDays(3),
            ],
            [
                'judul'       => 'Sosialisasi Program Bantuan Sosial bagi Keluarga Kurang Mampu',
                'isi'         => '<p>Pemerintah Kecamatan Ile Boleng bekerja sama dengan Dinas Sosial Kabupaten Flores Timur menyelenggarakan sosialisasi Program Bantuan Sosial (Bansos) bagi keluarga kurang mampu. Kegiatan ini bertujuan agar masyarakat memahami syarat dan prosedur pendaftaran penerima manfaat.</p><p>Program yang disosialisasikan antara lain Program Keluarga Harapan (PKH), Bantuan Pangan Non Tunai (BPNT), dan Bantuan Langsung Tunai (BLT). Masyarakat yang belum terdaftar namun merasa memenuhi syarat dapat melapor ke kantor desa masing-masing.</p>',
                'gambar_seed' => 'bantuan-sosial',
                'kategori'    => 1,
                'tanggal'     => now()->subDays(7),
            ],
            [
                'judul'       => 'Pelatihan Digital Marketing bagi UMKM Kecamatan',
                'isi'         => '<p>Dalam rangka meningkatkan kapasitas pelaku Usaha Mikro Kecil dan Menengah (UMKM), Kecamatan Ile Boleng menggelar pelatihan digital marketing selama dua hari. Peserta mendapatkan pembekalan tentang cara memasarkan produk melalui media sosial, marketplace, dan website.</p><p>Narasumber dari Dinas Koperasi dan UMKM Kabupaten Flores Timur memaparkan strategi pemasaran digital yang efektif dan terjangkau bagi pelaku usaha skala kecil. Sebanyak 45 pelaku UMKM dari 12 desa berpartisipasi dalam kegiatan ini.</p>',
                'gambar_seed' => 'umkm-digital',
                'kategori'    => 2,
                'tanggal'     => now()->subDays(10),
            ],
            [
                'judul'       => 'Pencanangan Kampung Sehat Bebas Stunting',
                'isi'         => '<p>Kecamatan Ile Boleng mendeklarasikan program Kampung Sehat Bebas Stunting sebagai komitmen bersama untuk menurunkan angka stunting di wilayah kecamatan. Program ini melibatkan seluruh unsur pemerintahan desa, tenaga kesehatan, dan kader posyandu.</p><p>Berdasarkan data terkini, angka stunting di kecamatan ini masih berada di atas rata-rata nasional. Melalui program ini, pemerintah kecamatan berkomitmen untuk melakukan intervensi gizi, edukasi ibu hamil, dan pemantauan tumbuh kembang anak secara berkala.</p>',
                'gambar_seed' => 'stunting-sehat',
                'kategori'    => 0,
                'tanggal'     => now()->subDays(14),
            ],
            [
                'judul'       => 'Gotong Royong Pembersihan Lingkungan Menyambut HUT RI',
                'isi'         => '<p>Menyambut Hari Ulang Tahun Kemerdekaan Republik Indonesia, seluruh elemen masyarakat Kecamatan Ile Boleng menggelar kegiatan gotong royong massal. Kegiatan ini diikuti oleh aparatur kecamatan, kepala desa, siswa sekolah, dan masyarakat umum.</p><p>Kegiatan bersih-bersih dilaksanakan di berbagai titik strategis seperti jalan utama kecamatan, area pasar tradisional, lingkungan sekolah, dan fasilitas umum lainnya. Semangat gotong royong ini mencerminkan nilai kebersamaan yang terus dijaga oleh masyarakat.</p>',
                'gambar_seed' => 'gotong-royong',
                'kategori'    => 2,
                'tanggal'     => now()->subDays(18),
            ],
            [
                'judul'       => 'Pengumuman Penerimaan CPNS dan PPPK Tahun 2024',
                'isi'         => '<p>Pemerintah Kabupaten Flores Timur membuka pendaftaran Calon Pegawai Negeri Sipil (CPNS) dan Pegawai Pemerintah dengan Perjanjian Kerja (PPPK) Tahun 2024. Pendaftaran dilakukan secara online melalui portal resmi Badan Kepegawaian Negara (BKN).</p><p>Formasi yang tersedia meliputi tenaga guru, tenaga kesehatan, dan tenaga teknis. Masyarakat yang berminat dapat mendaftarkan diri mulai 1 September hingga 30 September 2024. Informasi lebih lanjut dapat diperoleh di kantor kecamatan atau website resmi Pemkab Flores Timur.</p>',
                'gambar_seed' => 'cpns-pppk',
                'kategori'    => 1,
                'tanggal'     => now()->subDays(21),
            ],
            [
                'judul'       => 'Panen Raya Padi Varietas Unggul di Desa Ile Boleng',
                'isi'         => '<p>Petani Desa Ile Boleng merayakan panen raya padi varietas unggul yang berhasil meningkatkan produktivitas hingga 40% dibandingkan tahun sebelumnya. Keberhasilan ini merupakan hasil dari penerapan teknologi pertanian modern dan pendampingan intensif dari penyuluh pertanian.</p><p>Camat Ile Boleng menyampaikan apresiasi atas kerja keras para petani dan berharap keberhasilan ini dapat menjadi motivasi bagi desa-desa lain untuk menerapkan metode pertanian yang sama. Pemerintah kecamatan akan terus mendukung pengembangan sektor pertanian sebagai tulang punggung perekonomian masyarakat.</p>',
                'gambar_seed' => 'panen-padi',
                'kategori'    => 3,
                'tanggal'     => now()->subDays(25),
            ],
            [
                'judul'       => 'Pembangunan Jalan Desa Tahap II Dimulai',
                'isi'         => '<p>Pembangunan infrastruktur jalan desa tahap kedua resmi dimulai dengan peletakan batu pertama oleh Camat Ile Boleng bersama Kepala Desa dan tokoh masyarakat setempat. Proyek senilai Rp 1,2 miliar ini akan menghubungkan dua desa yang selama ini aksesnya terbatas.</p><p>Pembangunan ditargetkan selesai dalam waktu empat bulan dan diharapkan dapat meningkatkan mobilitas masyarakat, memperlancar distribusi hasil pertanian, serta membuka akses ke fasilitas pendidikan dan kesehatan yang lebih mudah.</p>',
                'gambar_seed' => 'jalan-desa',
                'kategori'    => 0,
                'tanggal'     => now()->subDays(30),
            ],
            [
                'judul'       => 'Peluncuran Layanan Administrasi Online Kecamatan',
                'isi'         => '<p>Kecamatan Ile Boleng resmi meluncurkan layanan administrasi berbasis online sebagai bagian dari program transformasi digital pemerintahan. Melalui layanan ini, masyarakat dapat mengurus berbagai keperluan administrasi tanpa harus antri panjang di kantor kecamatan.</p><p>Layanan yang tersedia secara online antara lain pengurusan surat keterangan, rekomendasi, dan verifikasi dokumen. Masyarakat cukup mengakses website kecamatan dan mengikuti panduan yang tersedia. Tim IT kecamatan siap membantu masyarakat yang mengalami kesulitan.</p>',
                'gambar_seed' => 'layanan-online',
                'kategori'    => 3,
                'tanggal'     => now()->subDays(35),
            ],
            [
                'judul'       => 'Festival Budaya Kecamatan: Merayakan Keberagaman Tradisi Lokal',
                'isi'         => '<p>Festival Budaya Kecamatan Ile Boleng sukses digelar selama dua hari dengan menampilkan berbagai pertunjukan seni dan budaya dari seluruh desa yang ada di wilayah kecamatan. Ribuan penonton memadati lokasi festival yang berlangsung meriah dan penuh warna.</p><p>Berbagai atraksi budaya ditampilkan, mulai dari tarian tradisional, musik daerah, pameran kerajinan tangan, hingga kuliner khas lokal. Festival ini bertujuan untuk melestarikan budaya lokal sekaligus menjadi ajang promosi wisata dan ekonomi kreatif di kecamatan.</p>',
                'gambar_seed' => 'festival-budaya',
                'kategori'    => 2,
                'tanggal'     => now()->subDays(40),
            ],
        ];

        foreach ($artikelData as $index => $data) {
            $gambar = $this->downloadGambar($data['gambar_seed']);

            Artikel::create([
                'id_kategori'    => $kategoriIds[$data['kategori']] ?? $kategoriIds[0],
                'judul'          => $data['judul'],
                'gambar'         => $gambar,
                'isi'            => $data['isi'],
                'status'         => 1,
                'tanggal_terbit' => $data['tanggal'],
            ]);
        }
    }

    /**
     * Unduh gambar dari Picsum Photos dan simpan ke storage/artikel/.
     */
    private function downloadGambar(string $seed, int $width = 800, int $height = 500): ?string
    {
        try {
            $url      = "https://picsum.photos/seed/{$seed}/{$width}/{$height}";
            $response = Http::timeout(15)->get($url);

            if ($response->successful()) {
                $filename = "demo_{$seed}.jpg";
                Storage::disk('public')->put("artikel/{$filename}", $response->body());

                return $filename;
            }
        } catch (\Exception $e) {
            // Jika gagal unduh, kembalikan null (aman — accessor handle null)
            logger()->warning("DemoArtikelSeeder: gagal unduh gambar [{$seed}]: " . $e->getMessage());
        }

        return null;
    }
}
