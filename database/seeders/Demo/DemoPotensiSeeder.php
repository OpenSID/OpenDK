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

use App\Models\Potensi;
use App\Models\TipePotensi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DemoPotensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Potensi::truncate();
        TipePotensi::truncate();
        Schema::enableForeignKeyConstraints();

        $tipeData = [
            ['nama_kategori' => 'Pertanian & Perkebunan'],
            ['nama_kategori' => 'Pariwisata & Alam'],
            ['nama_kategori' => 'UMKM & Ekonomi Kreatif'],
        ];

        $tipeIds = [];
        foreach ($tipeData as $tipe) {
            $tipePotensi  = TipePotensi::create($tipe);
            $tipeIds[]    = $tipePotensi->id;
        }

        $potensiData = [
            [
                'kategori_id'  => $tipeIds[0],
                'nama_potensi' => 'Kebun Kopi Arabika Ile Boleng',
                'deskripsi'    => 'Kawasan perkebunan kopi arabika seluas ±120 hektar yang dikelola oleh kelompok tani Makmur Sejahtera. Kopi Ile Boleng dikenal dengan cita rasa yang khas dan telah diekspor ke beberapa negara di Asia dan Eropa.',
                'lokasi'       => 'Desa Ile Boleng, Kecamatan Ile Boleng, Flores Timur',
                'lat'          => '-8.3245',
                'long'         => '122.9876',
                'file_gambar'  => 'https://picsum.photos/seed/kopi-arabika/800/500',
            ],
            [
                'kategori_id'  => $tipeIds[1],
                'nama_potensi' => 'Pantai Batu Karang Ile Boleng',
                'deskripsi'    => 'Pantai dengan formasi batu karang unik yang menjadi daya tarik wisata alam. Air laut yang jernih dengan warna biru toska menawarkan pemandangan bawah laut yang memukau, cocok untuk snorkeling dan diving.',
                'lokasi'       => 'Pesisir Selatan Kecamatan Ile Boleng, Flores Timur',
                'lat'          => '-8.4120',
                'long'         => '123.0123',
                'file_gambar'  => 'https://picsum.photos/seed/pantai-karang/800/500',
            ],
            [
                'kategori_id'  => $tipeIds[2],
                'nama_potensi' => 'Kerajinan Tenun Ikat Tradisional',
                'deskripsi'    => 'Kerajinan tenun ikat tradisional Flores yang diproduksi oleh para pengrajin lokal menggunakan teknik turun-temurun. Motif yang beragam mencerminkan kekayaan budaya dan filosofi masyarakat Flores Timur.',
                'lokasi'       => 'Kelompok Pengrajin Tenun, Desa Terong, Kecamatan Ile Boleng',
                'lat'          => '-8.3567',
                'long'         => '122.9543',
                'file_gambar'  => 'https://picsum.photos/seed/tenun-ikat/800/500',
            ],
        ];

        foreach ($potensiData as $potensi) {
            Potensi::create($potensi);
        }
    }
}
