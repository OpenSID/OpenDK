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
use App\Models\Penduduk;
use App\Models\SettingAplikasi;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use function Pest\Laravel\withToken;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

const KOLOM_XLSX_PENDUDUK = [
    'desa_id', 'id', 'nomor_nik', 'nama', 'nomor_kk', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
    'agama', 'pendidikan_dlm_kk', 'pendidikan_sdg_ditempuh', 'pekerjaan', 'kawin', 'hubungan_keluarga',
    'kewarganegaraan', 'nama_ibu', 'nama_ayah', 'gol_darah', 'akta_lahir', 'nomor_dokumen_pasport',
    'tanggal_akhir_pasport', 'nomor_dokumen_kitas', 'nik_ayah', 'nik_ibu', 'nomor_akta_perkawinan',
    'tanggal_perkawinan', 'nomor_akta_perceraian', 'tanggal_perceraian', 'cacat', 'cara_kb', 'hamil',
    'foto', 'alamat_sekarang', 'alamat', 'dusun', 'rw', 'rt', 'status_dasar', 'status_rekam',
    'created_at', 'updated_at',
];

function barisPenduduk(string $desaId, int $idPendDesa): array
{
    return array_merge(array_fill_keys(KOLOM_XLSX_PENDUDUK, null), [
        'desa_id' => $desaId,
        'id' => $idPendDesa,
        'nomor_nik' => '3324030101900001',
        'nama' => 'Penduduk Sinkron',
        'nomor_kk' => '3324030101900002',
        'jenis_kelamin' => 1,
        'tempat_lahir' => 'Kendal',
        'tanggal_lahir' => '1990-01-01',
        'agama' => 1,
        'pendidikan_dlm_kk' => 3,
        'pendidikan_sdg_ditempuh' => 1,
        'pekerjaan' => 1,
        'kawin' => 1,
        'hubungan_keluarga' => 1,
        'kewarganegaraan' => 1,
        'nama_ibu' => 'Ibu',
        'nama_ayah' => 'Ayah',
        'gol_darah' => 1,
        'hamil' => 0,
        'alamat' => 'Jl. Desa',
        'dusun' => 'Dusun 1',
        'rw' => '001',
        'rt' => '001',
        'status_dasar' => 1,
        'status_rekam' => 1,
        'created_at' => '2026-01-01 00:00:00',
        'updated_at' => '2026-01-01 00:00:00',
    ]);
}

/**
 * Buat zip seperti kiriman OpenSID; tanpa $baris zip hanya berisi berkas teks.
 */
function zipPenduduk(?array $baris): UploadedFile
{
    $dir = storage_path('framework/testing/sinkron-penduduk');
    File::ensureDirectoryExists($dir);
    $zipPath = $dir.'/penduduk.zip';

    $zip = new ZipArchive();
    $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    if ($baris === null) {
        $zip->addFromString('bukan_data.txt', 'kosong');
    } else {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray([KOLOM_XLSX_PENDUDUK, array_values($baris)]);
        $xlsxPath = $dir.'/penduduk_opendk.xlsx';
        (new Xlsx($spreadsheet))->save($xlsxPath);
        $zip->addFile($xlsxPath, 'penduduk_opendk.xlsx');
    }

    $zip->close();

    return new UploadedFile($zipPath, 'penduduk.zip', 'application/zip', null, true);
}

beforeEach(function () {
    $this->desa = DataDesa::factory()->create(['desa_id' => '33.24.03.2008']);

    $this->token = auth('api')->login(User::factory()->create());
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_opendk'],
        ['value' => $this->token, 'type' => 'input', 'description' => 'API Key OpenDK', 'kategori' => 'aplikasi', 'option' => '[]']
    );

    $this->berkasTempAwal = File::glob(storage_path('app/public/temp/*'));
});

afterEach(function () {
    // storedata meninggalkan zip di disk public/temp; bersihkan hanya berkas hasil test ini
    File::delete(array_diff(File::glob(storage_path('app/public/temp/*')), $this->berkasTempAwal));
    File::deleteDirectory(storage_path('framework/testing/sinkron-penduduk'));
});

describe('POST /api/v1/penduduk/storedata', function () {
    test('berhasil mengimpor penduduk dengan id_pend_desa di atas batas int', function () {
        $idBesar = 2499099148;

        $response = withToken($this->token)->post('/api/v1/penduduk/storedata', [
            'file' => zipPenduduk(barisPenduduk($this->desa->desa_id, $idBesar)),
        ]);

        $response->assertOk()->assertJson(['status' => 'success']);

        expect(Penduduk::where('desa_id', $this->desa->desa_id)->where('id_pend_desa', $idBesar)->exists())
            ->toBeTrue();
    });

    test('membalas json 500 bila zip tidak berisi xlsx', function () {
        $response = withToken($this->token)->post('/api/v1/penduduk/storedata', [
            'file' => zipPenduduk(null),
        ]);

        $response->assertStatus(500)
            ->assertExactJson([
                'status' => 'danger',
                'message' => 'Import data penduduk gagal. Periksa log OpenDK untuk detail kesalahan.',
            ]);
    });

    test('membalas json 500 bila desa pada xlsx tidak terdaftar', function () {
        $response = withToken($this->token)->post('/api/v1/penduduk/storedata', [
            'file' => zipPenduduk(barisPenduduk('99.99.99.9999', 1)),
        ]);

        $response->assertStatus(500)->assertJson(['status' => 'danger']);
    });

    test('membalas json 422 tanpa redirect bila file tidak dikirim', function () {
        // OpenSID tidak mengirim header Accept: application/json
        $response = withToken($this->token)->post('/api/v1/penduduk/storedata', []);

        $response->assertStatus(422)
            ->assertJson(['status' => 'danger'])
            ->assertJsonValidationErrors('file');
    });
});
