<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class SecureFileUpload implements ValidationRule
{
    protected array $allowedMimes;
    protected int $maxSize;

    public function __construct(array $allowedMimes = [], int $maxSize = 2048)
    {
        $this->allowedMimes = $allowedMimes;
        $this->maxSize = $maxSize;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value instanceof UploadedFile) {
            $fail("The {$attribute} must be a valid file.");
            return;
        }

        // Check MIME type
        if (!empty($this->allowedMimes)) {
            $mime = $value->getMimeType();
            if (!in_array($mime, $this->allowedMimes)) {
                $fail("The {$attribute} must be one of: " . implode(', ', $this->allowedMimes));
                return;
            }
        }

        // Check file size
        $sizeInKB = $value->getSize() / 1024;
        if ($sizeInKB > $this->maxSize) {
            $fail("The {$attribute} may not be greater than {$this->maxSize} kilobytes.");
        }

        // Check for dangerous extensions
        $dangerousExtensions = ['php', 'phtml', 'php3', 'php4', 'php5', 'exe', 'bat', 'sh'];
        $extension = strtolower($value->getClientOriginalExtension());
        
        if (in_array($extension, $dangerousExtensions)) {
            $fail("The {$attribute} has a forbidden file extension.");
        }
    }
}