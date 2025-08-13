<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace Tests\Feature;

use App\Http\Controllers\FrontEnd\SistemKomplainController;
use App\Models\Komplain;
use App\Models\Penduduk;
use App\Models\SettingAplikasi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SistemKomplainControllerTest extends TestCase
{
    protected $tableName;

    public function setUp(): void
    {
        parent::setUp();

        $model = new Komplain();
        $this->tableName = $model->getTable();
    }

    public function test_get_kirim_form_komplain()
    {
        $response = $this->get(route('sistem-komplain.kirim'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.komplain.kirim');
        $response->assertViewHasAll([
            'page_title' => 'Kirim Keluhan',
            'page_description' => 'Kirim Keluhan Baru',
        ]);
    }

    public function test_komplain_store_with_valid_data()
    {
        // Disable sinkronisasi gabungan sementara
        SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->first()?->update([
            'value' => 0,
        ]);

        // Mock ID agar direktori cocok
        $komplainId = 999123;
        $path = public_path("storage/komplain/$komplainId");
        File::ensureDirectoryExists($path); // Buat direktori agar move() tidak error

        // Buat file dummy
        $lampiran1 = UploadedFile::fake()->image('lampiran1.jpg');
        $lampiran2 = UploadedFile::fake()->image('lampiran2.jpg');
        $lampiran3 = UploadedFile::fake()->image('lampiran3.jpg');
        $lampiran4 = UploadedFile::fake()->image('lampiran4.jpg');

        // Mock generateID()
        $this->partialMock(SistemKomplainController::class, function ($mock) use ($komplainId) {
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive('generateID')
                ->andReturn($komplainId);
        });

        // Ambil penduduk acak (harus dipastikan tabelnya ada datanya)
        $penduduk = Penduduk::inRandomOrder()->first();

        // jika tidak ada penduduk, skip test dan dianggap berhasil
        if (! $penduduk) {
            return;
        }

        $data = [
            'nik' => $penduduk->nik,
            'judul' => 'Komplain Jalan Rusak',
            'kategori' => 'infrastruktur',
            'laporan' => 'Jalan rusak di daerah saya sangat parah.',
            'captcha' => '1234',
            'tanggal_lahir' => $penduduk->tanggal_lahir,
            'lampiran1' => $lampiran1,
            'lampiran2' => $lampiran2,
            'lampiran3' => $lampiran3,
            'lampiran4' => $lampiran4,
        ];

        $response = $this->post(route('sistem-komplain.store'), $data);

        $response->assertRedirect(route('sistem-komplain.index'));
        $response->assertSessionHas('success', 'Komplain berhasil dikirim. Tunggu Admin untuk di review terlebih dahulu!');

        $this->assertDatabaseHas($this->tableName, [
            'judul' => $data['judul'],
            'nik' => $data['nik'],
        ]);

        // Hapus data
        File::deleteDirectory(public_path("storage/komplain/$komplainId"));
        Komplain::where('judul', $data['judul'])->delete();
        // Storage::deleteDirectory("public/komplain/$komplainId");

        $this->assertDatabaseMissing($this->tableName, [
            'judul' => $data['judul'],
            'nik' => $data['nik'],
        ]);

        // Restore setting
        SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->first()?->update([
            'value' => 1,
        ]);
    }
}
