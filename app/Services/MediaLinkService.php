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

namespace App\Services;

use App\Enums\TipeMedia;
use App\ValueObjects\MediaLink;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Resolusi link media galeri menjadi informasi siap tampil.
 *
 * Menangani link Google Drive (foto maupun video), YouTube, Vimeo, serta
 * file foto/video yang dihosting langsung. Media tidak diunduh ke server,
 * melainkan dinormalkan menjadi URL yang bisa dimuat langsung oleh browser.
 */
class MediaLinkService
{
    /** @var array<int, string> Ekstensi foto yang dikenali. */
    protected const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'avif', 'svg'];

    /** @var array<int, string> Ekstensi video yang dikenali. */
    protected const VIDEO_EXTENSIONS = ['mp4', 'webm', 'ogv', 'ogg', 'mov', 'm4v', 'mkv', 'avi'];

    /**
     * Resolusi link menjadi objek media tanpa melakukan permintaan jaringan.
     *
     * Parameter $hint adalah tipe media yang dipilih manual oleh pengguna.
     * Hint dipakai hanya bila link sendiri tidak memberikan petunjuk yang jelas,
     * sehingga pilihan pengguna tidak dapat menimpa provider yang pasti
     * (misalnya YouTube tetap dianggap YouTube).
     *
     * @param  string|null  $url  Link media yang dimasukkan pengguna
     * @param  string|null  $hint  Tipe media pilihan manual (image/video/youtube)
     */
    public function resolve(?string $url, ?string $hint = null): MediaLink
    {
        $url = is_string($url) ? trim($url) : '';

        if ($url === '' || ! $this->isHttpUrl($url)) {
            return MediaLink::unknown();
        }

        if (($youtubeId = $this->youtubeId($url)) !== null) {
            return new MediaLink(
                type: TipeMedia::Youtube,
                id: $youtubeId,
                url: $url,
                embedUrl: 'https://www.youtube.com/embed/' . $youtubeId,
                thumbnail: 'https://img.youtube.com/vi/' . $youtubeId . '/hqdefault.jpg',
            );
        }

        if (($vimeoId = $this->vimeoId($url)) !== null) {
            return new MediaLink(
                type: TipeMedia::Video,
                id: $vimeoId,
                url: $url,
                embedUrl: 'https://player.vimeo.com/video/' . $vimeoId,
            );
        }

        if (($driveId = $this->googleDriveId($url)) !== null) {
            return $this->resolveGoogleDrive($url, $driveId, $hint);
        }

        $extension = $this->extension($url);

        if ($extension !== null && in_array($extension, self::IMAGE_EXTENSIONS, true)) {
            return new MediaLink(TipeMedia::Image, null, $url, null, $url);
        }

        if ($extension !== null && in_array($extension, self::VIDEO_EXTENSIONS, true)) {
            return new MediaLink(TipeMedia::Video, null, $url, null, null);
        }

        $hinted = $this->normalizeHint($hint);

        if ($hinted !== null && $hinted !== TipeMedia::Unknown) {
            return new MediaLink($hinted, null, $url, null, $hinted === TipeMedia::Image ? $url : null);
        }

        return MediaLink::unknown($url);
    }

    /**
     * Tentukan tipe media, dengan melakukan probing HTTP bila link tidak informatif.
     *
     * @param  string|null  $url  Link media
     * @param  string|null  $hint  Tipe media pilihan manual; bila diisi, probing dilewati
     */
    public function detectType(?string $url, ?string $hint = null): string
    {
        $hinted = $this->normalizeHint($hint);
        $resolved = $this->resolve($url, $hint);

        // Tautan Google Drive tidak memuat ekstensi berkas, sehingga "image" di sana
        // hanya asumsi dan harus dipastikan lewat pemeriksaan HTTP. Tanpa ini, video
        // Google Drive akan salah diperlakukan sebagai foto dan gagal ditampilkan.
        $needsProbe = $resolved->type === TipeMedia::Unknown
            || ($resolved->type === TipeMedia::Image && $this->isGoogleDrive((string) $url));

        if (! $needsProbe) {
            return $resolved->type;
        }

        if ($hinted !== null) {
            return $hinted;
        }

        return $this->probe($url) ?? $resolved->type;
    }

    /**
     * Deteksi tipe media lewat HTTP HEAD, hanya untuk link yang tidak punya petunjuk.
     *
     * @return string|null Tipe media, atau null bila link tidak dapat diperiksa sama sekali
     *                     (URL tidak valid, request gagal, atau timeout)
     */
    public function probe(?string $url): ?string
    {
        $url = is_string($url) ? trim($url) : '';

        if ($url === '' || ! $this->isHttpUrl($url)) {
            return null;
        }

        // Google Drive hanya-serving halaman HTML pada /view, jadi periksa file langsungnya.
        $target = $this->isGoogleDrive($url) ? $this->googleDriveDownloadUrl($this->googleDriveId($url) ?? '') : $url;

        if ($target === null) {
            return null;
        }

        try {
            $response = Http::timeout(5)
                ->withOptions(['allow_redirects' => ['max' => 3]])
                ->head($target);

            if (! $response->successful()) {
                return null;
            }

            return $this->typeFromResponse($response);
        } catch (\Throwable $e) {
            Log::debug('Gagal memeriksa tipe media dari link.', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Bangun URL preview Google Drive untuk media video.
     */
    public function googleDrivePreviewUrl(string $fileId): string
    {
        return 'https://drive.google.com/file/d/' . $fileId . '/preview';
    }

    /**
     * Bangun URL unduh langsung Google Drive untuk media video.
     */
    public function googleDriveDownloadUrl(?string $fileId): ?string
    {
        return $fileId === null
            ? null
            : 'https://drive.google.com/uc?export=download&id=' . $fileId;
    }

    /**
     * Bangun URL tampil langsung (untuk foto) Google Drive.
     */
    public function googleDriveViewUrl(string $fileId): string
    {
        return 'https://drive.google.com/uc?export=view&id=' . $fileId;
    }

    /**
     * Resolusi link Google Drive menjadi media siap tampil.
     */
    protected function resolveGoogleDrive(string $originalUrl, string $fileId, ?string $hint): MediaLink
    {
        $hint = $this->normalizeHint($hint);
        $isVideo = $hint === TipeMedia::Video;

        if ($isVideo) {
            return new MediaLink(
                type: TipeMedia::Video,
                id: $fileId,
                url: $this->googleDriveDownloadUrl($fileId),
                embedUrl: $this->googleDrivePreviewUrl($fileId),
            );
        }

        return new MediaLink(
            type: TipeMedia::Image,
            id: $fileId,
            url: $this->googleDriveViewUrl($fileId),
            thumbnail: $this->googleDriveViewUrl($fileId),
        );
    }

    /**
     * Ambil ID video YouTube dari berbagai format link.
     */
    protected function youtubeId(string $url): ?string
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $path = (string) parse_url($url, PHP_URL_PATH);

        if ($host === 'youtu.be') {
            return $this->trimId(ltrim($path, '/'));
        }

        if (! in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com'], true)) {
            return null;
        }

        if (preg_match('~^/(?:embed|shorts|live|v)/([^/?&#]+)~', $path, $matches) === 1) {
            return $this->trimId($matches[1]);
        }

        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

        if (isset($query['v']) && is_string($query['v'])) {
            return $this->trimId($query['v']);
        }

        return null;
    }

    /**
     * Ambil ID video Vimeo dari link vimeo.com.
     */
    protected function vimeoId(string $url): ?string
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');

        if (! in_array($host, ['vimeo.com', 'www.vimeo.com', 'player.vimeo.com'], true)) {
            return null;
        }

        if (preg_match('#(?:^|/)(?:video/)?(\d+)#', $path, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Ambil file ID Google Drive dari berbagai format link.
     */
    protected function googleDriveId(string $url): ?string
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        if (! $this->isGoogleDrive($url)) {
            return null;
        }

        $path = (string) parse_url($url, PHP_URL_PATH);
        $query = [];
        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

        if (preg_match('~/(?:file/)?d/([^/?]+)~', $path, $matches) === 1) {
            return $this->trimId($matches[1]);
        }

        // docs.google.com hanya punya viewer untuk berkas, parameter ?id= dipakai form editor.
        if ($host !== 'docs.google.com' && isset($query['id']) && is_string($query['id']) && $query['id'] !== '') {
            return $this->trimId($query['id']);
        }

        return null;
    }

    /**
     * Cek apakah host berasal dari Google Drive / Google Docs viewer.
     */
    protected function isGoogleDrive(string $url): bool
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        return in_array($host, ['drive.google.com', 'docs.google.com'], true)
            || str_ends_with($host, '.drive.google.com');
    }

    /**
     * Ekstrak tipe media dari respons HTTP.
     *
     * @return string Tipe media, atau TipeMedia::Unknown bila respons tidak membocorkan jenis berkasnya
     */
    protected function typeFromResponse(Response $response): string
    {
        $contentType = strtolower(trim(explode(';', (string) $response->header('Content-Type'))[0]));

        if (str_starts_with($contentType, 'image/')) {
            return TipeMedia::Image;
        }

        if (str_starts_with($contentType, 'video/')) {
            return TipeMedia::Video;
        }

        // Google Drive sering mengirim application/octet-stream, andalkan nama berkas.
        $disposition = (string) $response->header('Content-Disposition');

        if (preg_match('/filename="?([^";]+)"?/i', $disposition, $matches) === 1) {
            $extension = $this->extension($matches[1]);

            if ($extension !== null && in_array($extension, self::VIDEO_EXTENSIONS, true)) {
                return TipeMedia::Video;
            }

            if ($extension !== null && in_array($extension, self::IMAGE_EXTENSIONS, true)) {
                return TipeMedia::Image;
            }
        }

        return TipeMedia::Unknown;
    }

    /**
     * Ekstensi berkas lowercase dari URL, tanpa titik sama sekali.
     */
    protected function extension(string $value): ?string
    {
        $path = parse_url($value, PHP_URL_PATH);

        if (! is_string($path) || ! str_contains($path, '.')) {
            return null;
        }

        $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));

        return $extension === '' ? null : $extension;
    }

    /**
     * Normalisasi pilihan tipe media manual dari pengguna.
     */
    protected function normalizeHint(?string $hint): ?string
    {
        $hint = is_string($hint) ? strtolower(trim($hint)) : '';

        if ($hint === '' || $hint === TipeMedia::Unknown || $hint === 'auto') {
            return null;
        }

        return TipeMedia::isValid($hint) ? $hint : null;
    }

    /**
     * Pastikan nilai adalah URL http(s) yang valid.
     */
    protected function isHttpUrl(string $url): bool
    {
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        $host = (string) parse_url($url, PHP_URL_HOST);

        return in_array($scheme, ['http', 'https'], true) && $host !== '' && filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Bersihkan identifier agar aman dipakai di URL.
     */
    protected function trimId(string $id): ?string
    {
        $id = trim(Str::before(trim($id), '/'));

        return preg_match('/^[A-Za-z0-9_-]{3,128}$/', $id) === 1 ? $id : null;
    }
}
