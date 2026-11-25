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

use App\Models\ToiletSanitasi;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

beforeEach(function () {
    $this->withoutMiddleware();

    Excel::fake();

    // Kosongkan data agar validasi unique bulan/tahun tidak bentrok dengan data lama (di-rollback oleh transaksi test).
    ToiletSanitasi::query()->delete();

    $this->actingAs(User::factory()->create());
});

function file_impor_toilet_sanitasi(): UploadedFile
{
    return UploadedFile::fake()->create(
        'toilet-sanitasi.xlsx',
        10,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    );
}

test('impor toilet sanitasi redirect ke halaman index dengan pesan sukses', function () {
    $response = $this->post(route('data.toilet-sanitasi.do_import'), [
        'file' => file_impor_toilet_sanitasi(),
        'bulan' => 1,
        'tahun' => 2099,
    ]);

    $response->assertRedirect(route('data.toilet-sanitasi.index'))
        ->assertSessionHas('success', 'Data berhasil diimpor!');

    Excel::matchByRegex();
    Excel::assertQueued('/.+/');
});

test('impor toilet sanitasi gagal validasi tanpa file', function () {
    $response = $this->from(route('data.toilet-sanitasi.import'))
        ->post(route('data.toilet-sanitasi.do_import'), [
            'bulan' => 1,
            'tahun' => 2099,
        ]);

    $response->assertRedirect(route('data.toilet-sanitasi.import'))
        ->assertSessionHasErrors('file');
});
