<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class SafeFileContent implements ValidationRule
{
    /**
     * Batas byte yang dipindai dari awal file.
     *
     * Pemindaian dibatasi dengan sengaja: seluruh file tidak perlu dibaca karena
     * penanda skrip pada payload yang disisipkan selalu berada di bagian awal,
     * sedangkan pemindaian penuh membuat file besar menjadi vektor kehabisan memori.
     */
    private const SCAN_LIMIT = 1048576;

    /**
     * MIME berbasis markup yang isinya diparse oleh browser saat disajikan inline.
     */
    private const MARKUP_MIMES = [
        'image/svg+xml',
        'image/svg',
        'text/html',
        'application/xhtml+xml',
        'text/xml',
        'application/xml',
    ];

    /**
     * Penanda skrip yang sah-sah saja muncul pada payload yang disisipkan.
     *
     * Token generik seperti "function" SENGAJA TIDAK disertakan: string tersebut
     * muncul wajar pada struktur biner PDF/Office sehingga memicu false positive
     * pada dokumen yang sah.
     */
    private const SCRIPT_SIGNATURES = '/<\?php|<\?=|<script\b|__halt_compiler/i';

    /**
     * Pola tambahan yang hanya relevan untuk format berbasis markup.
     * Diterapkan pada SVG/XML/HTML karena isinya dieksekusi browser (stored XSS).
     */
    private const MARKUP_DANGERS = '/<\s*script\b|<\s*html\b|<\s*iframe\b|<\s*embed\b|<\s*object\b|<\s*foreignObject\b'
        .'|\bon[a-z]{3,}\s*=|javascript\s*:|vbscript\s*:|data:text\/html/i';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            $this->fail($attribute, $fail);

            return;
        }

        $head = $this->readHead($value);

        if ($this->matches(self::SCRIPT_SIGNATURES, $head)) {
            $this->fail($attribute, $fail);

            return;
        }

        $mime = $value->getMimeType() ?? '';

        if (in_array($mime, self::MARKUP_MIMES, true) && $this->matches(self::MARKUP_DANGERS, $head)) {
            $this->fail($attribute, $fail);
        }
    }

    /**
     * Baca bagian awal file tanpa memuat seluruh isinya ke memori.
     */
    protected function readHead(UploadedFile $file): string
    {
        $path = $file->getRealPath();

        if ($path === false || ! is_readable($path)) {
            return '';
        }

        $handle = @fopen($path, 'rb');

        if ($handle === false) {
            return '';
        }

        try {
            $head = fread($handle, self::SCAN_LIMIT);

            return $head === false ? '' : $head;
        } finally {
            fclose($handle);
        }
    }

    protected function matches(string $pattern, string $content): bool
    {
        return $content !== '' && preg_match($pattern, $content) === 1;
    }

    protected function fail(string $attribute, Closure $fail): void
    {
        $fail(__('validation.valid_file', ['attribute' => str_replace('_', ' ', $attribute)]));
    }
}
