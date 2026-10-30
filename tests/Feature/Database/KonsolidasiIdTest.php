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


namespace Tests\Feature\Database;

use Illuminate\Support\Facades\DB;

const OFFSET_SLOT = 300000000000; // slot 3

function tipeKolom(string $tabel, string $kolom): string
{
    return (string) DB::table('information_schema.columns')
        ->where('table_schema', DB::getDatabaseName())
        ->where('table_name', $tabel)->where('column_name', $kolom)
        ->value('data_type');
}

describe('Konsolidasi: kolom id BIGINT', function () {
    test('kolom id desa dan id penduduk gabungan bertipe bigint', function () {
        foreach ([
            'das_penduduk' => ['id_pend_desa'],
            'das_pembangunan' => ['id'],
            'das_pembangunan_dokumentasi' => ['id', 'id_pembangunan'],
            'das_program' => ['id'],
            'das_peserta_program' => ['program_id'],
            'das_lembaga' => ['penduduk_id_gabungan'],
            'das_lembaga_anggota' => ['penduduk_id_gabungan'],
            'das_suplemen_terdata' => ['penduduk_id_gabungan'],
        ] as $tabel => $kolom) {
            foreach ($kolom as $k) {
                expect(tipeKolom($tabel, $k))->toBe('bigint', "{$tabel}.{$k}");
            }
        }
    });

    test('id peserta program (kunci utama) bigint unsigned auto-increment dan menyimpan id sebesar offset slot', function () {
        $kolom = DB::table('information_schema.columns')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', 'das_peserta_program')->where('column_name', 'id')
            ->first(['column_type as tipe', 'extra as tambahan']);
        expect(strtolower($kolom->tipe))->toBe('bigint unsigned');
        expect(strtolower($kolom->tambahan))->toContain('auto_increment');

        DB::table('das_peserta_program')->insert([
            'id' => OFFSET_SLOT + 9, 'desa_id' => '1234567890123', 'peserta' => '1234567890123456',
            'program_id' => OFFSET_SLOT + 2, 'kartu_nik' => '1234567890123456',
        ]);
        expect((int) DB::table('das_peserta_program')->where('desa_id', '1234567890123')->value('id'))->toBe(OFFSET_SLOT + 9);
    });

    test('id laporan APBDes dan laporan penduduk bigint dan menyimpan id sebesar offset slot', function () {
        expect(tipeKolom('das_apbdes', 'id_apbdes'))->toBe('bigint');
        expect(tipeKolom('das_laporan_penduduk', 'id_laporan_penduduk'))->toBe('bigint');

        DB::table('das_apbdes')->insert(['judul' => 'a', 'desa_id' => '1234567890123', 'id_apbdes' => OFFSET_SLOT + 4]);
        DB::table('das_laporan_penduduk')->insert([
            'judul' => 'b', 'bulan' => 1, 'tahun' => 2026, 'nama_file' => 'b.pdf',
            'desa_id' => '1234567890123', 'id_laporan_penduduk' => OFFSET_SLOT + 6,
        ]);
        expect((int) DB::table('das_apbdes')->where('desa_id', '1234567890123')->value('id_apbdes'))->toBe(OFFSET_SLOT + 4);
        expect((int) DB::table('das_laporan_penduduk')->where('desa_id', '1234567890123')->value('id_laporan_penduduk'))->toBe(OFFSET_SLOT + 6);
    });

    test('id sebesar offset slot dapat disimpan', function () {
        DB::table('das_pembangunan')->insert([
            'id' => OFFSET_SLOT + 7, 'desa_id' => '1234567890123', 'judul' => 'uji',
        ]);
        expect((int) DB::table('das_pembangunan')->where('desa_id', '1234567890123')->value('id'))->toBe(OFFSET_SLOT + 7);
    });
});

describe('konsolidasi:geser-id-desa', function () {
    test('menggeser id satu desa saja dan menolak dijalankan dua kali', function () {
        DB::table('das_pembangunan')->insert([
            ['id' => 5, 'desa_id' => '1234567890123', 'judul' => 'a'],
            ['id' => 5, 'desa_id' => '9999999999999', 'judul' => 'b'],
        ]);
        DB::table('das_program')->insert(['id' => 2, 'desa_id' => '1234567890123', 'nama' => 'p']);

        $this->artisan('konsolidasi:geser-id-desa', ['desa_id' => '1234567890123', 'offset' => OFFSET_SLOT, '--dry-run' => true])->assertSuccessful();
        expect((int) DB::table('das_pembangunan')->where('desa_id', '1234567890123')->value('id'))->toBe(5);

        $this->artisan('konsolidasi:geser-id-desa', ['desa_id' => '1234567890123', 'offset' => OFFSET_SLOT])->assertSuccessful();
        expect((int) DB::table('das_pembangunan')->where('desa_id', '1234567890123')->value('id'))->toBe(OFFSET_SLOT + 5);
        expect((int) DB::table('das_pembangunan')->where('desa_id', '9999999999999')->value('id'))->toBe(5);
        expect((int) DB::table('das_program')->where('desa_id', '1234567890123')->value('id'))->toBe(OFFSET_SLOT + 2);

        $this->artisan('konsolidasi:geser-id-desa', ['desa_id' => '1234567890123', 'offset' => OFFSET_SLOT])->assertFailed();
        expect((int) DB::table('das_pembangunan')->where('desa_id', '1234567890123')->value('id'))->toBe(OFFSET_SLOT + 5);
    });

    test('menggeser id_apbdes dan id_laporan_penduduk satu desa saja agar upsert tidak menggandakan baris', function () {
        DB::table('das_apbdes')->insert([
            ['judul' => 'a', 'desa_id' => '1234567890123', 'id_apbdes' => 3],
            ['judul' => 'a', 'desa_id' => '9999999999999', 'id_apbdes' => 3],
        ]);
        DB::table('das_laporan_penduduk')->insert([
            ['judul' => 'b', 'bulan' => 1, 'tahun' => 2026, 'nama_file' => 'b.pdf', 'desa_id' => '1234567890123', 'id_laporan_penduduk' => 8],
            ['judul' => 'b', 'bulan' => 1, 'tahun' => 2026, 'nama_file' => 'b.pdf', 'desa_id' => '9999999999999', 'id_laporan_penduduk' => 8],
        ]);

        $this->artisan('konsolidasi:geser-id-desa', ['desa_id' => '1234567890123', 'offset' => OFFSET_SLOT])->assertSuccessful();

        expect((int) DB::table('das_apbdes')->where('desa_id', '1234567890123')->value('id_apbdes'))->toBe(OFFSET_SLOT + 3);
        expect((int) DB::table('das_apbdes')->where('desa_id', '9999999999999')->value('id_apbdes'))->toBe(3);
        expect((int) DB::table('das_laporan_penduduk')->where('desa_id', '1234567890123')->value('id_laporan_penduduk'))->toBe(OFFSET_SLOT + 8);
        expect((int) DB::table('das_laporan_penduduk')->where('desa_id', '9999999999999')->value('id_laporan_penduduk'))->toBe(8);
    });

    test('menolak offset yang bukan kelipatan slot', function () {
        $this->artisan('konsolidasi:geser-id-desa', ['desa_id' => '1234567890123', 'offset' => 12345])->assertExitCode(2);
    });
});
