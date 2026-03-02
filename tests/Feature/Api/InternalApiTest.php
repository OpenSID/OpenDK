<?php

use App\Enums\JenisJabatan;
use App\Jobs\PendudukQueueJob;
use App\Models\DataDesa;
use App\Models\DataUmum;
use App\Models\Jabatan;
use App\Models\Profil;
use App\Models\SettingAplikasi;
use App\Models\User;
use Database\Seeders\RoleSpatieSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use function Pest\Laravel\postJson;
use function Pest\Laravel\seed;
use function Pest\Laravel\withToken;

uses(DatabaseTransactions::class);

beforeEach(function () {
    seed(RoleSpatieSeeder::class);

    // Seed das_profil - required by Controller::__construct()
    $profil = Profil::first();
    if (!$profil) {
        $profil = Profil::create([
            'provinsi_id' => 32,
            'nama_provinsi' => 'Jawa Barat',
            'kabupaten_id' => 3201,
            'nama_kabupaten' => 'Bogor',
            'kecamatan_id' => 320101,
            'nama_kecamatan' => 'Kecamatan Test',
            'alamat' => 'Jl. Test No. 1',
        ]);
    }

    // Seed das_data_umum - required by Controller::__construct() (kirimTrack uses $this->umum->bts_wil_*)
    if (!DataUmum::exists()) {
        DB::table('das_data_umum')->insert([
            'profil_id' => $profil->id,
            'bts_wil_utara' => '-',
            'bts_wil_timur' => '-',
            'bts_wil_selatan' => '-',
            'bts_wil_barat' => '-',
            'lat' => 0,
            'lng' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Seed ref_jabatan - required by Controller::__construct() line 111-112
    if (!Jabatan::where('jenis', JenisJabatan::Camat)->exists()) {
        DB::table('ref_jabatan')->insert([
            ['nama' => 'Camat', 'jenis' => JenisJabatan::Camat, 'tupoksi' => '-', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Sekretaris', 'jenis' => JenisJabatan::Sekretaris, 'tupoksi' => '-', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    // Seed required das_setting keys
    $requiredSettings = [
        ['key' => 'tte', 'value' => '0'],
        ['key' => 'pemeriksaan_camat', 'value' => '0'],
        ['key' => 'pemeriksaan_sekretaris', 'value' => '0'],
        ['key' => 'judul_aplikasi', 'value' => 'OpenDK Test'],
    ];
    foreach ($requiredSettings as $s) {
        if (!SettingAplikasi::where('key', $s['key'])->exists()) {
            $setting = new SettingAplikasi();
            $setting->key = $s['key'];
            $setting->value = $s['value'];
            $setting->type = 'input';
            $setting->description = $s['key'];
            $setting->kategori = 'aplikasi';
            $setting->option = '[]';
            $setting->save();
        }
    }

    // Create/find user
    $user = User::where('email', 'admin@mail.com')->first();
    if (!$user) {
        $user = User::factory()->create([
            'email' => 'admin@mail.com',
            'password' => 'password',
            'status' => 1,
        ]);
        $user->assignRole('super-admin');
    }
    $user->password = 'password';
    $user->save();
    $this->user = $user;

    // Login to get JWT
    $response = postJson('/api/v1/auth/login', [
        'email' => 'admin@mail.com',
        'password' => 'password',
    ]);

    $this->token = $response->json('access_token');

    // Register token in das_setting for token.registered middleware
    $setting = SettingAplikasi::where('key', 'api_key_opendk')->first() ?? new SettingAplikasi();
    $setting->key = 'api_key_opendk';
    $setting->value = $this->token;
    $setting->type = 'input';
    $setting->description = 'API Key OpenDK';
    $setting->kategori = 'aplikasi';
    $setting->option = '[]';
    $setting->save();
});

test('can sync penduduk data (dispatches job)', function () {
    Queue::fake();

    DataDesa::factory()->create(['desa_id' => '1234567890']);

    $payload = [
        'hapus_penduduk' => [
            [
                'id_pend_desa' => 1,
                'desa_id' => '1234567890',
            ]
        ]
    ];

    $response = withToken($this->token)->postJson('/api/v1/penduduk', $payload);

    if ($response->status() !== 200) {
        dump('Penduduk Sync Response: ' . $response->content());
    }

    $response->assertStatus(200);
    Queue::assertPushed(PendudukQueueJob::class);
});

test('can sync identitas desa', function () {
    DataDesa::factory()->create(['desa_id' => '1234567890']);

    $payload = [
        'kode_desa' => '1234567890',
        'sebutan_desa' => 'Desa Maju',
        'website' => 'https://maju.desa.id',
        'path' => '[]', // path field is JSON in database
    ];

    $response = withToken($this->token)->postJson('/api/v1/identitas-desa', $payload);

    if ($response->status() !== 200) {
        dump('Identitas Desa Response: ' . $response->content());
    }

    $response->assertStatus(200)
        ->assertJson(['status' => 'success']);

    $this->assertDatabaseHas('das_data_desa', [
        'desa_id' => '1234567890',
        'sebutan_desa' => 'Desa Maju',
        'website' => 'https://maju.desa.id',
    ]);
});

test('cannot access internal api with invalid token', function () {
    $response = withToken('invalid-token')->postJson('/api/v1/penduduk', []);
    $response->assertStatus(401);
});

test('cannot access internal api with unregistered token', function () {
    $otherUser = User::factory()->create();
    $token = auth('api')->login($otherUser);

    $response = withToken($token)->postJson('/api/v1/penduduk', []);

    $response->assertStatus(401)
        ->assertJson(['error' => 'Token not registered']);
});
