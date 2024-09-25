<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait HandlesFileUpload
{
    /**
     * Menangani upload file dari request.
     *
     * @param \Illuminate\Http\Request $request
     * @param array &$input
     * @param string $field
     * @param string $directory
     * @return void
     */
    public function handleFileUpload($request, &$input, $field = 'file', $directory = 'uploads')
    {
        if ($request->hasFile($field)) {
            $file = $request->file($field);

            if ($file instanceof UploadedFile) {
                $fileName = $file->hashName();
                $path = $file->storeAs("public/{$directory}", $fileName);
                $input[$field] = str_replace('public/', 'storage/', $path);
            }
        }
    }
}
