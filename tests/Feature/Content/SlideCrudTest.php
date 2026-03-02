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

use App\Models\Slide;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\CrudTestCase;

beforeEach(function () {
    Storage::fake('public');
});

describe('Slide CRUD', function () {
    test('index displays slide list view', function () {
        $response = $this->get(route('setting.slide.index'));

        $response->assertStatus(200);
        $response->assertViewIs('setting.slide.index');
        $response->assertViewHas('page_title', 'Slide');
        $response->assertViewHas('page_description', 'Daftar Slide');
    });

    test('store creates new slide successfully', function () {
        $file = UploadedFile::fake()->image('slide.jpg');

        $validData = [
            'judul' => 'Slide Baru',
            'deskripsi' => 'Deskripsi slide yang menarik.',
            'gambar' => $file,
        ];

        $response = $this->post(route('setting.slide.store'), $validData);

        $response->assertRedirect(route('setting.slide.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('slides', [
            'judul' => 'Slide Baru',
        ]);
    });

    test('store fails with invalid data', function () {
        $invalidData = [
            'judul' => '',
            'deskripsi' => '',
        ];

        $response = $this->post(route('setting.slide.store'), $invalidData);

        $response->assertSessionHasErrors(['judul', 'deskripsi']);
    });

    test('edit displays edit form', function () {
        $slide = Slide::create([
            'judul' => 'Test Slide',
            'deskripsi' => 'Test Description',
            'gambar' => '/img/slide-default.jpg',
        ]);

        $response = $this->get(route('setting.slide.edit', $slide->id));

        $response->assertStatus(200);
        $response->assertViewIs('setting.slide.edit');
        $response->assertViewHas('slide', $slide);
    });

    test('update updates slide successfully', function () {
        $slide = Slide::create([
            'judul' => 'Original Slide',
            'deskripsi' => 'Original Description',
            'gambar' => '/img/slide-default.jpg',
        ]);

        $updateData = [
            'judul' => 'Updated Slide',
            'deskripsi' => 'Updated Description',
        ];

        $response = $this->put(route('setting.slide.update', $slide->id), $updateData);

        $response->assertRedirect(route('setting.slide.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('slides', [
            'id' => $slide->id,
            'judul' => 'Updated Slide',
        ]);
    });

    test('destroy deletes slide successfully', function () {
        $slide = Slide::create([
            'judul' => 'Slide to Delete',
            'deskripsi' => 'Description',
            'gambar' => '/img/slide-default.jpg',
        ]);

        $response = $this->delete(route('setting.slide.destroy', $slide->id));

        $response->assertRedirect(route('setting.slide.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('slides', [
            'id' => $slide->id,
        ]);
    });

    test('validation requires judul', function () {
        $invalidData = [
            'judul' => '',
            'deskripsi' => 'Some description',
        ];

        $response = $this->post(route('setting.slide.store'), $invalidData);

        $response->assertSessionHasErrors('judul');
    });

    test('validation requires deskripsi', function () {
        $invalidData = [
            'judul' => 'Slide Title',
            'deskripsi' => '',
        ];

        $response = $this->post(route('setting.slide.store'), $invalidData);

        $response->assertSessionHasErrors('deskripsi');
    });
});
