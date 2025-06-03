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

namespace App\Http\Controllers\Surat;

use App\Enums\LogVerifikasiSurat;
use App\Enums\StatusSurat;
use App\Enums\StatusVerifikasiSurat;
use App\Http\Controllers\Controller;
use App\Models\LogTte;
use App\Models\Surat;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class PermohonanController extends Controller
{
    public function index()
    {
        $page_title = 'Permohonan Surat';
        $page_description = 'Daftar Permohonan Surat';

        return view('surat.permohonan.index', compact('page_title', 'page_description'));
    }

    public function getData()
    {
        return DataTables::of(Surat::permohonan())
            ->addColumn('aksi', function ($row) {
                $user = auth()->user()->pengurus_id;
                $isAllow = false;
                if ($row->log_verifikasi == LogVerifikasiSurat::Operator && $user == null) {
                    $isAllow = true;
                } elseif ($row->log_verifikasi == LogVerifikasiSurat::Sekretaris && $user == $this->akun_sekretaris->id) {
                    $isAllow = true;
                } elseif ($row->log_verifikasi == LogVerifikasiSurat::Camat && $user == $this->akun_camat->id) {
                    $isAllow = true;
                }

                if ($isAllow) {
                    $data['show_url'] = route('surat.permohonan.show', $row->id);
                }

                if ($row->log_verifikasi == LogVerifikasiSurat::ProsesTTE && $user == $this->akun_camat->id) {
                    $data['passphrase'] = route('surat.permohonan.show', $row->id);
                }

                $data['download_url'] = route('surat.permohonan.download', $row->id);

                return view('forms.aksi', $data);
            })
            ->editColumn('nama', function ($row) {
                return "Surat {$row->nama}";
            })
            ->editColumn('log_verifikasi', function ($row) {
                if ($row->log_verifikasi == LogVerifikasiSurat::ProsesTTE) {
                    return "<span class='label label-primary'>Menunggu Ditandatangani {$this->settings['sebutan_camat']}</span>";
                } elseif ($row->log_verifikasi == LogVerifikasiSurat::Camat) {
                    return "<span class='label label-warning'>Menunggu Verifikasi {$this->settings['sebutan_camat']}</span>";
                } elseif ($row->log_verifikasi == LogVerifikasiSurat::Sekretaris) {
                    return "<span class='label label-warning'>Menunggu Verifikasi {$this->settings['sebutan_sekretaris']}</span>";
                } else {
                    return "<span class='label label-warning'>Menunggu Verifikasi Operator</span>";
                }
            })
            ->editColumn('tanggal', function ($row) {
                return format_date($row->tanggal);
            })
            ->rawColumns(['aksi', 'nama', 'log_verifikasi'])->make();
    }

    public function show($id)
    {
        $surat = Surat::findOrFail($id);
        $page_title = 'Detail Surat';
        $page_description = "Detail Data Surat: {$surat->nama}";

        // Cek pemeriksa
        $user = auth()->user()->pengurus_id;
        $isAllow = false;
        if ($surat->log_verifikasi == LogVerifikasiSurat::Operator && $user == null) {
            $isAllow = true;
        } elseif ($surat->log_verifikasi == LogVerifikasiSurat::Sekretaris && $user == $this->akun_sekretaris->id) {
            $isAllow = true;
        } elseif ($surat->log_verifikasi == LogVerifikasiSurat::Camat && $user == $this->akun_camat->id) {
            $isAllow = true;
        } elseif ($surat->log_verifikasi == LogVerifikasiSurat::ProsesTTE && $user == $this->akun_camat->id) {
            $isAllow = true;
        }

        if (! $isAllow) {
            return back()->with('error', 'Anda tidak memiliki akses');
        }

        return view('surat.permohonan.show', compact('page_title', 'page_description', 'surat'));
    }

    public function download($id)
    {
        try {
            $surat = Surat::findOrFail($id);

            return Storage::download('public/surat/'.$surat->file);
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Dokumen tidak ditemukan');
        }
    }

    public function setujui($id)
    {
        try {
            $surat = Surat::findOrFail($id);
            $log_sekarang = $surat->log_verifikasi;

            if ($log_sekarang == LogVerifikasiSurat::Operator) {
                $log_verifikasi = $surat->verifikasi_sekretaris == StatusVerifikasiSurat::MenungguVerifikasi ?
                    LogVerifikasiSurat::Sekretaris : LogVerifikasiSurat::Camat;
                $surat->update(['verifikasi_operator' => StatusVerifikasiSurat::TelahDiverifikasi]);
            } elseif ($log_sekarang == LogVerifikasiSurat::Sekretaris) {
                $log_verifikasi = LogVerifikasiSurat::Camat;
                $surat->update(['verifikasi_sekretaris' => StatusVerifikasiSurat::TelahDiverifikasi]);
            } else {
                $log_verifikasi = LogVerifikasiSurat::ProsesTTE;
                $surat->update(['verifikasi_camat' => StatusVerifikasiSurat::TelahDiverifikasi]);
            }

            $surat->update(['log_verifikasi' => $log_verifikasi]);
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json();
    }

    public function tolak(Request $request, $id)
    {
        try {
            Surat::findOrFail($id)->update([
                'log_verifikasi' => LogVerifikasiSurat::Ditolak,
                'status' => StatusSurat::Ditolak,
                'keterangan' => $request['keterangan'],
            ]);
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json();
    }

    public function passphrase(Request $request, $id)
    {
        $surat = Surat::findOrFail($id);

        DB::beginTransaction();

        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->settings['tte_api'],
            'auth' => [
                $this->settings['tte_username'],
                $this->settings['tte_password'],
            ],
        ]);

        try {
            $file_path = public_path("storage/surat/{$surat->file}");
            $width = 90;
            $height = 90;
            $tag = '[qr_camat]';

            $response = $client->post('api/sign/pdf', [
                'headers' => ['X-Requested-With' => 'XMLHttpRequest'],
                'multipart' => [
                    ['name' => 'file', 'contents' => Psr7\Utils::tryFopen($file_path, 'r')],
                    ['name' => 'nik', 'contents' => $surat->pengurus->nik],
                    ['name' => 'passphrase', 'contents' => $request['passphrase']],
                    ['name' => 'tampilan', 'contents' => 'visible'],
                    ['name' => 'linkQR', 'contents' => route('surat.arsip.qrcode', $surat->id)],
                    ['name' => 'width', 'contents' => $width],
                    ['name' => 'height', 'contents' => $height],
                    ['name' => 'tag_koordinat', 'contents' => $tag],
                ],
            ]);

            $surat->update([
                'status' => StatusSurat::Arsip,
                'log_verifikasi' => LogVerifikasiSurat::SudahTTE,
            ]);

            DB::commit();

            // overwrite dokumen lama dengan response dari bsre
            if ($response->getStatusCode() == 200) {
                $file = fopen($file_path, 'wb');
                fwrite($file, $response->getBody()->getContents());
                fclose($file);
            }

            return $this->response([
                'status' => true,
                'pesan_error' => 'success',
                'jenis' => 'success',
            ]);
        } catch (ClientException $e) {
            report($e);

            DB::rollback();

            return $this->response([
                'status' => false,
                'pesan_error' => $e->getMessage(),
                'jenis' => 'ClientException',
            ]);
        }
    }

    protected function response($notif = [])
    {
        LogTte::create([
            'pesan_error' => $notif['pesan_error'],
            'jenis' => $notif['jenis'],
        ]);

        return response()->json($notif);
    }

    public function ditolak()
    {
        $page_title = 'Permohonan Surat Ditolak';
        $page_description = 'Daftar Permohonan Surat Ditolak';

        return view('surat.permohonan.ditolak', compact('page_title', 'page_description'));
    }

    public function getDataDitolak()
    {
        return DataTables::of(Surat::ditolak())
            ->editColumn('nama', function ($row) {
                return "Surat {$row->nama}";
            })
            ->editColumn('log_verifikasi', function () {
                return "<span class='label label-danger'>Ditolak</span>";
            })
            ->editColumn('tanggal', function ($row) {
                return format_date($row->tanggal);
            })
            ->rawColumns(['nama', 'log_verifikasi'])->make();
    }
}
