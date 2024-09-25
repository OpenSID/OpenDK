<?php

namespace App\Observers;

use App\Models\Galeri;
use Illuminate\Support\Facades\Storage;

class GaleriObserver
{
    /**
     * Handle the Galeri "created" event.
     *
     * @param  \App\Models\Galeri  $galeri
     * @return void
     */
    public function created(Galeri $galeri)
    {
        //
    }

    /**
     * Handle the Galeri "updated" event.
     *
     * @param  \App\Models\Galeri  $galeri
     * @return void
     */
    public function updated(Galeri $galeri)
    {
        if ($galeri->isDirty('gambar')) {
            // Ambil gambar lama dan baru dari database
            $oldImages = $galeri->getOriginal('gambar');
            $newImages = $galeri->gambar;

            if (is_array($oldImages)) {
                // Cari gambar yang ada di array lama tapi tidak ada di array baru
                $deletedImages = array_diff($oldImages, $newImages);

                // Hapus setiap gambar yang sudah tidak ada di array baru
                foreach ($deletedImages as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        }
    }

    /**
     * Handle the Galeri "deleted" event.
     *
     * @param  \App\Models\Galeri  $galeri
     * @return void
     */
    public function deleted(Galeri $galeri)
    {
        // Decode JSON images array
        $images = $galeri->gambar;

        if (is_array($images)) {
            foreach ($images as $image) {
                // Cek jika file ada
                if (Storage::disk('public')->exists($image)) {
                    // Hapus file gambar dari storage
                    Storage::disk('public')->delete($image);
                }
            }
        }
    }

    /**
     * Handle the Galeri "restored" event.
     *
     * @param  \App\Models\Galeri  $galeri
     * @return void
     */
    public function restored(Galeri $galeri)
    {
        //
    }

    /**
     * Handle the Galeri "force deleted" event.
     *
     * @param  \App\Models\Galeri  $galeri
     * @return void
     */
    public function forceDeleted(Galeri $galeri)
    {
        //
    }
}
