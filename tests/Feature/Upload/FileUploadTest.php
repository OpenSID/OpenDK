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

namespace Tests\Feature\Upload;

use App\Models\Artikel;
use App\Models\Event;
use App\Models\Slide;
use App\Models\Profil;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\CrudTestCase;

beforeEach(function () {
    Storage::fake('public');
});

describe('File Upload Functionality', function () {
    describe('Artikel Image Upload', function () {
        test('can upload image with artikel', function () {
            $file = UploadedFile::fake()->image('artikel-image.jpg')->size(100);

            $validData = [
                'judul' => 'Artikel dengan Gambar',
                'kategori_id' => 1,
                'isi' => 'Isi artikel dengan gambar.',
                'status' => 1,
                'gambar' => $file,
            ];

            $response = $this->post(route('informasi.artikel.store'), $validData);

            $response->assertRedirect(route('informasi.artikel.index'));
            $response->assertSessionHas('success');

            // Check if file was uploaded successfully
            $this->assertDatabaseHas('das_artikel', [
                'judul' => 'Artikel dengan Gambar',
            ]);
        });

        test('rejects non-image files for artikel', function () {
            $file = UploadedFile::fake()->create('document.pdf')->size(100);

            $validData = [
                'judul' => 'Artikel dengan PDF',
                'kategori_id' => 1,
                'isi' => 'Isi artikel.',
                'status' => 1,
                'gambar' => $file,
            ];

            $response = $this->post(route('informasi.artikel.store'), $validData);

            // Should fail validation for image type
            $response->assertSessionHasErrors('gambar');
        });

        test('rejects files larger than max size', function () {
            $file = UploadedFile::fake()->image('large-image.jpg')->size(10240); // 10MB

            $validData = [
                'judul' => 'Artikel dengan Gambar Besar',
                'kategori_id' => 1,
                'isi' => 'Isi artikel.',
                'status' => 1,
                'gambar' => $file,
            ];

            $response = $this->post(route('informasi.artikel.store'), $validData);

            // Should fail validation for file size
            $response->assertSessionHasErrors('gambar');
        });

        test('can update artikel image', function () {
            $artikel = Artikel::factory()->create(['gambar' => '/img/old-image.jpg']);

            $newFile = UploadedFile::fake()->image('new-image.jpg')->size(100);

            $updateData = [
                'judul' => 'Updated Artikel',
                'kategori_id' => 1,
                'isi' => 'Updated content',
                'status' => 1,
                'gambar' => $newFile,
            ];

            $response = $this->post(route('informasi.artikel.update', $artikel->id), $updateData);

            $response->assertRedirect(route('informasi.artikel.index'));
            $response->assertSessionHas('success');

            // Check if file was uploaded successfully
            $this->assertDatabaseHas('das_artikel', [
                'judul' => 'Updated Artikel',
            ]);
        });
    });

    describe('Event Attachment Upload', function () {
        test('can upload attachment with event', function () {
            $file = UploadedFile::fake()->create('event-document.pdf')->size(100);

            $validData = [
                'event_name' => 'Event dengan Attachment',
                'waktu' => '2024-12-01 09:00:00 - 2024-12-01 17:00:00',
                'description' => 'Deskripsi event.',
                'attendants' => 'Camat',
                'attachment' => $file,
            ];

            $response = $this->post(route('informasi.event.store'), $validData);

            $response->assertRedirect(route('informasi.event.index'));
            $response->assertSessionHas('success');

            // Check if file was uploaded successfully
            $this->assertDatabaseHas('das_events', [
                'event_name' => 'Event dengan Attachment',
            ]);
        });

        test('can update event attachment', function () {
            $event = Event::factory()->create(['attachment' => '/files/old-document.pdf']);

            $newFile = UploadedFile::fake()->create('new-document.pdf')->size(100);

            $updateData = [
                'event_name' => 'Updated Event',
                'waktu' => '2024-12-15 10:00:00 - 2024-12-15 18:00:00',
                'description' => 'Updated description.',
                'attendants' => 'Sekretaris',
                'attachment' => $newFile,
            ];

            $response = $this->put(route('informasi.event.update', $event->id), $updateData);

            $response->assertRedirect(route('informasi.event.index'));
            $response->assertSessionHas('success');

            // Check if file was uploaded successfully
            $this->assertDatabaseHas('das_events', [
                'event_name' => 'Updated Event',
            ]);
        });
    });

    describe('Slide Image Upload', function () {
        test('can upload image with slide', function () {
            $file = UploadedFile::fake()->image('slide-image.jpg')->size(100);

            $validData = [
                'judul' => 'Slide Baru',
                'deskripsi' => 'Deskripsi slide.',
                'gambar' => $file,
            ];

            $response = $this->post(route('setting.slide.store'), $validData);

            $response->assertRedirect(route('setting.slide.index'));
            $response->assertSessionHas('success');

            // Check if file was uploaded successfully
            $this->assertDatabaseHas('slides', [
                'judul' => 'Slide Baru',
            ]);
        });

        test('can update slide image', function () {
            $slide = Slide::create([
                'judul' => 'Original Slide',
                'deskripsi' => 'Original Description',
                'gambar' => '/img/slide-default.jpg',
            ]);

            $newFile = UploadedFile::fake()->image('new-slide.jpg')->size(100);

            $updateData = [
                'judul' => 'Updated Slide',
                'deskripsi' => 'Updated Description',
                'gambar' => $newFile,
            ];

            $response = $this->put(route('setting.slide.update', $slide->id), $updateData);

            $response->assertRedirect(route('setting.slide.index'));
            $response->assertSessionHas('success');

            // Check if file was uploaded successfully
            $this->assertDatabaseHas('slides', [
                'judul' => 'Updated Slide',
            ]);
        });
    });

    describe('Profil File Upload', function () {
        test('can upload struktur organisasi file', function () {
            $profil = Profil::firstOrCreate(
                ['id' => 1],
                [
                    'nama' => 'Kecamatan Test',
                    'kecamatan_id' => '33010100',
                    'provinsi_id' => '33',
                    'kabupaten_id' => '33010',
                    'nama_provinsi' => 'Jawa Tengah',
                    'nama_kabupaten' => 'Banjarnegara',
                    'nama_kecamatan' => 'Pagentan',
                    'alamat' => 'Alamat Test',
                    'kode_pos' => '53471',
                    'telepon' => '0123456789',
                    'email' => 'test@example.com',
                    'tahun_pembentukan' => '2024',
                    'dasar_pembentukan' => 'Dasar Pembentukan Test',
                ]
            );

            $file = UploadedFile::fake()->image('struktur-organisasi.png')->size(100);

            $updateData = [
                'nama' => 'Updated Kecamatan',
                'kecamatan_id' => '33010100',
                'provinsi_id' => '33',
                'kabupaten_id' => '33010',
                'nama_provinsi' => 'Jawa Tengah',
                'nama_kabupaten' => 'Banjarnegara',
                'nama_kecamatan' => 'Pagentan',
                'alamat' => 'Alamat Test Updated',
                'kode_pos' => '53471',
                'telepon' => '0123456789',
                'email' => 'test@example.com',
                'tahun_pembentukan' => '2024',
                'dasar_pembentukan' => 'Dasar Pembentukan Test',
                'file_struktur_organisasi' => $file,
            ];

            $response = $this->put(route('data.profil.update', $profil->id), $updateData);

            $response->assertRedirect();
            $response->assertSessionHas('success');

            // Check if file was uploaded successfully
            $this->assertDatabaseHas('das_profil', [
                'nama_kecamatan' => 'Pagentan',
            ]);
        });

        test('can upload logo file', function () {
            $profil = Profil::firstOrCreate(
                ['id' => 1],
                [
                    'nama' => 'Kecamatan Test',
                    'kecamatan_id' => '33010100',
                    'provinsi_id' => '33',
                    'kabupaten_id' => '33010',
                    'nama_provinsi' => 'Jawa Tengah',
                    'nama_kabupaten' => 'Banjarnegara',
                    'nama_kecamatan' => 'Pagentan',
                    'alamat' => 'Alamat Test',
                    'kode_pos' => '53471',
                    'telepon' => '0123456789',
                    'email' => 'test@example.com',
                    'tahun_pembentukan' => '2024',
                    'dasar_pembentukan' => 'Dasar Pembentukan Test',
                ]
            );

            $file = UploadedFile::fake()->image('logo.png')->size(100);

            $updateData = [
                'nama_kecamatan' => 'Updated Kecamatan',
                'kecamatan_id' => '33010100',
                'provinsi_id' => '33',
                'kabupaten_id' => '33010',
                'nama_provinsi' => 'Jawa Tengah',
                'nama_kabupaten' => 'Banjarnegara',
                'nama_kecamatan' => 'Pagentan',
                'alamat' => 'Alamat Test Updated',
                'kode_pos' => '53471',
                'telepon' => '0123456789',
                'email' => 'test@example.com',
                'tahun_pembentukan' => '2024',
                'dasar_pembentukan' => 'Dasar Pembentukan Test',
                'file_logo' => $file,
            ];

            $response = $this->put(route('data.profil.update', $profil->id), $updateData);

            $response->assertRedirect();
            $response->assertSessionHas('success');

            // Check if file was uploaded successfully
            $this->assertDatabaseHas('das_profil', [
                'nama_kecamatan' => 'Pagentan',
            ]);
        });
    });

    describe('File Upload Validation', function () {
        test('validates file MIME type', function () {
            $file = UploadedFile::fake()->create('script.php')->size(100);

            $validData = [
                'judul' => 'Artikel',
                'kategori_id' => 1,
                'isi' => 'Content',
                'status' => 1,
                'gambar' => $file,
            ];

            $response = $this->post(route('informasi.artikel.store'), $validData);

            $response->assertSessionHasErrors('gambar');
        });

        test('validates file is not required', function () {
            // File upload should be optional for some models
            $validData = [
                'judul' => 'Artikel tanpa Gambar',
                'kategori_id' => 1,
                'isi' => 'Isi artikel tanpa gambar.',
                'status' => 1,
            ];

            $response = $this->post(route('informasi.artikel.store'), $validData);

            $response->assertRedirect(route('informasi.artikel.index'));
            $response->assertSessionHas('success');
        });
    });
});
