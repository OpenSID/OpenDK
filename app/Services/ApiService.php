<?php

namespace App\Services;

use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

class ApiService
{

    protected $server;
    protected $setting;

    public function __construct()
    {
        // Ambil settings sebagai objek
        $this->setting = (object) SettingAplikasi::pluck('value', 'key')->toArray();

        $this->server = config('app.server_layanan');
    }

    public function register($data)
    {

        $response = Http::withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->attach(
            'permohonan',
            Storage::get($data['permohonan']),
            'dokumen-permohonan.pdf'
        )->post("{$this->server}/api/v1/pelanggan/register-kecamatan", [
            'user_id'          => auth()->user()->id,
            'email'            => email($data['email']),
            'kecamatan'     => bilangan_titik($data['kecamatan_id']),
            'kontak_no_hp'     => bilangan($data['kontak_no_hp']),
            'kontak_nama'      => nama($data['kontak_nama']),
            'no_hp_pj'     => bilangan($data['kontak_no_hp']),
            'nama_pj'      => nama($data['kontak_nama']),
            'domain'           => alamat_web($data['domain']),
            'status_langganan' => (int) $data['status_langganan_id'],
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Pendaftaran pelanggan berhasil.',
                'data'    => $response->json(),
            ];
        } elseif ($response->failed()) {
            return [
                'success' => false,
                'message' => 'Pendaftaran pelanggan gagal.',
                'error'   => $response->json(),
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Terjadi masalah pada pendaftaran pelanggan.',
                'status'  => $response->status(),
            ];
        }
    }

    public function terdaftar($kode_kecamatan)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->setting->layanan_opendesa_token,
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post("{$this->server}/api/v1/pelanggan/terdaftar-kecamatan", [
            'kecamatan_id' => $kode_kecamatan,
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Data berhasil diambil.',
                'data'    => $response->json(),
            ];
        } else {

            return [
                'success' => false,
                'message' => $response->object()->message,
                'data'  => $response->body(),
            ];
        }
    }

    public function getFormRegister()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->setting->layanan_opendesa_token,
            'X-Requested-With' => 'XMLHttpRequest',
        ])->get("{$this->server}/api/v1/pelanggan/form-register-kecamatan");

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Data berhasil diambil.',
                'data'    => $response->json(),
            ];
        }

        return [
            'success' => false,
            'message' => 'Gagal mengambil data.',
            'status'  => $response->status(),
        ];
    }
}
