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

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait HandlesFileUpload
{
    /**
     * Daftar MIME types default yang diizinkan
     */
    protected array $defaultAllowedMimes = [
        'image' => ['jpeg', 'png', 'jpg', 'gif', 'svg', 'webp'],
        'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv'],
        'archive' => ['zip', 'rar', '7z'],
    ];

    /**
     * Menangani upload file dari request dengan validasi MIME.
     *
     * @param \Illuminate\Http\Request $request
     * @param array &$input
     * @param string $field
     * @param string $directory
     * @param bool $withDirectory
     * @param array $allowedMimes Optional list of allowed mime extensions
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handleFileUpload($request, &$input, $field = 'file', $directory = 'uploads', $withDirectory = true, array $allowedMimes = [])
    {
        if ($request->hasFile($field)) {
            $file = $request->file($field);

            if ($file instanceof UploadedFile) {
                // Validate MIME type if allowedMimes is provided
                if (!empty($allowedMimes)) {
                    $this->validateMimeType($file, $allowedMimes);
                }

                $fileName = $this->generateSafeFileName($file);
                $path = $file->storeAs("public/{$directory}", $fileName);
                $input[$field] = str_replace('public/', 'storage/', $path);

                if (!$withDirectory) {
                    $input[$field] = $fileName;
                }
            }
        }
    }

    /**
     * Validasi MIME type file.
     *
     * @param UploadedFile $file
     * @param array $allowedMimes List of allowed extensions (e.g., ['jpg', 'png', 'pdf'])
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateMimeType(UploadedFile $file, array $allowedMimes): void
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedMimes)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => "Tipe file tidak diizinkan. Tipe yang diizinkan: " . implode(', ', $allowedMimes),
            ]);
        }

        // Additional check using actual MIME type for security
        $mimeType = $file->getMimeType();
        $allowedMimeTypes = $this->extensionsToMimeTypes($allowedMimes);

        if (!empty($allowedMimeTypes) && !in_array($mimeType, $allowedMimeTypes)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => "MIME type file tidak valid. Pastikan file yang diupload sesuai dengan ekstensinya.",
            ]);
        }
    }

    /**
     * Generate nama file yang aman untuk penyimpanan.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateSafeFileName(UploadedFile $file): string
    {
        return $file->hashName();
    }

    /**
     * Convert extensions to MIME types.
     *
     * @param array $extensions
     * @return array
     */
    protected function extensionsToMimeTypes(array $extensions): array
    {
        $mimeMap = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'csv' => 'text/csv',
            'zip' => 'application/zip',
            'rar' => 'application/vnd.rar',
            '7z' => 'application/x-7z-compressed',
        ];

        $mimeTypes = [];
        foreach ($extensions as $ext) {
            if (isset($mimeMap[strtolower($ext)])) {
                $mimeTypes[] = $mimeMap[strtolower($ext)];
            }
        }

        return $mimeTypes;
    }

    /**
     * Mendapatkan daftar MIME types berdasarkan kategori.
     *
     * @param string $category 'image', 'document', atau 'archive'
     * @return array
     */
    protected function getAllowedMimesByCategory(string $category): array
    {
        return $this->defaultAllowedMimes[$category] ?? [];
    }
}
