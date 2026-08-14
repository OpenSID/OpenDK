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

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Kategori Sarana untuk mengelompokkan jenis sarana
 */
final class KategoriSarana extends Enum
{
    // Sarana Kesehatan
    public const PUSKESMAS = 'puskesmas';
    public const PUSKESMAS_PEMBANTU = 'puskesmas_pembantu';
    public const POSYANDU = 'posyandu';
    public const PONDOK_BERSALIN = 'pondok_bersalin';

    // Sarana Pendidikan
    public const PAUD = 'paud';
    public const SD = 'sd';
    public const SMP = 'smp';
    public const SMA = 'sma';

    // Sarana Umum
    public const MASJID_BESAR = 'masjid_besar';
    public const MUSHOLA = 'mushola';
    public const GEREJA = 'gereja';
    public const PASAR = 'pasar';
    public const BALAI_PERTEMUAN = 'balai_pertemuan';

    /**
     * Get description for the enum value
     *
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value) {
            case self::PUSKESMAS:
                return 'Puskesmas';
            case self::PUSKESMAS_PEMBANTU:
                return 'Puskesmas Pembantu';
            case self::POSYANDU:
                return 'Posyandu';
            case self::PONDOK_BERSALIN:
                return 'Pondok Bersalin';
            case self::PAUD:
                return 'PAUD/Sederajat';
            case self::SD:
                return 'SD/Sederajat';
            case self::SMP:
                return 'SMP/Sederajat';
            case self::SMA:
                return 'SMA/Sederajat';
            case self::MASJID_BESAR:
                return 'Masjid Besar';
            case self::MUSHOLA:
                return 'Mushola';
            case self::GEREJA:
                return 'Gereja';
            case self::PASAR:
                return 'Pasar';
            case self::BALAI_PERTEMUAN:
                return 'Balai Pertemuan';
            default:
                return parent::getDescription($value);
        }
    }

    /**
     * Get all options grouped by category
     *
     * @return array
     */
    public static function getGroupedOptions(): array
    {
        return [
            'Sarana Kesehatan' => [
                self::PUSKESMAS => self::getDescription(self::PUSKESMAS),
                self::PUSKESMAS_PEMBANTU => self::getDescription(self::PUSKESMAS_PEMBANTU),
                self::POSYANDU => self::getDescription(self::POSYANDU),
                self::PONDOK_BERSALIN => self::getDescription(self::PONDOK_BERSALIN),
            ],
            'Sarana Pendidikan' => [
                self::PAUD => self::getDescription(self::PAUD),
                self::SD => self::getDescription(self::SD),
                self::SMP => self::getDescription(self::SMP),
                self::SMA => self::getDescription(self::SMA),
            ],
            'Sarana Umum' => [
                self::MASJID_BESAR => self::getDescription(self::MASJID_BESAR),
                self::MUSHOLA => self::getDescription(self::MUSHOLA),
                self::GEREJA => self::getDescription(self::GEREJA),
                self::PASAR => self::getDescription(self::PASAR),
                self::BALAI_PERTEMUAN => self::getDescription(self::BALAI_PERTEMUAN),
            ],
        ];
    }
    /**
     * Get enum value (constant) from its description label.
     *
     * @param string $label
     * @return string|null
     */
    public static function getValueFromDescription(string $label): ?string
    {
        foreach (self::getValues() as $value) {
            if (self::getDescription($value) === $label) {
                return $value;
            }
        }

        return null;
    }
}