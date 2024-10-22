<?php

namespace App\Http\Livewire\Kerjasama;

use Livewire\Component;
use App\Services\ApiService;
use Livewire\WithFileUploads;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PendaftaranKerjasama extends Component
{
    use WithFileUploads;

    // state
    public string $page_title = 'Pendaftaran Kerjasama';

    // state form
    public int $user_id = 0;
    public int $status_registrasi_id = 4;
    public string $pesan_terdaftar;
    public string $status_langganan = 'belum terdaftar';
    public string $status_registrasi = 'belum terdaftar';
    public string $email;
    public string $domain;
    public string $kontak_nama;
    public string $kontak_no_hp;
    public string $kecamatan_id;
    public $permohonan;

    public $iteration;

    public $response;

    public function mount()
    {
        $this->kecamatan_id = view()->shared('profil')->kecamatan_id;

        $this->domain = config('app.url');

        // Ambil settings sebagai objek
        $setting = (object) SettingAplikasi::pluck('value', 'key')->toArray();

        // Periksa apakah 'layanan_opendesa_token' tidak ada
        if (!property_exists($setting, 'layanan_opendesa_token')) {
            DB::table('das_setting')->insert([
                'key' => 'layanan_opendesa_token',
                'value' => 0,
                'type' => 'input',
                'description' => 'Token pelanggan Layanan OpenDESA',
                'kategori' => 'pelanggan',
                'option' => '{}',
            ]);
        }
    }

    public function render()
    {
        $apiService = new ApiService();
        $response = $apiService->terdaftar($this->kecamatan_id);

        if ($response['success']) {

            $this->status_registrasi_id = $response['data']['data']['status_langgaan_id'];
            $this->pesan_terdaftar = $response['data']['message'];

            $this->response = $response['data']['data'];
        } else {

            $apiService = new ApiService();
            $res_form = $apiService->getFormRegister();

            if ($res_form['success']) {
                $this->response = $res_form['data']['data'];
                $this->status_langganan = $this->response['status_langganan'];
            }
        }

        return view('livewire.kerjasama.pendaftaran_kerjasama.index');
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'domain' => 'required',
            'kontak_nama' => 'required|min:5',
            'kontak_no_hp' => 'required',
            'kecamatan_id' => 'required',
            'permohonan' => 'required:mimes:pdf|max:1024',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function register()
    {

        $this->validate();

        try {

            $ext = $this->permohonan->guessExtension();
            $name = "dokumen-permohonan.{$ext}";
            $path = $this->permohonan->storeAs('public/kecamatan/upload/dokumen', $name);

            $data = [
                'email' => $this->email,
                'domain' => $this->domain,
                'kontak_nama' => $this->kontak_nama,
                'desa' => view()->shared('profil')->nama_kecamatan,
                'kontak_no_hp' => $this->kontak_no_hp,
                'kecamatan_id' => $this->kecamatan_id,
                'status_langganan_id' => $this->status_registrasi_id,
                'permohonan' => $path
            ];

            $apiService = new ApiService();
            $response = $apiService->register($data);

            if ($response['success']) {

                SettingAplikasi::where('key', 'layanan_opendesa_token')->update([
                    'value' => $response['data']['data']['token']
                ]);

                // kalo sudah berhasil panggil service daftar
                $apiService = new ApiService();
                $res_terdaftar = $apiService->terdaftar($this->kecamatan_id);
                if ($res_terdaftar['success']) {
                    $this->response = $res_terdaftar;
                    $this->emit('triggerAlert', $response['data']['message'], 'success');
                } else {
                    $this->emit('triggerAlert', $response['message'], 'success');
                }
            } else {
                $this->emit('triggerAlert', $response['error']['message'], 'danger');
            }
        } catch (\Exception $e) {
            $this->emit('triggerAlert', $e->getMessage(), 'danger');
        }
    }
}
