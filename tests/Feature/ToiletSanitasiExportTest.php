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

use App\Models\DataDesa;
use App\Models\ToiletSanitasi;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withoutMiddleware();

    // Buat user untuk testing
    $this->user = User::factory()->create();

    // Buat desa untuk testing
    $this->desa = DataDesa::factory()->create();
});

afterEach(function () {
    // Bersihkan data test
    ToiletSanitasi::query()->delete();
    DataDesa::query()->delete();
    User::query()->delete();
});

test('dapat mengakses halaman export toilet sanitasi', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('data.toilet-sanitasi.export-excel'));

    $response->assertSuccessful();
    expect($response->headers->get('Content-Type'))->toBe('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

test('dapat export data toilet sanitasi kosong', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('data.toilet-sanitasi.export-excel'));

    $response->assertSuccessful();
    expect($response->headers->get('Content-Type'))->toBe('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

test('dapat export data toilet sanitasi dengan data', function () {
    $this->actingAs($this->user);

    // Buat data toilet sanitasi untuk testing
    ToiletSanitasi::factory()->count(3)->create([
        'desa_id' => $this->desa->desa_id
    ]);

    $response = $this->get(route('data.toilet-sanitasi.export-excel'));

    $response->assertSuccessful();
    expect($response->headers->get('Content-Type'))->toBe('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

test('export toilet sanitasi menghasilkan filename dengan timestamp', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('data.toilet-sanitasi.export-excel'));

    $response->assertSuccessful();

    $disposition = $response->headers->get('Content-Disposition');
    expect($disposition)->toContain('data-toilet-sanitasi-')
        ->and($disposition)->toContain('.xlsx');
});

test('export toilet sanitasi memiliki header yang benar', function () {
    $this->actingAs($this->user);

    // Buat data untuk memastikan ada konten
    ToiletSanitasi::factory()->create([
        'desa_id' => $this->desa->desa_id
    ]);

    $response = $this->get(route('data.toilet-sanitasi.export-excel'));

    $response->assertSuccessful();
    expect($response->headers->get('Content-Type'))->toBe('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});
