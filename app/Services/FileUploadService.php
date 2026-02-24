<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload file dengan validasi keamanan
     */
    public function uploadSecure(
        UploadedFile $file,
        string $directory,
        array $allowedMimes = [],
        int $maxSize = 2048
    ): string {
        // 1. Validate MIME type
        $this->validateMimeType($file, $allowedMimes);
        
        // 2. Validate file size (KB)
        $this->validateFileSize($file, $maxSize);
        
        // 3. Sanitize directory to prevent path traversal
        $directory = $this->sanitizeDirectoryPath($directory);
        
        // 4. Generate safe filename
        $safeFileName = $this->generateSafeFileName($file);
        
        // 5. Store file securely
        $path = $file->storeAs($directory, $safeFileName, 'public');
        
        return $path;
    }

    /**
     * Upload multiple files
     */
    public function uploadMultiple(
        array $files,
        string $directory,
        array $allowedMimes = [],
        int $maxSize = 2048
    ): array {
        $uploadedPaths = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $uploadedPaths[] = $this->uploadSecure($file, $directory, $allowedMimes, $maxSize);
            }
        }
        
        return $uploadedPaths;
    }

    /**
     * Delete file
     */
    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
    }

    /**
     * Validate MIME type
     */
    protected function validateMimeType(UploadedFile $file, array $allowedMimes): void
    {
        if (empty($allowedMimes)) {
            return;
        }
        
        $fileMime = $file->getMimeType();
        
        if (!in_array($fileMime, $allowedMimes)) {
            throw new \InvalidArgumentException(
                "File type not allowed. Allowed types: " . implode(', ', $allowedMimes)
            );
        }
    }

    /**
     * Validate file size
     */
    protected function validateFileSize(UploadedFile $file, int $maxSize): void
    {
        $fileSizeInKB = $file->getSize() / 1024;
        
        if ($fileSizeInKB > $maxSize) {
            throw new \InvalidArgumentException(
                "File size exceeds maximum allowed size of {$maxSize}KB"
            );
        }
    }

    /**
     * Sanitize directory path to prevent path traversal
     */
    protected function sanitizeDirectoryPath(string $directory): string
    {
        // Remove any path traversal attempts
        $directory = str_replace(['../', '..\\', './', '.\\'], '', $directory);
        
        // Additional sanitization to prevent directory traversal
        $directory = preg_replace('#/\.{2}/#', '/', $directory); // Prevent /../
        $directory = preg_replace('#\\\\\.{2}\\\\#', '\\', $directory); // Prevent \..\
        
        return $directory;
    }
    
    /**
     * Sanitize file extension to prevent malicious extensions
     */
    protected function sanitizeExtension(string $extension): string
    {
        // Only allow alphanumeric characters and a few safe characters in extension
        $sanitized = preg_replace('/[^a-zA-Z0-9]/', '', $extension);
        
        // Return sanitized extension or empty string if invalid
        return ctype_alnum($sanitized) ? $sanitized : 'tmp';
    }
    
    /**
     * Generate safe filename
     */
    protected function generateSafeFileName(UploadedFile $file): string
    {
        // Get original name and sanitize it to prevent path traversal
        $originalName = $file->getClientOriginalName();
        
        // Check if original name contains path traversal characters
        if (str_contains($originalName, '../') || str_contains($originalName, '..\\')) {
            throw new \InvalidArgumentException("File name contains path traversal attempts");
        }
        
        // Generate unique hash-based filename
        $extension = $file->getClientOriginalExtension();
        $timestamp = time();
        $random = Str::random(16);
        
        // Make sure extension is safe too
        $extension = $this->sanitizeExtension($extension);
        
        return "{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Get allowed MIME types by category
     */
    public static function getAllowedMimes(string $category): array
    {
        return match($category) {
            'image' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
            'document' => ['application/pdf', 'application/msword', 
                          'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'spreadsheet' => ['application/vnd.ms-excel', 
                             'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                             'text/csv'],
            'archive' => ['application/zip', 'application/x-zip-compressed'],
            default => [],
        };
    }
}