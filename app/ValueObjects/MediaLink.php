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
 * KEWAJIBAN ATAUPUN PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\ValueObjects;

use App\Enums\TipeMedia;
use JsonSerializable;

/**
 * Hasil resolusi sebuah link media.
 *
 * Objek ini dipakai oleh front-end (API) maupun Blade untuk menentukan
 * elemen HTML mana yang harus dirender saat menampilkan galeri.
 */
final class MediaLink implements JsonSerializable
{
    /**
     * @param  string  $type  Salah satu dari nilai {@see TipeMedia}
     * @param  string|null  $id  Identifier media pada penyedia (YouTube/Vimeo/Drive), jika ada
     * @param  string|null  $url  URL media yang bisa dimuat langsung (src)
     * @param  string|null  $embedUrl  URL untuk <iframe> bila <video> tidak mendukung
     * @param  string|null  $thumbnail  URL poster/thumbnail untuk pratinjau
     */
    public function __construct(
        public readonly string $type = TipeMedia::Unknown,
        public readonly ?string $id = null,
        public readonly ?string $url = null,
        public readonly ?string $embedUrl = null,
        public readonly ?string $thumbnail = null,
    ) {
    }

    /**
     * Buat hasil resolusi kosong untuk link yang tidak valid.
     */
    public static function unknown(?string $url = null): self
    {
        return new self(TipeMedia::Unknown, null, $url, null, null);
    }

    /**
     * Apakah media ini bisa ditampilkan langsung tanpa membuka tab baru.
     */
    public function isEmbeddable(): bool
    {
        return $this->embedUrl !== null || $this->thumbnail !== null || $this->url !== null;
    }

    /**
     * @return array<string, string|null>
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'id' => $this->id,
            'url' => $this->url,
            'embed_url' => $this->embedUrl,
            'thumbnail' => $this->thumbnail,
        ];
    }
}
