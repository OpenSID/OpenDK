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

namespace Tests\Feature\Content;

use App\Models\Artikel;
use App\Models\ArtikelKategori;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\CrudTestCase;

beforeEach(function () {
    Storage::fake('public');

    // Create default kategori for testing using correct primary key
    ArtikelKategori::firstOrCreate(
        ['id_kategori' => 1],
        ['nama_kategori' => 'Berita Umum', 'slug' => 'berita-umum']
    );
});

describe('Artikel CRUD', function () {
    test('index displays artikel list view', function () {
        $response = $this->get(route('informasi.artikel.index'));

        $response->assertStatus(200);
        $response->assertViewIs('informasi.artikel.index');
        $response->assertViewHas('page_title', 'Artikel');
        $response->assertViewHas('page_description', 'Daftar Artikel');
    });

    test('create displays artikel creation form', function () {
        $response = $this->get(route('informasi.artikel.create'));

        $response->assertStatus(200);
        $response->assertViewIs('informasi.artikel.create');
        $response->assertViewHas('page_title', 'Artikel');
        $response->assertViewHas('page_description', 'Tambah Artikel');
    });

    test('store creates new artikel successfully', function () {
        $validData = [
            'judul' => 'Judul Artikel Baru',
            'id_kategori' => 1,
            'isi' => 'Isi artikel yang lengkap dan informatif.',
            'status' => 1,
        ];

        $response = $this->post(route('informasi.artikel.store'), $validData);

        $response->assertRedirect(route('informasi.artikel.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('das_artikel', [
            'judul' => 'Judul Artikel Baru',
        ]);
    });    

    test('store fails with invalid data', function () {
        $invalidData = [
            'judul' => '',
            'isi' => '',
        ];

        $response = $this->post(route('informasi.artikel.store'), $invalidData);

        $response->assertSessionHasErrors(['judul', 'isi']);
    });

    test('edit displays edit form', function () {
        $artikel = Artikel::factory()->create();

        $response = $this->get(route('informasi.artikel.edit', $artikel->id));

        $response->assertStatus(200);
        $response->assertViewIs('informasi.artikel.edit');
        $response->assertViewHas('artikel', $artikel);
        $response->assertViewHas('page_description');
    });

    test('update updates artikel successfully', function () {
        $artikel = Artikel::factory()->create();

        $updateData = [
            'judul' => 'Updated Judul',
            'id_kategori' => 1,
            'isi' => 'Updated isi artikel.',
            'status' => 0,
        ];

        $response = $this->post(route('informasi.artikel.update', $artikel->id), $updateData);

        $response->assertRedirect(route('informasi.artikel.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('das_artikel', [
            'id' => $artikel->id,
            'judul' => 'Updated Judul',
            'status' => 0,
        ]);
    });

    test('destroy deletes artikel successfully', function () {
        $artikel = Artikel::factory()->create();

        $response = $this->delete(route('informasi.artikel.destroy', $artikel->id));

        $response->assertRedirect(route('informasi.artikel.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('das_artikel', [
            'id' => $artikel->id,
        ]);
    });

    test('validation requires judul', function () {
        $invalidData = [
            'judul' => '',
            'id_kategori' => 1,
            'isi' => 'Some content',
        ];

        $response = $this->post(route('informasi.artikel.store'), $invalidData);

        $response->assertSessionHasErrors('judul');
    });

    test('validation requires isi', function () {
        $invalidData = [
            'judul' => 'Judul Artikel',
            'id_kategori' => 1,
            'isi' => '',
        ];

        $response = $this->post(route('informasi.artikel.store'), $invalidData);

        $response->assertSessionHasErrors('isi');
    });
});
