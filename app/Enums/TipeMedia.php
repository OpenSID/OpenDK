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

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Tipe media galeri untuk media berbasis link (Google Drive, YouTube, file langsung).
 *
 * Menentukan bagaimana media ditampilkan pada web maupun admin:
 * - image  : dirender sebagai <img>
 * - video  : dirender sebagai <video> atau <iframe> preview
 * - youtube: dirender sebagai <iframe> embed YouTube
 * - unknown: hanya ditampilkan sebagai tautan biasa
 */
final class TipeMedia extends Enum
{
    /** @var string Media foto. */
    public const Image = 'image';

    /** @var string Media video. */
    public const Video = 'video';

    /** @var string Video YouTube, ditampilkan melalui iframe embed. */
    public const Youtube = 'youtube';

    /** @var string Link tidak dikenali sebagai media. */
    public const Unknown = 'unknown';

    /**
     * Tipe media yang boleh dipilih manual oleh pengguna pada form galeri.
     *
     * @return array<string, string>
     */
    public static function selectable(): array
    {
        return [
            self::Image => 'Foto',
            self::Video => 'Video',
            self::Youtube => 'YouTube',
        ];
    }

    /**
     * Daftar seluruh nilai tipe media yang valid.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return [self::Image, self::Video, self::Youtube, self::Unknown];
    }

    /**
     * Cek apakah nilai yang diberikan merupakan tipe media yang valid.
     */
    public static function isValid(?string $value): bool
    {
        return $value !== null && in_array($value, self::values(), true);
    }
}
