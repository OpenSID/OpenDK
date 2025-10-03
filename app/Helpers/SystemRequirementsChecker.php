<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Helpers;

/**
 * Pengganti untuk RachidLaasri\LaravelInstaller\Helpers\RequirementsChecker
 * 
 * Class ini dibuat untuk menggantikan functionality dari package yang sudah abandoned
 */
class SystemRequirementsChecker
{
    /**
     * Check PHP version against minimum requirement
     *
     * @param string $minPhpVersion
     * @return array
     */
    public function checkPHPversion($minPhpVersion = '8.1')
    {
        $currentPhpVersion = phpversion();
        $supported = version_compare($currentPhpVersion, $minPhpVersion, '>=');

        return [
            'full' => $currentPhpVersion,
            'safe' => $currentPhpVersion,
            'current' => (float) $currentPhpVersion,
            'minimum' => $minPhpVersion,
            'supported' => $supported
        ];
    }

    /**
     * Check system requirements
     *
     * @param array $requirements
     * @return array
     */
    public function check(array $requirements)
    {
        $results = [];

        foreach ($requirements as $type => $requirement) {
            switch ($type) {
                case 'php':
                    foreach ($requirement as $requirement_name) {
                        $results['requirements'][$type][$requirement_name] = true;

                        if (!extension_loaded($requirement_name)) {
                            $results['requirements'][$type][$requirement_name] = false;
                            $results['errors'] = true;
                        }
                    }
                    break;
                case 'apache':
                    foreach ($requirement as $requirement_name) {
                        $results['requirements'][$type][$requirement_name] = true;

                        if (function_exists('apache_get_modules') && !in_array($requirement_name, apache_get_modules())) {
                            $results['requirements'][$type][$requirement_name] = false;
                            $results['errors'] = true;
                        }
                    }
                    break;
            }
        }

        return $results;
    }

    /**
     * Check folder permissions
     *
     * @param array $folders
     * @return array
     */
    public function checkPermissions(array $folders)
    {
        $results = [];

        foreach ($folders as $folder => $permission) {
            $path = base_path($folder);

            if (file_exists($path)) {
                $results['permissions'][$folder] = [
                    'folder' => $folder,
                    'permission' => $permission,
                    'isSet' => substr(sprintf('%o', fileperms($path)), -3) >= $permission
                ];
            } else {
                $results['permissions'][$folder] = [
                    'folder' => $folder,
                    'permission' => $permission,
                    'isSet' => false
                ];
                $results['errors'] = true;
            }
        }

        return $results;
    }
}
