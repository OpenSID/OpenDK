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
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah, dan/atau mendistribusikan,
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

use App\Enums\LogVerifikasiSurat;
use App\Enums\StatusSurat;
use App\Enums\StatusVerifikasiSurat;
use App\Models\DataDesa;
use App\Models\Jabatan;
use App\Models\Pengurus;
use App\Models\Surat;
use Illuminate\Database\Seeder;

class SuratPermohonanSeeder extends Seeder
{
    private ?Pengurus $operator = null;

    public function run(): void
    {
        $desa = DataDesa::firstOrCreate(
            ['nama' => 'Desa Contoh'],
            [
                'desa_id' => '3301011234567',
                'nama' => 'Desa Contoh',
                'website' => 'https://example.com',
                'luas_wilayah' => 10.5,
            ]
        );

        $this->seedVerifikasiChain($desa->desa_id);

        $this->seedMenungguSekretaris($desa->desa_id);

        $this->seedMenungguCamat($desa->desa_id);

        $this->seedMenungguTTE($desa->desa_id);

        $this->seedArsip($desa->desa_id);

        $this->seedDitolak($desa->desa_id);
    }

    private function seedVerifikasiChain(string $desaId): void
    {
        Surat::updateOrCreate(
            ['nomor' => '001/' . now()->year . '/DPRD', 'desa_id' => $desaId],
            [
                'nik' => '3275010101010001',
                'nama_penduduk' => 'Budi Santoso',
                'pengurus_id' => $this->getOperator()->id,
                'tanggal' => now()->subDays(5),
                'nama' => 'Surat Pengantar RT/RW',
                'file' => 'dummy.pdf',
                'keterangan' => 'Permohonan surat pengantar untuk keperluan administrasi kependudukan',
                'log_verifikasi' => LogVerifikasiSurat::Operator,
                'verifikasi_operator' => StatusVerifikasiSurat::MenungguVerifikasi,
                'verifikasi_sekretaris' => StatusVerifikasiSurat::TidakAktif,
                'verifikasi_camat' => StatusVerifikasiSurat::TidakAktif,
                'status_tte' => true,
                'status' => StatusSurat::Permohonan,
            ]
        );

        Surat::updateOrCreate(
            ['nomor' => '002/' . now()->year . '/DPRD', 'desa_id' => $desaId],
            [
                'nik' => '3275010101010002',
                'nama_penduduk' => 'Siti Aminah',
                'pengurus_id' => $this->getOperator()->id,
                'tanggal' => now()->subDays(4),
                'nama' => 'Fotokopi KK',
                'file' => 'dummy.pdf',
                'keterangan' => 'Permohonan fotokopi Kartu Keluarga untuk keperluan pembuatan KTP',
                'log_verifikasi' => LogVerifikasiSurat::Operator,
                'verifikasi_operator' => StatusVerifikasiSurat::MenungguVerifikasi,
                'verifikasi_sekretaris' => StatusVerifikasiSurat::TidakAktif,
                'verifikasi_camat' => StatusVerifikasiSurat::TidakAktif,
                'status_tte' => true,
                'status' => StatusSurat::Permohonan,
            ]
        );
    }

    private function seedMenungguSekretaris(string $desaId): void
    {
        Surat::updateOrCreate(
            ['nomor' => '003/' . now()->year . '/DPRD', 'desa_id' => $desaId],
            [
                'nik' => '3275010101010003',
                'nama_penduduk' => 'Ahmad Hidayat',
                'pengurus_id' => $this->getOperator()->id,
                'tanggal' => now()->subDays(3),
                'nama' => 'Surat Keterangan Kematian',
                'file' => 'dummy.pdf',
                'keterangan' => 'Permohonan surat keterangan kematian dari keluarga',
                'log_verifikasi' => LogVerifikasiSurat::Sekretaris,
                'verifikasi_operator' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_sekretaris' => StatusVerifikasiSurat::MenungguVerifikasi,
                'verifikasi_camat' => StatusVerifikasiSurat::TidakAktif,
                'status_tte' => true,
                'status' => StatusSurat::Permohonan,
            ]
        );
    }

    private function seedMenungguCamat(string $desaId): void
    {
        Surat::updateOrCreate(
            ['nomor' => '004/' . now()->year . '/DPRD', 'desa_id' => $desaId],
            [
                'nik' => '3275010101010004',
                'nama_penduduk' => 'Dewi Lestari',
                'pengurus_id' => $this->getOperator()->id,
                'tanggal' => now()->subDays(2),
                'nama' => 'Surat Pindah Datang',
                'file' => 'dummy.pdf',
                'keterangan' => 'Permohonan surat pindah datang dari luar kota',
                'log_verifikasi' => LogVerifikasiSurat::Camat,
                'verifikasi_operator' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_sekretaris' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_camat' => StatusVerifikasiSurat::MenungguVerifikasi,
                'status_tte' => true,
                'status' => StatusSurat::Permohonan,
            ]
        );
    }

    private function seedMenungguTTE(string $desaId): void
    {
        Surat::updateOrCreate(
            ['nomor' => '005/' . now()->year . '/DPRD', 'desa_id' => $desaId],
            [
                'nik' => '3275010101010005',
                'nama_penduduk' => 'Rizky Ramadhan',
                'pengurus_id' => $this->getOperator()->id,
                'tanggal' => now()->subDay(),
                'nama' => 'Surat Keterangan Cerai',
                'file' => 'dummy.pdf',
                'keterangan' => 'Permohonan surat keterangan cerai untuk keperluan pengadilan',
                'log_verifikasi' => LogVerifikasiSurat::ProsesTTE,
                'verifikasi_operator' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_sekretaris' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_camat' => StatusVerifikasiSurat::TelahDiverifikasi,
                'status_tte' => true,
                'status' => StatusSurat::Permohonan,
            ]
        );
    }

    private function seedArsip(string $desaId): void
    {
        Surat::updateOrCreate(
            ['nomor' => '006/' . now()->year . '/DPRD', 'desa_id' => $desaId],
            [
                'nik' => '3275010101010006',
                'nama_penduduk' => 'Maya Putri',
                'pengurus_id' => $this->getOperator()->id,
                'tanggal' => now()->subDays(10),
                'nama' => 'Fotokopi Ijasah Terakhir',
                'file' => 'dummy.pdf',
                'keterangan' => 'Permohonan fotokopi ijasah untuk melamar pekerjaan',
                'log_verifikasi' => LogVerifikasiSurat::SudahTTE,
                'verifikasi_operator' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_sekretaris' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_camat' => StatusVerifikasiSurat::TelahDiverifikasi,
                'status_tte' => true,
                'status' => StatusSurat::Arsip,
            ]
        );
    }

    private function seedDitolak(string $desaId): void
    {
        Surat::updateOrCreate(
            ['nomor' => '007/' . now()->year . '/DPRD', 'desa_id' => $desaId],
            [
                'nik' => '3275010101010007',
                'nama_penduduk' => 'Andi Wijaya',
                'pengurus_id' => $this->getOperator()->id,
                'tanggal' => now()->subDays(7),
                'nama' => 'Surat Keterangan Kematian dari Kepala Desa',
                'file' => 'dummy.pdf',
                'keterangan' => 'Dokumen pendukung tidak lengkap, NIK tidak terdata',
                'log_verifikasi' => LogVerifikasiSurat::Ditolak,
                'verifikasi_operator' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_sekretaris' => StatusVerifikasiSurat::TidakAktif,
                'verifikasi_camat' => StatusVerifikasiSurat::TidakAktif,
                'status_tte' => true,
                'status' => StatusSurat::Ditolak,
            ]
        );
    }

    private function getOperator(): Pengurus
    {
        if ($this->operator === null) {
            $jabatan = Jabatan::factory()->create([
                'nama' => 'Operator',
            ]);

            $this->operator = Pengurus::factory()->create([
                'jabatan_id' => $jabatan->id,
                'status' => 1,
                'pangkat' => 'Operator',
            ]);
        }

        return $this->operator;
    }
}
