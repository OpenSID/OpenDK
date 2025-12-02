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

use App\Enums\{JenisJabatan, LogVerifikasiSurat, StatusSurat};
use App\Http\Middleware\{Authenticate, CompleteProfile};
use App\Models\Jabatan;
use App\Models\Pengurus;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Middleware\{PermissionMiddleware, RoleMiddleware};
use Tests\TestCase;

class PermohonanControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected string $tableName;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tableName = (new Surat())->getTable();

        $this->withViewErrors([]);
        $this->withoutMiddleware([
            Authenticate::class,
            RoleMiddleware::class,
            PermissionMiddleware::class,
            CompleteProfile::class,
        ]);
    }

    public function test_index_menampilkan_view_permohonan()
    {
        $this->get(route('surat.permohonan'))
            ->assertStatus(200)
            ->assertViewIs('surat.permohonan.index');
    }

    public function test_show_menampilkan_detail_permohonan_surat()
    {
        $jabatan = Jabatan::factory()->create([
            'nama' => 'Sekretaris',
            'jenis' => JenisJabatan::Sekretaris,
        ]);

        $pengurus = Pengurus::factory()->create([
            'jabatan_id' => $jabatan->id,
        ]);

        $user = User::factory()->create([
            'pengurus_id' => $pengurus->id,
        ]);

        $this->actingAs($user);

        $surat = Surat::factory()->create([
            'log_verifikasi' => LogVerifikasiSurat::Sekretaris,
            'pengurus_id' => $pengurus->id,
        ]);

        $this->app->bind(\App\Http\Controllers\Surat\PermohonanController::class, function () use ($pengurus) {
            $controller = new \App\Http\Controllers\Surat\PermohonanController();
            $controller->setAkunSekretaris($pengurus);
            return $controller;
        });

        $this->get(route('surat.permohonan.show', $surat->id))
            ->assertStatus(200)
            ->assertViewIs('surat.permohonan.show');

        $this->assertDatabaseHas($this->tableName, [
            'id' => $surat->id,
        ]);
    }

    public function test_tolak_memperbarui_status_surat_ke_ditolak()
    {
        $surat = Surat::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = ['keterangan' => 'Alasan penolakan surat ini'];

        $this->postJson(route('surat.permohonan.tolak', $surat->id), $data)
            ->assertStatus(200);

        $this->assertDatabaseHas($this->tableName, [
            'id' => $surat->id,
            'log_verifikasi' => LogVerifikasiSurat::Ditolak,
            'status' => StatusSurat::Ditolak,
            'keterangan' => $data['keterangan'],
        ]);
    }

    public function test_ditolak_menampilkan_view_ditolak()
    {
        $this->get(route('surat.permohonan.ditolak'))
            ->assertStatus(200)
            ->assertViewIs('surat.permohonan.ditolak');
    }
}
