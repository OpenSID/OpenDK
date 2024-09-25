<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait HandlesResourceDeletion
{
    public static function bootHandlesResourceDeletion()
    {
        static::updating(function ($model) {
            $model->deleteResource();
        });

        static::deleting(function ($model) {
            $model->deleteResource(true);
        });
    }

    protected function deleteResource($deleting = false)
    {
        $resourceFields = $this->resources ?? [];

        foreach ($resourceFields as $field) {
            if ($this->isDirty($field) || $deleting) {
                $resourcePath = public_path($this->getOriginal($field));
                if (File::exists($resourcePath)) {
                    File::delete($resourcePath);
                }
            }
        }
    }
}
