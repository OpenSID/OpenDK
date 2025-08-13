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

namespace App\Exports;

use App\Models\Keluarga;
use App\Services\KeluargaService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportKeluarga implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected bool $gabungan;
    protected array $params;
    protected KeluargaService $keluargaService;

    public function __construct($gabungan, $params = [])
    {
        $this->gabungan = $gabungan;
        $this->params = $params;
        $this->keluargaService = new KeluargaService();
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil data dari database lokal dengan filter desa jika ada
        $desa = $this->params['desa'] ?? null;

        $query = Keluarga::has('kepala_kk')->with(['kepala_kk', 'desa']);

        // Hanya tambahkan filter jika ada parameter desa dan bukan 'Semua'
        if ($desa && $desa !== 'Semua') {
            $query->where('desa_id', $desa);
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'NIK Kepala Keluarga',
            'Nama Kepala Keluarga',
            'No. Kartu Keluarga',
            'Alamat',
            'Dusun',
            'RW',
            'RT',
            'Nama Desa',
            'Tanggal Daftar',
            'Tanggal Cetak KK',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    /**
     * @param mixed $keluarga
     * @return array
     */
    public function map($keluarga): array
    {
        return [
            $keluarga->id,
            $keluarga->nik_kepala,
            $keluarga->kepala_kk ? $keluarga->kepala_kk->nama : 'N/A',
            $keluarga->no_kk,
            $keluarga->alamat,
            $keluarga->dusun,
            $keluarga->rw,
            $keluarga->rt,
            $keluarga->desa ? $keluarga->desa->nama : 'N/A',
            $keluarga->tgl_daftar ? date('d/m/Y', strtotime($keluarga->tgl_daftar)) : '',
            $keluarga->tgl_cetak_kk ? date('d/m/Y', strtotime($keluarga->tgl_cetak_kk)) : '',
            $keluarga->created_at ? $keluarga->created_at->format('d/m/Y H:i:s') : '',
            $keluarga->updated_at ? $keluarga->updated_at->format('d/m/Y H:i:s') : '',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ],
        ];
    }
}
