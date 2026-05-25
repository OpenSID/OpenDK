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

use App\Models\PpidJenisDokumen;
use App\Models\PpidPengaturan;
use Illuminate\Database\Seeder;

class PpidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedPengaturan();
        $this->seedJenisDokumen();
    }

    /**
     * Seed default PPID settings
     */
    private function seedPengaturan(): void
    {
        $pengaturan = [
            [
                'kunci' => 'banner',
                'nilai' => 'default-ppid-banner.jpg',
                'keterangan' => 'Banner PPID',
            ],
            [
                'kunci' => 'memperoleh_informasi',
                'nilai' => json_encode([
                    'Mengambil informasi di kantor desa (hardcopy)',
                    'Dikirim melalui email',
                    'Melalui Whatsapp pengguna (Softcopy)',
                ]),
                'keterangan' => 'Opsi memperoleh informasi',
            ],
            [
                'kunci' => 'alasan_keberatan',
                'nilai' => json_encode([
                    'Permohonan Informasi ditolak',
                    'Informasi berkala tidak tersedia',
                    'Permintaan informasi ditanggapi tidak sebagaimana yang diminta',
                    'Permintaan informasi tidak dipenuhi',
                    'Biaya yang dikenakan tidak wajar',
                    'Informasi disampaikan melebihi jangka waktu yang ditentukan',
                ]),
                'keterangan' => 'Opsi pemohonan keberatan',
            ],
            [
                'kunci' => 'salinan_informasi',
                'nilai' => json_encode([
                    'Mengambil Langsung',
                    'Email',
                    'Faksimili',
                    'Kurir',
                    'Pos',
                ]),
                'keterangan' => 'Opsi salinan informasi',
            ],
        ];

        foreach ($pengaturan as $data) {
            PpidPengaturan::updateOrCreate(
                ['kunci' => $data['kunci']],
                [
                    'nilai' => $data['nilai'],
                    'keterangan' => $data['keterangan'],
                ]
            );
        }

        $this->command->info('PPID Pengaturan seeded successfully.');
    }

    /**
     * Seed default jenis dokumen PPID
     */
    private function seedJenisDokumen(): void
    {
        $jenisDokumen = [
            [
                'slug' => 'berkala',
                'nama' => 'Berkala',
                'urutan' => 1,
                'status' => 'aktif',
                'is_kunci' => false,
            ],
            [
                'slug' => 'serta_merta',
                'nama' => 'Serta Merta',
                'urutan' => 2,
                'status' => 'aktif',
                'is_kunci' => false,
            ],
            [
                'slug' => 'setiap_saat',
                'nama' => 'Setiap Saat',
                'urutan' => 3,
                'status' => 'aktif',
                'is_kunci' => false,
            ],
        ];

        foreach ($jenisDokumen as $data) {
            PpidJenisDokumen::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $this->command->info('PPID Jenis Dokumen seeded successfully.');
    }
}
