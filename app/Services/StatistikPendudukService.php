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

namespace App\Services;

use App\Enums\LabelStatistik;
use App\Models\Penduduk;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatistikPendudukService extends BaseApiService
{
    public function minYear()
    {
        // gunakan cache untuk mempercepat load data melalui api
        return Cache::remember('yearList', 60 * 60 * 24, function () {
            if ($this->useDatabaseGabungan()) {
                try {
                    $tahun = $this->apiRequest('/api/v1/statistik-web/penduduk/tahun');
                    $tahunAwal = intval($tahun['tahun_awal'] ?? date('Y'));
                    $tahunSekarang = intval(date('Y'));
                    $batasTahun = 10;
                    return $tahunAwal + $batasTahun > $tahunSekarang ? $tahunAwal : $tahunSekarang - $batasTahun;
                } catch (\Exception $e) {
                    $tahun = date('Y');
                    \Log::error('Failed get data in '.__FILE__.' function minYear()'. $e->getMessage());
                }
                return $tahun;
            }
            return Penduduk::select(DB::raw('MIN(YEAR(created_at)) as min_year'))->value('min_year') ?? date('Y');
        });
    }

    /**
     * Get Unique Desa
     */
    public function desa(array $filters = [])
    {
        // Panggil API dan ambil data
        $data = $this->apiRequest('/api/v1/wilayah/desa', $filters);

        return collect($data)->map(function ($item) {
            return (object)[
                'desa_id' => $item['attributes']['kode_desa'] ?? null, // Ambil kode desa
                'kode_desa' => $item['attributes']['kode_desa'] ?? null, // Ambil kode desa
                'nama' => $item['attributes']['nama_desa'] ?? null, // Ambil nama desa
                'sebutan_desa' => $item['attributes']['sebutan_desa'] ?? null, // Ambil sebutan desa
                'website' => $item['attributes']['website'] ?? null, // Ambil website
                'luas_wilayah' => $item['attributes']['luas_wilayah'] ?? null, // Ambil luas wilayah
                'path' => $item['attributes']['path'] ?? null, // Ambil path
            ];
        })?->first();
    }

    public function dashboard($did, $year)
    {
        $summary = [
            "total_penduduk" => 0,
            "total_lakilaki" => 0,
            "total_perempuan" => 0,
            "total_disabilitas" => 0,
            "ktp_wajib" => 0,
            "ktp_terpenuhi" => 0,
            "ktp_persen_terpenuhi" => 0,
            "akta_terpenuhi" => 0,
            "akta_persen_terpenuhi" => 0,
            "aktanikah_wajib" => 0,
            "aktanikah_terpenuhi" => 0,
            "aktanikah_persen_terpenuhi" => 0,
        ];
        if ($this->useDatabaseGabungan()) {
            try {
                $filters = [
                    'filter[id]' => 'jenis-kelamin',
                    'filter[tahun]' => $year,
                    'filter[kecamatan]' => $this->kodeKecamatan,
                ];
                if ($did != 'Semua') {
                    $filters['filter[desa]'] = $did;
                }
                $filtersCacat = $filtersKtp = $filtersAkta = $filtersNikah = $filtersAktaNikah = $filters;
                $filtersCacat['filter[id]'] = 'penyandang-cacat';
                $filtersKtp['filter[id]'] = 'ktp';
                $filtersAkta['filter[id]'] = 'akta-kelahiran';
                $filtersAktaNikah['filter[id]'] = 'akta-nikah';
                $filtersNikah['filter[id]'] = 'status-perkawinan';
                $client = new Client(['base_uri' => $this->baseUrl, 'headers' => $this->header]);
                $promises = Utils::unwrap([
                    'penduduk' => $client->getAsync('/api/v1/statistik-web/penduduk', ['query' => $filters]),
                    'cacat' => $client->getAsync('/api/v1/statistik-web/penduduk', ['query' => $filtersCacat]),
                    'ktp' => $client->getAsync('/api/v1/statistik-web/penduduk', ['query' => $filtersKtp]),
                    'akta_kelahiran' => $client->getAsync('/api/v1/statistik-web/penduduk', ['query' => $filtersAkta]),
                    'nikah' => $client->getAsync('/api/v1/statistik-web/penduduk', ['query' => $filtersNikah]),
                    'akta_nikah' => $client->getAsync('/api/v1/statistik-web/penduduk', ['query' => $filtersAktaNikah]),
                ]);

                $responses = Utils::settle($promises)->wait();
                $data = json_decode($responses['penduduk']['value']->getBody()->getContents(), true)['data'];
                $dataCacat = json_decode($responses['cacat']['value']->getBody()->getContents(), true)['data'];
                $dataKtp = json_decode($responses['ktp']['value']->getBody()->getContents(), true)['data'];
                $dataAkta = json_decode($responses['akta_kelahiran']['value']->getBody()->getContents(), true)['data'];
                $dataNikah = json_decode($responses['nikah']['value']->getBody()->getContents(), true)['data'];
                $dataAktaNikah = json_decode($responses['akta_nikah']['value']->getBody()->getContents(), true)['data'];
                if ($dataCacat) {
                    $totalDisabilitas  = collect($dataCacat)->filter(function ($q) {
                        return ! in_array($q['id'], [7, LabelStatistik::Jumlah, LabelStatistik::Total, LabelStatistik::BelumMengisi]);
                    })->sum('attributes.jumlah');
                    $summary['total_disabilitas'] = $totalDisabilitas;
                }
                if ($data) {
                    $mapData  = collect($data)->mapWithKeys(function ($item) {
                        return [$item['id'] => $item['attributes']];
                    });
                    $summary["total_penduduk"] = $mapData[LabelStatistik::Total]['jumlah'] ?? 0;
                    $summary["total_lakilaki"] = $mapData[LabelStatistik::LakiLaki]['jumlah'] ?? 0;
                    $summary["total_perempuan"] = $mapData[LabelStatistik::Perempuan]['jumlah'] ?? 0;

                }
                if ($dataKtp) {
                    $ktpWajib  = collect($dataKtp)->filter(static fn ($q) => $q['id'] == LabelStatistik::Total)->sum('attributes.jumlah');
                    $ktpTerpenuhi = collect($dataKtp)->filter(static fn ($q) => $q['id'] == LabelStatistik::Jumlah)->sum('attributes.jumlah');
                    $summary['ktp_wajib'] = $ktpWajib;
                    $summary['ktp_terpenuhi'] = $ktpTerpenuhi;
                    $summary['ktp_persen_terpenuhi'] = $ktpWajib == 0 ? 0 : format_number_id(($ktpTerpenuhi / $ktpWajib) * 100);
                }
                if ($dataAkta) {
                    $aktaTerpenuhi = collect($dataAkta)->filter(static fn ($q) => $q['id'] == LabelStatistik::Jumlah)->sum('attributes.jumlah');
                    $summary['akta_terpenuhi'] = $aktaTerpenuhi;
                    $summary['akta_persen_terpenuhi'] = $summary["total_penduduk"] == 0 ? 0 : format_number_id(($aktaTerpenuhi / $summary["total_penduduk"]) * 100);
                }
                if ($dataNikah) {
                    $aktanikahWajib = collect($dataNikah)->filter(static fn ($q) => $q['id'] == '2')->sum('attributes.jumlah');
                    $aktanikahTerpenuhi = collect($dataAktaNikah)->filter(static fn ($q) => $q['id'] == LabelStatistik::Jumlah)->sum('attributes.jumlah');
                    $summary['aktanikah_wajib'] = $aktanikahWajib;
                    $summary['aktanikah_terpenuhi'] = $aktanikahTerpenuhi;
                    $summary['aktanikah_persen_terpenuhi'] = $aktanikahWajib == 0 ? 0 : format_number_id(($aktanikahTerpenuhi / $aktanikahWajib) * 100);
                }

                return $summary;
            } catch (\Exception $e) {
                \Log::error('Failed get data in '.__FILE__.' function dashboard()'. $e->getMessage());
            }
            return $summary;
        }
        return $this->localDashboard($did, $year);

    }

    private function localDashboard($did, $year)
    {
        $data = [];
        $penduduk = new Penduduk();
        // Get Total Penduduk Aktif
        $queryTotalPendudukAktif = $penduduk->getPendudukAktif($did, $year);

        $totalPenduduk = (clone $queryTotalPendudukAktif)->count();
        $data['total_penduduk'] = $totalPenduduk;

        // Get Total Laki-Laki
        $totalLakiLaki = (clone $queryTotalPendudukAktif)
            ->where('sex', 1)
            ->count();

        $data['total_lakilaki'] = $totalLakiLaki;

        // Get Total Perempuan
        $totalPerempuan = (clone $queryTotalPendudukAktif)
            ->where('sex', 2)
            ->count();

        $data['total_perempuan'] = $totalPerempuan;

        // Get Total Disabilitas
        $total_disabilitas = (clone $queryTotalPendudukAktif)
            ->where('cacat_id', '<>', 7)
            ->count();

        $data['total_disabilitas'] = $total_disabilitas;

        if ($totalPenduduk == 0) {
            $data['ktp_wajib'] = 0;
            $data['ktp_terpenuhi'] = 0;
            $data['ktp_persen_terpenuhi'] = 0;

            $data['akta_terpenuhi'] = 0;
            $data['akta_persen_terpenuhi'] = 0;

            $data['aktanikah_wajib'] = 0;
            $data['aktanikah_terpenuhi'] = 0;
            $data['aktanikah_persen_terpenuhi'] = 0;
        } else {
            // Get Data KTP Penduduk Terpenuhi
            $ktpWajib = (clone $queryTotalPendudukAktif)
                ->whereRaw('DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(das_penduduk.tanggal_lahir)), \'%Y\')+0 >= ? ', 17)
                ->orWhere('status_kawin', '<>', 1) // Status Selain Belum Kawin
                ->count();

            $ktpTerpenuhi = (clone $queryTotalPendudukAktif)
                ->where('status_rekam', '>=', 3) // Selain Belum Wajib & Belum Rekam E-KTP
                ->count();

            $ktpPersenTerpenuhi = ($ktpTerpenuhi / $ktpWajib) * 100;

            $data['ktp_wajib'] = $ktpWajib;
            $data['ktp_terpenuhi'] = $ktpTerpenuhi;
            $data['ktp_persen_terpenuhi'] = format_number_id($ktpPersenTerpenuhi);

            // Get Data Akta Penduduk Terpenuhi
            $aktaTerpenuhi = (clone $queryTotalPendudukAktif)
                ->where('akta_lahir', '<>', null)
                ->where('akta_lahir', '<>', ' ')
                ->where('akta_lahir', '<>', '-')
                ->count();

            $aktaPersenTerpenuhi = ($aktaTerpenuhi / $totalPenduduk) * 100;
            $data['akta_terpenuhi'] = $aktaTerpenuhi;
            $data['akta_persen_terpenuhi'] = format_number_id($aktaPersenTerpenuhi);

            // Get Data Akta Nikah Penduduk Terpenuhi
            $queryAktanikahWajib = (clone $queryTotalPendudukAktif)
                ->where('status_kawin', 2);

            $aktanikahWajib = (clone $queryAktanikahWajib)->count();
            $aktanikahTerpenuhi = $queryAktanikahWajib
                ->where('akta_perkawinan', '<>', null)
                ->where('akta_perkawinan', '<>', ' ')
                ->where('akta_perkawinan', '<>', '-')
                ->count();

            $data['aktanikah_wajib'] = 0;
            $data['aktanikah_terpenuhi'] = 0;
            $data['aktanikah_persen_terpenuhi'] = format_number_id(0);
            if ($aktanikahWajib != 0) {
                $aktanikahPersenTerpenuhi = ($aktanikahTerpenuhi / $aktanikahWajib) * 100;
                $data['aktanikah_wajib'] = $aktanikahWajib;
                $data['aktanikah_terpenuhi'] = $aktanikahTerpenuhi;
                $data['aktanikah_persen_terpenuhi'] = format_number_id($aktanikahPersenTerpenuhi);
            }
        }

        return $data;
    }
}
