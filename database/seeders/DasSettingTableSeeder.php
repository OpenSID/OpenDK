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

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DasSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'id' => 1,
                'key' => 'judul_aplikasi',
                'value' => 'Kecamatan',
                'type' => 'input',
                'description' => 'Judul halaman aplikasi.',
                'kategori' => 'sistem',
                'option' => '{}',
            ],
            [
                'id' => 2,
                'key' => 'artikel_kecamatan_perhalaman',
                'value' => '10',
                'type' => 'number',
                'description' => 'Jumlah artikel kecamatan dalam satu halaman yang ditampilkan',
                'kategori' => 'web',
                'option' => '{}',
            ],
            [
                'id' => 3,
                'key' => 'artikel_desa_perhalaman',
                'value' => '10',
                'type' => 'number',
                'description' => 'Jumlah artikel desa dalam satu halaman yang ditampilkan',
                'kategori' => 'web',
                'option' => '{}',
            ],
            [
                'id' => 4,
                'key' => 'jumlah_artikel_desa',
                'value' => '150',
                'type' => 'number',
                'description' => 'Jumlah semua artikel desa yang ditampilkan',
                'kategori' => 'web',
                'option' => '{}',
            ],
            [
                'id' => 5,
                'key' => 'tte',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Mengaktifkan modul TTE',
                'kategori' => 'surat',
                'option' => '{}',
            ],
            [
                'id' => 6,
                'key' => 'tte_api',
                'value' => '',
                'type' => 'input',
                'description' => 'URL API TTE',
                'kategori' => 'surat',
                'option' => '{}',
            ],
            [
                'id' => 7,
                'key' => 'tte_username',
                'value' => '',
                'type' => 'input',
                'description' => 'Username API TTE',
                'kategori' => 'surat',
                'option' => '{}',
            ],
            [
                'id' => 8,
                'key' => 'tte_password',
                'value' => '',
                'type' => 'input',
                'description' => 'Password API TTE',
                'kategori' => 'surat',
                'option' => '{}',
            ],
            [
                'id' => 9,
                'key' => 'pemeriksaan_camat',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Mengaktifkan pemeriksaan camat pada alur pemeriksaan surat',
                'kategori' => 'surat',
                'option' => '{}',
            ],
            [
                'id' => 10,
                'key' => 'pemeriksaan_sekretaris',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Mengaktifkan pemeriksaan sekretaris pada alur pemeriksaan surat',
                'kategori' => 'surat',
                'option' => '{}',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('das_setting')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
